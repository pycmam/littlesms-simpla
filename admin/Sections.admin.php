<?PHP
require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('../placeholder.php');

############################################
# Class Sections displays a list of sections
############################################
class Sections extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  var $menu;
  
  function Sections(&$parent)
  {
	parent::Widget($parent);
    $this->add_param('page');
    $this->add_param('menu');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_GET['delete_item_id']))
  	{
  		$this->check_token();

        $delete_item_id = $this->param('delete_item_id');  		
        
  		$query = sql_placeholder("DELETE FROM sections WHERE sections.section_id=? LIMIT 1", $delete_item_id);
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
 	
  	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
  		$this->check_token();
  		
  		$menu_id = intval($this->param('menu'));
  		$section_id = intval($this->param('item_id'));
  		$this->db->query("SELECT @id:=s1.section_id
  		                  FROM sections s1, sections s2
  		                  WHERE s1.order_num>s2.order_num
  		                  AND s2.section_id = '$section_id'
  		
  		                  AND s1.menu_id =  s2.menu_id
  		                  ORDER BY s1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE sections s1, sections s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.section_id = '$section_id'
  		                  AND s2.section_id = @id");
  		$get = $this->form_get(array());
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
  		header("Location: index.php$get");
  	}
  	
 	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$this->check_token();

  		$menu_id = intval($this->param('menu'));
  		$section_id = intval($this->param('item_id'));
  		$this->db->query("SELECT @id:=s1.section_id
  		                  FROM sections s1, sections s2
  		                  WHERE s1.order_num<s2.order_num
  		                  AND s2.section_id = '$section_id'

  		                  AND s1.menu_id =  s2.menu_id
  		                  ORDER BY s1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE sections s1, sections s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.section_id = '$section_id'
  		                  AND s2.section_id = @id");
  		$get = $this->form_get(array());
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
  		header("Location: index.php$get");
  	}
  	
    # Сделать страницу видимой
    if(isset($_GET['set_enabled']))
    {
      $this->check_token();

      $id = intval($this->param('set_enabled'));
      $query = sql_placeholder('UPDATE sections SET enabled=1-enabled WHERE section_id=? LIMIT 1', $id);
      $this->db->query($query );
  	  
  	  $get = $this->form_get(array());
      if(isset($_GET['from']))
        header("Location: ".$_GET['from']);
      else
 		header("Location: index.php$get");
    }
  	
  	
  }

  function fetch()
  {
  	$current_page = intval($this->param('page'));
  	$menu_id = intval($this->param('menu'));

  	$this->db->query("SELECT * FROM menu WHERE menu_id>0 ORDER BY menu_id=1, menu_id");
  	$this->menus = $this->db->results();

  	if(empty($menu_id))
  	  $menu_id = $this->menus[0]->menu_id;

    $query = sql_placeholder("SELECT * FROM menu WHERE menu_id = ? LIMIT 1", $menu_id);
    $this->db->query($query);
  	$this->menu = $this->db->result();
  	$this->title = $this->menu->name;

  	$start_item = $current_page*$this->items_per_page;
  	
    $query = sql_placeholder("SELECT SQL_CALC_FOUND_ROWS sections.*,
                      modules.name as module_name
    				  FROM sections, modules
    				  WHERE sections.module_id = modules.module_id
    				  AND sections.menu_id = ?
    				  ORDER BY order_num
    				  LIMIT ?, ?", $menu_id, $start_item, $this->items_per_page);
  	
    $this->db->query($query);
  	$sections = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($sections as $key=>$section)
    {
       $sections[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$section->section_id, 'token'=>$this->token));
       $sections[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$section->section_id, 'token'=>$this->token));
       $sections[$key]->edit_get = $this->form_get(array('section'=>'Section','item_id'=>$section->section_id, 'token'=>$this->token));
       $sections[$key]->delete_get = $this->form_get(array('delete_item_id'=>$section->section_id, 'token'=>$this->token));
       $sections[$key]->enable_get = $this->form_get(array('set_enabled'=>$section->section_id, 'token'=>$this->token));
    }

  	$this->pages_navigation->fetch($pages_num);
  	$this->smarty->assign('Menu', $this->menu);
  	$this->smarty->assign('Menus', $this->menus);
	$this->smarty->assign('Fixed', $this->menu->fixed);
 	$this->smarty->assign('Sections', $sections);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('sections.tpl');
  }
}
