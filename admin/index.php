<?PHP

// Засекаем время
$time_start = microtime(true);

require_once('Page.admin.php');
session_start();

// Кеширование в админке нам не нужно
Header("Cache-Control: no-cache, must-revalidate");
Header("Pragma: no-cache");


// Если в админку перешли неизвестно откуда, просто показать главную страничку
if(empty($_SERVER['HTTP_REFERER']) || !preg_match('#^http://'.$_SERVER['HTTP_HOST'].'#i',$_SERVER['HTTP_REFERER']) && (!empty($_GET) || !empty($_POST)))
{
  //header('Location: ./');
  //exit();
}

// Установим переменную сессии, чтоб фронтенд нас узнал как админа
$_SESSION['admin'] = 'admin';

if ($_SESSION["logout"]) {
    header('HTTP/1.0 401 Unauthorised');
    header('WWW-Authenticate: Basic realm="Administrator Area"'); // Change MyRealm to be the same as AuthName in .htaccess
    $_SESSION["logout"] = false;
}

// Если попросили разлогинится - убиваем сессию и переходим на сайт
if(isset($_GET['action']) && $_GET['action']=='logout')
{
	$_SESSION["logout"] = true;
    session_unregister('admin');
    header('location: ../');
    exit();
}


// Page ни от кого не наследуется так что передаем ноль
$page = new Page($a = 0);
$page->fetch();
$page->db->disconnect();

// Выводим страницу на экран
print $page->body;

// Отладочная информация
if($page->config->debug)
{
	print "<!--\r\n";
	$i = 0;
	$sql_time = 0;
	foreach($page->db->queries as $q)
	{
		$i++;
		print "$i.\t$q->exec_time sec\r\n$q->sql\r\n\r\n";
		$sql_time += $q->exec_time;
	}
  
	$time_end = microtime(true);
	$exec_time = $time_end-$time_start;
  
  	if(function_exists('memory_get_peak_usage'))
		print "memory peak usage: ".memory_get_peak_usage()." bytes\r\n";  
	print "page generation time: ".$exec_time." seconds\r\n";  
	print "sql queries time: ".$sql_time." seconds\r\n";  
	print "php run time: ".($exec_time-$sql_time)." seconds\r\n";  
	print "-->";
}
?>