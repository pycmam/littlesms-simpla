<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Этот класс использует шаблон index.tpl,
 * который содержит всю страницу кроме центрального блока
 * По get-параметру section мы определяем что сожержится в центральном блоке
 *
 */

// Как и все классы, наследуется от класса widget
require_once('Widget.class.php');

// Storefront нам понадобится для некоторых методов, связанных с товарами
require_once('Storefront.class.php');

class Site extends Widget
{
	var $main; // Центральный блок (класс). В данном случае он может быть разных классов, в зависимости от раздела
	var $categories; // Категории товаров
	var $articles_count = 5; // Количество свежих статей
	var $news_count = 5; // Количество свежих новостей
	var $section; // текущий раздел
	
	/**
	 *
	 * Конструктор
	 *
	 */	
	function Site(&$parent)
	{
		Widget::Widget($parent);
		
		// Пока не знаем какого класса центральный блок, он будет базового класса
		$this->main = new Widget($this);
		
        // Категории товаров
		$this->categories = Storefront::get_categories(); 
		$this->smarty->assign('categories', $this->categories);
		
		// Разделы меню
		if($this->mobile_user)
			$this->db->query("SELECT * FROM sections WHERE enabled=1 AND menu_id=3 ORDER BY order_num");
		else
			$this->db->query("SELECT * FROM sections WHERE enabled=1 AND menu_id=2 ORDER BY order_num");
			
		$sections = $this->db->results();		
		$this->smarty->assign('sections', $sections);
		
		// Новости (несколько последних)
		$query = sql_placeholder("SELECT *, DATE_FORMAT(date, '%d.%m.%Y') as date FROM news WHERE enabled=1 ORDER BY news.date DESC LIMIT ?", $this->news_count);
		$this->db->query($query);
		$news = $this->db->results();
		$this->smarty->assign('news', $news);
		
		// Статьи (несколько последних)
		$query = sql_placeholder("SELECT * FROM articles WHERE enabled=1 ORDER BY order_num DESC LIMIT ?", $this->articles_count);
		$this->db->query($query);
		$articles = $this->db->results();
		$this->smarty->assign('articles', $articles);
		
		// Бренды
		$query = sql_placeholder("SELECT * FROM brands ORDER BY name");
		$this->db->query($query);
		$allbrands = $this->db->results();
		$this->smarty->assign('all_brands', $allbrands);
		
		// Состояние корзины
		$this->cart_products_num = 0;
		$this->cart_total_price = 0;

    // Сформируем массив товаров в корзине
    if(is_array($_SESSION['shopping_cart']))
    {
        $variants_ids = array_keys($_SESSION['shopping_cart']);
          
		$discount=isset($this->user->discount)?$this->user->discount:0;
		$query = sql_placeholder("SELECT products_variants.*, products.model as model, products.url as url,
                products_variants.price*(100-$discount)/100 as discount_price
                FROM products_variants, products WHERE products.product_id=products_variants.product_id AND products_variants.variant_id in (?@) ORDER BY products.order_num, products_variants.position", $variants_ids);
		$this->db->query($query);
		$variants = $this->db->results();


      if(!empty($variants))
      {
  	    foreach($variants as $k=>$variant)
        {
         $amount = min($variant->stock, $_SESSION['shopping_cart'][$variant->variant_id]);
		 $this->cart_total_price += $variant->discount_price*$amount; 
		 $this->cart_products_num += $amount;   
        }
      }
  	}
		$this->smarty->assign('cart_total_price', $this->cart_total_price);
		$this->smarty->assign('cart_products_num', $this->cart_products_num);
		
		
		// Необходимо определить что выводить в центральном блоке.
		// Это может быть указано в качестве конкретного раздела сайта (section)
		// или в качестве указания модуля, который следует вывести
		// В первом случае мы узнаем из базы какого модуля нужный раздел
		// и создаем соотвествующий класс (например статическая страница или лента новостей)
		// а во втором - и узнавать ничего не надо, модуль по сути и есть класс 
		// (ну почти, нужно только глянуть в таблице modules название класса для данного модуля, обычно оно совпадает)
		
		// итак, url текущего раздела (может быть не задан)
		$section_url = $this->url_filtered_param('section');

		// модуль (тоже может быть не задан)
		$module = $this->url_filtered_param('module');
		
		// если ничего не задано, текущим разделом будет раздел, заданный в настройках как главная страница
		if (empty($section_url) && empty($module))
		{
			if($this->mobile_user)
				$section_url = $this->settings->main_mobile_section;
			else
				$section_url = $this->settings->main_section;
		}
		// если url раздела задан,
		if (!empty($section_url))
		{
		    // выбираем из базы этот раздел
			$query = sql_placeholder("SELECT sections.section_id as section_id, modules.module_id, modules.class, sections.url, sections.body, sections.header, sections.module_id, sections.meta_title, sections.meta_description, sections.meta_keywords  FROM sections LEFT JOIN modules ON sections.module_id=modules.module_id WHERE sections.url=? limit 1", $section_url);
			$this->db->query($query);
			$this->section = $this->db->result();
			$this->smarty->assign('section', $this->section);
		}
		
		// Если раздел с таким url действительно существует,
		if (!empty($this->section))
		{
		    // создадим класс этого раздела
			$class = $this->section->class;
			include_once("$class.class.php");
			if (class_exists($class))
			{
				$_GET['section'] = $this->section->url;
				$this->main = new $class($this);
			}
			// возвращение false приводит к отображению 404 ошибки. Кстати это работает в любом модуле
			else return false;
		}
		// Если задан модуль, аналогично создаем нужный класс
		elseif (!empty($module))
		{
			$query = sql_placeholder("SELECT class FROM modules WHERE modules.class=? LIMIT 1", $module);
			$this->db->query($query);
			$module = $this->db->result();
			if (!empty($module->class))
			{
				$class = $module->class;
				include_once("$class.class.php");
				if (class_exists($class))
				{
					$this->main = new $class($this);
				} else return false;
			} else return false;
		} else return false;

		// Коме главного блока, нам на всех (в общем случае) страницах необходимы такие вещи:
		
	}
	
	/**
	 *
	 * Отображение
	 *
	 */
	function fetch()
	{
		
		// Создаем основной блок страницы
		if (!$this->main->fetch())
		{
			return false;
		}
		// Если у main установлен флаг single, значит страница состоит только из main, не обромляем ее ничем больше
		if (isset($this->main->single) && $this->main->single)
		{
			$this->body = $this->main->body;
			return $this->body;
		}
		else
		{
			$content = $this->main->body;
			
			//  Устанавливаем мета-теги, указанные в главном блоке
			$this->title = isset($this->section->meta_title)?$this->section->meta_title:$this->main->title;
			$this->keywords = isset($this->section->meta_keywords)?$this->section->meta_keywords:$this->main->keywords;			
			$this->description = isset($this->section->meta_description)?$this->section->meta_description:$this->main->description;

		}
		$this->smarty->assign('title', $this->title);
		$this->smarty->assign('keywords', $this->keywords);
		$this->smarty->assign('description', $this->description);
		$this->smarty->assign('content', $content);		
				
		
		// Создаем текущую страницу сайта
		$this->body = $this->smarty->fetch('index.tpl');
		return $this->body;
	}
}
