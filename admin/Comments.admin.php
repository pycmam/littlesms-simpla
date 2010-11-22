<?PHP
	require_once('Widget.admin.php');
	require_once('PagesNavigation.admin.php');
	require_once('../placeholder.php');


	// -- Class Name : Comments
	// -- Purpose : Manage users comments to products
	class Comments extends Widget
	{
		var $pages_navigation;
		var $items_per_page = 100;
		

		// -- Function Name : Comments
		// -- Params : &$parent
		// -- Purpose : Class Constructor
		function Comments(&$parent)
		{
			parent::Widget($parent);
			$this->add_param('page');
			$this->pages_navigation = new PagesNavigation($this);
			$this->prepare();
		}


		// -- Function Name : prepare
		// -- Params : 
		// -- Purpose : handling get and post params
		function prepare()
		{
			
			if(isset($_GET['act']) && $_GET['act']=='delete')
			{				
				$this->check_token();
		
				if(isset($_GET['item_id']))
				{
					$delete_id = intval($_GET['item_id']);
					$query = sql_placeholder("DELETE FROM products_comments
 		                          			WHERE products_comments.comment_id=? LIMIT 1", $delete_id);
					$this->db->query($query);
					
           			if(isset($_GET['from']))
            			 header("Location: ".$_GET['from']);
					
				}
			}

		}

		

		// -- Function Name : fetch
		// -- Params : 
		// -- Purpose : 
		function fetch()
		{
			$this->title = 'Комментарии к товарам';
			
			$keyword = $this->param('keyword');
			$keyword_filter = '';			
			if($keyword)
			{
				$keywords = split(' ', $keyword);
				foreach($keywords as $keyword)
				{
					$keywords = mysql_real_escape_string(trim($keyword));
					$keyword_filter .= "AND (products_comments.name LIKE '%$keyword%'
                              			OR products_comments.comment LIKE '%$keyword%'
                              			OR products_comments.ip LIKE '%$keyword%'
                              			OR products.model LIKE '%$keyword%'
                              			OR categories.single_name LIKE '%$keyword%'
                              			OR brands.name LIKE '%$keyword%'
                              			OR categories.single_name LIKE '%$keyword%') ";
				}
			}

			$current_page = $this->param('page');
			$start_item = $current_page*$this->items_per_page;
			$query = sql_placeholder("SELECT SQL_CALC_FOUND_ROWS products_comments.*,
                      				DATE_FORMAT(date, '%d.%m.%Y %H:%i') as date,
                      				products.model as model, brands.name as brand, categories.single_name as category, products.url as product_url
    				  				FROM products_comments
    				  				LEFT JOIN products
    				  				ON products.product_id = products_comments.product_id
    				  				LEFT JOIN categories
    				  				ON products.category_id = categories.category_id
    				  				LEFT JOIN brands
    				  				ON products.brand_id = brands.brand_id
    				  				WHERE 1 $keyword_filter
    				  				ORDER BY products_comments.comment_id DESC
    				  				LIMIT ?, ?", $start_item, $this->items_per_page);
			$this->db->query($query);
			$items = $this->db->results();
			
			$this->db->query("SELECT FOUND_ROWS() as count");
			$pages_num = $this->db->result();
			$pages_num = $pages_num->count/$this->items_per_page;
			
			if($items)
			{
				foreach($items as $key=>$item)
				{
					$items[$key]->delete_get = $this->form_get(array('act'=>'delete','item_id'=>$item->comment_id, 'token'=>$this->token));
				}
			}

			$this->pages_navigation->fetch($pages_num);
			
			$this->smarty->assign('Items', $items);
			$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
			$this->smarty->assign('Lang', $this->lang);
			
			$this->body = $this->smarty->fetch('comments.tpl');
		}
	}