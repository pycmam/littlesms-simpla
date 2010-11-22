<?PHP
require_once('Widget.admin.php');
require_once('pclzip/pclzip.lib.php');
require_once('../placeholder.php');


function myPostExtractCallBack($p_event, &$p_header)
{
	// проверяем успешность распаковки
	if ($p_header['status'] == 'ok')
	{
		// Меняем права доступа
		chmod($p_header['filename'], 0777);
	}
	return 1;
}


############################################
# Class Themes
############################################
class Themes extends Widget
{
  var $themes_dir = '../design';
  var $error='';
  
  function Themes(&$parent)
  {
	parent::Widget($parent);

    $this->prepare();
  }

  function prepare()
  {
  
    # Theme activation
  	if(isset($_GET['activate']))
  	{
  	  $this->check_token();

  	  $theme = $this->param('activate');
  	  if(is_dir($this->themes_dir.'/'.$theme))
  	  {
  	  
  	    # Удаляем скомпилированные шаблоны
        if ($handle = opendir('../compiled'))
        {
          while (false !== ($file = readdir($handle)))
          { 
            if (is_file('../compiled/'.$file) && $file[0] != '.')
            {
              @unlink('../compiled/'.$file);
            } 
          }          
  	    }
        $query = sql_placeholder("UPDATE settings SET value=? WHERE name='theme' LIMIT 1", $theme);
        $this->db->query($query);
        
    	$get = $this->form_get(array());
  		header("Location: index.php$get");
  	    
  	  }
  	  else
  	  {
  	    $this->error = 'Не могу открыть тему';
  	  } 
 	}
 	
   
    # Theme upload
  	
  	 $theme_uploaded = false;
  	 if($this->config->demo)
  	 {
  	 	$this->error = 'В демонстрационной версии загрузка файлов ограничена';
  	 }
     elseif(isset($_FILES['theme']) && !empty($_FILES['theme']['tmp_name']))
     {     

  	   if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
  	   {
  	      header('Location: http://'.$this->root_url.'/admin/');
  	      exit();
  	   }

       $filename = $_FILES['theme']['name'];
       if(preg_match("/^[\_\-\w\d]*\.zip$/i", $filename))
       {
		 if (!@move_uploaded_file($_FILES['theme']['tmp_name'], 'temp/'.$filename))
		 {
		   $this->error = 'Ошибка загрузки файла';
		 }
		 else
		 {
		   $theme_uploaded = true;
		 }		 
       }
       else
       {
         $this->error = 'Некорректное имя файла';
       }
     }
     elseif(isset($_POST['theme_url']) && !empty($_POST['theme_url']))
     {     

  	   if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
  	   {
  	      header('Location: http://'.$this->root_url.'/admin/');
  	      exit();
  	   }

       $url = str_replace('http://', '', $_POST['theme_url']);
       $filename = basename($_POST['theme_url']);
       if(preg_match("/^[\_\-\w\d]*\.[\w]{3}$/i",  $filename))
       {
		 if (!@copy('http://'.$url,  'temp/'.$filename))
		 {
		   $this->error = 'Ошибка загрузки файла. Проверьте установлен ли параметр allow_url_fopen в настройках php';
		 }
		 else
		 {
		   $theme_uploaded = true;
		 }		 
       }
       else
       {
         $this->error = 'Некорректное имя файла';
       }
     }	
     
     if($theme_uploaded)     
     {
 
       $archive = new PclZip('temp/'.$filename);
       $ext = strrchr($filename, '.');
 	   if($ext !== false)
         $theme_name = substr($filename, 0, -strlen($ext));

	   if(!is_dir($this->themes_dir.'/'.$theme_name))
         mkdir($this->themes_dir.'/'.$theme_name, 0777);
       chmod($this->themes_dir.'/'.$theme_name, 0777);
       if ($archive->extract(PCLZIP_OPT_PATH, $this->themes_dir.'/'.$theme_name, PCLZIP_OPT_REMOVE_PATH, $theme_name, PCLZIP_CB_POST_EXTRACT, 'myPostExtractCallBack') == 0)
       {
          rmdir($this->themes_dir);
          $this->error = "Не могу разархивировать архив (".$archive->errorInfo(true).")";
          @unlink('temp/'.$filename);
       }
       else
       {     
         @unlink('temp/'.$filename);
    	 $get = $this->form_get(array());
  		 header("Location: index.php$get");
  	   }
      }

    # Theme download
  	if(isset($_GET['download']))
  	{
  	  $theme = $_GET['download'];
  	  if(preg_match("/^[\_\-\w\d]*$/i",  $theme))
  	  {
        $archive = new PclZip('temp/'.$theme.'.zip');
        if($archive->create($this->themes_dir.'/'.$theme, PCLZIP_OPT_REMOVE_PATH, $this->themes_dir.'/'.$theme) == 0)
        {
          $this->error = 'Не могу создать архив ('.$archive->errorInfo(true).')';  	  
  	    }
  	    else
  	    {
          header('Content-type: application/zip'); 
          header('Content-Disposition: attachment; filename="'.$theme.'.zip"');  	    
  	      print file_get_contents('temp/'.$theme.'.zip');
  	      @unlink('temp/'.$theme.'.zip');  	    
  	      exit();
  	    } 
  	   }


  	}

  }

  function fetch()
  {
  	$this->title = 'Дизайн';
    $themes = array();
    
    # Choose all subdirs in design dir
  
    if ($handle = opendir($this->themes_dir)) {
    while (false !== ($file = readdir($handle)))
    { 
        if (is_dir($this->themes_dir.'/'.$file) && $file[0] != '.')
        {
          unset($theme); 
          
          $theme->dir = $file; 
          $theme->name = $file; 
          
		preg_match("/theme name:([^\n]+)\n/i", $css, $matches);
		if(isset($matches[1]))
		$name = trim($matches[1]);
		if(!empty($name))
		  $theme->name = $name;
		preg_match("/theme description:([^\n]+)\n/i", $css, $matches);
		if(isset($matches[1]))
		$theme->description = trim($matches[1]);
		$theme->activate_url = $this->form_get(array('activate'=>$theme->dir, 'token'=>$this->token));
		$theme->download_url = $this->form_get(array('download'=>$theme->dir));

          
          $themes[] = $theme; 
        } 
    }
    closedir($handle); 
    }
    else
    {
       $this->error = 'Темы не найдены';
    }

  	$this->smarty->assign('Themes', $themes);
  	$this->smarty->assign('Error', $this->error);
 	$this->body = $this->smarty->fetch('themes.tpl');
  }
}
