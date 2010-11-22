<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Отображение новостей на сайте
 * Использует шаблон news.tpl для ленты новостей, и news_item.tpl для вывода одной новости
 *
 */
 
require_once('Widget.class.php');

class NewsLine extends Widget
{

	/**
	 *
	 * Конструктор
	 *
	 */
	function NewsLine(&$parent)
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
		// Какую новость нужно вывести?
		$news_url = $this->url_filtered_param('news_url');
		
		if (!empty($news_url))
		{
			// Если передан url новости, выводим ее
			return $this->fetch_item($news_url);
		}
		else
		{
			// Если нет, выводим список всех новостей
			return $this->fetch_list();
		}
	}
	
	/**
	 *
	 * Отображение списка новостей
	 *
	 */	
	function fetch_list()
	{
		// Выбераем новости из базы
		$this->db->query('SELECT *, DATE_FORMAT(date, \'%d.%m.%Y\') as date FROM news WHERE enabled=1 ORDER BY news.date DESC');
		$news = $this->db->results();
		
		// Передаем в шаблон
		$this->smarty->assign('news', $news);
		$this->body = $this->smarty->fetch('news.tpl');
		
		// Устанавливаем метатеги для ленты новостей (если она вызвана как голый модуль)
		$this->title = 'Новости';
		
		return $this->body;
	}
	
	/**
	 *
	 * Отображение отдельной новости
	 *
	 */	
	function fetch_item($url)
	{
		// Выбираем новость из базы
		$query = sql_placeholder('SELECT *, DATE_FORMAT(date, \'%d.%m.%Y\') as date FROM news WHERE url = ? AND enabled=1 LIMIT 1', $url);
		$this->db->query($query);
		
		// Если не существует такая новость - ошибка 404
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
		$this->smarty->assign('news_item', $item);
		$this->body = $this->smarty->fetch('news_item.tpl');
		return $this->body;
	}
}

