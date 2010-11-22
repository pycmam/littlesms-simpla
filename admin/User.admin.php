<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');

############################################
# Class EditServiceSection - edit the static section
############################################
class User extends Widget
{
  var $user;
  function User(&$parent)
  {
    Widget::Widget($parent);
    $this->add_param('page');
    $this->add_param('group_id');
    $this->prepare();
  }

  function prepare()
  {
  	$user_id = $this->param('user_id');
  	if(isset($_POST['name']))
  	{
  	    $this->check_token();
  	    
  		$this->item->name = $_POST['name'];
  		$this->item->email = $_POST['email'];
  		$this->item->group_id = $_POST['group_id'];
  		$this->item->enabled = 0;
        if(isset($_POST['enabled']))
          $this->item->enabled = $_POST['enabled'];

  		$query = sql_placeholder('UPDATE users SET name=?, email=?, group_id=?, enabled=? WHERE user_id=? LIMIT 1',
  			                          $this->item->name,
  			                          $this->item->email,
  			                          $this->item->group_id,
  			                          $this->item->enabled,
  			                          $user_id);

  		$this->db->query($query);
  		if($this->item->group_id == $this->param('group_id'))
    		$get = $this->form_get(array('section'=>'Users', 'group'=>$this->item->group_id, 'page'=>$this->param('page'), 'keyword'=>$this->param('keyword')));
        else
    		$get = $this->form_get(array('section'=>'Users', 'group'=>$this->item->group_id, 'keyword'=>$this->param('keyword')));
  		header("Location: index.php$get");

  	}

   $query = sql_placeholder('SELECT * FROM users WHERE user_id=? LIMIT 1', $user_id);
   $this->db->query($query);
   $this->user = $this->db->result();
  }

  function fetch()
  {
      $this->title = $this->lang->EDIT_USER;

      $this->db->query("SELECT * FROM groups ORDER BY discount");
      $groups = $this->db->results();

      $this->smarty->assign('Groups', $groups);
      $this->smarty->assign('User', $this->user);
 	  $this->smarty->assign('Error', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('user.tpl');
  }
}
