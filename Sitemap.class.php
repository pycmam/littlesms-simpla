<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Генерация google sitemap
 *
 */
 
require_once('Widget.class.php');
require_once('Storefront.class.php');

class Sitemap extends Widget
{
	var $single = false;
	
	var $title = 'Карта сайта';
	
	var $sections = array();
	var $news = array();
	var $articles = array();
	var $catalog = array();
	
	/**
	 *
	 * Конструктор
	 *
	 */
	function Sitemap(&$parent)
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
		// Разделы меню
		$this->db->query("SELECT url, DATE_FORMAT(modified, '%Y-%m-%d') as lastmod FROM sections WHERE enabled=1 AND menu_id>0 AND url!='404' ORDER BY order_num");
		$this->sections = $this->db->results();
		
		// Новости
		$this->db->query("SELECT url, DATE_FORMAT(modified, '%Y-%m-%d') as lastmod FROM news WHERE enabled=1 ORDER BY news_id DESC");
		$this->news = $this->db->results();
	
		//  Статьи
		$this->db->query("SELECT url, DATE_FORMAT(modified, '%Y-%m-%d') as lastmod  FROM articles WHERE enabled=1 ORDER BY order_num DESC");
		$this->articles = $this->db->results();	
		
		// Каталог
		$this->catalog = Storefront::get_catalog();


		if($this->param('format')=='google')
		{
			$this->single = true;
			return $this->google_site_map();
		}
		else
		{
			return $this->site_map();
		}

	}	 
	 
	function google_site_map()
	{		
		header('Content-Type: text/xml');
		$map = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$map.= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
		
		// Главная страница

		$url = "http://$this->root_url";
		$lastmod = date("Y-m-d");
		$map.= "\t<url>"."\n";
		$map.= "\t\t<loc>$url</loc>"."\n";
		$map.= "\t\t<lastmod>$lastmod</lastmod>"."\n";
		$map.= "\t</url>"."\n";

		
		// Разделы меню
		if(!empty($this->sections))		
		foreach($this->sections as $section)
		{
			$url = "http://$this->root_url/sections/".$this->esc($section->url);
			$map.= "\t<url>"."\n";
			$map.= "\t\t<loc>$url</loc>"."\n";
			$map.= "\t\t<lastmod>$section->lastmod</lastmod>"."\n";
			$map.= "\t</url>"."\n";
		}
		
		// Новости
		if(!empty($this->news))
		foreach($this->news as $n)
		{
			$url = "http://$this->root_url/news/".$this->esc($n->url);
			$map.= "\t<url>"."\n";
			$map.= "\t\t<loc>$url</loc>"."\n";
			$map.= "\t\t<lastmod>$n->lastmod</lastmod>"."\n";
			$map.= "\t</url>"."\n";
		}
		
		//  Статьи
		if(!empty($this->articles))
		foreach($this->articles as $article)
		{
			$url = "http://$this->root_url/articles/".$this->esc($article->url);
			$map.= "\t<url>"."\n";
			$map.= "\t\t<loc>$url</loc>"."\n";
			$map.= "\t\t<lastmod>$article->lastmod</lastmod>"."\n";
			$map.= "\t</url>"."\n";
		}
		
		//  Товары
		$map.= $this->category_map_recursive($this->catalog);
		
		$map.= '</urlset>'."\n";
		
		$this->body = $map;
		return $map;

	}
	
	function category_map_recursive($categories)
	{
		foreach($categories as $category)
		{
			if($category->products)
			{
				$url = "http://$this->root_url/catalog/".$this->esc($category->url);
				$map.= "\t<url>"."\n";
				$map.= "\t\t<loc>$url</loc>"."\n";
				$map.= "\t\t<lastmod>".date("Y-m-d")."</lastmod>"."\n";
				$map.= "\t</url>"."\n";
				foreach($category->products as $product)
				{
					$url = "http://$this->root_url/products/".$this->esc($product->url);
					$map.= "\t<url>"."\n";
					$map.= "\t\t<loc>$url</loc>"."\n";
					$map.= "\t\t<lastmod>$product->modified</lastmod>"."\n";
					$map.= "\t</url>"."\n";
				}
			}
			if($category->subcategories)
				$map .= $this->category_map_recursive($category->subcategories);
		}
		return $map;
	} 

	function site_map()
	{		
		// Передаем в шаблон
		$this->smarty->assign('catalog', $this->catalog);
		$this->body = $this->smarty->fetch('sitemap.tpl');

		return $this->body;

	}

	
	function esc($s)
	{
		return(htmlspecialchars($s, ENT_QUOTES, 'UTF-8'));	
	}
	
}

