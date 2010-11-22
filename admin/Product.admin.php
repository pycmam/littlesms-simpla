<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('../placeholder.php');
require_once('Storefront.admin.php');

############################################
# Class Product - edit the static section
############################################
class Product extends StorefrontGeneral
{
  var $item;
  function Product(&$parent)
  {
    StorefrontGeneral::StorefrontGeneral($parent);
    $this->add_param('page');
    $this->add_param('brand_id');
    $this->add_param('category');
    $this->prepare();
  }

  function prepare()
  {
  	$this->item->product_id = intval($this->param('item_id'));
  	if(!$this->item->product_id)
  	  $this->item->product_id = intval(isset($_POST['product_id'])?$_POST['product_id']:null);
  	
    $this->item->category_id = $this->param('category');
    $this->item->brand_id = $this->param('brand_id');

  	if(!empty($this->item->product_id))
  	{
      $this->item = $this->get_product($this->item->product_id);
  	}
  	
  	if(isset($_POST['category_id']) &&
  	   isset($_POST['brand_id']) &&
  	   isset($_POST['model']) &&
  	   isset($_POST['description']) &&
  	   isset($_POST['body']))
  	{

  	    $this->check_token();

  		$this->item->url = $_POST['url'];
  		$this->item->category_id = $_POST['category_id'];
  		$this->item->brand_id =  $_POST['brand_id'];
  		$this->item->model = trim($_POST['model']);
  		$this->item->description = $_POST['description'];
  		$this->item->body = $_POST['body'];
  		$this->item->meta_title = $_POST['meta_title'];
  		$this->item->meta_keywords = $_POST['meta_keywords'];
  		$this->item->meta_description = $_POST['meta_description'];
  		$this->item->related = trim($_POST['related']);
  		$this->item->categories = $_POST['categories'];

        if(isset($_POST['enabled']))
  		  $this->item->enabled = 1;
        else
  		  $this->item->enabled = 0;
        if(isset($_POST['hit']))
  		  $this->item->hit = 1;
        else
  		  $this->item->hit = 0;

        ## Не допустить одинаковые URL товаров.
    	$query = sql_placeholder('select count(*) as count from products where url=? and product_id!=?',
                $this->item->url,
                $this->item->product_id);
        $this->db->query($query);
        $res = $this->db->result();


  		if(empty($this->item->model))
  		  $this->error_msg = $this->lang->ENTER_MODEL;
  		elseif($res->count>0)
  		  $this->error_msg = 'Товар с таким URL уже существует. Выберите другой URL.';
        else
  		{
  			if(empty($this->item->product_id))
  			{

  			   $query = sql_placeholder('INSERT INTO products(url, category_id, brand_id, model, description, body, enabled, hit, meta_title, meta_keywords, meta_description, created, modified) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now(), now())',
                                      $this->item->url,
                                      $this->item->category_id,
  			                          $this->item->brand_id,
  			                          $this->item->model,
  			                          $this->item->description,
  			                          $this->item->body,
                                      $this->item->enabled,
                                      $this->item->hit,
                                      $this->item->meta_title,
                                      $this->item->meta_keywords,
                                      $this->item->meta_description                                      
                                      );
	  			$this->db->query($query);
  			    $this->item->product_id = $this->db->insert_id();
  		    	$query = sql_placeholder('UPDATE products SET order_num=product_id WHERE product_id=?',
  			                          $this->item->product_id);
  			    $this->db->query($query);
  			    
                ### Save product to session
                $_SESSION['last_added_product'] = $this->item;
                ###
  			}
  			else
  			{
 				if(empty($this->error_msg))
 				{
	  				$query = sql_placeholder('UPDATE products SET url=?, category_id=?, brand_id=?, model=?, description=?, body=?, enabled=?, hit=?, meta_title=?, meta_keywords=?, meta_description=?, modified=now() WHERE product_id=?',
                                              $this->item->url,
                                              $this->item->category_id,
  					                          $this->item->brand_id,
  					                          $this->item->model,
  			                		          $this->item->description,
  			                        		  $this->item->body,
  			                        		  $this->item->enabled,
                                              $this->item->hit,
                                              $this->item->meta_title,
                                              $this->item->meta_keywords,
                                              $this->item->meta_description,
  			                          		  $this->item->product_id);
	  			    $this->db->query($query);
 				}
  			}

             $this->db->query("UPDATE products SET url=product_id WHERE url=''");

             ## Если нужно, удаляем фотографии товара

             if(isset($_POST['delete_small_image']) && $_POST['delete_small_image']==1)
             {
                $this->delete_small_image($this->item->product_id);
             }

             if(isset($_POST['delete_large_image']) && $_POST['delete_large_image']==1)
             {
               $this->delete_large_image($this->item->product_id);
             }

             if(isset($_POST['delete_fotos']))
             {
                $delete_fotos = split(',', $_POST['delete_fotos']);
                $delete_fotos;
                $this->delete_images($this->item->product_id, $delete_fotos);
             }
             if(!isset($_POST['digital_product']))
             {
             	$this->remove_download($this->item->product_id);
             }

            $fotos_added = $this->add_fotos($this->item->product_id);
            $download_added = $this->add_download($this->item->product_id);


  		    // Варианты товара

  		    // транспонируем матрицу вариантов
  		    if(isset($_POST['variants']))
  		    {
				foreach($_POST['variants'] as $n=>$va)
					foreach($va as $i=>$v)
						$variants[$i][$n] = $v;
				
				$position = 1;

 
				///////////// Спасибо fefa4ka@gmail.com за этот блок!
				//////////////
				$variants_ids = array();
				foreach($variants as $variant_id=>$variant)
				{  
					if($variant['id'] > 0)
					{
								$query = sql_placeholder('UPDATE products_variants SET product_id=?, sku=?, name=?, price=?, stock=?, position=? WHERE variant_id = ?',
				    	        $this->item->product_id, $variant['sku'], $variant['name'], $variant['price'], $variant['stock'], $position++, $variant_id);
				                $this->db->query($query);
						
						$variants_ids[] = $variant_id;
					}
					else
					{
						$query = sql_placeholder('INSERT INTO products_variants (product_id, variant_id, sku, name, price, stock, position) VALUES (?, NULL, ?, ?, ?, ?, ?)',
						$this->item->product_id, $variant['sku'], $variant['name'], $variant['price'], $variant['stock'], $position++);
						$this->db->query($query);
									    
						 // Узнаём присвоенный айдишник 
						$this->db->query("SELECT LAST_INSERT_ID() as id");
						$result  = $this->db->results();
						$variants_ids[] = $result[0]->id;
					}
				}
				// Удаляем другие варианты, которые были, но не были переданы для сохранения
				$query = sql_placeholder("DELETE FROM products_variants WHERE product_id = ? AND variant_id NOT IN (?@)", $this->item->product_id, $variants_ids);
				$this->db->query($query);	
				////////////////

			}


			
  			
  		    // Связанные товары
			$query = sql_placeholder('DELETE from related_products WHERE product_id=?', $this->item->product_id);
  		    $this->db->query($query);
  		    
			$related_products = split(',', $this->item->related);
			foreach($related_products as $sku)
			{
				$sku = trim($sku);
				// существует ли такой товар?
				if(!empty($sku))
				{
				  $query = sql_placeholder('SELECT product_id FROM products_variants WHERE sku=? LIMIT 1',$sku);
				  $this->db->query($query);
				  $prod = $this->db->result();
				  if(!empty($prod))
				  {
					$query = sql_placeholder('INSERT INTO related_products VALUES (?, ?)',
								  $this->item->product_id, $sku);
					$this->db->query($query);
				  }
				}
			}
			
  		    // Дополнительные категории
			$query = sql_placeholder('DELETE from products_categories WHERE product_id=?', $this->item->product_id);
  		    $this->db->query($query);
  		    
  		    if($_POST['use_additional_categories'])
			foreach($this->item->categories as $additional_category_id)
			{
				$additional_category_id = intval($additional_category_id);
				// существует ли такой товар?
				if(!empty($additional_category_id))
				{
					$query = sql_placeholder('INSERT INTO products_categories VALUES (?, ?)',
								  $this->item->product_id, $additional_category_id);
					$this->db->query($query);
				}
			}

  		    // Характеристики товара
			$query = sql_placeholder('DELETE from properties_values WHERE product_id=?', $this->item->product_id);
  		    $this->db->query($query);
  		    
			$query = sql_placeholder("SELECT properties_categories.property_id FROM properties_categories WHERE category_id=?", $this->item->category_id);
			$this->db->query($query);
			$category_properties = $this->db->results();
  	 
			foreach($category_properties as $property)
			{
				if(isset($_POST[properties][$property->property_id]) && $_POST[properties][$property->property_id] != '')
				{
					$query = sql_placeholder('INSERT INTO properties_values VALUES (?, ?, ?)',
								  $this->item->product_id, $property->property_id, $_POST[properties][$property->property_id]);
					$this->db->query($query);
				}
			}

  			if(empty($this->error_msg))
  			{
                if($fotos_added ||  $download_added)
                {
    				$get = $this->form_get(array('section'=>'Product', 'item_id'=>$this->item->product_id, 'from'=>$this->param('from'), 'token'=>$this->token));
    		    	header("Location: index.php$get");
                }
                elseif(isset($_GET['from']))
                {
                    header("Location: ".$this->param('from'));
                }
                else
                {
    				$get = $this->form_get(array('section'=>'Storefront', 'page'=>$this->param('page'), 'category'=>$this->param('category'), 'brand_id'=>$this->param('brand_id')));
    		    	header("Location: index.php$get");
                }
  			}

  			
  		}
  	}
    elseif(isset($_SESSION['last_added_product']) && $_SESSION['last_added_product']->category_id == $this->item->category_id && $_SESSION['last_added_product']->brand == $this->item->brand && !$this->item->product_id)
    {
       $this->item = $_SESSION['last_added_product'];
       unset($this->item->product_id);
    }

  }

  function fetch()
  {
    if ($this->item->product_id && !$_POST)
    {
      //$this->item = $this->get_product($this->item->product_id);
      $this->title = $this->lang->EDIT_PRODUCT.' &laquo;'.$this->item->brand.' '.$this->item->model.'&raquo;';
  	}
    else
    {
      $this->title = $this->lang->NEW_PRODUCT;
    }


 	  $categories = Storefront::get_categories();
 	  $category_id = $this->param('category');
 	  if(empty($category_id))
 	    $category_id = $this->item->category_id;
 	  $category = Storefront::category_by_id($categories, $category_id);


 	  $this->db->query("SELECT * FROM brands  ORDER BY name");
      $brands = $this->db->results();
      
      $query = sql_placeholder("SELECT properties.*, properties_values.value as value FROM properties 
      							LEFT JOIN properties_values ON properties_values.product_id = ? AND properties_values.property_id = properties.property_id
      							WHERE properties.enabled
      							ORDER BY properties.order_num",
      							$this->item->product_id);
 	  $this->db->query($query);
      $properties = $this->db->results();
      
      if($properties)
      foreach($properties as $k=>$property)
      {
      
		$query = sql_placeholder("SELECT properties_categories.category_id FROM properties_categories WHERE property_id=?", $property->property_id);
      	$this->db->query($query);
      	$property_categories = $this->db->results();
      	$properties[$k]->categories = $property_categories;
   
      	if(!empty($property->options))
      		$properties[$k]->options = unserialize($property->options);
      }

 	  $this->smarty->assign('Categories', $categories);
 	  $this->smarty->assign('Category', $category);
 	  $this->smarty->assign('Properties', $properties);
 	  $this->smarty->assign('Brands', $brands);
 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('Error', $this->error_msg);
 	  $this->smarty->assign('MaxImageSize', $this->max_image_size*1024);
      $this->smarty->assign('Lang', $this->lang);
      $this->smarty->assign('FotosNum', $this->fotos_num);
 	  $this->body = $this->smarty->fetch('product.tpl');
  }
  
}


?>