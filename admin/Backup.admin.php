<?PHP

require_once('Widget.admin.php');
require_once('pclzip/pclzip.lib.php');
require_once('mysqldump.php');

function myPreAddCallBack($p_event, &$p_header)
{
	$p_header['filename'];
	// пропускаем backup-файлы
	if (strpos($p_header['filename'], '../admin/backups/') === 0)
	{
		return 0;
	}
	// остальные файлы просто добавляются
	else {
		return 1;
	}
}

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

function clean_dir($path)
{
    $path= rtrim($path, '/').'/';
    $handle = opendir($path);
    for (;false !== ($file = readdir($handle));)
        if($file != "." and $file != ".." )
        {
            $fullpath= $path.$file;
            if( is_dir($fullpath) )
            {
                clean_dir($fullpath);
                rmdir($fullpath);
            }
            else
              unlink($fullpath);
        }
    closedir($handle);
}



############################################
# Class Backup
############################################

class Backup extends Widget
{
	var $error = '';
	
	function Backup(&$parent)
	{
		parent::Widget($parent);
		$this->prepare();
	}
	
	function prepare()
	{
	
  	 	if($this->config->demo)
  	 	{
  		 	$this->error = 'В демонстрационной версии загрузка файлов ограничена';
	  	}
		elseif (!is_writable('backups'))
		{
			$this->error = 'Для корректной работы установите права на запись для папки admin/backus';
		}
		elseif (isset($_FILES['backup']))
		{

			if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
			{
				header('Location: http://'.$this->root_url.'/admin/');
				exit();
			}

			$file = $_FILES['backup'];
			if(pathinfo($file['name'], PATHINFO_EXTENSION) != 'zip')
			{
			   $this->error = 'Недопустимый тип файла';
			}
			elseif(!move_uploaded_file($file['tmp_name'], 'backups/'.$file['name']))
			{
				$this->error = 'Не могу загрузить файл';
			}
			else
			{
				chmod('backups/'.$file['name'], 0777);
				$get = $this->form_get(array());
				header("Location: index.php$get");
			}
		}
		if (isset($_GET['delete_backup']))
		{

            $this->check_token();

			$file = $_GET['delete_backup'];
			if (is_writable('backups/'.$file))
			{
				unlink('backups/'.$file);
				$get = $this->form_get(array());
				header("Location: index.php$get");
			}
			else
			{
				$this->error = 'Недостаточно прав для удаления файла. Удалите файл через FTP';
			}
		}
		
		
		# Создаем бекап данных
		if (isset($_GET['action']) && $_GET['action'] == 'create_backup')
		{

            $this->check_token();

			$filename = 'backups/simpla_'.date("Y_m_d_G-i-s").'.zip';
			
			##Дамп базы
			@mysqldump('temp/simpla.sql');
			
			### Архивируем
			$zip = new PclZip($filename);
			$v_list = $zip->create('../files', PCLZIP_OPT_REMOVE_PATH, '../', PCLZIP_CB_PRE_ADD, 'myPreAddCallBack');
			if ($v_list == 0)
			{
				$this->error = 'Не могу заархивировать '.$zip->errorInfo(true);
			}
			else
			{
				$v_list = $zip->add('temp/simpla.sql', PCLZIP_OPT_REMOVE_PATH, 'temp/');
				unlink('temp/simpla.sql');
				if ($v_list == 0)
				{
					$this->error = 'Не могу добавить sql файл в архив '.$zip->errorInfo(true);
					unlink($filename);
				}
				else
				{
					$get = $this->form_get(array());
					header("Location: index.php$get");
				}
			}
			@chmod($filename, 0777);
		}
		
		
		# Восстанавливаем из бекапа
		if (isset($_GET['restore_backup']))
		{
            $this->check_token();

			$archive = 'backups/'.$_GET['restore_backup'];
			$zip = new PclZip($archive);
			
			$allow_restore = false;
			// Если найдем свежий бекап, разрешим восстановление 
			
			$handle = opendir('backups');
			for (;false !== ($file = readdir($handle));)
				if($file != "." and $file != ".." )
				{
					if(time()-filectime('backups/'.$file)<60*60)
						$allow_restore = true;
				}
			closedir($handle);

			
			
			// Начальные проверки
			if (!$allow_restore)
			{
				$this->error = 'Перед восстановлением создайте текущую копию';
			}
			if (!is_readable($archive))
			{
				$this->error = 'Архив не найден';
			}
			elseif (!is_writable('temp'))
			{
				$this->error = 'Запрещена запись в папку /admin/temp/';
			}
			elseif (!is_writable('../files'))
			{
				$this->error = 'Запрещена запись в папку /files/';
			}
			
			if(empty($this->error))
			{
				clean_dir('../files');

				if (!$zip->extract(PCLZIP_OPT_PATH, '../', PCLZIP_OPT_BY_PREG, "/^files\//", PCLZIP_CB_POST_EXTRACT, 'myPostExtractCallBack'))
				{
					$this->error = 'Не могу разархивировать '.$zip->errorInfo(true);
				}
				elseif (!$zip->extract(PCLZIP_OPT_PATH, 'temp/', PCLZIP_OPT_BY_NAME, 'simpla.sql'))
				{
					$this->error = 'Не могу разархивировать '.$zip->errorInfo(true);
				}
				elseif (!is_readable('temp/simpla.sql'))
				{
					$this->error = 'Не могу прочитать файл /temp/simpla.sql';
				}
				else
				{
					mysqlrestore('temp/simpla.sql');
					unlink('temp/simpla.sql');				
					$this->smarty->assign('Message', 'Восстановлена резервная копия '.$_GET['restore_backup']);	
				}
			}
		}
	}

	function fetch()
	{
		$this->title = $this->lang->BACKUP;
		$backup_files = glob("backups/*.zip");
		$backups = array();
		if ($backup_files)
		{
			$i=0;
			foreach ($backup_files as $backup_file)
			{
				$backups[$i]->file = basename($backup_file);
				$backups[$i]->size = filesize($backup_file);
				$backups[$i]->delete_url = $this->form_get(array('delete_backup'=>$backups[$i]->file, 'token'=>$this->token));
				$backups[$i]->restore_url = $this->form_get(array('restore_backup'=>$backups[$i]->file, 'token'=>$this->token));
				$i++;
			}
		}
		$backups = array_reverse($backups);
		$this->smarty->assign('Error', $this->error);
		$this->smarty->assign('Backups', $backups);
		$this->body = $this->smarty->fetch('backup.tpl');
	}
}
