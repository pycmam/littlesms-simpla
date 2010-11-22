<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');


############################################
# Class PaymentMethod
############################################
class PaymentMethod extends Widget
{
  var $item;
  function PaymentMethod(&$parent)
  {
    Widget::Widget($parent);
    $this->prepare();
  }

  function prepare()
  {
  	$item_id = intval($this->param('item_id'));
  	if(isset($_POST['name']) &&
  	   isset($_POST['currency_id']) &&
  	   isset($_POST['description']))
  	{
  	    $this->check_token();
  	    
  		$this->item->name = $_POST['name'];
  		$this->item->currency_id = $_POST['currency_id'];
  		$this->item->description = $_POST['description'];
  		$this->item->module = $_POST['module'];
  		$this->item->params = $_POST['params'];
  		$this->item->enabled = 0;
  		if(isset($_POST['enabled']))
  		  $this->item->enabled = 1;

  		if(empty($this->item->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
        else
  		{
  			if(empty($item_id))
  			{
  			  $query = sql_placeholder('INSERT INTO payment_methods (name, currency_id, description, module, enabled, params) VALUES(?, ?, ?, ?, ?, ?)',
                                        $this->item->name,
  			                            $this->item->currency_id,
  			                            $this->item->description,
  			                            $this->item->module,
  			                            $this->item->enabled,
  			                            serialize($this->item->params));
  			                            
  			  $this->db->query($query);                          
  			  $item_id = $this->db->insert_id();
  			}
  			else
  			{
  			  $query = sql_placeholder('UPDATE payment_methods SET name=?, currency_id=?, description=?, module=?, enabled=?, params=? WHERE payment_method_id=?',
                                      $this->item->name,
  			                          $this->item->currency_id,
  			                          $this->item->description,
  			                          $this->item->module,
  			                          $this->item->enabled,
  			                          serialize($this->item->params),
  			                          $item_id);
  
  			  $this->db->query($query);
  			}
  			
  			// Способы доставки
  	        $query = sql_placeholder('DELETE FROM delivery_payment WHERE payment_method_id=?', $item_id);
  	        $this->db->query($query);
  	        
  	        $delivery_methods = $_POST['delivery_methods'];

            if(!empty($delivery_methods))
  	        foreach($delivery_methods as $k=>$delivery_method)
  	        {
  	          $query = sql_placeholder('INSERT INTO delivery_payment (delivery_method_id, payment_method_id) VALUES(?, ?)', $k, $item_id);
  	          $this->db->query($query);
  	        } 



 			$get = $this->form_get(array('section'=>'PaymentMethods'));
          if(isset($_GET['from']))
            header("Location: ".$_GET['from']);
          else
 		    header("Location: index.php$get");
  		}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM payment_methods WHERE payment_method_id=?', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	  $this->item->params = unserialize($this->item->params);
  	}
  }

  function fetch()
  {
  	  if(empty($this->item->payment_method_id))
  	    $this->title = 'Новый способ оплаты';
  	  else
  	    $this->title = 'Изменение способа оплаты';
  	    
  	  $query = sql_placeholder('SELECT * FROM currencies');
  	  $this->db->query($query);
  	  $currencies = $this->db->results();

  	  $query = sql_placeholder('SELECT delivery_methods.*, (delivery_payment.payment_method_id IS NOT NULL) as enabled FROM delivery_methods
  	                            LEFT JOIN delivery_payment
  	                            ON delivery_methods.delivery_method_id=delivery_payment.delivery_method_id
  	                            AND delivery_payment.payment_method_id=?', empty($this->item->payment_method_id)?1:$this->item->payment_method_id);
  	  $this->db->query($query);
  	  $delivery_methods = $this->db->results(); 
  	        
  	  if(isset($_POST['delivery_methods']))
  	  {
        foreach($delivery_methods as $k=>$d_m)
        {
          if(isset($_POST['delivery_methods'][$d_m->delivery_method_id]))
            $delivery_methods[$k]->enabled=1;
          else
          	$delivery_methods[$k]->enabled=0;
        }  
  	  }
  	            

 	  $this->smarty->assign('Item', $this->item);
      $this->smarty->assign('Currencies', $currencies);
      $this->smarty->assign('DeliveryMethods', $delivery_methods);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('payment_method.tpl');
  }
}