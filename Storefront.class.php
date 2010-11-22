<?PHP

/**
 * Simpla CMS
 * Storefront class: Каталог товаров
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Этот класс использует шаблоны catalog.tpl, products.tpl, product.tpl
 *
 */
 
require_once('Widget.class.php');

class Storefront extends Widget
{
	var $items_per_page = 24; // Количество товаров на странице
	var $categories = array();
	var $error = '';
	
	/**
	 *
	 * Конструктор
	 *
	 */	
	function Storefront($parent)
	{
		Widget::Widget($parent);
		
		// Если у родителя уже выбраны категории
		if(is_array($parent->categories))		
		{
			$this->categories = $parent->categories;
		}
		else
		{ 
			// иначе выбираем
			$this->categories = $this->get_categories();
		}
	}
		
	/**
	 *
	 * Отображение
	 *
	 */	
	function fetch()
	{
		// Все возможные GET-параметры. Фильтруем для безопасности
		$category = $this->url_filtered_param('category');
		$brand = $this->url_filtered_param('brand');
		$product = $this->url_filtered_param('product');

		if (!empty($product))
		{
			// Если задан товар, выводим его
			return $this->fetch_product($product);
		}
		elseif (!empty($category) || !empty($brand))
		{
			// Если задана категория, выводим товары этой категории
			return $this->fetch_products($category, $brand);
		}
		else
		{
			// По умолчанию выводим каталог
			return $this->fetch_catalog();
		}
	}
	
	/**
	 *
	 * Отображение каталога товаров
	 *
	 */	
	function fetch_catalog()
	{
		// Если пользователь залогиен, применим сразу его скидку к ценам на товар
		$discount=isset($this->user->discount)?$this->user->discount:0;
		
		// Популярные товары
		$products = $this->get_products(null, null, null, null, null, 1);
		
		$this->smarty->assign('products', $products);
		$this->body = $this->smarty->fetch('catalog.tpl');
		return $this->body;
	}
	
	
	/**
	 *
	 * Отображение списка товаров в категории
	 *
	 */	
	function fetch_products($category_url, $brand_url)
	{
		// берем количество товаров из настроек
		if(!empty($this->settings->products_num))
			$this->items_per_page = $this->settings->products_num;
	
		// Если задан бренд, выберем его из базы
		if (isset($brand_url) && !empty($brand_url))
		{
			$query = sql_placeholder('SELECT * FROM brands WHERE url=? LIMIT 1', $brand_url);
			$this->db->query($query);
			$brand = $this->db->result();
			if (empty($brand))
			{
				return false;
			}
			$this->smarty->assign('brand', $brand);
		}
		
		// Выберем текущую категорию
		if (isset($category_url) && !empty($category_url))
		{
			$category = $this->category_by_url($this->categories, $category_url);
			if (empty($category))
			{
				return false;
			}
			$this->smarty->assign('category', $category);
		}
		
		// Текущая страница в постраничном выводе
		// Единицу отнимаем, потому что в коде страницы нумеруются с 0 а не с 1 как снаружи
		$current_page = intval($this->param('page'))-1;
		
		// Если не задана, то равна 0
		$current_page = max(0, $current_page);
		$this->smarty->assign('page', $current_page);
		
		// Порядковый номер первого товара на странице
		$start_item = $current_page*$this->items_per_page;
		
		// Выбираем свойства категории
		$query = sql_placeholder("SELECT * FROM properties, properties_categories
									WHERE properties.property_id = properties_categories.property_id
									AND properties_categories.category_id=?
									AND enabled AND in_filter AND options!='' ORDER BY properties.order_num", $category->category_id);
		$this->db->query($query);
		if($properties = $this->db->results())
		{
		
			foreach($properties as $k=>$property)
				$this->add_param($property->property_id);
			foreach($properties as $k=>$property)
			{
				$properties[$k]->clear_url = $this->form_get(array($property->property_id=>''));
				$options = array();
				$opts = unserialize($property->options);
				foreach($opts as $i=>$o)
				{
					$options[$i]->value = $o;
					$options[$i]->url = $this->form_get(array($property->property_id=>$o));				
				}
				$properties[$k]->options = $options;
			}
			$this->smarty->assign('properties', $properties);
			$this->smarty->assign('filter_params', $this->form_get(array()));
			////////////////////////	
			
			
			// Переданные значения свойств для фильтра
			$filter = array();
			foreach($properties as $k=>$property)
			{
				if($val = $this->param($property->property_id))
					$filter[$property->property_id] = $val;
			}	
		}
		
		// Выбираем из базы товары
		$products = $this->get_products(null, $category->subcats_ids, isset($brand->brand_id)?$brand->brand_id:null, $start_item, $filter);

		$this->smarty->assign('products', $products);
				
		// Вычисляем количество страниц
		$products_count = $this->count_products(null, $category->subcats_ids, isset($brand->brand_id)?$brand->brand_id:null, $start_item, $filter);
		$pages_num = ceil($products_count/$this->items_per_page);
		$this->smarty->assign('total_pages', $pages_num);
		
		// Устанавливаем мета-теги
		if($category)
		{
			$this->title = $category->meta_title;
			$this->description = $category->meta_description;
			$this->keywords = $category->meta_keywords;
		}elseif($brand)
		{
			$this->title = $brand->meta_title;
			$this->description = $brand->meta_description;
			$this->keywords = $brand->meta_keywords;
		}
		
		// Выбираем все бренды, они нужны нам в шаблоне
		if(is_array($category->subcats_ids))
		{	
			if($this->use_optional_categories)
			{
				//С дополнительными категориями
				
				// Если задана категория, добавляем фильт по категории
				$category_filter = "AND ( (products.category_id in(".join($category->subcats_ids, ',').") ) OR (products_categories.category_id in(".join($category->subcats_ids, ',').") ) )";
				
				$query = sql_placeholder("SELECT DISTINCT brands.*
							FROM brands, products LEFT JOIN products_categories ON products.product_id = products_categories.product_id
							WHERE
							products.brand_id = brands.brand_id 
							AND products.enabled=1					 
							$category_filter
							ORDER BY brands.name", $category->category_id);
			}else{
	
				$category_filter = "AND products.category_id in(".join($category->subcats_ids, ',').")";
				
				$query = sql_placeholder("SELECT DISTINCT brands.*
									  FROM brands, products
									  WHERE products.brand_id = brands.brand_id
									  AND products.enabled=1
									  $category_filter
									  ORDER BY brands.name", $category->category_id);
			}
			$this->db->query($query);
			$brands = $this->db->results();
		}
		$this->smarty->assign('brands', $brands);		
		
		$this->body = $this->smarty->fetch('products.tpl');
		return $this->body;
	}
	
	
	/**
	 *
	 * Отображение отдельного товара
	 *
	 */	
	function fetch_product($product_url)
	{
		// Выбираем товар из базы
		$product = $this->get_product($product_url);
		if (empty($product))
		{
			// страница 404
			return false;
		}		
		
		// Дополнительные фото товара
		$query = sql_placeholder("SELECT * FROM products_fotos WHERE product_id =  ?", $product->product_id);
		$this->db->query($query);
		$product->fotos = $this->db->results();
		
		// И передаем его в шаблон
		$this->smarty->assign('product', $product);
		
		// Выберем текущую категорию
		$category = $this->category_by_url($this->categories, $product->category_url);
		if (empty($category))
		{
			// страница 404
			return false;
		}
		
		$this->smarty->assign('category', $category);
		
		if(isset($this->user->name))
		{
			$this->smarty->assign('name', $this->user->name);
		}		
		
		### Принимает отзыв
		if (isset($_POST['comment']))
		{
			$name = trim(strip_tags($_POST['name']));
			$comment = trim(strip_tags($_POST['comment']));
			if ($this->gd_loaded && ($_SESSION['captcha_code'] != $_POST['captcha_code'] || empty($_POST['captcha_code'])))
			{
				$this->error = 'Неверно введено число с картинки';
			}
			elseif (empty($name))
			{
				$this->error = 'Введите имя';
			}
			elseif (empty($comment))
			{
				$this->error = 'Пустой комментарий';
			}
			else
			{
				$query = sql_placeholder("INSERT INTO products_comments (date, product_id, ip, name, comment) VALUES(NOW(), ?, ?, ?, ?)", $product->product_id, $_SERVER['REMOTE_ADDR'], $name, $comment);
				$this->db->query($query);
			}
			// Приберем сохраненную капчу, иначе можно отключить загрузку рисунков и постить старую
			unset($_SESSION['captcha_code']);
						
			if($this->error)
			{
				$this->smarty->assign('name', $name);
				$this->smarty->assign('comment', $comment);		
			}
		}
		###
		
		$this->title = $product->meta_title;
		$this->keywords = $product->meta_keywords;
		$this->description = $product->meta_description;
		
		// Отзывы о товаре
		$query = sql_placeholder("SELECT *, DATE_FORMAT(date, '%d.%m.%Y') as date FROM products_comments WHERE product_id=? ORDER BY comment_id DESC", $product->product_id);
		$this->db->query($query);
		$comments = $this->db->results();
		
		// Соседние товары
		$query = sql_placeholder("SELECT products.model as model, products.url as url, brands.name as brand, categories.single_name as category FROM categories, products LEFT JOIN brands ON products.brand_id = brands.brand_id WHERE categories.category_id = products.category_id AND categories.category_id=? AND products.enabled=1 AND categories.enabled=1 AND products.order_num<? ORDER BY products.order_num DESC LIMIT 1", $product->category_id, $product->order_num);
		$this->db->query($query);
		$next_product = $this->db->result();
		$this->smarty->assign('next_product', $next_product);
		$query = sql_placeholder("SELECT products.model as model, products.url as url, brands.name as brand, categories.single_name as category FROM categories, products LEFT JOIN brands ON products.brand_id = brands.brand_id WHERE categories.category_id = products.category_id AND categories.category_id=? AND products.enabled=1 AND categories.enabled=1 AND products.order_num>? ORDER BY products.order_num LIMIT 1", $product->category_id, $product->order_num);
		$this->db->query($query);
		$prev_product = $this->db->result();
		$this->smarty->assign('prev_product', $prev_product);
		///
		
		$this->smarty->assign('comments', $comments);
		$this->smarty->assign('error', $this->error);
		$this->body = $this->smarty->fetch('product.tpl');
		return $this->body;
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
			$flag = false;
			foreach($categories as $k=>$category)
			{ 				
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
	function category_by_url($categories, $url)
	{
		foreach ($categories as $category)
		{
			if ($category->url == $url)
			{
				return $category;
			}
			elseif(is_array($category->subcategories))
			{
				if ($result = Storefront::category_by_url($category->subcategories, $url))
				{
					return $result;
				}
			}
		}
		return false;
	}
	
	
	// Функция возвращает категории товаров, и их подкатегории
	function get_categories($parent=0)
	{
		// Выбираем все категории
		$query = sql_placeholder("SELECT * FROM categories WHERE enabled=1 ORDER BY parent, order_num", $parent);
		$this->db->query($query);
		$temp_categories = $this->db->results();
		$categories = Storefront::categories_tree($temp_categories);
		return $categories;
	}
	
	// Функция возвращает товары
	function get_products($ids = null, $categories = null, $brand_id = null, $start_item=null, $filter=null, $hit=null)
	{
		// Если заданы id
		$id_filter = '';
		if (is_array($ids))
		{
			foreach ($ids as $k=>$id)
			{
				$ids[$k]=intval($id);
			}
			$id_filter = is_null($ids)?"":"AND (products.product_id in(".join($ids, ',')."))";
		}
		
		
		// Если задан бренд, добавляем фильт по бренду
		$brand_filter = is_null($brand_id)?"":"AND brands.brand_id = $brand_id";
		
		// Если задан хит, добавляем фильт по хитам
		$hit_filter = is_null($hit)?"":"AND products.hit = $hit";
		
		// Если задан бренд, добавляем фильт по бренду
		$limit = is_null($start_item)?"":"LIMIT $start_item, $this->items_per_page";
		
		// Фильтр по свойствам
		$properties_filter = '';
		if($filter)
		{
			foreach($filter as $property=>$value)
				$properties_filter .= sql_placeholder(" AND products.product_id in (SELECT properties_values.product_id FROM properties_values WHERE properties_values.product_id = products.product_id AND properties_values.value=? AND properties_values.property_id=?) ", $value, $property);
		}
		
		//С дополнительными категориями
		
		// Если задана категория, добавляем фильт по категории
		$category_filter = is_null($categories)?"":"AND ( (categories.category_id in(".join($categories, ',').") ) OR (products_categories.category_id in(".join($categories, ',').") ) )";
		
		$query = "SELECT  
				products.product_id, products.url, products.category_id, products.brand_id, products.model, products.description, products.body, products.hit, products.order_num, products.small_image, products.large_image, DATE_FORMAT(products.created, '%Y-%m-%d') as created, DATE_FORMAT(products.modified, '%Y-%m-%d') as  modified, products.enabled, 
				brands.name as brand, brands.url as brand_url,
				categories.single_name as category, categories.url as category_url, categories.image as category_image
				FROM products LEFT JOIN categories ON categories.category_id = products.category_id
				LEFT JOIN brands ON products.brand_id = brands.brand_id
				LEFT JOIN products_categories ON products.product_id = products_categories.product_id 
				WHERE 
				categories.enabled=1
				and products.enabled=1					 
				$id_filter $category_filter $brand_filter $properties_filter $hit_filter 
				GROUP BY products.product_id 
				ORDER BY products.order_num desc
				$limit";

		

		$this->db->query($query);

		$temp_products = $this->db->results();
		foreach($temp_products as $product)
			$products[$product->product_id] = $product;
		
		if(is_array($products))
		{
			$ids = array_keys($products);
	
			// Если пользователь залогиен, применим сразу его скидку к ценам на товар
			$discount=isset($this->user->discount)?$this->user->discount:0;
		
			$query = sql_placeholder("SELECT products_variants.*,
					products_variants.price*(100-$discount)/100 as discount_price,
					products_variants.stock as stock,
					products_variants.name as variant_name
					FROM products_variants WHERE products_variants.product_id in (?@)  
					AND products_variants.stock>0 AND products_variants.price>0              
					ORDER BY products_variants.position", $ids);
	
			
	
			$this->db->query($query);
	
			$variants = $this->db->results();
			
			
			foreach($variants as $variant)
			{
				if(!empty($products[$variant->product_id]))
				{
					$products[$variant->product_id]->variants[]=$variant;
				}			
			}
		}

		return $products;
	}
	
	// Функция возвращает товары
	function count_products($ids = null, $categories = null, $brand_id = null, $start_item=null, $filter=null)
	{
		// Если заданы id
		$id_filter = '';
		if (is_array($ids))
		{
			foreach ($ids as $k=>$id)
			{
				$ids[$k]=intval($id);
			}
			$id_filter = is_null($ids)?"":"AND (products.product_id in(".join($ids, ',')."))";
		}
		
		
		// Если задан бренд, добавляем фильт по бренду
		$brand_filter = is_null($brand_id)?"":"AND brands.brand_id = $brand_id";
		
		// Если задан бренд, добавляем фильт по бренду
		$limit = is_null($start_item)?"":"LIMIT $start_item, $this->items_per_page";
		
		// Если пользователь залогиен, применим сразу его скидку к ценам на товар
		$discount=isset($this->user->discount)?$this->user->discount:0;
		
		// Фильтр по свойствам
		$properties_filter = '';
		if($filter)
		{
			foreach($filter as $property=>$value)
				$properties_filter .= sql_placeholder(" AND products.product_id in (SELECT properties_values.product_id FROM properties_values WHERE properties_values.product_id = products.product_id AND properties_values.value=? AND properties_values.property_id=?) ", $value, $property);
		}
		
		//С дополнительными категориями
		
		// Если задана категория, добавляем фильт по категории
		$category_filter = is_null($categories)?"":"AND ( (categories.category_id in(".join($categories, ',').") ) OR (products_categories.category_id in(".join($categories, ',').") ) )";
		
		$query = "SELECT count(distinct products.product_id) as count
				FROM products LEFT JOIN categories ON categories.category_id = products.category_id
      			LEFT JOIN brands ON products.brand_id = brands.brand_id
				LEFT JOIN products_categories ON products.product_id = products_categories.product_id 
				WHERE 
				categories.enabled=1
				and products.enabled=1					 
				$id_filter $category_filter $brand_filter $properties_filter";

		$this->db->query($query);

		$count_products = $this->db->result();
		return $count_products->count;
	}
	

	// Функция возвращает товар по url
	function get_product($product_url)
	{
		$query = sql_placeholder("SELECT products.*,
                brands.name as brand, brands.url as brand_url,
                categories.single_name as category, categories.url as category_url, categories.image as category_image
                FROM products
                LEFT JOIN brands ON products.brand_id = brands.brand_id, categories
                WHERE products.url = ?
                AND categories.category_id = products.category_id
                AND products.enabled=1
                AND categories.enabled=1 
                GROUP BY products.product_id
                LIMIT 1", $product_url);
		$this->db->query($query);
		$product = $this->db->result();
		
		if(empty($product))
		  return false;
		  
		// связанные товары
		$products = array();
		$query = sql_placeholder('SELECT products_variants.product_id FROM products_variants, related_products WHERE products_variants.sku=related_products.related_sku AND related_products.product_id = ?', $product->product_id);
		$this->db->query($query);
		$related = $this->db->results();
		if(!empty($related))
		{
			foreach($related as $r)
				$ids[] = $r->product_id;
			$product->related_products = Storefront::get_products($ids);
		}
		
		// параметры товара
		$query = sql_placeholder("SELECT * FROM properties,
								properties_values WHERE properties_values.product_id = ? AND properties_values.property_id = properties.property_id
								AND enabled ORDER BY properties.order_num",
      							$product->product_id);
		$this->db->query($query);
		$product->properties = $this->db->results();
		
		// варианты товара
		// Если пользователь залогиен, применим сразу его скидку к ценам на товар
		$discount=isset($this->user->discount)?$this->user->discount:0;
		
		$query = sql_placeholder("SELECT products_variants.variant_id as variant_id, products_variants.sku as sku, products_variants.name as name, products_variants.price as price, products_variants.stock as stock,
									products_variants.price*(100-$discount)/100 as discount_price
									FROM products_variants
								  WHERE products_variants.product_id = ? AND products_variants.stock>0 AND products_variants.price>0 ORDER BY products_variants.position",
      							$product->product_id);
		$this->db->query($query);
		$product->variants = $this->db->results();
		
		$product->properties;

		return $product;
	}

	// Функция возвращает вариант товара
	function get_variant($variant_id)
	{
		// Если пользователь залогиен, применим сразу его скидку к ценам на товар
		$discount=isset($this->user->discount)?$this->user->discount:0;
		$query = sql_placeholder("SELECT products_variants.*, 
                products_variants.price*(100-$discount)/100 as discount_price
                FROM products_variants WHERE products_variants.variant_id = ?
                LIMIT 1", $variant_id);
		$this->db->query($query);
		$variant = $this->db->result();
		
		if(empty($variant))
		  return false;
		return $variant;
	}



	// Функция возвращает дерево категорий с товарами
	function get_catalog()
	{
		// Выбираем все категории
		$query = sql_placeholder("SELECT * FROM categories WHERE enabled=1 ORDER BY parent, order_num", $parent);
		$this->db->query($query);
		$temp_categories = $this->db->results();
		
		foreach($temp_categories as $temp_category)
			$categories[$temp_category->category_id] = $temp_category;
		
		$products = Storefront::get_products();

		foreach($products as $product)
			$categories[$product->category_id]->products[] = $product;
		
		$categories = Storefront::categories_tree($categories);
		
		return $categories;	   
	}
}
