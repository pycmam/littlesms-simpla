<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('Order.admin.php');
require_once('../placeholder.php');

############################################
# Class Orders
############################################
class Orders extends Widget
{
  var $pages_navigation;
  var $items_per_page = 10;
  
  function Orders(&$parent)
  {
	parent::Widget($parent);
    $this->add_param('page');
    $this->add_param('view');
    $this->add_param('keyword');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }
  
  function prepare()
  {
    // Удаление заказа
  	if(isset($_GET['delete_id']))
  	{
  		$this->check_token();

  	    $delete_id = intval($_GET['delete_id']);
  	    $query = sql_placeholder('SELECT * FROM orders WHERE order_id=? LIMIT 1', $delete_id);
  	    $this->db->query($query);
  	    $order = $this->db->result();
  	    
  	    // Можно удалять только неоплаченные заказы
  	    if($order->payment_status == 0)
  	    {
  	      // Вернем товары на склад
  	      if($order->written_off == 1)
  	      {
             Order::products_write($order->order_id, 1);
  	      }
  		  $query = sql_placeholder("DELETE FROM orders WHERE order_id = ? LIMIT 1", $order->order_id);
  		  $this->db->query($query);
  		  $query = sql_placeholder("DELETE FROM orders_products WHERE order_id =?", $order->order_id);
  		  $this->db->query($query);
  		  $get = $this->form_get(array());
 		  header("Location: index.php$get");
 		}
 	}
 	
 	// Изменение статуса заказа
 	if(isset($_GET['change_status_id']))
 	{
  	  $this->check_token();

      $change_status_id = intval($this->param('change_status_id'));
      $new_status = intval($this->param('new_status'));

	  $order = Order::get_order_by_id($change_status_id);
       
      // Списываем товары со склада
      if($order->status == 0 && $new_status > 0 && $order->written_off == 0)
      {
        Order::products_write($order->order_id, -1);
  	  }
  	  $query = sql_placeholder('UPDATE orders SET status=? WHERE order_id=?', $new_status, $change_status_id);
  	  $this->db->query($query);
  	  
	  $order = Order::get_order_by_id($change_status_id);
  	  
       // Уведомление пользователя
       $this->smarty->assign('order', $order);
       $this->smarty->assign('main_currency', $this->main_currency);
       $message = $this->smarty->fetch('file:../../design/'.$this->settings->theme.'/html/email_order.tpl');
       $this->email($order->email, 'Состояние заказа №'.$order->order_id, $message);	  
  	  
 	}
 	
  }

  function fetch()
  {
  	$this->title = $this->lang->ORDERS;
  	$current_page = intval($this->param('page'));

  	$view = $this->param('view');
  	if(empty($view))
  	  $view = 'new';


    $filter = '';

    if($view == 'new')
      $filter .= 'AND orders.status=0';
    
    if($view == 'process')
      $filter .= 'AND orders.status=1';
    
    if($view == 'done')
      $filter .= 'AND orders.status=2';
    
    if($view == 'search')
    {
      $keyword = mysql_real_escape_string($this->param('keyword'));
      if(!empty($keyword))
      {
        if(substr($keyword, 0, 5) == 'user:')
        {
          $user_id = intval(substr($keyword, 5, strlen($keyword)-5));
          $filter .= " AND (orders.user_id = $user_id)";          
        }
        else
        {
          $filter .= " AND (orders.order_id LIKE '%$keyword%' OR orders.name LIKE '%$keyword%' OR orders.email LIKE '%$keyword%' OR orders.address LIKE '%$keyword%' OR orders.phone LIKE '%$keyword%')";
        }
      }
    }
    
    
    if($this->param('login'))
      $filter .= 'AND orders.login = "'.$this->param('login').'"';

    if($this->param('keywords'))
    {
      $keywords = preg_split('/[\s]+/', $this->param('keywords'));
      foreach($keywords as $keyword)
      {
        $keyword = mysql_real_escape_string($keyword);
        $filter .= "AND (CONCAT(orders.order_id, orders.name, orders.phone) like '%$keyword%')";
      }
    }
    
    ####
    #### Выборка заказов

  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS orders.*,
                      DATE_FORMAT(orders.date, '%d.%m.%Y %k:%i') as date,
                      delivery_methods.name as delivery_method,
                      payment_methods.name as payment_method,
                      SUM(orders_products.price*orders_products.quantity)+orders.delivery_price as total_amount
                      FROM orders 
                      LEFT JOIN delivery_methods ON delivery_methods.delivery_method_id = orders.delivery_method_id
                      LEFT JOIN orders_products ON orders.order_id = orders_products.order_id
                      LEFT JOIN payment_methods ON orders.payment_method_id = payment_methods.payment_method_id
                      WHERE 1 $filter
                      GROUP BY orders.order_id
    				  ORDER BY orders.order_id DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$orders = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

  	foreach($orders as $k=>$order)
    {
  	  $orders[$k]->set_to_process_url = $this->form_get(array('change_status_id'=>$order->order_id, 'new_status'=>1, 'token'=>$this->token));
  	  $orders[$k]->set_done_url = $this->form_get(array('change_status_id'=>$order->order_id, 'new_status'=>2, 'token'=>$this->token));
  	  $orders[$k]->edit_url = $this->form_get(array('section'=>'Order', 'order_id'=>$order->order_id, 'view'=>$this->param('view'), 'page'=>$this->param('page'), 'token'=>$this->token));
  	  $orders[$k]->delete_url = $this->form_get(array('delete_id'=>$order->order_id, 'token'=>$this->token));
      $this->db->query("SELECT orders_products.*, products_variants.stock as stock, products.url as url
                        FROM orders_products LEFT JOIN products ON products.product_id = orders_products.product_id LEFT JOIN products_variants ON orders_products.variant_id = products_variants.variant_id
    				    WHERE orders_products.order_id = '$order->order_id'");
  	  $products = $this->db->results();
      $orders[$k]->products = $products;
    }

  	$this->pages_navigation->fetch($pages_num);
 	$this->smarty->assign('Orders', $orders);
 	$this->smarty->assign('View', $view);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('orders.tpl');
  }
}


