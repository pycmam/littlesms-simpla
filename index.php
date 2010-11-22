<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 */
 
// Засекаем время
$time_start = microtime(true);

session_start();

require_once('Site.class.php');

// Создаем экземпляр класса Site
// В параметре передается родитель класса, но не в смысле наследования,
// а в смысле вложенности блоков страницы. Класс Site не имеет родителя,
// поэтому передаем null
$site = new Site($a = null);

// Если все хорошо
if($site->fetch() !== false)
{
	// Выводим результат
	print $site->body;
}
else 
{
	// Иначе страница об ошибке
	header("http/1.0 404 not found");
	// Подменим переменную, чтобы вывести страницу 404
	$_GET['section'] = '404';
	$site = new Site($a = 0);
	$site->fetch();
	print $site->body;    
}

// Отладочная информация
if($site->config->debug)
{
	print "<!--\r\n";
	$i = 0;
	$sql_time = 0;
	foreach($site->db->queries as $q)
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