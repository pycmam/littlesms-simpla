<?php

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * К этому скрипту обращается webmoney в процессе оплаты
 *
 */
 
// Работаем в корневой директории
chdir ('../');

require_once('Widget.class.php');
require_once('Order.class.php');

class Robokassa extends Widget
{   
	function Robokassa(&$parent)
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

       // Сумма платежа
       // Сумма, которую заплатил покупатель. Дробная часть отделяется точкой.
       $amount = $_POST['OutSum'];
       
       // Внутренний номер покупки продавца
       // В этом поле передается id заказа в нашем магазине.
       $order_id = intval($_POST['InvId']);
       
       // Контрольная подпись
       $crc = strtoupper($_POST['SignatureValue']);
       
       // В пользовательском параметре Shp_item передается id способа оплаты в нашем магазине
       $payment_method_id = intval(strtoupper($_POST['Shp_item']));
       
       ////////////////////////////////////////////////
       // Выбираем из базы соответствующий типу кошелька метод оплаты
       // и его курс к основной валюте
       ////////////////////////////////////////////////
       $query = sql_placeholder("SELECT payment_methods.*, currencies.rate_from as rate_from, currencies.rate_to as rate_to FROM payment_methods, currencies
                                 WHERE payment_methods.enabled=1 AND payment_methods.currency_id = currencies.currency_id AND payment_methods.payment_method_id=? LIMIT 1", $payment_method_id);
       $this->db->query($query);
       $method = $this->db->result();
       if(empty($method))
         return "Неизвестный метод оплаты";
         
       $payment_params = unserialize($method->params);
       
       $mrh_pass2 = $payment_params['robokassa_password2'];
       

       // Проверяем контрольную подпись
       $my_crc = strtoupper(md5("$amount:$order_id:$mrh_pass2:Shp_item=$payment_method_id"));  
       if($my_crc != $crc)
         return "bad sign $amount:$order_id:$mrh_pass2\n";      


       ////////////////////////////////////////////////
       // Выберем заказ из базы
       ////////////////////////////////////////////////
       $order = Order::get_order_by_id($order_id);
       if(empty($order))
         return 'Оплачеваемый заказ не найден';
         
       // Нельзя оплатить уже оплаченный заказ  
       if($order->payment_status == 1)  
         return 'Этот заказ уже оплачен';
         
       ////////////////////////////////////
       // Проверка суммы платежа
       ////////////////////////////////////
       

       // Сумма заказа у нас в магазине
       $order_amount = round($order->total_amount*$method->rate_from/$method->rate_to, 2);
       
       // Должна быть равна переданной сумме
       if($order_amount != $amount || $amount<=0)
         return "Неверная сумма оплаты";
  
       // Установим статус оплачен
       $query = sql_placeholder('UPDATE orders SET payment_status=1, payment_date=NOW(), payment_method_id=?, payment_details=? WHERE order_id=? LIMIT 1', $payment_method_id, var_export($_POST, true), $order->order_id);
       $this->db->query($query);  
         
       // Спишем товары  
       foreach($order->products as $order_product)
       {
         $query = sql_placeholder("UPDATE products SET quantity=quantity-? WHERE product_id=? LIMIT 1", $order_product->quantity, $order_product->product_id);
         $this->db->query($query);

         $query = sql_placeholder("UPDATE orders SET written_off=1 WHERE order_id=? LIMIT 1", $order->order_id);
         $this->db->query($query);       
       }     

       

	   $order = Order::get_order_by_id($order_id);	
       $this->smarty->assign('order', $order);
    	 // Письмо администратору
    	 $message = $this->smarty->fetch('../../../admin/templates/email_order_admin.tpl');
		 $this->email($this->settings->admin_email, 'Оплачен заказ №'.$order->order_id, $message);
		
		 // Письмо пользователю
		 if(!empty($order->email))
		 {
			$message = $this->smarty->fetch('email_order.tpl');
			$this->email($order->email, 'Принята оплата заказа №'.$order->order_id, $message);
		 }

       
       return "OK".$order_id."\n"; 
  	}		
}

// Собсвенно скрипт

$robox = new Robokassa($a = 0);

// Нельзя выводить ошибки, иначе они засветятся в merchant webmoney
$result = @$robox->process();
  
print $result;
?>