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
 

require_once('Widget.class.php');
require_once('Order.class.php');

class Download extends Widget
{   

	var $expire_days = 7; // время жизни ссылок для скачивания (от момента оплаты заказа)
	
	function Download(&$parent)
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
	
		$order_code = $_GET['order_code'];
		$file = $_GET['file'];
		

       $query = sql_placeholder("SELECT count(*) as count FROM orders, products, orders_products
                                 WHERE orders.code=? AND products.download=?
                                 AND orders.payment_status=1
                                 AND orders.order_id=orders_products.order_id AND products.product_id=orders_products.product_id
                                 AND DATEDIFF(now(), orders.payment_date)<?", $order_code, $file, $this->expire_days);
       $this->db->query($query);
       $c = $this->db->result();
       $count = $c->count;
       if($count>0)
       {
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Description: File Transfer");  
			header("Content-Length: " . filesize("files/downloads/".$file)."; "); 
			header("Content-Disposition: attachment; filename=\"$file\"");
			readfile("files/downloads/".$file);
			exit();
		}
		else
		{
			header("http/1.0 404 not found");		
			exit();
		}
  	}		
}

// Собсвенно скрипт

$d = new Download($a = 0);

// Нельзя выводить ошибки
@$d->process();

?>