<?php

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Этот класс является базовым для всех классов.
 * В нем происходит подключение к бд, и другие важные действия
 *
 */

require_once('Config.class.php');
require_once('Database.class.php');
require_once('placeholder.php');
require_once('detect_browser.php');
require_once('Smarty/libs/Smarty.class.php');


class Widget
{
    var $parent; // Родительский контейнер
	var $params = array(); // get-параметры
    var $title = null; // meta title
    var $description = null; // meta description
    var $keywords = null; // metakeywords
    var $body = null; // содержимое блока

    var $db; // база данных
	var $smarty; // смарти
	var $config; // конфиг (из конфиг-класса)
    var $settings; // параметры сайта (из таблицы settings)
    var $user; // пользователь, если залогинен
    var $currency; // текущая валюта
    var $gd_loaded = false; // подключена ли графическая библиотека
    var $mobile_user = false; // подключена ли графическая библиотека

	/**
	 *
	 * Конструктор
	 *
	 */
    function Widget(&$parent)
    {
    	// если текущий блок входит в некий другой блок
        if (is_object($parent))
        {
        	// стырить у того все параметры
        	$this->parent=$parent;
            $this->db=&$parent->db;
            $this->smarty=&$parent->smarty;
            $this->config=&$parent->config;
            $this->params=&$parent->params;
            $this->settings=&$parent->settings;
            $this->user=&$parent->user;
            $this->root_dir=&$parent->root_dir;
            $this->root_url=&$parent->root_url;
            $this->currency=&$parent->currency;
            $this->main_currency=&$parent->main_currency;
            $this->currencies=&$parent->currencies;
            $this->gd_loaded=&$parent->gd_loaded;
        }
        else
        {

			// Читаем конфиг
			$this->config = new Config();
						
			// Мобильный браузер?
			if(is_mobile_browser())
				$this->mobile_user = true;
						
			// Если установлены magic_quotes, убираем лишние слеши
			if(get_magic_quotes_gpc())
			{
				$_POST = $this->stripslashes_recursive($_POST);
				$_GET = $this->stripslashes_recursive($_GET);
			}

			// Подключаемся к базе данных
			$this->db=new Database($this->config->dbname,$this->config->dbhost,
			$this->config->dbuser,$this->config->dbpass);

			if(!$this->db->connect())
			{
				print "Не могу подключиться к базе данных. Проверьте настройки подключения";
				exit();
			}
			$this->db->query('SET NAMES utf8');

			// Выбираем из базы настройки, которые задаются в разделе Настройки в панели управления
			$query = 'SELECT name, value FROM settings';
			$this->db->query($query);
			$sts = $this->db->results();
			foreach($sts as $s)
			{	
				$name = $s->name;
				$this->settings->$name = $s->value;
			}
			
			// Настраиваем смарти
			$this->smarty = new Smarty();
			$this->smarty->compile_check = true;
			$this->smarty->caching = false;
			$this->smarty->cache_lifetime = 0;
			$this->smarty->debugging = false;
			$this->smarty->security = true;
			$this->smarty->secure_dir[] = 'admin/templates';
			
			// Для мобильного клиента подменяем тему дизайна на мобильную
			if($this->mobile_user)
			{
				$this->settings->theme = 'mobile';
				$this->smarty->compile_dir = 'compiled/mobile';
			}
			else
			{
				$this->smarty->compile_dir = 'compiled';
			}
			
			$this->smarty->template_dir = $this->smarty->secure_dir[] = 'design/'.$this->settings->theme.'/html';
			
			$this->smarty->config_dir = 'configs';
			$this->smarty->cache_dir = 'cache';
	  
			$this->smarty->assign('settings', $this->settings);

			// Проверка подключения графической библиотеки
			if(extension_loaded('gd'))
				$this->gd_loaded = true;
			$this->smarty->assign('gd_loaded', $this->gd_loaded);

			// Определяем корневую директорию сайта
			$this->root_dir =  str_replace(basename($_SERVER["PHP_SELF"]), '', $_SERVER["PHP_SELF"]);
			$this->smarty->assign('root_dir', $this->root_dir);

			// Корневой url сайта
			$dir = trim(dirname($_SERVER['SCRIPT_NAME']));
			$dir = str_replace("\\", '/', $dir);
			$this->root_url = $_SERVER['HTTP_HOST'];
			if($dir!='/')
			    $this->root_url = $this->root_url.$dir;
			$this->smarty->assign('root_url', $this->root_url);

			// Залогиниваем юзера
			$this->user = null;
			if(isset($_SESSION['user_email']) && isset($_SESSION['user_password']))
			{
				$email = $_SESSION['user_email'];
				$password = $_SESSION['user_password'];
				if(!empty($email) && !empty($password))
				{
					$query = sql_placeholder("SELECT users.*, groups.discount AS discount, groups.name AS group_name FROM users LEFT JOIN groups ON groups.group_id=users.group_id WHERE email=? AND password=? AND enabled=1", $email, $password);
					$this->db->query($query);
					$this->user = $this->db->result();
					$this->smarty->assign('user', $this->user);
				}
			}
			
			// Курсы валют
			$query = "SELECT currency_id, name, sign, code, rate_from, rate_to, main, def FROM currencies ORDER BY currency_id";
			$this->db->query($query);
			$temp_currencies = $this->db->results();

			foreach($temp_currencies as $currency)
			{
				$this->currencies[$currency->currency_id] = $currency;
				if($currency->main)
					$this->main_currency = $currency;
				if($currency->def)
					$this->default_currency = $currency;
			}

			if(isset($_POST['currency_id']))
			{
				$_SESSION['currency_id'] = intval($_POST['currency_id']);
			}

			if(isset($_SESSION['currency_id']))
			{
				$this->currency = $this->currencies[intval($_SESSION['currency_id'])];
			}
			else
			{
				$this->currency = $this->default_currency;
			}

			$this->smarty->assign('currencies', $this->currencies);
			$this->smarty->assign('currency', $this->currency);
			$this->smarty->assign('main_currency', $this->main_currency);



		}
    }

	/**
	 *
	 * Отображение блока
	 *
	 */
    function fetch()
    {
    	return $this->body="";
    }

	/**
	 *
	 * Рекурсивная уборка магических слешей
	 *
	 */
    function stripslashes_recursive($var)
    {
    	$res = null;
    	if(is_array($var))
    	  foreach($var as $k=>$v)
    	    $res[stripcslashes($k)] = $this->stripslashes_recursive($v);
    	  else
    	    $res = stripcslashes($var);
    	return $res;
    }
 

    function param($name)
    {
    	if(!empty($name))
      	{
      		if(isset($this->params[$name]))
	  		  return $this->params[$name];
	  		elseif(isset($_GET[$name]))
	  		  return $_GET[$name];
    	}
	    return null;
    }

	/**
	 *
	 * Подмена параметра get
	 *
	 */

    function add_param($name)
    {
    	if(!empty($name) && isset($_GET[$name]))
    	{
			$this->params[$name] = $_GET[$name];
	        return true;
    	}
	    return false;
    }

    function url_filter($val)
    {
    	$val =  preg_replace('/[^A-zА-я0-9_\-\.\%\s]/ui', '', $val);
	    return $val;
    }

    function url_filtered_param($name)
    {
	    return $this->url_filter($this->param($name));
    }
    
    function form_get($extra_params)
    {
    	$copy=$this->params;
      	foreach($extra_params as $key=>$value)
      	{
	    	if(!is_null($value))
    	  	{
          		$copy[$key]=$value;
	        }
    	}

	    $get='';
    	foreach($copy as $key=>$value)
		{
        	if(strval($value)!="")
	        {
    		    if(empty($get))
            	  $get .= '?';
	        	else
    	          $get .= '&';
    	        $get .= urlencode($key).'='.urlencode($value);
        	}
	    }
      	return $get;
    }
    
    
    function email($to, $subject, $message, $additional_headers='')
    {
    	$site_name = "=?utf-8?B?".base64_encode($this->settings->site_name)."?=";
    	
    	if(!empty($this->settings->notify_from_email))
    		$from = "$site_name <".$this->settings->notify_from_email.">";
    	else
    		$from = "$site_name <simpla@".$_SERVER['HTTP_HOST'].">";
    		
    	$headers = "MIME-Version: 1.0\n" ;
    	$headers .= "Content-type: text/html; charset=utf-8; \r\n"; 
    	$headers .= "From: $from \r\n";
    	
    	$subject = "=?utf-8?B?".base64_encode($subject)."?=";

    	@mail($to, $subject, $message, $headers);
    }
}

?>