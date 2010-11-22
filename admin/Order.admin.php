<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');

class Order extends Widget
{
  var $order;
  
  function Order(&$parent)
  {
	parent::Widget($parent);
    $this->add_param('order_id');
    $this->add_param('view');
    $this->add_param('page');
    $this->prepare();
  }

  function prepare()
  {
    // С каким заказом работаем?
  	$this->order_id = intval($this->param('order_id'));
  	
    $this->order = $this->get_order_by_id($this->order_id);
    
    // Если что-то запостили, нужно обновить запись в базе
  	if(isset($_POST['name']))
  	{  		

  	  $this->check_token();

      $order->name = $_POST['name'];
  	  $order->email = $_POST['email'];
  	  $order->phone = $_POST['phone'];
  	  $order->address = $_POST['address'];
  	  $order->comment = $_POST['comment'];
  	  $order->delivery_method_id = $_POST['delivery_method_id'];
  	  $order->delivery_price = $_POST['delivery_price'];
  	  $order->payment_method_id = $_POST['payment_method_id'];
  	  $order->status = $_POST['status'];
  	  if(isset($_POST['payment_status']) && $_POST['payment_status']==1)
  	  	$order->payment_status = 1;
  	  else
  	  	$order->payment_status = 0;
  	  
  	  $sql_set_payment_date = '';
  	  if($order->payment_status==1 && $this->order->payment_status==0)
  	  	$sql_set_payment_date = 'payment_date = NOW(),';
  	  
  	  $error = '';
  	
  	  // Если ошибок не возникло, обновим заказ в базе	
  	  if(empty($error))
  	  {	
  	      $query = sql_placeholder("UPDATE orders SET
                                  name=?,
                                  email=?,
                                  phone=?,
                                  address=?,
                                  comment=?,
                                  delivery_method_id=?,
                                  delivery_price=?,
                                  status=?,
                                  payment_method_id=?,
                                  $sql_set_payment_date
                                  payment_status=?                               
                                  WHERE order_id=?",
                                  $order->name,
                                  $order->email,
                                  $order->phone,
                                  $order->address,                                
                                  $order->comment,                                
                                  $order->delivery_method_id,                                
                                  $order->delivery_price,  
                                  $order->status,                              
                                  $order->payment_method_id,    
                                  $order->payment_status,    
                                  $this->order->order_id);
         $this->db->query($query);
         
         //спишем товары если сменили статус заказа с нового
         if($this->order->status == 0 && $order->status > 0 && $this->order->written_off == 0)
         {
         	$this->products_write($this->order_id, -1);
         }
         
         //вернем товары если сменили статус заказа обратно на новый
         if($this->order->status > 0 && $order->status == 0 && $this->order->written_off == 1)
         {
         	$this->products_write($this->order_id, 1);
         }
         
         // Уведомление пользователя
         if(isset($_POST['notify_user']) && $_POST['notify_user']==1)
         {
         	$order = $this->get_order_by_id($this->order_id);
         	$this->smarty->assign('order', $order);
         	$this->smarty->assign('main_currency', $this->main_currency);
         	$message = $this->smarty->fetch('file:../../design/'.$this->settings->theme.'/html/email_order.tpl');
         	$this->email($order->email, 'Состояние заказа №'.$this->order->order_id, $message);
         }
         
  		 $get = $this->form_get(array('section'=>'Orders'));
 	     header("Location: index.php$get");
 	     
 	   }
 	   else
 	   {
 	     $this->smarty->assign('Error', $error); 	     
 	   }
 	}
  }
  
  
  // Вывод заказа на экран
  function fetch()
  {
  	$this->title = 'Заказ №'.$this->order_id;
  	
    // Сформируем массив способов доставки
  	$query = "SELECT * FROM delivery_methods WHERE enabled ORDER BY delivery_method_id";
  	$this->db->query($query);
  	$delivery_methods = $this->db->results();
  	foreach($delivery_methods as $k=>$method)
  	{
  	  $delivery_methods[$k]->final_price = $method->price;
  	  if($method->free_from <= $this->order->amount)
  	    $delivery_methods[$k]->final_price = 0;
  	}  	
  
    // Передаем их в шаблон
    $this->smarty->assign('DeliveryMethods', $delivery_methods); 
    
    // Сформируем массив форм оплаты
  	$query = "SELECT * FROM payment_methods WHERE enabled ORDER BY payment_method_id";
  	$this->db->query($query);
  	$payment_methods = $this->db->results();
  
    // Передаем их в шаблон
    $this->smarty->assign('PaymentMethods', $payment_methods); 
    
    // И сам заказ педедадим в шаблон
    // Этот заказ может быть из базы, а может быть и из $_post   
 	$this->body = $this->smarty->assign('Order', $this->order);

 	$this->body = $this->smarty->fetch('order.tpl');
  }
  
	/**
	 *
	 * Возвращает заказ по id
	 *
	 */
	function get_order_by_id($order_id)
	{
		// На всякий случай приводим к числу
		$order_id = intval($order_id);
		$query = sql_placeholder("SELECT orders.*,
									 SUM(orders_products.price*orders_products.quantity) as amount,
									 SUM(orders_products.price*orders_products.quantity)+orders.delivery_price as total_amount,									 
									 DATE_FORMAT(orders.date, '%d.%m.%Y %H:%i') as date,
									 DATE_FORMAT(orders.payment_date, '%d.%m.%Y %H:%i') as payment_date,									 
									 delivery_methods.name as delivery_method
							  	FROM orders
								   LEFT JOIN orders_products ON orders.order_id = orders_products.order_id
								   LEFT JOIN delivery_methods ON orders.delivery_method_id = delivery_methods.delivery_method_id
							  	WHERE orders.order_id=?
							 	GROUP BY orders.order_id
							 	LIMIT 1", $order_id);
		$this->db->query($query); 
		$order = $this->db->result();

		if ($order)
		{
			// Все товары в этом заказе
			$query = sql_placeholder("SELECT orders_products.*, products.url as url, products.download as download
										FROM orders_products LEFT JOIN products ON products.product_id=orders_products.product_id WHERE orders_products.order_id=?", $order_id);
			$this->db->query($query);
			$order->products = $this->db->results();
		}
		return $order;
	}
  
  	// Списывание - возвращение товаров на склад из заказа
  	// $write_on = -1 - списать со склада
  	// $write_on = 1 - вернуть на скдад
  	function products_write($order_id, $write_on = -1)
  	{
    	$query = sql_placeholder('SELECT * FROM orders_products WHERE order_id=?', $order_id);
    	$this->db->query($query);
    	$order_products = $this->db->results();
    	foreach($order_products as $order_product)
    	{
      		$query = sql_placeholder('UPDATE products_variants SET stock=stock+? WHERE variant_id=?', $order_product->quantity*$write_on, $order_product->variant_id);
      		$this->db->query($query);
    	}
    	$query = sql_placeholder('UPDATE orders SET written_off=1-written_off WHERE order_id=?', $order_id);
    	$this->db->query($query);
  	}
}
