<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('Storefront.admin.php');


############################################
# Class goodCategories displays a list of products categories
############################################
class Categories extends Widget
{

  function Categories(&$parent)
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


  		$query = "SELECT cats.* FROM categories cats
  					LEFT JOIN products ON products.category_id=cats.category_id
                    LEFT JOIN categories subcats   ON subcats.parent = cats.category_id
  					WHERE (products.product_id is not null OR subcats.category_id is not null)
  					AND (cats.category_id IN ('$items_sql')) GROUP BY cats.category_id";
  		$this->db->query($query);
  		$noemptycats = $this->db->results();
  		
  		if(!empty($noemptycats))
  		{
  		  $this->error_msg = "Категория ";
  		  foreach($noemptycats as $cat)
  		  {
  		    	 $this->error_msg .= "$cat->name ";
  		  }
          $this->error_msg .= "не может быть удалена. Она содержит товары или подкатегории.";
  		}

  		$query = "DELETE cats, products_categories, properties_categories FROM categories cats
  					LEFT JOIN products ON products.category_id=cats.category_id
                    LEFT JOIN categories subcats   ON subcats.parent = cats.category_id
                    LEFT JOIN products_categories ON products_categories.category_id = cats.category_id
                    LEFT JOIN properties_categories ON properties_categories.category_id = cats.category_id
  					WHERE products.product_id is null AND subcats.category_id is null
  					AND cats.category_id IN ('$items_sql')";
  		$this->db->query($query);

  		if(empty($this->error_msg))
  		{
  		  $get = $this->form_get(array());
 		  header("Location: index.php$get");
  		}
 	}
  	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
  	    $this->check_token();
  	    
  		$category_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=s1.category_id
  		                  FROM categories s1, categories s2
  		                  WHERE s1.parent=s2.parent AND s1.order_num>s2.order_num
  		                  AND s2.category_id = '$category_id'
  		                  ORDER BY s1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE categories s1, categories s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.category_id = '$category_id'
  		                  AND s2.category_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  	    $this->check_token();
  	    
  		$category_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=s1.category_id
  		                  FROM categories s1, categories s2
  		                  WHERE s1.parent=s2.parent AND  s1.order_num<s2.order_num
  		                  AND s2.category_id = '$category_id'
  		                  ORDER BY s1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE categories s1, categories s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.category_id = '$category_id'
  		                  AND s2.category_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
  	
    # Сделать категорию видимой
    if(isset($_GET['set_enabled']))
    {
      $this->check_token();
      
      $id = $_GET['set_enabled'];
      $query = sql_placeholder('UPDATE categories SET enabled=1-enabled WHERE category_id=?',$id);
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
  	$this->title = $this->lang->PRODUCTS_CATEGORIES;

    $categories = Storefront::get_categories();

 	$this->smarty->assign('Categories', $categories);
  	$this->smarty->assign('Error', $this->error_msg);
    $this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('categories.tpl');
  }
}
