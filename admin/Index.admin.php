<?PHP

require_once('Widget.admin.php');
require_once('MainPage.admin.php');
require_once('Page.admin.php');

class Index extends Widget
{
	var $page;
	function Index(&$parent)
	{
		parent::Widget($parent);
		$this->add_param('section');
		$section = $this->param('section');
		if(empty($section) || !class_exists($section))
		$this->page = new Page($this);
		else
	   	$this->page = new Page($this);
	}

	function fetch()
	{
		$this->page->fetch();
		$this->body = $this->page->body;
		$this->db->disconnect();
	}
}