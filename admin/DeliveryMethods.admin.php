<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');


############################################
# Class NewsLine displays news
############################################
class DeliveryMethods extends Widget
{
  function DeliveryMethods(&$parent)
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
          $items = array($_GET['item_id']);  		

  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM delivery_methods
 		          WHERE delivery_methods.delivery_method_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}

    if(isset($_GET['enable_id']))
    {
      $this->check_token();
      
      $id = $_GET['enable_id'];
      $query = sql_placeholder('UPDATE delivery_methods SET enabled=1-enabled WHERE delivery_method_id=?',$id);
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
    $this->title = 'Способы доставки';

    $this->db->query("SELECT delivery_methods.*
                      FROM delivery_methods 
    				  ORDER BY delivery_methods.delivery_method_id");
  	$items = $this->db->results();


    foreach($items as $key=>$item)
    {
       $items[$key]->edit_get = $this->form_get(array('section'=>'DeliveryMethod','item_id'=>$item->delivery_method_id, 'token'=>$this->token));
    }

 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('delivery_methods.tpl');
  }
}
