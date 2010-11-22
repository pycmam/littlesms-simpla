<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Отображение статей на сайте
 * Этот класс использует шаблоны articles.tpl и article.tpl
 *
 */
 
require_once('Widget.class.php');


class Articles extends Widget
{
	/**
	 *
	 * Конструктор
	 *
	 */
	function Articles(&$parent)
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
		// Какую статью нужно вывести?
		$article_url = $this->url_filtered_param('article_url');
		
		if (!empty($article_url))
		{	
			// Если передан id статьи, выводим ее
			return $this->fetch_item($article_url);
		}
		else
		{
			// Если нет, выводим список всех новостей
			return $this->fetch_list();
		}
	}
	
	/**
	 *
	 * Отображение списка статей
	 *
	 */	
	function fetch_list()
	{
		// Выбираем статьи из базы
		$this->db->query('SELECT * FROM articles WHERE enabled=1 ORDER BY order_num DESC');
		$articles = $this->db->results();
		
		// Передаем в шаблон
		$this->smarty->assign('articles', $articles);
		$this->body = $this->smarty->fetch('articles.tpl');
		
		// Устанавливаем метатеги для списка (если он вызван как голый модуль)
		$this->title = 'Статьи';
		
		return $this->body;
	}
	
	/**
	 *
	 * Отображение отдельной статьи
	 *
	 */	
	function fetch_item($url)
	{
		// Выбираем статью из базы
		$query = sql_placeholder('SELECT * FROM articles WHERE url = ? AND enabled=1 LIMIT 1', $url);
		$this->db->query($query);
		
		// Если не существует такая статья - ошибка 404
		if ($this->db->num_rows() == 0)
		{
			return false;
		}
		
		$item = $this->db->result();
		
		// Устанавливаем метатеги для страницы с этой новостью
		$this->title = $item->meta_title;
		$this->keywords = $item->meta_keywords;
		$this->description = $item->meta_description;
		
		// Передаем в шаблон
		$this->smarty->assign('article', $item);
		
		$this->body = $this->smarty->fetch('article.tpl');
		return $this->body;
	}
}
