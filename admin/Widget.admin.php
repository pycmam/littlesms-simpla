<?php

////////////////////////////////////////////////
// class Widget - базовый класс для всех модулей
////////////////////////////////////////////////

require_once('../Config.class.php');
require_once('../Database.class.php');

class Widget
{
	var $params = array(); // get-параметры
    var $title = null; // meta-title
    var $description = null; // meta-description
    var $keywords = null; // meta-keywords
    var $body = null; // тело страницы
    var $error_msg = null; // сообщение об ошибке

    var $db; // база данный (класса Database)
	var $smarty; //  шаблонизатор
	var $config; // Config.class.php - класс с найтройками
    var $settings; // настройки сайта (из таблицы settings)
	var $lang;  // язык панели управления
    var $parent;  // родитель текущего объекта (в плане иерархии simpla)
    var $main_currency;  // родитель текущего объекта (в плане иерархии simpla)
    var $use_gd = true;
    var $root_url = '';
    var $root_dir = '';
  
    function stripslashes_recursive($var)
    {
    	if(is_array($var))
    	  foreach($var as $k=>$v)
    	    $var[$k] = $this->stripslashes_recursive($v);
    	  else
    	    $var = stripcslashes($var);
    	return $var;
    }

    function Widget(&$parent)
    {
        if (is_object($parent))
        {
        	$this->parent=$parent;
            $this->db=&$parent->db;
            $this->smarty=&$parent->smarty;
            $this->config=&$parent->config;
            $this->params=&$parent->params;
            $this->settings=&$parent->settings;
            $this->main_currency=&$parent->main_currency;
            $this->lang=&$parent->lang;
            $this->root_url=&$parent->root_url;
            $this->root_dir=&$parent->root_dir;
            $this->token=&$parent->token;
        }
        else
        {
			if(get_magic_quotes_gpc())
			{
			  $_POST = $this->stripslashes_recursive($_POST);
			  $_GET = $this->stripslashes_recursive($_GET);
			}

			$this->config=new Config();

            require_once("Language.".$this->config->lang.".admin.php");
            $this->lang = new Language();

            require_once("../Smarty/libs/Smarty.class.php");
            $this->smarty = new Smarty();
            $this->smarty->compile_check = true;
            $this->smarty->caching = false;
            $this->smarty->cache_lifetime = 0;
     		$this->smarty->debugging = false;
			$this->smarty->template_dir = 'templates/';
			$this->smarty->compile_dir = 'compiled/';
			$this->smarty->config_dir = 'configs/';
			$this->smarty->cache_dir = 'cache/';

            $this->db=new Database($this->config->dbname,$this->config->dbhost,
    							$this->config->dbuser,$this->config->dbpass);
    		$this->db->connect();
    		$this->db->query("SET NAMES utf8");

   		    $query = 'SELECT * FROM settings';
    		$this->db->query($query);
    		$sts = $this->db->results();
    		foreach($sts as $s)
    		{
      			$name = $s->name;
    			$this->settings->$name = $s->value;
	   		}

			// Определяем корневую директорию сайта
			$this->root_dir = rtrim(dirname(dirname(($_SERVER["PHP_SELF"]))), '/');
			$this->smarty->assign('root_dir', $this->root_dir);

			// Корневой url сайта
			$dir = trim(dirname(dirname($_SERVER['SCRIPT_NAME'])));
			$dir = str_replace("\\", '/', $dir);
			$this->root_url = $_SERVER['HTTP_HOST'];
			if($dir!='/')
			    $this->root_url = $this->root_url.$dir;
			$this->smarty->assign('root_url', $this->root_url);


   		    $query = 'SELECT * FROM currencies WHERE main=1 LIMIT 1';
    		$this->db->query($query);
    		$this->main_currency = $this->db->result();
    		
    		// Если не установлена библиотека GD, не используем ее
            if(!extension_loaded('gd'))
              $this->use_gd = false;
              
			// Устанавливает token для защиты от xss
			if(empty($_GET) && empty($_POST))
			{
				$this->token =  md5(uniqid(rand(), true));
				$_SESSION['token'] = $this->token;
			}elseif(isset($_SESSION['token']) && !empty($_SESSION['token']))
			{
				$this->token = $_SESSION['token'];  			
			}else
			{
				$this->token = '';
			}
	   		$this->smarty->assign('Token', $this->token);

            $this->smarty->assign('UseGd', $this->use_gd);

	   		$this->smarty->assign('Settings', $this->settings);
	   		$this->smarty->assign('Config', $this->config);
	   		$this->smarty->assign('MainCurrency', $this->main_currency);
	   		$this->smarty->assign('Lang', $this->lang);
    	}
    }

    function fetch()
    {
    	$this->body="";
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

    function add_param($name)
    {
    	if(!empty($name) && isset($_GET[$name]))
    	{
			$this->params[$name] = $_GET[$name];
	        return true;
    	}
	    return false;
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
    
    function email($to, $subject, $message)
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
    
    function check_token()
    {
    	$token = '';
    	if(isset($_GET['token'])) $token = $_GET['token'];
    	elseif(isset($_POST['token'])) $token = $_POST['token'];
    	if(empty($token)
    	|| !isset($_SESSION['token']) || empty($_SESSION['token'])
    	|| $token !== $_SESSION['token'])
    	{
    		header('Location: http://'.$this->root_url.'/admin/');
    		exit();
    	}
    }
    
}

?>