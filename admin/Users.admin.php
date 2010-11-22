<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('../placeholder.php');


############################################
# Class Users displays users
############################################
class Users extends Widget
{
  var $pages_navigation;
  var $items_per_page = 20;
  function Users(&$parent)
  {
    parent::Widget($parent);
    $this->add_param('page');
    $this->add_param('group');
    $this->add_param('keyword');

    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
    if(isset($_GET['enable']))
    {
  	    $this->check_token();
        $user_id = intval($_GET['enable']);
  		$query = sql_placeholder('UPDATE users
 		          SET enabled=1-enabled
                  WHERE user_id=? LIMIT 1', $user_id);
  		$this->db->query($query);
    }

  	if(isset($_GET['delete_user']))
  	{
  	    $this->check_token();
  	    
        $user_id = intval($_GET['delete_user']);
 
  		$query = sql_placeholder("SELECT count(*) as count FROM orders
 		          WHERE orders.user_id = ?  LIMIT 1", $user_id);
  		$this->db->query($query);
  		$user_orders_num = $this->db->result();

  		if($user_orders_num->count)
  		{
  		  $this->error_msg = 'Нельзя удалить пользователя, имеющего заказы';
  		}
  	    else
  	    {
  		  $query = sql_placeholder("DELETE FROM users
 		          WHERE users.user_id =?", $user_id);
  		  $this->db->query($query);
  		  $get = $this->form_get(array());
 		  header("Location: index.php$get");
 		}
 	}
  }

  function fetch()
  {
    $this->title = $this->lang->USERS;
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;

    $name = $this->param('name');
    $keyword = mysql_real_escape_string($this->param('keyword'));
    $group_id = $this->param('group');
    $filter = '1';
    if(!empty($keyword))
      $filter .= " AND (users.name LIKE '%$keyword%' OR users.email LIKE '%$keyword%') ";
    if(!empty($group_id))
      $filter .= " AND users.group_id = '$group_id' ";
    else
      $filter .= " AND users.group_id = 0 ";

    $query = "SELECT SQL_CALC_FOUND_ROWS users.*,
                      groups.name as group_name,
                      COUNT(orders.order_id) as orders_num
    				  FROM users LEFT JOIN groups
                      ON groups.group_id = users.group_id
    				  LEFT JOIN orders
                      ON orders.user_id = users.user_id
                      WHERE $filter
                      GROUP BY users.user_id
    				  ORDER BY orders.order_id DESC
    				  LIMIT $start_item ,$this->items_per_page";
    				  
    $this->db->query($query);
  	$users = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($users as $key=>$user)
    {
       $users[$key]->edit_get = $this->form_get(array('section'=>'User','user_id'=>$user->user_id, 'token'=>$this->token));
       $users[$key]->enable_get = $this->form_get(array('enable'=>$user->user_id, 'token'=>$this->token));
       $users[$key]->delete_get = $this->form_get(array('delete_user'=>$user->user_id,'token'=>$this->token));
    }

    $query = 'SELECT * FROM groups';
    $this->db->query($query);
    $groups = $this->db->results();


  	$this->pages_navigation->fetch($pages_num);
	$this->smarty->assign('Users', $users);
	$this->smarty->assign('Groups', $groups);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Error', $this->error_msg);
 	$this->body = $this->smarty->fetch('users.tpl');
  }
}

