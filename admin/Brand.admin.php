<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');


############################################
# EditBrand
############################################
class Brand extends Widget
{
  var $brand;
  function Brand(&$parent)
  {
    Widget::Widget($parent);
    $this->prepare();
  }

  function prepare()
  {
    if($this->param('item_id'))
      $this->brand->brand_id = $this->param('item_id');
    else
      $this->brand->brand_id = '';

    if(isset($_POST['name']))
    {
        $this->check_token();

  	    $this->brand->name = $_POST['name'];
  	    $this->brand->url = $_POST['url'];
  	    $this->brand->meta_title = $_POST['meta_title'];
  	    $this->brand->meta_keywords = $_POST['meta_keywords'];
  	    $this->brand->meta_description = $_POST['meta_description'];
  	    $this->brand->description = $_POST['description'];


        ## Не допустить одинаковые URL брендов.
    	$query = sql_placeholder('select count(*) as count from brands where url=? and brand_id!=?',
                $this->brand->url,
                $this->brand->brand_id);
        $this->db->query($query);
        $res = $this->db->result();

  		if(empty($this->brand->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
  		elseif($res->count>0)
  		  $this->error_msg = 'Бренд с таким URL уже существует. Выберите другой URL.';
        else
        {
  	  	  if(!empty($this->brand->brand_id))
          {
            $brand_id = $this->brand->brand_id;
            if(empty($this->brand->url))
            $this->brand->url = $brand_id;
	        $query = sql_placeholder('UPDATE brands
  	                    		  SET name=?, url=?, meta_title=?, meta_keywords=?, meta_description=?, description=?
  	                    		  WHERE brand_id=?',
  	                    		  $this->brand->name,
  	                    		  $this->brand->url,
  	                    		  $this->brand->meta_title,
  	                    		  $this->brand->meta_keywords,
  	                    		  $this->brand->meta_description,
  	                    		  $this->brand->description,
  	                    		  $this->brand->brand_id);
  	        $this->db->query($query);
          }
          else
          {
  			$query = sql_placeholder('INSERT INTO brands (name, url, meta_title, meta_keywords, meta_description, description) VALUES(?, ?, ?, ?, ?, ?)',
  			                          $this->brand->name,
  			                          $this->brand->url,
  	                    		      $this->brand->meta_title,
  	                                  $this->brand->meta_keywords,
  	                                  $this->brand->meta_description,
  	                                  $this->brand->description
 			                         );
  			$this->db->query($query);
  			$brand_id = $last_insert_id = $this->db->insert_id();
            if(empty($this->brand->url))
              $this->brand->url = $brand_id;
  		  }
     	  $get = $this->form_get(array('section'=>'Brands'));
           if(isset($_GET['from']))
				header("Location: ".$_GET['from']);
  	       else     	  
				header("Location: index.php$get");
        }

        $this->db->query("UPDATE brands SET url=brand_id WHERE url=''");
  	}
    else
  	{
      $query = sql_placeholder('SELECT *
	                    		FROM brands
	                    		WHERE brand_id=?',
            		            $this->brand->brand_id);
 	  $this->db->query($query);
  	  $this->brand = $this->db->result();
  	}
  }

  function fetch()
  {
      if(!empty($this->brand->brand_id))
    	  $this->title = 'Редактирование &laquo;'.$this->brand->name.'&raquo;';
      else
    	  $this->title = 'Новый бренд';

 	  $this->smarty->assign('Item', $this->brand);
 	  $this->smarty->assign('Error', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('brand.tpl');
  }
}