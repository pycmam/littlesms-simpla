<?php

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * К этому скрипту обращается Украинский Процессинговый Центр в процессе оплаты
 *
 */
 
// Работаем в корневой директории
chdir ('../');

require_once('Widget.class.php');
require_once('Order.class.php');

class UPC extends Widget
{

	var $debug = false; // Опасно. Включать только для теста.
	var $debug_file = 'connectors/upc_debug.txt'; 
	var $order = null;
	
	function UPC(&$parent)
	{
		// Вызываем конструктор базового класса
		Widget::Widget($parent);
	}


	function fetch()
	{
		return '';
	}
	
	function process()
	{
		if($this->debug)
			$f = fopen($this->debug_file, 'a');
	    
	    if($this->debug)
	    	fputs($f, "\n\r~~~~~~~~~\n\r".date("m.d.Y H:i:s")."\n\r".var_export($_POST, true)."\n\r");
  	   	 
  	   	///////////////////   	
		// Принимаем параметры уведомления
		///////////////////
		
		// ID продавца
		$merchant_id = $_POST['MerchantID'];
		
		// ID терминала
		$terminal_id = $_POST['TerminalID'];
		
		// Номер заказа (у нас в магазине)
		$order_id = intval($_POST['OrderID']);
		
		// Код валюты (гривна = 980)
		$currency_id = $_POST['Currency'];
		
		// Данные сессии. Мы их используем как payment_method_id,
		// так как никаких других способов передачи этого параметра
		// УПЦ не предоставляет		
		$session_data = $_POST['SD'];

		// Время совершения заказа
		$total_amount = intval($_POST['TotalAmount']);

		// Время совершения заказа
		$purchase_time = $_POST['PurchaseTime'];
		
		
		// Параметры со значением результата попытки авторизации
		// Это вроде номер карточки, но нам он все равно ни к чему
		$proxy_pan = $_POST['ProxyPan'];
		$tran_code = $_POST['TranCode'];
		$approval_code = $_POST['ApprovalCode'];
		$rtn = $_POST['Rrn'];
		$xid = $_POST['XID'];
		
		// Подпись всего вышеизложенного барахла
		$signature = base64_decode($_POST['Signature']);

		////////////////////////////////////////////////
		// Выбираем из базы метод оплаты, который мы сами себе заслази через параметр session_data
		// и его курс к основной валюте
		////////////////////////////////////////////////
		$method_id = intval($session_data);
		$query = sql_placeholder("SELECT payment_methods.*, currencies.rate_from as rate_from, currencies.rate_to as rate_to FROM payment_methods, currencies
                                 WHERE payment_methods.enabled=1 AND payment_methods.currency_id = currencies.currency_id AND payment_methods.payment_method_id=? LIMIT 1", $method_id);
		$this->db->query($query);
		$method = $this->db->result();
		
		if (empty($method))
		{
	    	if($this->debug)
	    		fputs($f, "Неизвестная форма оплаты (id=$session_data)\n\r");		
			return "Неизвестная форма оплаты (id=$session_data)";
		}
		$params = unserialize($method->params);

		////////////////////////////////////////////////
		// Проверяем подпись
		////////////////////////////////////////////////
		
		$data = "$merchant_id;$terminal_id;$purchase_time;$order_id;$xid;$currency_id;$total_amount;$session_data;$tran_code;$approval_code;";

		// извлечь сертификат 
		if (!is_readable($params['ssl_cert_file']))
		{
	    	if($this->debug)
	    		fputs($f, "Ошибка чтения файла ключа\n\r");		
			return 'Ошибка чтения файла ключа';
		}
		$fp = fopen($params['ssl_cert_file'], "r");
		$cert = fread($fp, 8192); 
		fclose($fp); 
		$pubkeyid = openssl_get_publickey($cert);
 
		// проверка подписи 
		$ok = openssl_verify($data, $signature, $pubkeyid); 
		if ($ok == 1)
		{ 
	    	if($this->debug)
	    		fputs($f, "Проверка подписи прошла успешно\n\r");
	    	openssl_free_key($pubkeyid);		
		}
		elseif ($ok == 0)
		{ 
	    	if($this->debug)
	    		fputs($f, "Подпись неверна\n\r");
	    	openssl_free_key($pubkeyid);	
	    	return 'Подпись неверна';
		}
		else
		{ 
	    	if($this->debug)
	    		fputs($f, "Ошибка проверки подписи\n\r");
	    	openssl_free_key($pubkeyid);
	    	return 'Ошибка проверки подписи';		
		} 


		
		////////////////////////////////////////////////
		// Выберем заказ из базы
		////////////////////////////////////////////////
		$this->order = Order::get_order_by_id($order_id);

		if (empty($this->order))
		{
	    	if($this->debug)
	    		fputs($f, "Оплачиваемый заказ не найден\n\r");
			return 'Оплачиваемый заказ не найден';
		}else
		{		
	    	if($this->debug)
	    		fputs($f, "Заказ найден\n\r");			
		}
		// Нельзя оплатить уже оплаченный заказ
		if ($this->order->payment_status == 1)
		{
	    	if($this->debug)
	    		fputs($f, "Этот заказ уже оплачен\n\r");
			return 'Этот заказ уже оплачен';
		}

		////////////////////////////////////
		// Проверка суммы платежа
		////////////////////////////////////
		// 
		
		// Сумма заказа у нас в магазине
		$order_amount = round($this->order->total_amount*$method->rate_from/$method->rate_to*100);
		// Должна быть равна переданной сумме
		if ($order_amount != $total_amount || $total_amount<=0)
		{
	    	if($this->debug)
	    		fputs($f, "Неверная сумма оплаты\n\r");
			return "Неверная сумма оплаты";
		}
				
		////////////////////////////////////
		// Проверка наличия товара
		////////////////////////////////////
		foreach ($this->order->products as $order_product)
		{
			$query = sql_placeholder("SELECT * FROM products WHERE product_id=? LIMIT 1", $order_product->product_id);
			$this->db->query($query);
			$product = $this->db->result();
			if (!$product->enabled || $product->quantity < $order_product->quantity)
			{
	    		if($this->debug)
	    			fputs($f, "Нехватка товара $product->model\n\r");
				return "Нехватка товара $product->model";
			}else
			{
	    		if($this->debug)
	    			fputs($f, "Товар $product->model проверен\n\r");						
			}
		}
		
		////////////////////////////////////
		// Проверка других параметров
		////////////////////////////////////
		if ($merchant_id != $params['merchant_id'])
		{
	    	if($this->debug)
	    		fputs($f, "Неверный MerchantId\n\r");
			return "Неверный MerchantId";
		}

		if ($terminal_id != $params['terminal_id'])
		{
	    	if($this->debug)
	    		fputs($f, "Неверный TerminalId\n\r");
			return "Неверный TerminalId";
		}
		
		if ($tran_code !== '000')
		{
	    	if($this->debug)
	    		fputs($f, "TranCode не равен '000'\n\r");
			return "TranCode не равен '000'";
		}
		
		

		// Установим статус оплачен
		$query = sql_placeholder('UPDATE orders SET payment_status=1, payment_date=NOW(), payment_method_id=?, payment_details=? WHERE order_id=? LIMIT 1', $method_id, var_export($_POST, true), $this->order->order_id);
		$this->db->query($query);
		
		if($this->debug)
			fputs($f, "Заказ отмечен как оплаченный\n\r");						

		
		// Спишем товары
		foreach ($this->order->products as $order_product)
		{
			$query = sql_placeholder("UPDATE products SET quantity=quantity-? WHERE product_id=? LIMIT 1", $order_product->quantity, $order_product->product_id);
			$this->db->query($query);
			$query = sql_placeholder("UPDATE orders SET written_off=1 WHERE order_id=? LIMIT 1", $this->order->order_id);
			$this->db->query($query);
		}
		
	    if($this->debug)
	    	fputs($f, "Все проверки пройдены успешно, база обновлена\n\r");
	    
	    $this->order = Order::get_order_by_id($order_id);		
		$this->smarty->assign('order', $this->order);
    	// Письмо администратору
    	$message = $this->smarty->fetch('../../../admin/templates/email_order_admin.tpl');
		$this->email($this->settings->admin_email, 'Оплачен заказ №'.$this->order->order_id, $message);
		
		// Письмо пользователю
		if(!empty($this->order->email))
		{
			$message = $this->smarty->fetch('email_order.tpl');
			$this->email($this->order->email, 'Принята оплата заказа №'.$this->order->order_id, $message);
		}
		///
		fclose($f);
		return 'ok';
	}
}

// Собсвенно скрипт
$pc = new UPC($a = 0);

// Нельзя выводить ошибки, иначе они засветятся в merchant webmoney

if($pc->debug)
	$result = $pc->process();
else
	$result = @$pc->process();

$response = 'error';
$reason = '';

$forward = 'http://'.dirname($pc->root_url).'/order/'.$pc->order->code;

if($result === 'ok')
{
	$response = 'approve';
	$forward = 'http://'.dirname($pc->root_url).'/order/'.$pc->order->code;	
}
else
{
	$response = 'reverse';
	$reason = $result;
	$forward = 'http://'.dirname($pc->root_url).'/order/'.$pc->order->code;
}

$answer  = "MerchantID=".$_POST['MerchantID']."\n";
$answer .= "TerminalID=".$_POST['TerminalID']."\n";
$answer .= "OrderID=".$_POST['OrderID']."\n";
$answer .= "Currency=".$_POST['Currency']."\n";
$answer .= "TotalAmount=".$_POST['TotalAmount']."\n";
$answer .= "XID=".$_POST['XID']."\n";
$answer .= "PurchaseTime=".$_POST['PurchaseTime']."\n";
$answer .= "Response.action=".$response."\n";
$answer .= "Response.reason=".$reason."\n";
$answer .= "Response.forwardUrl=".$forward;

print $answer;

?>