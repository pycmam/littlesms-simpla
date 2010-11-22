<?php
require_once('Widget.admin.php');
require_once('Storefront.admin.php');
require_once('../placeholder.php');

############################################
# Class Category - Edit the good gategory
############################################
class Category extends Widget
{
  var $category;
  var $max_level = 5;
  var $uploaddir = '../files/categories/';
  function Category(&$parent)
  {
    Widget::Widget($parent);
    $this->add_param('parent');
    $this->prepare();
  }

  function prepare()
  {
    $this->category->category_id = $this->param('item_id');
    $this->category->enabled=1;
    
    if($this->category->category_id)
  	{
      $query = sql_placeholder('SELECT *
	                    		FROM categories
	                    		WHERE category_id=?',
            		            $this->category->category_id);
 	  $this->db->query($query);
  	  $this->category = $this->db->result();
  	}

    if(isset($_POST['name']))
    {
  	    $this->category->name = $_POST['name'];
  	    $this->category->single_name = $_POST['single_name'];
  	    $this->category->meta_title = $_POST['meta_title'];
  	    $this->category->meta_keywords = $_POST['meta_keywords'];
  	    $this->category->meta_description = $_POST['meta_description'];
  	    $this->category->description = $_POST['description'];
  	    $this->category->url = $_POST['url'];
        $this->category->enabled = 0;
        $this->category->parent = $_POST['parent'];
        if(isset($_POST['enabled']))
          $this->category->enabled = 1;

        $this->check_token();
        
        $category_id = $this->category->category_id;
          
	    ## Не допустить одинаковые URL категорий.
	  	$query = sql_placeholder('SELECT count(*) AS count FROM categories WHERE url=? AND categories.category_id!=? ',
	                $this->category->url, intval($category_id));
	    $this->db->query($query);
	    $res = $this->db->result();

          
  		if(empty($this->category->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
  	    elseif($res->count>0)
  		  $this->error_msg = 'Категория с таким URL уже существует';
  		elseif(!empty($this->category->category_id))
        {

          if(empty($this->category->url))
            $this->category->url = $category_id;
	      $query = sql_placeholder('UPDATE categories
  	                    		  SET name=?, single_name=?, meta_title=?, meta_keywords=?, meta_description=?, description=?, url=?, enabled=?, parent=?
  	                    		  WHERE category_id=?',
  	                    		  $this->category->name,
  	                    		  $this->category->single_name,
  	                    		  $this->category->meta_title,
  	                    		  $this->category->meta_keywords,
  	                    		  $this->category->meta_description,
                                  $this->category->description,
  	                    		  $this->category->url,
                                  $this->category->enabled,
                                  $this->category->parent,
  	                    		  $this->category->category_id);
  	      $this->db->query($query);
        }
        else
        {
  			$query = sql_placeholder('INSERT INTO categories (parent, name, single_name, meta_title, meta_keywords, meta_description, description, url, enabled) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)',
  									  $this->category->parent,
  			                          $this->category->name,
  			                          $this->category->single_name,
  			                          $this->category->meta_title,
  			                          $this->category->meta_keywords,
  			                          $this->category->meta_description,
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
         
         

         if(!empty($category_id) && isset($_POST['delete_image']) && $_POST['delete_image']==1)
         {
            $this->db->query("SELECT * FROM categories WHERE category_id = '$category_id'");
            $category = $this->db->result();
            $file = $this->uploaddir.$category->image;
  		    if(is_file($file))
  		      unlink($file);
            $this->db->query("UPDATE categories SET image='' WHERE category_id=$category_id");
         }

         if(!empty($category_id))
  	     {
           $uploadfile = $category_id.".jpg";
   	       if(isset($_FILES['image']) && !empty($_FILES['image']['tmp_name']))
   	       {
             if (!move_uploaded_file($_FILES['image']['tmp_name'], $this->uploaddir.$uploadfile))
               $this->error_msg = $this->lang->FILE_UPLOAD_ERROR; 
             else
             {
               @chmod($this->uploaddir.$uploadfile, 0644); 
               $this->db->query("UPDATE categories SET image='$uploadfile' WHERE category_id='$category_id'");  	       
             }
   	       }elseif(isset($_POST['image_url']))
   	       {
   	         $image_url = trim($_POST['image_url']);
   	         if(preg_match("/^http:\/\/.+(\.jpg|\.jpeg)/i", $image_url))
   	         {
   	            $image_content = @file_get_contents($image_url);
   	            if(!empty($image_content))
   	            {
   	              $image_file = fopen($this->uploaddir.$uploadfile, 'wb');
   	              fwrite($image_file, $image_content);
   	              fclose($image_file);  
   	              $this->db->query("UPDATE categories SET image='$uploadfile' WHERE category_id='$category_id'");	         
   	            }
   	         }  	  
   	         
   	       }

 	     }
  	   
  	     if(empty($this->error_msg))
  	     { 
  	       $get = $this->form_get(array('section'=>'Categories'));
           if(isset($_GET['from']))
             header("Location: ".$_GET['from']);
  	       else
             header("Location: index.php$get");
 	     }
 	     
 	  
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
 	  $this->smarty->assign('MaxLevel', $this->max_level);
      $this->smarty->assign('Lang', $this->lang);
      $this->smarty->assign('Error', $this->error_msg);
 	  $this->body = $this->smarty->fetch('category.tpl');
  }
}