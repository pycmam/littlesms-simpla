<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Класс для поиска товаров на сайте
 * Этот класс использует шаблон search.tpl
 *
 */
 
require_once('Widget.class.php');
require_once('placeholder.php');

class Search extends Widget
{
	var $max=300; // Максимальное количество найденных товаров
	/**
	 *
	 * Конструктор
	 *
	 */
	function Search(&$parent)
	{
		Widget::Widget($parent);
	}
	
	/**
	 *
	 * Отображение
	 *
	 */
	function fetch()
	{	 
		$keyword = strip_tags($this->param('keyword'));
		$keys = preg_split('/\s/', $keyword, 4);
		$s = '';
		
		foreach ($keys as $k=>$key)
		{
			
			if (strlen($key)>2)
			{
				$key = mysql_real_escape_string($key);
				$s.= " AND (categories.name LIKE '%$key%' OR brands.name LIKE '%$key%' OR products.model LIKE  '%$key%' OR products.description LIKE '%$key%' OR products.body LIKE '%$key%' )";
			}
		}
		
		$keyword = join(' ', $keys);
		
		if (!empty($s))
		{
			$query =  "SELECT products.*, categories.single_name as category, categories.url as category_url, brands.name as brand, brands.url as brand_url  FROM categories, products LEFT JOIN brands ON brands.brand_id = products.brand_id WHERE categories.category_id = products.category_id AND categories.enabled=1 AND products.enabled=1 $s GROUP BY products.product_id ORDER BY products.order_num DESC LIMIT $this->max";
			$this->db->query($query);
			$products = $this->db->results();
			if($this->db->num_rows() == 1)
			{
				$product_url = $products[0]->url;
				header('Location: http://'.$this->root_url.'/products/'.$product_url);
				exit();			
			}
		}
		else
		{
			$products = array();
		}
 
		$this->title = 'Поиск '.htmlspecialchars($keyword);
		$this->smarty->assign('products', $products);
		$this->smarty->assign('keyword', $keyword);
		$this->body = $this->smarty->fetch('search.tpl');
		return $this->body;
	}
}

