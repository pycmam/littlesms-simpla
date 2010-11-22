<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('../placeholder.php');

############################################
# Class UsersCategories
############################################
class Groups extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  function Groups(&$parent)
  {
    parent::Widget($parent);
    $this->add_param('page');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_GET['delete_id']))
  	{
  	    $this->check_token();

   		$delete_id = intval($_GET['delete_id']);
  		
  		$query = sql_placeholder('SELECT count(*) as count FROM users WHERE group_id=?', $delete_id);
  		$this->db->query($query);
  		$users_num = $this->db->result();
  		$users_num = $users_num->count;
  		
  		if($users_num>0)
  		{
  		  $this->error_msg = "Не могу удалить группу, в которой $users_num пользователей";
  		}
  		else
  		{
  		
  		  $query = sql_placeholder('DELETE FROM groups
 		                            WHERE groups.group_id = ? LIMIT 1', $delete_id);
  		  $this->db->query($query);
  		  $get = $this->form_get(array('page'=>$this->param('page')));
 		  header("Location: index.php$get");
 		}
 	}
  }

  function fetch()
  {
    $this->title = $this->lang->USERS_CATEGORIES;
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
    				  FROM groups
    				  ORDER BY discount
    				  LIMIT $start_item ,$this->items_per_page");
  	$groups = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($groups as $key=>$group)
    {
       $groups[$key]->edit_get = $this->form_get(array('section'=>'Group','group_id'=>$group->group_id, 'token'=>$this->token));
    }

  	$this->pages_navigation->fetch($pages_num);
 	$this->smarty->assign('Groups', $groups);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
  	$this->smarty->assign('Error', $this->error_msg);
 	$this->body = $this->smarty->fetch('groups.tpl');
  }
}
