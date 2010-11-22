<?PHP
require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('../placeholder.php');

class Section extends Widget
{
  var $section;
  function Section(&$parent)
  {
    Widget::Widget($parent);
    $this->add_param('menu');
    $this->prepare();
  }

  function prepare()
  {
    $item_id = intval($this->param('item_id'));
    $this->section->menu_id = intval($this->param('menu'));
    
  	if(isset($_POST['section_id']))
  	{
  	  if(isset($_POST['name']))
  	    $this->section->name = $_POST['name'];
  	  if(isset($_POST['header']))
  	    $this->section->header = $_POST['header'];
  	  if(isset($_POST['url']))
  	    $this->section->url = $_POST['url'];
      if(isset($_POST['meta_title']))
  	    $this->section->meta_title = $_POST['meta_title'];
  	  if(isset($_POST['meta_description']))
  	    $this->section->meta_description = $_POST['meta_description'];
  	  if(isset($_POST['meta_keywords']))
  	    $this->section->meta_keywords = $_POST['meta_keywords'];
  	  if(isset($_POST['body']))
  	    $this->section->body = $_POST['body'];
  	  if(isset($_POST['module_id']))
  	    $this->section->module_id = $_POST['module_id'];
  	  if(isset($_POST['menu_id']))
  	    $this->section->menu_id = $_POST['menu_id'];

  	  if(isset($_POST['enabled']) && $_POST['enabled'] == 1)
  	    $this->section->enabled = 1;
  	  else
  	    $this->section->enabled = 0;

	  $this->check_token();
	  
      ## Не допустить одинаковые URL разделов.
  	  $query = sql_placeholder('SELECT count(*) AS count FROM sections WHERE url=? AND sections.section_id!=? ',
                $this->section->url,
  	  			$item_id);
      $this->db->query($query);
      $res = $this->db->result();

  	  if(empty($this->section->name))
  		  $this->error_msg = $this->lang->ENTER_SECTION_NAME;
  	  elseif($res->count>0)
  		  $this->error_msg = $this->lang->SECTION_WITH_SAME_URL_ALREADY_EXISTS;
      else{

  		if(empty($item_id))
        {
                $query = sql_placeholder('INSERT INTO sections
  	                    		SET sections.name=?,
  	                    		sections.header=?,
                                sections.url=?,
  	                    		sections.meta_title = ?,
  	                    		sections.meta_description = ?,
  	                    		sections.meta_keywords = ?,
  	                    		sections.body = ?,
  	                    		sections.module_id = ?,
  	                    		sections.menu_id = ?,
  	                    		sections.enabled = ?,
  	                    		sections.created = now(),
  	                    		sections.modified = now()',
  	  			$this->section->name,
  	  			$this->section->header,
                $this->section->url,
  	  			$this->section->meta_title,
  	  			$this->section->meta_description,
  	  			$this->section->meta_keywords,
  	  			$this->section->body,
  	  			$this->section->module_id,
  	  			$this->section->menu_id,
  	  			$this->section->enabled
  	  			);

  	            $this->db->query($query);
	  			$inserted_id = $this->db->insert_id();

  				$query = sql_placeholder('UPDATE sections SET order_num=section_id WHERE section_id=?',
  			                          $inserted_id);
  				$this->db->query($query);
  	    }
  	    else
  	    {
               $query = sql_placeholder('UPDATE sections
  	                    		SET sections.name=?,
  	                    		sections.header=?,
                                sections.url=?,
  	                    		sections.meta_title = ?,
  	                    		sections.meta_description = ?,
  	                    		sections.meta_keywords = ?,
  	                    		sections.body = ?,
  	                    		sections.module_id = ?,
  	                    		sections.menu_id = ?,
				  	  			sections.enabled=?,  	
				  	  			sections.modified = now()                    		
  	                    		WHERE sections.section_id=?',
  	  			$this->section->name,
  	  			$this->section->header,
                $this->section->url,
  	  			$this->section->meta_title,
  	  			$this->section->meta_description,
  	  			$this->section->meta_keywords,
  	  			$this->section->body,
  	  			$this->section->module_id,
  	  			$this->section->menu_id,
  	  			$this->section->enabled,
  	  			$item_id);

  	            $this->db->query($query);
  	    }

        $this->db->query("UPDATE sections SET url=section_id WHERE url=''");

  	    $get = $this->form_get(array('section'=>'Sections', 'menu'=>$this->section->menu_id));
        if(isset($_GET['from']))
          header("Location: ".$_GET['from']);
        else
 		  header("Location: index.php$get");
      }
  	}
    elseif($item_id)
    {
      $query = sql_placeholder('SELECT *
  	                    		FROM sections
  	                    		WHERE section_id=?',
                                $item_id);
  	  $this->db->query($query);
  	  $this->section = $this->db->result();
    }
    
  }

  function fetch()
  {
  	  if(empty($this->section->name))
  	    $this->title = 'Новая страница';
      else
  	    $this->title = $this->section->name;

 	  $this->smarty->assign('Error', $this->error_msg);
 	  $this->smarty->assign('Section', $this->section);
	  $this->smarty->assign('Lang', $this->lang);

 	  $this->db->query("SELECT * FROM modules WHERE valuable");
 	  $modules = $this->db->results();
 	  $this->smarty->assign('Modules', $modules);
 	  
 	  $query = sql_placeholder("SELECT * FROM menu WHERE menu_id = ?", $this->section->menu_id);
      $this->db->query($query);
  	  $this->menu = $this->db->result();
      $this->smarty->assign('Menu', $this->menu);
 
      $this->db->query("SELECT * FROM menu WHERE menu_id>0 ORDER BY menu_id DESC");
      $this->menus = $this->db->results();
      $this->smarty->assign('Menus', $this->menus);
  	
 	  $this->body = $this->smarty->fetch('section.tpl');
  }
}

