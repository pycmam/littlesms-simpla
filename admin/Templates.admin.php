<?PHP
require_once('Widget.admin.php');
require_once('../placeholder.php');

############################################
# Class Templates manage smarty templates
############################################
class Templates extends Widget
{

  var $templates_dir;
  var $error;

  function Templates(&$parent)
  {
	parent::Widget($parent);
    $this->prepare();
  }

  function prepare()
  {

    $this->templates_dir = '../design/'.$this->settings->theme.'/html';

    # Сохранить шаблон
    
    
  	 if($this->config->demo)
  	 {
  	 	$this->error = 'В демонстрационной версии редактирование шаблонов запрещено';
 	 }
    elseif(isset($_POST['content']) && isset($_POST['filename']))
    {
    
  	  if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
  	  {
  	    header('Location: http://'.$this->root_url.'/admin/');
  	    exit();
  	  }
    
    
      $template_file = $_POST['filename'];
      if(preg_match("/^[\_\w\d]*\.tpl$/i", $template_file))
      {
        if($handle = @fopen($this->templates_dir.'/'.$template_file, 'w'))
        {
          fputs($handle, $_POST['content']);
          fclose($handle);
        }
        else
        {
          $this->error = "Нет прав для записи файла ".$this->templates_dir.'/'.$template_file;
        }
      }
      else
      {
        $this->error = "Кривое имя файла ".$template_file;
      }
    }
    
  	  	
  }

  function fetch()
  {
  	$this->title = 'Шаблоны дизайна';
  
    $tpls = array();  
    
    # Собираем список шаблонов
    if ($handle = @opendir($this->templates_dir))
    {
      while (false !== ($file = readdir($handle)))
      { 
        if (is_file($this->templates_dir.'/'.$file) && preg_match("/^[\_\w\d]*\.tpl$/i", $file))
        {
          $template = null;
                    
          $template->filename = $file;
          $template->name = $file;
          $template->modified_date = filemtime($this->templates_dir.'/'.$file);
          $template->edit_url = $this->form_get(array('edit'=>$file));
                    
          if($cont = file_get_contents($this->templates_dir.'/'.$file))
          {  
            $name = '';    
            preg_match('/template name:(.+)\n/i', $cont, $matches);
            if(isset($matches[1]))
            $name = trim($matches[1]);
            if(!empty($name))
              $template->name = $name;

  
          }  
          $tpls[$template->modified_date.$template->filename] = $template;              
        } 
      }  
      
      ksort($tpls);
      $size = count($tpls);
      for($i=0; $i<$size; $i++)
      {
        $templates[$i] = array_pop($tpls);
        if($this->param('edit') == $templates[$i]->filename)
          $current_template = $templates[$i];
      }
      
      if(empty($current_template))
        $current_template = $templates[0];
      
      
      if(!$content = @file_get_contents($this->templates_dir.'/'.$current_template->filename))
      {
        $this->error = "Не могу прочитать файл ".$this->templates_dir.'/'.$current_template->filename;
      }
              
    }else
    {
      $this->error = "Не могу открыть папку $this->templates_dir";
    }
        
      
  	$this->smarty->assign('CurrentTemplate', $current_template);
  	$this->smarty->assign('Content', $content);
  	$this->smarty->assign('Templates', $templates);
  	$this->smarty->assign('Error', $this->error);
 	$this->body = $this->smarty->fetch('templates.tpl');
  }
}
