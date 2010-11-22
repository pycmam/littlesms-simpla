<?PHP
require_once('Widget.admin.php');
require_once('Storefront.admin.php');
require_once('../placeholder.php');

class Property extends Widget
{
	var $property;
	function Property(&$parent)
	{
		Widget::Widget($parent);
		$this->add_param('category');
		$this->prepare();
	}

	function prepare()
	{
		$item_id = intval($this->param('item_id'));
    
		if(isset($_POST['property_id']))
		{
			$this->check_token();

			$this->property->name = $_POST['name'];
			$this->property->categories = $_POST['categories'];
	
			$this->property->enabled = 0;
			$this->property->in_product = 0;
			$this->property->in_filter = 0;
			$this->property->in_compare = 0;
			if(isset($_POST['enabled']) && $_POST['enabled'] == 1)
				$this->property->enabled = 1;
			if(isset($_POST['in_product']) && $_POST['in_product'] == 1)
				$this->property->in_product = 1;
			if(isset($_POST['in_filter']) && $_POST['in_filter'] == 1)
				$this->property->in_filter = 1;
			if(isset($_POST['in_compare']) && $_POST['in_compare'] == 1)
				$this->property->in_compare = 1;
				
			
			$this->property->options = '';
			if(!empty($_POST['options']))
			{
				$options = split("\r\n", $_POST['options']);
				$this->property->options = serialize($options);
			}
		  
			if(empty($item_id))
			{
				$query = sql_placeholder('INSERT INTO properties
									SET name=?,
									options=?,
									in_product=?,
									in_filter=?,
									in_compare=?,
									enabled = ?',
				$this->property->name,
				$this->property->options,
				$this->property->in_product,
				$this->property->in_filter,
				$this->property->in_compare,
				$this->property->enabled
				);
	
				$this->db->query($query);
				$item_id = $this->db->insert_id();
	
				$query = sql_placeholder('UPDATE properties SET order_num=property_id WHERE property_id=?',
										  $item_id);
				$this->db->query($query);
			}
			else
			{
				$query = sql_placeholder('UPDATE properties
									SET name=?,
									options=?,
									in_product=?,
									in_filter=?,
									in_compare=?,
									enabled=?                 		
									WHERE properties.property_id=?',
						$this->property->name,
						$this->property->options,
						$this->property->in_product,
						$this->property->in_filter,
						$this->property->in_compare,
						$this->property->enabled,
						$item_id);
	
				$this->db->query($query);
			}


  		    // Категории
			$query = sql_placeholder('DELETE from properties_categories WHERE property_id=?', $item_id);
  		    $this->db->query($query);
  		    
  		    if(!empty($this->property->categories))
			foreach($this->property->categories as $cat_id)
			{
				$cat_id = intval($cat_id);
				if(!empty($cat_id))
				{
					$query = sql_placeholder('INSERT INTO properties_categories VALUES (?, ?)',
								  $item_id, $cat_id);
					$this->db->query($query);
				}
			}
	
			$get = $this->form_get(array('section'=>'Properties', 'category'=>intval($this->param('category'))));
			
			if(isset($_GET['from']))
				header("Location: ".$_GET['from']);
			else
				header("Location: index.php$get");
		}
		elseif($item_id)
		{
			$query = sql_placeholder('SELECT * FROM properties WHERE property_id=?', $item_id);
			$this->db->query($query);
			$this->property = $this->db->result();

			$this->property->options = unserialize($this->property->options);
			
			$this->property->categories=array();
			$query = sql_placeholder("SELECT * FROM properties_categories WHERE property_id = ?", $item_id);
			$this->db->query($query);
			$cats = $this->db->results();
			foreach($cats as $cat)
				$this->property->categories[]=$cat->category_id;
							
		}
	}

  function fetch()
  {
  	  if(empty($this->property->name))
  	    $this->title = 'Новое свойство';
      else
  	    $this->title = $this->property->name;

 	  $categories = Storefront::get_categories();

 	  $this->smarty->assign('Categories', $categories);
 	  $this->smarty->assign('Error', $this->error_msg);
 	  $this->smarty->assign('Property', $this->property);
	  $this->smarty->assign('Lang', $this->lang);
 	  

 	  $this->body = $this->smarty->fetch('property.tpl');
  }
}

