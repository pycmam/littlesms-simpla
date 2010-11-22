<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');

############################################
# Class DeliveryMethod
############################################
class DeliveryMethod extends Widget
{
  var $item;
  function DeliveryMethod(&$parent)
  {
    Widget::Widget($parent);
    $this->prepare();
  }

  function prepare()
  {
  	$item_id = intval($this->param('item_id'));
  	if(isset($_POST['name']) &&
  	   isset($_POST['price']) &&
  	   isset($_POST['free_from']))
  	{
  	
  	    $this->check_token();
  	      	
  		$this->item->name = $_POST['name'];
  		$this->item->description = $_POST['description'];
  		$this->item->price = $_POST['price'];
  		$this->item->free_from = $_POST['free_from'];
  		$this->item->enabled = 0;
  		if(isset($_POST['enabled']))
  		  $this->item->enabled = 1;

  		if(empty($this->item->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
        else
  		{
  			if(empty($item_id))
  			{
  			  $query = sql_placeholder('INSERT INTO delivery_methods (name, description, price, free_from, enabled) VALUES(?, ?, ?, ?, ?)',
                                        $this->item->name,
  			                            $this->item->description,
  			                            $this->item->price,
  			                            $this->item->free_from,
  			                            $this->item->enabled);
  			                            
  			  $this->db->query($query);                          
  			  $item_id = $this->db->insert_id();
  			}
  			else
  			{
  			  $query = sql_placeholder('UPDATE delivery_methods SET name=?, description=?, price=?, free_from=?, enabled=? WHERE delivery_method_id=?',
                                      $this->item->name,
  			                          $this->item->description,
  			                          $this->item->price,
  			                          $this->item->free_from,
  			                          $this->item->enabled,
  			                          $item_id);
  
  			  $this->db->query($query);
  			}
  			
  			// Способы доставки
  	        $query = sql_placeholder('DELETE FROM delivery_payment WHERE delivery_method_id=?', $item_id);
  	        $this->db->query($query);
  	        
  	        $payment_methods = array();
  	        if(isset($_POST['payment_methods']))
  	          $payment_methods = $_POST['payment_methods'];

            if(!empty($payment_methods))
  	        foreach($payment_methods as $k=>$payment_method)
  	        {
  	          $query = sql_placeholder('INSERT INTO delivery_payment (delivery_method_id, payment_method_id) VALUES(?, ?)', $item_id, $k);
  	          $this->db->query($query);
  	        } 

 			$get = $this->form_get(array('section'=>'DeliveryMethods'));
          if(isset($_GET['from']))
            header("Location: ".$_GET['from']);
          else
 		    header("Location: index.php$get");
  		}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM delivery_methods WHERE delivery_method_id=? LIMIT 1', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	}
  }

  function fetch()
  {
  	  if(empty($this->item->delivery_method_id))
  	    $this->title = 'Новый способ доставки';
  	  else
  	    $this->title = 'Изменение способа доставки';
  	    

  	  $query = sql_placeholder('SELECT payment_methods.*, (delivery_payment.delivery_method_id IS NOT NULL) as enabled FROM payment_methods
  	                            LEFT JOIN delivery_payment
  	                            ON payment_methods.payment_method_id=delivery_payment.payment_method_id
  	                            AND delivery_payment.delivery_method_id=?', empty($this->item->delivery_method_id)?0:$this->item->delivery_method_id);
  	  $this->db->query($query);
  	  $payment_methods = $this->db->results();       
  	  if(isset($_POST['payment_methods']))
  	  {
        foreach($payment_methods as $k=>$p_m)
        {
          if(isset($_POST['payment_methods'][$p_m->payment_method_id]))
            $payment_methods[$k]->enabled=1;
          else
          	$payment_methods[$k]->enabled=0;
        }  
  	  }
  	            

 	  $this->smarty->assign('Item', $this->item);
      $this->smarty->assign('PaymentMethods', $payment_methods);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('delivery_method.tpl');
  }
}