<?PHP 

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('../placeholder.php');
require_once('StorefrontGeneral.admin.php');

############################################
# General class for storefront
############################################

class StorefrontGeneral extends Widget
{
  var $items_per_page = 10; # Количество товаров на странице
  var $uploaddir = '../files/products/'; # Папка для хранения картинок к товарам
  var $downloadsdir = '../files/downloads/'; # Папка для хранения файлов к цифровым товарам
  var $accepted_file_types = array('image/pjpeg', 'image/gif', 'image/jpeg', 'image/jpg', 'image/png');
  var $max_image_size = 1000; # Максимальный размер картинки
  var $fotos_num = 10; #Количество дополнительных изображения товара

  function StorefrontGeneral(&$parent)
  {
	parent::Widget($parent);
  }

  function get_product($id)  # Возвращает товар из базы
  {
      # Берем из базы товар
  	  $query = sql_placeholder('SELECT products.*, brands.name as brand, categories.name as category_name, categories.single_name as category_single_name FROM products LEFT JOIN brands ON products.brand_id=brands.brand_id LEFT JOIN categories ON products.category_id=categories.category_id WHERE products.product_id=?', $id);
  	  $this->db->query($query);
 	  $product = $this->db->result();

      # Берем из базы варианты товара
  	  $query = sql_placeholder('SELECT * FROM products_variants WHERE products_variants.product_id=? ORDER BY position', $id);
  	  $this->db->query($query);
 	  $product->variants = $this->db->results();

      # Берем из базы картинки к товару
      $query = sql_placeholder("SELECT * FROM products_fotos WHERE product_id=?", $id);
      $this->db->query($query);
      $fotos = $this->db->results();
      $product->fotos = array();

      # Нам нужны только имена файлов для товара
      if(!empty($fotos))
        foreach($fotos as $key=>$foto)
        {
          $product->fotos[$foto->foto_id] = $foto->filename;
        }

      # Связанные товары
      $product->related=array();
      $query=sql_placeholder("SELECT * FROM related_products WHERE product_id=?", $id);
      $this->db->query($query);
      $related = $this->db->results();
      if(!empty($related))
        foreach($related as $r)
      	$product->related[]=$r->related_sku;
      	
      # Берем из базы дополнительные категории
      $product->additional_categories=array();
      $query=sql_placeholder("SELECT * FROM products_categories WHERE product_id=?", $id);
      $this->db->query($query);
      $add_cats = $this->db->results();
      foreach($add_cats as $cat)
      	$product->additional_categories[]=$cat->category_id;
      

      return $product;
  }

  function delete_small_image($product_id) # Удаляем маленькую картинку товара
  {
        # Берем из базы товар, походу вычисляя количество товаров, использующих эту же картинку
        $query = "SELECT p1.*, count(*) as count FROM products p1, products p2 WHERE p1.small_image = p2.small_image AND p1.small_image!='' AND p1.product_id = '$product_id' GROUP BY p1.small_image";
        $this->db->query($query);
        $product = $this->db->result();
        if(!empty($product->small_image))
        {
          $file = $this->uploaddir.$product->small_image;

          # Если только этот товар использует этот файл картинки, удаляем файл
  		  if($product->count<=1 && is_file($file))
  		    unlink($file);
  		}

        # И удаляем из товара картинку
        $this->db->query("UPDATE products SET small_image='' WHERE product_id=$product_id");
  }

  function delete_large_image($product_id) # Удаляем большую картинку товара
  {
        # Берем из базы товар, походу вычисляя количество товаров, использующих эту же картинку
        $query = "SELECT p1.*, count(*) as count FROM products p1, products p2 WHERE p1.large_image = p2.large_image AND p1.large_image!='' AND p1.product_id = '$product_id' GROUP BY p1.large_image";
        $this->db->query($query);
        $product = $this->db->result();
        if(!empty($product->large_image))
        {    
          $file = $this->uploaddir.$product->large_image;

          # Если только этот товар использует этот файл картинки, удаляем файл
  		  if($product->count<=1 && is_file($file))
  		    unlink($file);
  		}

        # И удаляем из товара картинку
        $this->db->query("UPDATE products SET large_image='' WHERE product_id=$product_id");
  }

  function delete_images($product_id, $fotos_ids = null) # Удаление дополнительных изображений товара. Если не указаны id картинок, удаляем все
  {
     # Если указан массив картинок, подлежащих удалению
     if(is_array($fotos_ids))
     {
       foreach($fotos_ids as $foto_id)
       {
         if($foto_id != '')
         {
             # Выбираем информацию о картинке (нам нужно имя файла)
             $query = "SELECT * FROM products_fotos WHERE foto_id = '$foto_id' and product_id = '$product_id'";
             $this->db->query($query);
             $foto = $this->db->result();
             
             # Использует ли еще кто-то эту картинку
             $query = sql_placeholder("SELECT * FROM products_fotos WHERE filename=? AND product_id != ?", $foto->filename, $product_id);
             $this->db->query($query);
             $fotoexists = $this->db->result();
                          
             # Удаляем из базы связь товара с этой картинкой
             $this->db->query("DELETE FROM products_fotos WHERE product_id = '$product_id' AND foto_id = '$foto_id'");
             $file = $this->uploaddir.$foto->filename;

             # Если только этот товар использует этот файл картинки, удалем этот файл
  			 if(!$fotoexists && is_file($file))
  			   unlink($file);
         }
       }
     }
     # Если не указаны id картинок, удаляем все доп. картинки этого товара
     elseif($fotos_ids === null)
     {
         # Выбираем информацию о картинках (нам нужны имена файлов), походу вычисляя количество товаров, использующих эту же картинку
         $query = "SELECT p1.*, count(*) as count FROM products_fotos p1, products_fotos p2  WHERE p1.filename=p2.filename AND p1.filename !='' AND p1.product_id = '$product_id' GROUP BY p1.filename";
         $this->db->query($query);
         $fotos = $this->db->results();

         foreach($fotos as $foto)
         {
           $file = $this->uploaddir.$foto->filename;
           # Если только этот товар использует этот файл картинки, удалем этот файл
  		   if($foto->count <=1 && is_file($file))
  		     unlink($file);
         }
         # Удаляем все картинки этого товара
         $this->db->query("DELETE FROM products_fotos WHERE product_id = '$product_id'");
     }
  }
  
  function image_resize($source_file, $dest_file, $max_width, $max_height)
  {
     if($this->use_gd)
     {
       $old_image = imagecreatefromstring(file_get_contents($source_file));

       $new_width = $old_width = imageSX($old_image);
       $new_height = $old_height= imageSY($old_image);

       if($old_width > $max_width && $max_width>0)
       {
         $new_height = $old_height * ($max_width/$old_width);
         $new_width = $max_width;
       }
       if($new_height > $max_height && $max_height>0)
       {
         $new_width = $new_width * ($max_height/$new_height);
         $new_height = $max_height;
       }
       $new_image=ImageCreateTrueColor($new_width,$new_height);
       imageinterlace($new_image, true);
       
       imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
   
       imagejpeg($new_image, $dest_file, $this->settings->image_quality);
     }elseif($source_file != $dest_file)
     {
       copy($source_file, $dest_file);
     }
  }

  function add_fotos($product_id)
  {
     $result = false;
  
     if(isset($_FILES['fotos']) || isset($_POST['fotos_url']))
     {
        for($i=0; $i<$this->fotos_num; $i++)
        {
          $image_uploaded = false;
          $uploadfile = $product_id."_$i.jpg";
          $image_url = str_replace('http://', '', $_POST['fotos_url'][$i]);
          if(!empty($_FILES['fotos']['name'][$i]))
          {
			 if (!@move_uploaded_file($_FILES['fotos']['tmp_name'][$i], $this->uploaddir.$uploadfile))
		  	   $this->error_msg = $this->lang->FILE_UPLOAD_ERROR;
		  	 else
		  	 {
		  	   $image_uploaded = true;
		  	   $result = true;
		  	 }
		  }
          elseif(!empty($image_url))
          {
            
            if(@copy('http://'.$image_url, $this->uploaddir.$uploadfile))
              $image_uploaded = true;
            else
              $this->error_msg = "Не могу загрузить ".($i+1)."-e дополнительное изображение с указанного адреса";         
          }

		  if($image_uploaded)	   
		  {	 
             // Подгоняем размер картинки
             if($this->use_gd)
             {	
 	           $this->image_resize($this->uploaddir.$uploadfile, $this->uploaddir.$uploadfile,
 	                             $this->settings->product_adimage_width, $this->settings->product_adimage_height);
		  	 }	 
		  	 @chmod($this->uploaddir.$uploadfile, 0644);
             $this->db->query("INSERT INTO products_fotos (product_id, foto_id, filename) VALUES ('$product_id', '$i', '$uploadfile')");
             $result = true;          
          }
        }
     }
     $largeuploadfile = $product_id."_large.jpg";
     $smalluploadfile = $product_id."_small.jpg";
     
     /// Загрузка большой картинки
     $large_image_uploaded = false;
     $large_image_url = str_replace('http://', '', $_POST['large_image_url']); 
     if(isset($_FILES['large_image']) && !empty($_FILES['large_image']['tmp_name']))
  	 {
	   if (!move_uploaded_file($_FILES['large_image']['tmp_name'], $this->uploaddir.$largeuploadfile))
		 $this->error_msg = $this->lang->FILE_UPLOAD_ERROR;
	   else
		 $large_image_uploaded = true;
     }elseif(!empty($large_image_url))
     {
       if(@copy('http://'.$large_image_url, $this->uploaddir.$largeuploadfile))
         $large_image_uploaded = true;
       else
         $this->error_msg = 'Не могу загрузить изображение с указанного адреса';         
     }
     if($large_image_uploaded)
     {		 
       // Подгоняем размер картинки	
       if($this->use_gd)
       {
 	     $this->image_resize($this->uploaddir.$largeuploadfile, $this->uploaddir.$largeuploadfile,
 	                       $this->settings->product_image_width, $this->settings->product_image_height);
       }
	   @chmod($this->uploaddir.$largeuploadfile, 0644); 
       $this->db->query("UPDATE products SET large_image='$largeuploadfile' WHERE product_id=$product_id");
       $result = true;
 	 }
 	 
 	 /// Загрузка маленькой картинки
     $small_image_uploaded = false;
     $small_image_url = str_replace('http://', '', $_POST['small_image_url']);       	 
     if(isset($_FILES['small_image']) && !empty($_FILES['small_image']['tmp_name']))
  	 {
       $uploadfile = $product_id."_small.jpg";
	   if (!move_uploaded_file($_FILES['small_image']['tmp_name'], $this->uploaddir.$smalluploadfile))
		 $this->error_msg = $this->lang->FILE_UPLOAD_ERROR;		 
	   else
		 $small_image_uploaded = true;
     }elseif(!empty($small_image_url))
     {
       if(@copy('http://'.$small_image_url, $this->uploaddir.$smalluploadfile))
         $small_image_uploaded = true;
       else
         $this->error_msg = 'Не могу загрузить маленькое изображение с указанного адреса';         
     }
     if($small_image_uploaded)
     {		 
         // Подгоняем размер картинки	
         if($this->use_gd)
         {
 	       $this->image_resize($this->uploaddir.$smalluploadfile, $this->uploaddir.$smalluploadfile,
 	                           $this->settings->product_thumbnail_width, $this->settings->product_thumbnail_height);
 	     }	 
 	     @chmod($this->uploaddir.$smalluploadfile, 0644);
         $this->db->query("UPDATE products SET small_image='$smalluploadfile' WHERE product_id=$product_id");
 	     $result = true;  
 	 }
 	
 	 if(is_file($this->uploaddir.$largeuploadfile) && isset($_POST['auto_small']) && $_POST['auto_small'] == '1' && is_file($this->uploaddir.$largeuploadfile))
 	 {
 	   if($this->use_gd)
 	   {
 	     $this->image_resize($this->uploaddir.$largeuploadfile, $this->uploaddir.$smalluploadfile, $this->settings->product_thumbnail_width, $this->settings->product_thumbnail_height);
 	     @chmod($this->uploaddir.$smalluploadfile, 0644);
         $this->db->query("UPDATE products SET small_image='$smalluploadfile' WHERE product_id=$product_id");
         $result = true;
       }else
       {
         $this->error_msg = 'Не доступна библиотека GD для обработки изображения';
       }
 	 }
 	 
 	 return $result;
  }
  
  
  
  function add_download($product_id)
  {
     $result = false;
  
     if(isset($_FILES['download_file']))
     {
          $file_uploaded = false;
          $uploadfile = $_FILES['download_file']['name'];
          
          if(!empty($_FILES['download_file']['name']))
          {
			 if (!@move_uploaded_file($_FILES['download_file']['tmp_name'], $this->downloadsdir.$uploadfile))
		  	   $this->error_msg = $this->lang->FILE_UPLOAD_ERROR;
		  	 else
		  	 {
		  	   $file_uploaded = true;
		  	   $result = true;
		  	 }
		  }

		  if($file_uploaded)	   
		  {	 
		  	 $query = sql_placeholder("UPDATE products SET download=? WHERE product_id=?", $uploadfile, $product_id);
             $this->db->query($query);
             $result = true;          
          }

     }

 	 return $result;
  }
  
  function remove_download($product_id) 
  {

	# Выбираем информацию о картинке (нам нужно имя файла), походу вычисляя количество товаров, использующих эту же картинку
	$query = "SELECT download FROM products WHERE product_id = '$product_id' LIMIT 1";
	$this->db->query($query);
	$p = $this->db->result();
	$download_file = $p->download;

	if($download_file != '')
	{
		$file = $this->downloadsdir.$download_file;
		if(is_file($file))
			unlink($file);
		$query = sql_placeholder("UPDATE products SET download='' WHERE product_id=?", $product_id);
		$this->db->query($query);    
	}
  }

  
  
	// Функция возвращает подкатегории
	function categories_tree($categories)
	{		
		$tree = array();
		
		// Указатели на узлы дерева
		$used_items = array(); 

		$end = false;
		
		// Не кончаем, пока не кончатся категории, или пока ниодну из оставшихся некуда приткнуть
		while(!empty($categories) && !$end)
		{	 
			foreach($categories as $k=>$category)
			{ 				
				$flag = false;
				if($category->parent == 0)
				{
					// Добавляем элемент в дерево
					$cat = null;
					$cat->name = $category->name;
					$cat->category_id = $category->category_id;
					$cat->url = $category->url;
					$category->path[0] = $cat;

					$tree[$category->category_id] = $category;
					$used_items[$category->category_id] = &$tree[$category->category_id];
					unset($categories[$k]);
					$flag = true;
				}else
				{
					if($used_items[$category->parent])
					{
						$cat = null;
						$cat->name = $category->name;
						$cat->category_id = $category->category_id;
						$cat->url = $category->url;
						
						$category->path = $used_items[$category->parent]->path;
						$category->path[] = $cat;

					
						$used_items[$category->parent]->subcategories[$category->category_id] = $category;
						$used_items[$category->category_id] = &$used_items[$category->parent]->subcategories[$category->category_id];
						unset($categories[$k]);
						$flag = true;
					}
				}
			}
			if(!$flag)
				$end = true;
		}
		
		$used_items = array_reverse($used_items, true);
		foreach($used_items as $k=>$item)
		{
			$used_items[$item->category_id]->subcats_ids[] = $item->category_id;
			if(is_array($used_items[$item->parent]->subcats_ids))
				$used_items[$item->parent]->subcats_ids =  array_merge($used_items[$item->parent]->subcats_ids, $item->subcats_ids);
			else
				$used_items[$item->parent]->subcats_ids = $item->subcats_ids;
		} 
		return $tree;
	}

  // Функция возвращает рекурсивно подкатегории
  function category_by_id($categories, $id)
  { 
    foreach($categories as $category)
    {
      if($category->category_id == $id)
      {
        return $category;
      }
      elseif(is_array($category->subcategories))
      {
        if($result =  StorefrontGeneral::category_by_id($category->subcategories, $id))
          return $result;
      }
    }
    return false;
  }
  

  // Функция возвращает категории товаров, и их подкатегории
  function get_categories($parent=0)
  {
  	  // Выбираем все категории
      $query = sql_placeholder("SELECT * FROM categories
                                ORDER BY parent, order_num", $parent);
      $this->db->query($query);
      $temp_categories = $this->db->results();

      $categories = StorefrontGeneral::categories_tree($temp_categories);

      return $categories;
  }
  
}


?>