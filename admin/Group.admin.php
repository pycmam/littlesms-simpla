<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');

############################################
# Class EditServiceSection - edit the static section
############################################
class Group extends Widget
{
  var $group;
  function Group(&$parent)
  {
    Widget::Widget($parent);
    $this->prepare();
  }

  function prepare()
  {
  	$group_id = intval($this->param('group_id'));
  	if(isset($_POST['name']) &&
  	   isset($_POST['discount']))
  	{
  	    $this->check_token();

  		$this->group->name = $_POST['name'];
  		$this->group->discount = $_POST['discount'];

  		if(empty($this->group->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
        else
  		{
  			if(empty($group_id))
  			$query = sql_placeholder('INSERT INTO groups(name, discount) VALUES(?, ?)',
  			                          $this->group->name,
  			                          $this->group->discount);
  			else
  			$query = sql_placeholder('UPDATE groups SET name=?, discount=? where group_id=?',
  			                          $this->group->name,
  			                          $this->group->discount,
  			                          $group_id);

  			$this->db->query($query);
 			$get = $this->form_get(array('section'=>'Groups', 'page'=>$this->param('page')));
  		    header("Location: index.php$get");
  		}
  	}

  	elseif (!empty($group_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM groups WHERE group_id=? LIMIT 1', $group_id);
  	  $this->db->query($query);
  	  $this->group = $this->db->result();
  	}
  }

  function fetch()
  {
  	  if(empty($this->group->group_id))
  	    $this->title = $this->lang->NEW_USERS_CATEGORY;
  	  else
  	    $this->title = $this->lang->EDIT_USERS_CATEGORY;

 	  $this->smarty->assign('Group', $this->group);
 	  $this->smarty->assign('Error', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('group.tpl');
  }
}