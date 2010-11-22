<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('../placeholder.php');


############################################
# Class NewsLine displays news
############################################
class NewsLine extends Widget
{
  var $pages_navigation;
  var $items_per_page = 20;
  function NewsLine(&$parent)
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
        
  		$items = $_POST['items'];
        if(isset($_GET['item_id']) && !empty($_GET['item_id']))
          $items = array($_GET['item_id']);  		

  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM news
 		          WHERE news.news_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
    # Сделать новость видимой
    if(isset($_GET['set_enabled']))
    {
      $this->check_token();

      $id = $_GET['set_enabled'];
      $query = sql_placeholder('UPDATE news SET enabled=1-enabled WHERE news_id=?',$id);
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
    $this->title = $this->lang->NEWS;
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS *,
                      DATE_FORMAT(date, '%d.%m.%Y') as date
    				  FROM news
    				  ORDER BY date DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
       $items[$key]->edit_get = $this->form_get(array('section'=>'NewsItem','item_id'=>$item->news_id, 'token'=>$this->token));
       $items[$key]->delete_get = $this->form_get(array('act'=>'delete','item_id'=>$item->news_id, 'token'=>$this->token));
       $items[$key]->enable_get = $this->form_get(array('set_enabled'=>$item->news_id, 'token'=>$this->token));       
    }
    
  	$this->pages_navigation->fetch($pages_num);
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('news.tpl');
  }
}
