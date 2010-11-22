<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');

############################################
# Class Currency
############################################
class Currency extends Widget
{

  function Currency(&$parent)
  {
	parent::Widget($parent);
    $this->prepare();
  }


  function prepare()
  {
  	if(isset($_GET['set_main']))
  	{
  	    $this->check_token();
        $main =  intval($_GET['set_main']);

		// Recalculate all prices
  	    $from_currency = $this->main_currency;  	    
        $this->db->query("SELECT * FROM currencies WHERE currency_id='$main'");
        $to_currency = $this->db->result();        
                        
        // Recalculate rates
        if($this->param('recalculate')==1)
        {
			$coef = $to_currency->rate_from/$to_currency->rate_to;        
			$this->db->query("UPDATE products_variants SET price=price*$coef");   
			$this->db->query("UPDATE delivery_methods SET price=price*$coef");        
			$this->db->query("UPDATE orders SET delivery_price=delivery_price*$coef");        
			$this->db->query("UPDATE orders_products SET price=price*$coef");                

			$this->db->query("UPDATE currencies SET rate_from=rate_from/$to_currency->rate_from");
			$this->db->query("UPDATE currencies SET rate_to=rate_to/$from_currency->rate_to");
		}

        $this->db->query("UPDATE currencies SET main=0");
        $this->db->query("UPDATE currencies SET main=1 where currency_id='$main'");

  		$get = $this->form_get(array());
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
 		  header("Location: index.php$get");
 	}
  	if(isset($_GET['set_default']))
  	{
  	    $this->check_token();
  	    
		$default =  intval($_GET['set_default']);

        $this->db->query("UPDATE currencies SET def=0");
        $this->db->query("UPDATE currencies SET def=1 where currency_id='$default'");

  		$get = $this->form_get(array());
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
 		  header("Location: index.php$get");
 	}
  	if(isset($_GET['delete_id']))
  	{
       	$this->check_token();
  	    
        $delete_id =  intval($_GET['delete_id']);
        
        $this->db->query("SELECT name FROM payment_methods WHERE currency_id='$delete_id'");
        $payment_methods = $this->db->results();
        
        if($payment_methods)
        {	
        	foreach($payment_methods as $p)
        		$payment_array[] = $p->name;
        	$error = 'Валюту нельзя удалить, так как она используется в следующих формах оплаты: '.join(', ', $payment_array);
        	$this->smarty->assign('Error', $error);
        }
        else
        {
        
			$this->db->query("DELETE FROM currencies WHERE main!=1 AND def!=1 AND currency_id='$delete_id'");
			
			if($this->db->affected_rows() == 0)
			{
				$this->smarty->assign('Error', 'Нельзя удалить базовую валюту или валюту, установленную по умолчанию');
			}
			else
			{
			
				$get = $this->form_get(array());
				if(isset($_GET['from']))
				  header("Location: ".$_GET['from']);
				else
				  header("Location: index.php$get");
			}
        
        }        
 	}
  	if(isset($_POST['act']) && $_POST['act']=='add' && isset($_POST['name']))
  	{
  	    if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
  	    {
  	      header('Location: http://'.$this->root_url.'/admin/');
  	      exit();
  	    }

        $name =  $_POST['name'];
        $rate_from =  $_POST['rate_from'];
        $rate_to =  $_POST['rate_to'];
        $sign =  $_POST['sign'];
        $code =  $_POST['code'];

  		$query = "INSERT INTO currencies (name, rate_from, rate_to, sign, code) VALUES('$name', '$rate_from', '$rate_to', '$sign', '$code')";
  		$this->db->query($query);

  		$get = $this->form_get(array());
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
 		  header("Location: index.php$get");
 	}
 	
    if(isset($_POST['names']))
    {
  	    if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
  	    {
  	      header('Location: http://'.$this->root_url.'/admin/');
  	      exit();
	    }

      $names = $_POST['names'];
      foreach($names as $id=>$name)
      {
        $rate_from =  $_POST['rates_from'][$id];
        $rate_to =  $_POST['rates_to'][$id];
        $sign =  $_POST['signs'][$id];
        $code =  $_POST['codes'][$id];
        $this->db->query("UPDATE currencies SET name='$name', sign='$sign', code='$code', rate_from='$rate_from', rate_to='$rate_to' WHERE currency_id='$id'");
      }
      if(isset($_GET['from']))
        header("Location: ".$_GET['from']);            
    }
  }

  function fetch()
  {
    $this->title = 'Валюты';

    $this->db->query("SELECT * FROM currencies ORDER BY currency_id");
    $items = $this->db->results();

 	$this->smarty->assign('Items', $items);
    $this->smarty->assign('Lang', $this->lang);
	$this->body = $this->smarty->fetch('currencies.tpl');
  }
}