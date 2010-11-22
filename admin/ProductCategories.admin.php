<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('placeholder.php');


############################################
# Class goodCategories displays a list of products categories
############################################
class ProductCategories extends Widget
{

  function ProductCategories(&$parent)
  {
    Widget::Widget($parent);
    $this->add_param('page');
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
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
  		  $this->error_msg = "—ледующие категории не могут быть удалены:<BR>";
  		  foreach($noemptycats as $cat)
  		  {
  		    	 $this->error_msg .= "$cat->name<BR>";
  		  }
          $this->error_msg .= " атегори€ содержит товары или подкатегории.";
  		}

  		$query = "DELETE cats FROM categories cats
  					LEFT JOIN products ON products.category_id=cats.category_id
                    LEFT JOIN categories subcats   ON subcats.parent = cats.category_id
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
  }

  function fetch()
  {
  	$this->title = $this->lang->PRODUCTS_CATEGORIES;

    $categories = Storefront::get_categories();

 	$this->smarty->assign('Categories', $categories);
  	$this->smarty->assign('ErrorMSG', $this->error_msg);
    $this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('categories.tpl');
  }
}

############################################
# Class EditGoodCategory - Edit the good gategory
############################################
class EditProductCategory extends Widget
{
  var $category;
  var $max_level = 1;
  var $uploaddir = '../foto/categories/';
  function EditProductCategory(&$parent)
  {
    Widget::Widget($parent);
    $this->add_param('parent');
    $this->prepare();
  }

  function prepare()
  {
    $this->category->category_id = $this->param('item_id');
    $this->category->enabled=1;

    if(isset($_POST['name']))
    {
  	    $this->category->name = $_POST['name'];
  	    $this->category->description = $_POST['description'];
  	    $this->category->url = $_POST['url'];
        $this->category->enabled = 0;
        $this->category->parent = $_POST['parent'];
        if(isset($_POST['enabled']))
          $this->category->enabled = 1;

  		if(empty($this->category->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
  		elseif(!empty($this->category->category_id))
        {
          $category_id = $this->category->category_id;
          if(empty($this->category->url))
            $this->category->url = $category_id;
	      $query = sql_placeholder('UPDATE categories
  	                    		  SET name=?, description=?, url=?, enabled=?, parent=?
  	                    		  WHERE category_id=?',
  	                    		  $this->category->name,
                                  $this->category->description,
  	                    		  $this->category->url,
                                  $this->category->enabled,
                                  $this->category->parent,
  	                    		  $this->category->category_id);
  	      $this->db->query($query);
        }
        else
        {
  			$query = sql_placeholder('INSERT INTO categories (parent, name, description, url, enabled) VALUES(?, ?, ?, ?, ?)',
  									  $this->category->parent,
  			                          $this->category->name,
                                      $this->category->description,
  			                          $this->category->url,
                                      $this->category->enabled
  			                         );
  			$this->db->query($query);
  			$category_id = $last_insert_id = $this->db->insert_id();
            if(empty($this->category->url))
              $this->category->url = $category_id;
  			$query = sql_placeholder('UPDATE categories SET order_num=category_id, url=? WHERE category_id=?',
  			                          $this->category->url, $last_insert_id
  			                         );
  			$this->db->query($query);
  		}

        if(isset($_POST['delete_image']) && $_POST['delete_image']==1)
        {
            $this->db->query("SELECT * FROM categories WHERE category_id = '$category_id'");
            $category = $this->db->result();
            $file = $this->uploaddir.$category->image;
  		    if(is_file($file))
  		      unlink($file);
            $this->db->query("UPDATE categories SET image='' WHERE category_id=$category_id");
        }

       if(isset($_FILES['image']) && !empty($_FILES['image']['tmp_name']))
  	   {
         $uploadfile = $category_id.".jpg";
	     if (!move_uploaded_file($_FILES['image']['tmp_name'], $this->uploaddir.$uploadfile))
		   $this->error_msg = $this->lang->FILE_UPLOAD_ERROR;
         $this->db->query("UPDATE categories SET image='$uploadfile' WHERE category_id='$category_id'");
 	   }


  	  $this->db->query($query);
  	  $get = $this->form_get(array('section'=>'ProductCategories'));
  	  header("Location: index.php$get");
  	}
    elseif($this->category->category_id)
  	{
      $query = sql_placeholder('SELECT *
	                    		FROM categories
	                    		WHERE category_id=?',
            		            $this->category->category_id);
 	  $this->db->query($query);
  	  $this->category = $this->db->result();
  	}
  }

  function fetch()
  {
      $categories = Storefront::get_categories();
      if($this->category->category_id)
    	  $this->title = $this->lang->EDIT_CATEGORY.' &laquo;'.$this->category->name.'&raquo;';
      else
    	  $this->title = $this->lang->NEW_CATEGORY;

 	  $this->smarty->assign('Item', $this->category);
 	  $this->smarty->assign('Categories', $categories);
 	  $this->smarty->assign('Parent', $parent);
 	  $this->smarty->assign('MaxLevel', $max_level);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('category.tpl');
  }
}