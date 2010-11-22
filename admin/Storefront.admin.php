<?PHP 

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('../placeholder.php');
require_once('StorefrontGeneral.admin.php');

########################################
class Storefront extends StorefrontGeneral
{
  var $pages_navigation; # Класс для постраничной навигации
  var $error = '';

  function Storefront(&$parent)
  {
	parent::StorefrontGeneral($parent);
    $this->add_param('page');
    $this->add_param('category');
    $this->add_param('brand_id');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }


  function prepare()
  {
  	$current_brand_id = $this->param('brand_id');

    # Удаление товара 
  	if((isset($_GET['act']) && $_GET['act']=='delete' && isset($_GET['item_id'])) || (isset($_POST['delete_items'])))
  	{
		$this->check_token();

  		$items = isset($_POST['delete_items'])?$_POST['delete_items']:null;
        if(isset($_GET['item_id']) && !empty($_GET['item_id']))
          $items = array($_GET['item_id']);

        foreach($items as $item)
        {
          $this->delete_small_image($item);
          $this->delete_large_image($item);
          $this->delete_images($item);
          $this->remove_download($item);
        }

        $items_sql = implode("', '", $items);
  	    $query = "DELETE products, products_fotos, products_comments, related_products, products_categories, products_variants, properties_values FROM products
                  LEFT JOIN products_fotos ON products.product_id = products_fotos.product_id
                  LEFT JOIN products_comments ON products_comments.product_id = products.product_id
                  LEFT JOIN related_products ON (products.product_id = related_products.product_id OR products.product_id = related_products.related_sku)
                  LEFT JOIN products_categories ON products_categories.product_id = products.product_id
                  LEFT JOIN products_variants ON products_variants.product_id = products.product_id
                  LEFT JOIN properties_values ON products.product_id = properties_values.product_id
                  WHERE products.product_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
  		
//        if(isset($_GET['from']))
 //         header("Location: ".$_GET['from']);
  //      else
 //		  header("Location: index.php$get");
 	}

    # Создание копии товара
  	if(isset($_GET['action']) && $_GET['action']=='copy' && isset($_GET['item_id']))
  	{
  	  $this->check_token();
  	  
      $id = $_GET['item_id'];
      $query = "INSERT INTO products (category_id, brand_id, model, description, body, enabled, hit, small_image, large_image, meta_title, meta_keywords, meta_description, created, modified) SELECT category_id, brand_id, CONCAT(model, ' (копия)'), description, body, 0, 0, small_image, large_image, meta_title, meta_keywords, meta_description, now(), now() FROM products WHERE product_id=$id";
      $this->db->query($query);
      $new_id = $this->db->insert_id();
      
      $query = "INSERT INTO products_variants (product_id, name, sku, price, stock, position) SELECT $new_id, name, sku, price, stock, position FROM products_variants WHERE product_id=$id";
      $this->db->query($query);

  	  $query = sql_placeholder('UPDATE products SET order_num=product_id, url=product_id WHERE product_id=?', $new_id);
      $this->db->query($query);
      $query = "INSERT INTO products_fotos (product_id, foto_id, filename) SELECT $new_id, foto_id, filename FROM products_fotos WHERE product_id=$id";
      $this->db->query($query);
      $query = "INSERT INTO related_products (product_id, related_id) SELECT $new_id, related_id FROM related_products WHERE product_id=$id";
      $this->db->query($query);
      $query = "INSERT INTO products_categories (product_id, category_id) SELECT $new_id, category_id FROM products_categories WHERE product_id=$id";
      $this->db->query($query);
      $query = "INSERT INTO properties_values (product_id, property_id, value) SELECT $new_id, property_id, value FROM properties_values WHERE product_id=$id";
      $this->db->query($query);

      # Сразу переход на его редактирование
      $get = $this->form_get(array('section'=>'Product','item_id'=>$new_id, 'page'=>'', 'token'=>$this->token, 'from'=>$_GET['from']));
      header("Location: index.php$get");

 	}

    # Изменение цен товаров
    if(isset($_POST['prices']))
    {
		$this->check_token();
      
      	$prices = $_POST['prices'];
      	foreach($prices as $id=>$price)
      	{
        	$stock =  $_POST['stock'][$id];
        	$this->db->query("UPDATE products_variants SET price='$price', stock='$stock' WHERE variant_id='$id'");
      	}
    }

    # Сделать товар хитом
    if(isset($_GET['set_hit']))
    {    
      $this->check_token();
      
      $id = $_GET['set_hit'];
      $query = sql_placeholder('UPDATE products SET hit=1-hit WHERE product_id=?',$id);
      $this->db->query($query );
  	  
  	  $get = $this->form_get(array());
      if(isset($_GET['from']))
        header("Location: ".$_GET['from']);
      else
 		header("Location: index.php$get");
    }

    # Сделать товар видимым
    if(isset($_GET['set_enabled']))
    {
      $this->check_token();
      
      $id = $_GET['set_enabled'];
      $query = sql_placeholder('UPDATE products SET enabled=1-enabled WHERE product_id=?',$id);
      $this->db->query($query );
  	  
  	  $get = $this->form_get(array());
      if(isset($_GET['from']))
        header("Location: ".$_GET['from']);
      else
 		header("Location: index.php$get");
    }

    # Сдвинуть товар вверх
  	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  	    $this->check_token();  	    
  		$product_id = $_GET['item_id'];
  		
		$current_category_id = $this->param('category');
		$category_filter = '';
		if($current_category_id)
		{
		  $categories = Storefront::get_categories();
		  $current_category = Storefront::category_by_id($categories, $current_category_id);
		  $subcats_list = join($current_category->subcats_ids, ',');  
		  $category_filter = "AND s1.category_id in ($subcats_list)";
		}

  		if($this->param('brand_id')) $filter_brand = ' AND s1.brand_id=s2.brand_id'; else $filter_brand = '';
  	 	$this->db->query("SELECT @id:=s1.product_id
  		                  FROM products s1, products s2
  		                  WHERE s1.order_num>s2.order_num $category_filter $filter_brand
  		                  AND s2.product_id = '$product_id'
  		                  ORDER BY s1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE products s1, products s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.product_id = '$product_id'
  		                  AND s2.product_id = @id");
  		$get = $this->form_get(array());
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
 		  header("Location: index.php$get");
  	}

    # Сдвинуть товар вниз
 	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
  	    $this->check_token();  	    
  		$product_id = $_GET['item_id'];

		$current_category_id = $this->param('category');
		$category_filter = '';
		if($current_category_id)
		{
		  $categories = Storefront::get_categories();
		  $current_category = Storefront::category_by_id($categories, $current_category_id);
		  $subcats_list = join($current_category->subcats_ids, ',');  
		  $category_filter = "AND s1.category_id in ($subcats_list)";
		}

  		if($this->param('brand_id')) $filter_brand = ' AND s1.brand_id=s2.brand_id'; else $filter_brand = '';
  		$this->db->query("SELECT @id:=s1.product_id
  		                  FROM products s1, products s2
  		                  WHERE s1.order_num<s2.order_num  $category_filter $filter_brand
  		                  AND s2.product_id = '$product_id'
  		                  ORDER BY s1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE products s1, products s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.product_id = '$product_id'
  		                  AND s2.product_id = @id");
  		$get = $this->form_get(array());
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
 		  header("Location: index.php$get");
  	}

  }

  function fetch()
  {
    $this->title = $this->lang->PRODUCTS;
  	$current_page = $this->param('page');
  	
  	if(!empty($this->settings->products_num_admin))
  		$this->items_per_page = $this->settings->products_num_admin;

  	$current_brand_id = $this->param('brand_id');
  	if($current_brand_id)
  	{
      $this->db->query("SELECT * FROM brands WHERE brand_id = '$current_brand_id'");
      $current_brand = $this->db->result();
    }

    $categories = Storefront::get_categories();
    
  	$current_category_id = $this->param('category');
    $category_filter = '';
    if($current_category_id)
    {
      $current_category = Storefront::category_by_id($categories, $current_category_id);
      $subcats_list = join($current_category->subcats_ids, ',');  
      $category_filter = "AND categories.category_id in ($subcats_list)";
    }


    if(!empty($current_category))
       $query = "SELECT brands.* FROM products, categories, brands WHERE products.brand_id = brands.brand_id AND products.category_id = categories.category_id AND (categories.category_id='$current_category->category_id' or categories.parent='$current_category->category_id') GROUP BY brands.name ORDER BY brands.name";
    else
       $query = "SELECT brands.* FROM products, brands WHERE products.brand_id = brands.brand_id GROUP BY brands.name ORDER BY brands.name";
    $this->db->query($query);
    $brands = $this->db->results();
 	foreach($brands as $k=>$brand)
  	{
  		  $brands[$k]->brand_url = $this->form_get(array('brand_id'=>$brand->brand_id, 'page'=>''));
  	}



    $brand_filter = '';
    if($current_brand_id)
      $brand_filter = "AND products.brand_id = '$current_brand_id' ";
      
    $keyword = $this->param('keyword');
    $keyword_filter = '';
    if($keyword)
    {
      $keywords = split(' ', $keyword);
      foreach($keywords as $keyword)
      {
        $keywords = mysql_real_escape_string(trim($keyword));
        $keyword_filter .= "AND (products_variants.sku LIKE '%$keyword%' OR  products.model LIKE '%$keyword%' OR products.description LIKE '%$keyword%' OR products.body LIKE '%$keyword%' ) ";
      }
    }
      
      
  	$start_item = $current_page*$this->items_per_page;
    $query = sql_placeholder("SELECT SQL_CALC_FOUND_ROWS products.*, categories.name as category_name, categories.single_name as category_single_name,  categories.url as category_url, brands.name as brand, brands.url as brand_url, COUNT(products_comments.comment_id) AS comments_num 
    				  FROM products
                      LEFT JOIN brands ON products.brand_id = brands.brand_id
                      LEFT JOIN products_comments ON products.product_id = products_comments.product_id
                      LEFT JOIN products_variants ON products.product_id = products_variants.product_id,
                      categories
    				  WHERE
    				  products.category_id = categories.category_id
    				  $category_filter                      
                      $brand_filter $keyword_filter
                      GROUP BY products.product_id
    				  ORDER BY products.order_num DESC
    				  LIMIT ? ,?", $start_item, $this->items_per_page);

    $this->db->query($query);
	$temp_products = $this->db->results();
	foreach($temp_products as $product)
			$items[$product->product_id] = $product;

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;
    

    # END Варианты товаров    
	if(is_array($items))
	{
		$ids = array_keys($items);

 		$query = sql_placeholder("SELECT products_variants.*,
				products_variants.stock as stock,
				products_variants.name as variant_name
				FROM products_variants WHERE products_variants.product_id in (?@)  
				ORDER BY products_variants.position", $ids);
		$this->db->query($query);

		$variants = $this->db->results();
		foreach($variants as $variant)
		{
			if(!empty($items[$variant->product_id]))
			{
				$items[$variant->product_id]->variants[]=$variant;
			}			
		}
	}
    # END Варианты товаров    

    if($items)
    foreach($items as $key=>$item)
    {
       $items[$key]->edit_get = $this->form_get(array('section'=>'Product','item_id'=>$item->product_id, 'token'=>$this->token));
       $items[$key]->copy_get = $this->form_get(array('action'=>'copy','item_id'=>$item->product_id, 'token'=>$this->token));
       $items[$key]->set_hit_get = $this->form_get(array('set_hit'=>$item->product_id, 'token'=>$this->token));
       $items[$key]->set_enabled_get = $this->form_get(array('set_enabled'=>$item->product_id, 'token'=>$this->token));
       $items[$key]->delete_get = $this->form_get(array('act'=>'delete', 'item_id'=>$item->product_id, 'token'=>$this->token));
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->product_id, 'token'=>$this->token));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->product_id, 'token'=>$this->token));
    }

  	$this->pages_navigation->fetch($pages_num);
 	$this->smarty->assign('Items', $items);
 	$this->smarty->assign('Brands', $brands);
 	$this->smarty->assign('Categories', $categories);
 	$this->smarty->assign('CurrentCategory', empty($current_category)?0:$current_category);
 	$this->smarty->assign('CurrentBrand', empty($current_brand)?0:$current_brand);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('CreateGoodURL', $this->form_get(array('section'=>'Product', 'category'=>empty($current_category->category_id)?0:$current_category->category_id, 'brand'=>empty($current_brand->brand_id)?0:$current_brand->brand_id, 'token'=>$this->token)));
    $this->smarty->assign('Lang', $this->lang);
    $this->smarty->assign('Error', $this->error);
	$this->body = $this->smarty->fetch('products.tpl');
  }
}


?>