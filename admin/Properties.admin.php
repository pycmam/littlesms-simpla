<?PHP
require_once('Widget.admin.php');
require_once('Storefront.admin.php');
require_once('../placeholder.php');

############################################
# Class Properties displays a list of product parameters
############################################
class Properties extends Widget
{

  var $menu;
  
  function Properties(&$parent)
  {
	parent::Widget($parent);
    $this->add_param('menu');
    $this->add_param('category');

    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_GET['delete_item_id']))
  	{
  		$this->check_token();

        $delete_item_id = $this->param('delete_item_id');  		
        
  		$query = sql_placeholder("DELETE properties, properties_categories, properties_values FROM properties LEFT JOIN properties_categories ON properties.property_id=properties_categories.property_id
  									LEFT JOIN properties_values ON properties.property_id=properties_values.property_id
  									WHERE properties.property_id=?", $delete_item_id);
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
 	
  	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
  		$this->check_token();  		
  		$category_id = intval($this->param('category'));
  		$property_id = intval($this->param('item_id'));
  		$this->db->query("SELECT @id:=s1.property_id
  		                  FROM properties s1, properties s2, properties_categories
  		                  WHERE
  		                  ((properties_categories.property_id = s1.property_id
  		  		          AND properties_categories.category_id='$category_id') OR '$category_id'=0)
		                  AND s1.order_num>s2.order_num
  		                  AND s2.property_id = '$property_id'
  		  		          ORDER BY s1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE properties s1, properties s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.property_id = '$property_id'
  		                  AND s2.property_id = @id");
  		$get = $this->form_get(array());
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
  		header("Location: index.php$get");
  	}
  	
 	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$this->check_token();  		
  		$category_id = intval($this->param('category'));
  		$property_id = intval($this->param('item_id'));
  		$this->db->query("SELECT @id:=s1.property_id
  		                  FROM properties s1, properties s2, properties_categories
  		                  WHERE
  		                  ((properties_categories.property_id = s1.property_id
  		  		          AND properties_categories.category_id='$category_id') OR '$category_id'=0)
		                  AND s1.order_num<s2.order_num
  		                  AND s2.property_id = '$property_id'
  		  		          ORDER BY s1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE properties s1, properties s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.property_id = '$property_id'
  		                  AND s2.property_id = @id");
  		$get = $this->form_get(array());
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
  		header("Location: index.php$get");
  	}
  	
    # Сделать параметр видимым
    if(isset($_GET['set_enabled']))
    {
      $this->check_token();
      $id = intval($this->param('set_enabled'));
      $query = sql_placeholder('UPDATE properties SET enabled=1-enabled WHERE property_id=? LIMIT 1', $id);
      $this->db->query($query );
  	  
  	  $get = $this->form_get(array());
      if(isset($_GET['from']))
        header("Location: ".$_GET['from']);
      else
 		header("Location: index.php$get");
    }
  	
    if(isset($_GET['set_in_product']))
    {
      $this->check_token();
      $id = intval($this->param('set_in_product'));
      $query = sql_placeholder('UPDATE properties SET in_product=1-in_product WHERE property_id=? LIMIT 1', $id);
      $this->db->query($query );
  	  
  	  $get = $this->form_get(array());
      if(isset($_GET['from']))
        header("Location: ".$_GET['from']);
      else
 		header("Location: index.php$get");
    }
  	
    # Сделать параметр видимым
    if(isset($_GET['set_in_compare']))
    {
      $this->check_token();
      $id = intval($this->param('set_in_compare'));
      $query = sql_placeholder('UPDATE properties SET in_compare=1-in_compare WHERE property_id=? LIMIT 1', $id);
      $this->db->query($query );
  	  
  	  $get = $this->form_get(array());
      if(isset($_GET['from']))
        header("Location: ".$_GET['from']);
      else
 		header("Location: index.php$get");
    }
  	
    # Сделать параметр видимым
    if(isset($_GET['set_in_filter']))
    {
      $this->check_token();
      $id = intval($this->param('set_in_filter'));
      $query = sql_placeholder('UPDATE properties SET in_filter=1-in_filter WHERE property_id=? LIMIT 1', $id);
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
    $this->title = 'Свойства товаров';

    $categories = Storefront::get_categories();

  	$category_id = intval($this->param('category'));

  	if($category_id)
  	{
  		$current_category = Storefront::category_by_id($categories, $category_id);
   	    $query = sql_placeholder("SELECT properties.*
								FROM  properties,  properties_categories
								WHERE  properties.property_id =  properties_categories. property_id
								AND category_id=?
								ORDER BY properties.order_num", $category_id);
  	}else
  	{
    	$query = sql_placeholder("SELECT * FROM  properties ORDER BY order_num");
  	
  	}

  	
    $this->db->query($query);
    
  	$properties = $this->db->results();

    foreach($properties as $key=>$property)
    {
       $properties[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$property->property_id, 'category'=>$category_id, 'token'=>$this->token));
       $properties[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$property->property_id, 'category'=>$category_id, 'token'=>$this->token));
       $properties[$key]->edit_get = $this->form_get(array('section'=>'Property','item_id'=>$property->property_id, 'category'=>$category_id, 'token'=>$this->token));
       $properties[$key]->delete_get = $this->form_get(array('delete_item_id'=>$property->property_id, 'token'=>$this->token));
       $properties[$key]->enable_get = $this->form_get(array('set_enabled'=>$property->property_id, 'token'=>$this->token));
       $properties[$key]->in_product_get = $this->form_get(array('set_in_product'=>$property->property_id, 'token'=>$this->token));
       $properties[$key]->in_compare_get = $this->form_get(array('set_in_compare'=>$property->property_id, 'token'=>$this->token));
       $properties[$key]->in_filter_get = $this->form_get(array('set_in_filter'=>$property->property_id, 'token'=>$this->token));
    }

  	$this->smarty->assign('Categories', $categories);
  	$this->smarty->assign('CurrentCategory', $current_category);
 	$this->smarty->assign('Properties', $properties);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('properties.tpl');
  }
}
