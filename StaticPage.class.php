<?PHP

/**
 * Simpla CMS
 * StaticPage class: Отображение статической страницы
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Этот класс использует шаблон static_page.tpl,
 * для вывода статической страницы
 *
 */
 
require_once('Widget.class.php');

class StaticPage extends Widget
{

	/**
	 *
	 * Конструктор
	 *
	 */	
	function StaticPage(&$parent)
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
		// url страницы
		$section_url = $this->url_filtered_param('section');
		
		// Выбираем из базы этот раздел
		$query = sql_placeholder("SELECT sections.*
								FROM sections
								WHERE enabled=1 AND sections.url = ? LIMIT 1", $section_url);
		$this->db->query($query);
		$page = $this->db->result();
		
		if (!$page)
		{
			// return false приводит к отображению ошибки 404
			return false;
		}
		
		// Метатеги
		$this->title = $page->meta_title;
		$this->keywords = $page->meta_keywords;
		$this->description = $page->meta_description;
		
		// Передаем в шаблон
		$this->smarty->assign('page', $page);
		$this->body = $this->smarty->fetch('static_page.tpl');
		return $this->body;
	}
}
