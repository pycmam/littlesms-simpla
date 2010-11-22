<?PHP

require_once('Widget.admin.php');

class PagesNavigation extends Widget
{
	function PageNavigation(&$parent)
	{
		parent::Widget($parent);
	}

	function fetch($pages_num)
	{
		if($pages_num > 1)
		{
			$current_page = $this->param('page');
			for($i = 0; $i<$pages_num; $i++)
			{
				$get = $this->form_get(array('page'=>$i));
				$pages[$i] = "index.php$get";
			}
			
			if($current_page>0)
 				$this->smarty->assign('PrevPageUrl', "index.php".$this->form_get(array('page'=>$current_page-1)));
			if($current_page<$pages_num-1)
 				$this->smarty->assign('NextPageUrl', "index.php".$this->form_get(array('page'=>$current_page+1)));
			
			$this->smarty->assign('Lang', $this->lang);
			$this->smarty->assign('Pages', $pages);
			$this->smarty->assign('CurrentPage', $current_page);
			$this->body = $this->smarty->fetch('pages_navigation.tpl');
		}
		else
		$this->body = '';
	}

}