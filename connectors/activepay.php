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


class ActivePay {
        var $method = "GET", $url_domain = "activepay.ru", $url_uri = "/merchant_pages/create/", $secret_key, $merchant_contract;

        private function check_var($flag = False) {
		if (!$flag) {
                    if ($this->method == "GET") die("You must set method to POST!\n");
                    if ($this->url_domain == "activepay.ru") die("You must set url_domain of own server\n");
                    if ($this->url_uri == "/merchant_pages/create/") die("You must set url_uri of own server\n");
                    if (!$this->secret_key) die("You must set secret_key\n");
                } else {
                    if (!$this->merchant_contract) die("You must set merchant_contract\n");
                    if (!$this->secret_key) die("You must set secret_key\n");
                }
        }

        private function build_query_string($data) {
                $query_string = "";
                ksort($data);
                foreach ($data as $item => $value) {
                 if ($query_string != "") {
                   $query_string .= "&";
                 }
                 $query_string .= rawurlencode($item)."=".rawurlencode($value);
                }
                return $query_string;
        }

        private function sign($data) {
                $url = "http://$this->url_domain$this->url_uri";
                $query_string = $this->build_query_string($data);
                $string_to_sign = "$this->method\n$this->url_domain\n$this->url_uri\n$query_string";
                $hmac_sha1_hash = hash_hmac("sha1", $string_to_sign, $this->secret_key, true);
                return urlencode(base64_encode($hmac_sha1_hash));
        }

	public function check_signature($data) {
                $this->check_var();
                $signature = $data["signature"];
                unset($data["signature"]);
                $signature2 = $this->sign($data);
                return urlencode($signature) == $signature2;
        }

        public function build_merchant_pages_url($data) {
                $this->check_var(true);
                $data["merchant_contract"] = $this->merchant_contract;
                $signature = $this->sign($data);
                $query_string = $this->build_query_string($data);
                return "http://$this->url_domain$this->url_uri?$query_string&signature=$signature";
        }
}


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

		

   
       ////////////////////////////////////////////////
       // Выбираем из базы соответствующий типу кошелька метод оплаты
       // и его курс к основной валюте
       ////////////////////////////////////////////////
       $query = sql_placeholder("SELECT payment_methods.*, currencies.rate_from as rate_from, currencies.rate_to as rate_to FROM payment_methods, currencies
                                 WHERE payment_methods.enabled=1 AND payment_methods.currency_id = currencies.currency_id AND payment_methods.module=? LIMIT 1", 'activepay');
       $this->db->query($query);
       $method = $this->db->result();
       if(empty($method))
         return "Неизвестный метод оплаты";
         
      $params = unserialize($method->params);
      
 $a = new ActivePay;
 $a->url_domain = $_SERVER['HTTP_HOST'];

 $a->url_uri = "/connectors/activepay.php";
 $a->method = "POST";
 $a->secret_key = $params['activepay_secret_key'];

 $data = json_decode(file_get_contents('php://input'), true);
 $debug = var_export($data, true);

 $ok = $a->check_signature($data);

 if(!$ok)
	exit();
	
if($data['result'] != 'success')
	exit();

	   $order_id = intval($data['merchant_data']);
	   

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
       
  
       // Установим статус оплачен
       $query = sql_placeholder('UPDATE orders SET payment_status=1, payment_date=NOW(), payment_method_id=?, payment_details=? WHERE order_id=? LIMIT 1', $method->payment_method_id, var_export($_POST, true), $order->order_id);
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