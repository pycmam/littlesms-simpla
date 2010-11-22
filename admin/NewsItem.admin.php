<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');


############################################
# Class NewsItem - edit the news item
############################################
class NewsItem extends Widget
{
  var $item;
  function NewsItem(&$parent)
  {
    Widget::Widget($parent);
    $this->add_param('page');
    $this->prepare();
  }

  function prepare()
  {
   	$item_id = intval($this->param('item_id'));
  	if(isset($_POST['date']) &&
  	   isset($_POST['header']) &&
  	   isset($_POST['meta_title']) &&
  	   isset($_POST['meta_keywords']) &&
  	   isset($_POST['meta_description']) &&
  	   isset($_POST['annotation']) &&
  	   isset($_POST['body']))
  	{
  		$this->item->url = $_POST['url'];
  		$this->item->date = $_POST['date'];
  		$this->item->header = $_POST['header'];
  		$this->item->meta_title = $_POST['meta_title'];
  		$this->item->meta_keywords = $_POST['meta_keywords'];
  		$this->item->meta_description = $_POST['meta_description'];
  		$this->item->annotation = $_POST['annotation'];
  		$this->item->body = $_POST['body'];
  		
  		if(isset($_POST['enabled']) && $_POST['enabled']==1)
  		  $this->item->enabled = 1;
  		else
  		  $this->item->enabled = 0;  		

        $this->check_token();

        ## Не допустить одинаковые URL новостей.
    	$query = sql_placeholder('select count(*) as count from news where url=? and news_id!=?',
                $this->item->url,
                $item_id);
        $this->db->query($query);
        $res = $this->db->result();


  		if(empty($this->item->header))
  		  $this->error_msg = $this->lang->ENTER_TITLE;
  		elseif($res->count>0)
  		  $this->error_msg = 'Новость с таким URL уже существует. Выберите другой URL.';
        else
  		{
  			if(empty($item_id))
  			$query = sql_placeholder('INSERT INTO news(news_id, header, url, date, meta_title, meta_keywords, meta_description, annotation, body, enabled, created, modified) VALUES(NULL, ?, ?, STR_TO_DATE(?, "%d.%m.%Y"), ?, ?, ?, ?, ?, ?, now(), now())',
                                      $this->item->header,
                                      $this->item->url,
  			                          $this->item->date,
  			                          $this->item->meta_title,
  			                          $this->item->meta_keywords,
  			                          $this->item->meta_description,
  			                          $this->item->annotation,
  			                          $this->item->body,
  			                          $this->item->enabled
  			                          );
  			else
  			$query = sql_placeholder('UPDATE news SET header=?, url=?, date=STR_TO_DATE(?, "%d.%m.%Y"), meta_title=?, meta_keywords=?, meta_description=?, annotation=?, body=?, enabled=?, modified=now() WHERE news_id=?',
                                      $this->item->header,
                                      $this->item->url,
  			                          $this->item->date,
  			                          $this->item->meta_title,
  			                          $this->item->meta_keywords,
  			                          $this->item->meta_description,
  			                          $this->item->annotation,
  			                          $this->item->body,
  			                          $this->item->enabled,
  			                          $item_id);

  			$this->db->query($query);

            $this->db->query("UPDATE news SET url=news_id WHERE url=''");

 			$get = $this->form_get(array('section'=>'NewsLine'));
          if(isset($_GET['from']))
            header("Location: ".$_GET['from']);
          else
 		    header("Location: index.php$get");
  		}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT *, DATE_FORMAT(date, "%d.%m.%Y") as date FROM news WHERE news_id=?', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	}
  }

  function fetch()
  {
  	  if(empty($this->item->news_id))
  	    $this->title = $this->lang->NEW_NEWS_ITEM;
  	  else
  	    $this->title = $this->lang->EDIT_NEWS_ITEM;


 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('Error', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('news_item.tpl');
  }
}