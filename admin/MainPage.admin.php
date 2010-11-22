<?PHP

require_once('Widget.admin.php');

class MainPage extends Widget
{
  var $menu;
  function MainPage(&$parent)
  {
		parent::Widget($parent);
  }

  function fetch()
  {

    $query = "SELECT  products.*, categories.name as category_name, categories.single_name as category_single_name,  categories.url as category_url, brands.name as brand, brands.url as brand_url 
    				  FROM products LEFT JOIN categories ON products.category_id = categories.category_id
                      LEFT JOIN brands ON products.brand_id = brands.brand_id
    				  ORDER BY products.modified DESC
    				  LIMIT 7";

    $this->db->query($query);
  	$products = $this->db->results();
    if($products)
    foreach($products as $key=>$item)
    {
       $products[$key]->edit_get = $this->form_get(array('section'=>'Product','item_id'=>$item->product_id, 'from'=>$_SERVER['REQUEST_URI'], 'token'=>$this->token));
       $products[$key]->set_hit_get = $this->form_get(array('section'=>'Storefront','set_hit'=>$item->product_id, 'from'=>$_SERVER['REQUEST_URI'], 'token'=>$this->token));
       $products[$key]->set_enabled_get = $this->form_get(array('section'=>'Storefront','set_enabled'=>$item->product_id, 'from'=>$_SERVER['REQUEST_URI'], 'token'=>$this->token));
       $products[$key]->delete_get = $this->form_get(array('section'=>'Storefront','act'=>'delete', 'item_id'=>$item->product_id, 'from'=>$_SERVER['REQUEST_URI'], 'token'=>$this->token));
    }  	
    
    $query = "SELECT SQL_CALC_FOUND_ROWS orders.*,
                      DATE_FORMAT(orders.date, '%d.%m.%Y %k:%i') as date,
                      SUM(orders_products.price*orders_products.quantity)+delivery_price AS total_amount
                      FROM orders LEFT JOIN orders_products ON orders_products.order_id = orders.order_id
                      WHERE orders.status=0
    				  GROUP BY orders.order_id
    				  ORDER BY orders.order_id DESC
    				  LIMIT 10";
    $this->db->query($query);
  	$orders = $this->db->results(); 

	$this->title = 'Simpla';
  	   
  	
  	$this->smarty->assign('Products', $products);
  	$this->smarty->assign('Orders', $orders);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body=$this->smarty->fetch('main_page.tpl');
  }
}