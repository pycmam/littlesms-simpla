<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');

class Brands extends Widget
{
  var $error_msg;
  function Brands(&$parent)
  {
    Widget::Widget($parent);
    $this->add_param('page');
    $this->prepare();
  }

  function prepare()
  {
  	if((isset($_POST['act']) && $_POST['act']=='delete' || isset($_GET['act']) && $_GET['act']=='delete') && (isset($_POST['items']) || isset($_GET['item_id']) ))
  	{  
 
        $this->check_token();

  		$items = $_POST['items'];
        if(isset($_GET['item_id']) && !empty($_GET['item_id']))
          $items = array($_GET['item_id']);

  		$items_sql = implode("', '", $items);


  		$query = "SELECT brands.* FROM brands
  					LEFT JOIN products ON products.brand_id=brands.brand_id
  					WHERE products.product_id is not null
  					AND brands.brand_id IN ('$items_sql') GROUP BY brands.brand_id";
  		$this->db->query($query);
  		$noemptybrands = $this->db->results();
  		if(!empty($noemptybrands))
  		{
  		  $this->error_msg = "Бренд ";
  		  foreach($noemptybrands as $brand)
  		  {
  		    	 $this->error_msg .= "$brand->name ";
  		  }
  		  $this->error_msg .= " не могжет быть удален, так как сожержит товары";
  		}
  		else
        {
  		  $query = "DELETE brands FROM brands
  					LEFT JOIN products ON products.brand_id=brands.brand_id
  					WHERE products.product_id is null
  					AND brands.brand_id IN ('$items_sql')";
  		  $this->db->query($query);

  		  $get = $this->form_get(array());
 		  header("Location: index.php$get");
  		}
  		 
 	}
  }

  function fetch()
  {
  	$this->title = 'Бренды';

    $query = "SELECT * FROM brands ORDER BY name";
    $this->db->query($query);
    $brands = $this->db->results();
    foreach($brands as $k=>$brand)
      {
        $brands[$k]->edit_get = $this->form_get(array('section'=>'Brand','item_id'=>$brand->brand_id, 'token'=>$this->token));
        $brands[$k]->delete_get = $this->form_get(array('act'=>'delete', 'item_id'=>$brand->brand_id, 'token'=>$this->token));
      }


 	$this->smarty->assign('Brands', $brands);
  	$this->smarty->assign('Error', $this->error_msg);
    $this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('brands.tpl');
  }
}

