<?PHP
require_once('Widget.admin.php');
require_once('../placeholder.php');

############################################
# Class Templates manage smarty templates
############################################
class Styles extends Widget
{

  var $styles_dir;
  var $error;

  function Styles(&$parent)
  {
	parent::Widget($parent);
    $this->prepare();
  }

  function prepare()
  {

    $this->styles_dir = '../design/'.$this->settings->theme.'/css';

    # Сохранить стиль
    if(isset($_POST['content']) && isset($_POST['filename']))
    {
    
  	  if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
  	  {
  	    header('Location: http://'.$this->root_url.'/admin/');
  	    exit();
  	  }    
    
      $style_file = $_POST['filename'];
      if(preg_match("/^[\_\w]*\.css$/i", $style_file))
      {
        if($handle = @fopen($this->styles_dir.'/'.$style_file, 'w'))
        {
          fputs($handle, $_POST['content']);
          fclose($handle);
        }
        else
        {
          $this->error = "Нет прав для записи файла ".$this->styles_dir.'/'.$style_file;
        }
      }
      else
      {
        $this->error = "Кривое имя файла ".$style_file;
      }
    }
  	  	
  }

  function fetch()
  {
  
  	$this->title = 'Стили CSS';

    $files = array();
    
    # Собираем список стилей
    if ($handle = @opendir($this->styles_dir))
    {
      while (false !== ($file = readdir($handle)))
      { 
        if (is_file($this->styles_dir.'/'.$file) && preg_match("/^[\_\w]*\.css$/i", $file))
        {
          unset($style);
          $style->filename = $file;
          $style->name = $file;
          $style->modified_date = filemtime($this->styles_dir.'/'.$file);
          $style->edit_url = $this->form_get(array('edit'=>$file));
                    
          if($cont = file_get_contents($this->styles_dir.'/'.$file))
          {        
            preg_match('/style name:(.+)\n/i', $cont, $matches);
            if(isset($matches[1]))
            $name = trim($matches[1]);
            if(!empty($name))
              $style->name = $name;

  
          }  
          $files[$style->modified_date.$style->filename] = $style;              
        } 
      }  
      
      ksort($files);
      $size = count($files);
      for($i=0; $i<$size; $i++)
      {
        $styles[$i] = array_pop($files);
        if($this->param('edit') == $styles[$i]->filename)
          $current_style = $styles[$i];
      }
      
      if(empty($current_style))
        $current_style = $styles[0];
      
      
      if(!$content = @file_get_contents($this->styles_dir.'/'.$current_style->filename))
      {
        $this->error = "Не могу прочитать файл ".$this->styles_dir.'/'.$current_style->filename;
      }
              
    }else
    {
      $this->error = "Не могу открыть папку $this->styles_dir";
    }
        
      
  	$this->smarty->assign('CurrentStyle', $current_style);
  	$this->smarty->assign('Content', $content);
  	$this->smarty->assign('Styles', $styles);
  	$this->smarty->assign('Error', $this->error);
 	$this->body = $this->smarty->fetch('styles.tpl');
  }
}
