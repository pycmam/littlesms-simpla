<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');


############################################
# Class PaymentMethods
############################################
class PaymentMethods extends Widget
{
  function PaymentMethods(&$parent)
  {
    parent::Widget($parent);
    $this->prepare();
  }

  function prepare()
  {
  	if((isset($_POST['act']) && $_POST['act']=='delete' || isset($_GET['act']) && $_GET['act']=='delete') && (isset($_POST['items']) || isset($_GET['item_id']) ))
  	{
  	    $this->check_token();
  	    
        if(isset($_GET['item_id']) && !empty($_GET['item_id']))
          $item_id = intval($_GET['item_id']);  		

  		$query = sql_placeholder("DELETE FROM payment_methods
 		          WHERE payment_methods.payment_method_id = ?", $item_id);
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
    if(isset($_GET['enable_id']))
    {
      $this->check_token();
    
      $id = $_GET['enable_id'];
      $query = sql_placeholder('UPDATE payment_methods SET enabled=1-enabled WHERE payment_method_id=?',$id);
      $this->db->query($query );
  	  
  	  $get = $this->form_get(array());
      if(isset($_GET['from']))
        header("Location: ".$_GET['from']);
      else
 		header("Location: index.php$get");
    }
  }

  function fetch()
  {
    $this->title = $this->lang->PAYMENT_METHODS;

    $this->db->query("SELECT payment_methods.*, currencies.name as currency, currencies.rate_from as rate_from, currencies.rate_to as rate_to, currencies.sign as sign
                      FROM payment_methods LEFT JOIN currencies ON payment_methods.currency_id = currencies.currency_id
    				  ORDER BY payment_methods.payment_method_id");
  	$items = $this->db->results();


    foreach($items as $key=>$item)
    {
       $this->db->query("SELECT * FROM delivery_methods, delivery_payment
                         WHERE delivery_methods.delivery_method_id = delivery_payment.delivery_method_id
                         AND delivery_payment.payment_method_id = ".$item->payment_method_id."
    				     ORDER BY delivery_methods.delivery_method_id");
       $delivery_methods = $this->db->results();
       $items[$key]->delivery_methods = $delivery_methods;
       $items[$key]->edit_get = $this->form_get(array('section'=>'PaymentMethod','item_id'=>$item->payment_method_id, 'token'=>$this->token));
    }

 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('payment_methods.tpl');
  }
}