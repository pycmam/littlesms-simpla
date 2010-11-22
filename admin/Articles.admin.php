<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');
require_once('PagesNavigation.admin.php');


############################################
# Class NewsLine displays news
############################################
class Articles extends Widget
{
  var $pages_navigation;
  var $items_per_page = 20;
  function Articles(&$parent)
  {
    parent::Widget($parent);
    $this->add_param('page');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
  	if((isset($_POST['act']) && $_POST['act']=='delete' || isset($_GET['act']) && $_GET['act']=='delete') && (isset($_POST['items']) || isset($_GET['item_id']) ))
  	{
		$this->check_token();

        if(isset($_GET['item_id']) && !empty($_GET['item_id']))
          $items = array($_GET['item_id']);  		

  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM articles
 		          WHERE articles.article_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
		$this->check_token();

  		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.article_id
  		                  FROM articles a1, articles a2
  		                  WHERE a1.order_num>a2.order_num
  		                  AND a2.article_id = '$item_id'
  		                  ORDER BY a1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE articles a1, articles a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.article_id = '$item_id'
  		                  AND a2.article_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
		$this->check_token();

   		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.article_id
  		                  FROM articles a1, articles a2
  		                  WHERE a1.order_num<a2.order_num
  		                  AND a2.article_id = '$item_id'
  		                  ORDER BY a1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE articles a1, articles a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.article_id = '$item_id'
  		                  AND a2.article_id = @id");
  		$get = $this->form_get(array());
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
 		  header("Location: index.php$get");
  	}
    # Сделать статью видимой
    if(isset($_GET['set_enabled']))
    {
      $this->check_token();

      $id = $_GET['set_enabled'];
      $query = sql_placeholder('UPDATE articles SET enabled=1-enabled WHERE article_id=?',$id);
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
    $this->title = 'Статьи';
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
    				  FROM articles
    				  ORDER BY order_num DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->article_id, 'token'=>$this->token));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->article_id, 'token'=>$this->token));
       $items[$key]->edit_get = $this->form_get(array('section'=>'Article','item_id'=>$item->article_id, 'token'=>$this->token));
       $items[$key]->delete_get = $this->form_get(array('act'=>'delete','item_id'=>$item->article_id, 'token'=>$this->token));
       $items[$key]->enable_get = $this->form_get(array('set_enabled'=>$item->article_id, 'token'=>$this->token));
    }

  	$this->db->query("SELECT * FROM menu WHERE menu_id>0 ORDER BY menu_id DESC");
  	$menus = $this->db->results();
 	$this->smarty->assign('Menus', $menus);

  	$this->pages_navigation->fetch($pages_num);
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('articles.tpl');
  }
}
