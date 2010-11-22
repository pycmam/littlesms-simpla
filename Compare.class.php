<?PHP

/**
 * Simpla CMS
 * Compare class: Стравнение товаров
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Этот класс использует шаблон compare.tpl
 *
 */
 
require_once('Widget.class.php');
require_once('Storefront.class.php');

class Compare extends Widget
{
	var $max_products = 4;
	
	/**
	 *
	 * Конструктор
	 *
	 */	
	function Compare($parent)
	{
		Widget::Widget($parent);
		if($this->param('product_url'))
		{
			$_SESSION['compared_products'][$this->param('product_url')] = $this->param('product_url');
			header("Location: http://$this->root_url/compare");
		}
		
		if(count($_SESSION['compared_products'])>$this->max_products)
			array_shift($_SESSION['compared_products']);
		
		if($this->param('remove_product_url'))
		{
			unset($_SESSION['compared_products'][$this->param('remove_product_url')]);
			header("Location: http://$this->root_url/compare");
		}
		
	}
		
	/**
	 *
	 * Отображение отдельного товара
	 *
	 */	
	function fetch()
	{
	
		// Выбираем товары из базы
		foreach($_SESSION['compared_products'] as $product_url)
		{
			$products[] = Storefront::get_product($product_url);
		}
		
		$properties = array();
		if($products)
		foreach($products as $k=>$product)
		{
			// Дополнительные фото товара
			$query = sql_placeholder("SELECT * FROM products_fotos WHERE product_id =  ?", $product->product_id);
			$this->db->query($query);
			$products[$k]->fotos = $this->db->results();
			
			foreach($product->properties as $property)
			{
				if($property->in_compare)
				$properties[$property->name][$product->product_id] = $property->value;			
			}			
		}
		
			
		// И передаем его в шаблон
		$this->smarty->assign('products', $products);
		$this->smarty->assign('properties', $properties);
						
		$this->title = $product->meta_title;
		$this->keywords = $product->meta_keywords;
		$this->description = $product->meta_description;
				
		$this->body = $this->smarty->fetch('compare.tpl');
		return $this->body;
	}
	
}