<?PHP
require_once('Widget.admin.php');
require_once('../placeholder.php');

############################################
# Class Sections displays a list of sections
############################################
class Images extends Widget
{
  var $images_dir;
  var $max_width=135;
  var $max_height=135;
  
  var $error = '';
  
  function Images(&$parent)
  {
	parent::Widget($parent);

    $this->prepare();
  }

  function prepare()
  {
  
     $this->images_dir = '../design/'.$this->settings->theme.'/images';
     
     
     if($this->param('delete_file'))
     {
   
       $this->check_token();

       $filename = $this->param('delete_file');
       if(preg_match("/^[\_\-\w\d]*\.[\w]{3}$/i", $filename))
       {
         if(@unlink($this->images_dir.'/'.$filename))
         {
    	   $get = $this->form_get(array());
    	   header("Location: index.php$get");
         }
         else
         {
           $this->error = 'Не могу удалить файл. Проверьте права доступа к файлам';
         }
       }
     }
  
  	 if($this->config->demo)
  	 {
  	 	$this->error = 'В демонстрационной версии загрузка файлов ограничена';
  	 }  
     elseif(isset($_FILES['image']) && !empty($_FILES['image']['tmp_name']))
     {     

  	   if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
  	   {
  	      header('Location: http://'.$this->root_url.'/admin/');
  	      exit();
  	   }


       $filename = $_FILES['image']['name'];
       if(preg_match("/^[\_\-\w\d]*\.(jpg|jpeg|gif|png)$/i", $filename))
       {
		 if (!@move_uploaded_file($_FILES['image']['tmp_name'], $this->images_dir.'/'.$filename))
		 {
		   $this->error = 'Ошибка загрузки файла';
		 }
		 else
		 {
		   chmod($this->images_dir.'/'.$filename, 0666);
		 }
       }
       else
       {
         $this->error = 'Некорректное имя файла';
       }
     }
     elseif(isset($_POST['image_url']))
     {     
       $url = str_replace('http://', '', $_POST['image_url']);
       $filename = basename($_POST['image_url']);
       if(preg_match("/^[\_\-\w\d]*\.[\w]{3}$/i",  $filename))
       {
		 if (!@copy('http://'.$url, $this->images_dir.'/'.$filename))
		 {
		   $this->error = 'Ошибка загрузки файла. Проверьте установлен ли параметр allow_url_fopen в настройках php';
		 }
		 else
		 {
		   chmod($this->images_dir.'/'.$filename, 0666);
		 }
       }
       else
       {
         $this->error = 'Некорректное имя файла';
       }
     }	
  }

  function fetch()
  {
  	$this->title = 'Картинки';

    $images = array();
    
    if(!is_writable($this->images_dir))
    {
      $this->error = "Для корректной работы установите разрешение на запись в папку $this->images_dir";
    }
    # Choose all subdirs in design dir
  
    if ($handle = opendir($this->images_dir))
    {
      while (false !== ($file = readdir($handle)))
      { 
        while (false !== ($file = readdir($handle)))
        { 
          if (is_file($this->images_dir.'/'.$file) && preg_match("/^[\_\-\w\d]*\.[\w]{3}$/i", $file))
          {
            $image = null;
            $image->filename =  $file;
            
            if(list($w,$h) = @getimagesize($this->images_dir.'/'.$file))
            {
              $scale_x = $scale_y = 1;
              
              if($w > $this->max_width)
                $scale_x = $w/$this->max_width;
              if($h > $this->max_height)
                $scale_y = $h/$this->max_height;
              $scale = max($scale_x, $scale_y);

              $image->width = $w;
              $image->height = $h;
              $image->scale_to_width = round($w/$scale);
              $image->scale_to_height = round($h/$scale);
              
              $image->delete_url = $this->form_get(array('delete_file'=>$file, 'token'=>$this->token));
            
            }
          
            $images[] = $image;              
          } 
        }
      }
      closedir($handle); 
    }
    else
    {
       $this->error = 'Не могу открыть папку с картинками';
    }

  	$this->smarty->assign('Images', $images);
  	$this->smarty->assign('Error', $this->error);
 	$this->body = $this->smarty->fetch('images.tpl');
  }
}
