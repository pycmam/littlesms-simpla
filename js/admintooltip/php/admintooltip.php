<?php
session_start();
if(isset($_SESSION['admin']) && $_SESSION['admin'] == 'admin' && isset($_SESSION['token']) && !empty($_SESSION['token']))
{
  $token = $_SESSION['token'];
}else
{
  $token = '';
}

function cl()
{
	$m = 40141;
	$p = 291;
	$d = split(' ', trim(file_get_contents('../../../license')));

	$result = 1;
	$max = count($d);
	for ($j=0x0; $j<$max; $j++)
	{
		$b=base_convert($d[$j],36,10);
		$result = 1;
		for($i=0x0; $i<$p; $i++)
		{
			$result = ($result*$b) % $m;
		}
		$decoded .= chr($result);
	}		
	
	  
	$license = split('#', $decoded);
	  
	$hash = $license[1];
	$data = $license[0];
	
	
	$host = $host1 = $_SERVER['HTTP_HOST'];
	$host2 = getenv('HTTP_HOST');
	if(function_exists('apache_getenv'))
		$host3 = apache_getenv('HTTP_HOST');
	else
		$host3 = $host1;
		
	if(!($host1 == $host2 && $host1 == $host3))
		return false;
	
	$ip = getenv('REMOTE_ADDR');
	if($_SERVER['REMOTE_ADDR'] == $ip && substr($ip,0,3)=='127' && strtoupper(substr(php_uname(), 0, 3)) === 'WIN' )
	{
		return true;
	}

	$l_array = split(';', $data);

	
	$domain = $l_array[0];
	if(isset($l_array[1]))
		$start = $l_array[1];
	else
		return  false;



	if(isset($l_array[2]))
		$end = $l_array[2];
	else
		return false;

	if(isset($l_array[3]))
		$comment = $l_array[3];
	else
		$comment = '';
		
		
	$domns = split(',', $domain);
	
	$ok = false;
	foreach($domns as $d)
	{
		if(trim($d) == $host)
			$ok = true;
	}


	if(!$ok)
  		return  false;
	if(strtotime($start)>time())
  		return false;	
  		
	if(strtotime($end)<time())
  		return false;


	return true;
}

if(!@cl())
{
	print("al"."ert(une"."scape('%u041B%u0438%u0446%u0435%u043D%u0437%u0438%u044F%20%u043D%u0435%u0434%u0435%u0439%u0441%u0442%u0432%u0438%u0442%u0435%u043B%u044C%u043D%u0430'))");
	exit();
}

?>
oldOnLoad = window.onload;
if (typeof window.onload != 'function') {
    window.onload = function() {
	CreateTooltip();
	SetTooltips();
    };
}
else {
    window.onload = function() {
        oldOnLoad();
	CreateTooltip();
	SetTooltips();
    };
}



function CreateTooltip() {
	tooltip = document.createElement('DIV');
	tooltip.setAttribute('id', 'tooltip');

	tooltipHeader = document.createElement('DIV');
	tooltipHeader.setAttribute('id', 'tooltipHeader');
	tooltipHeader.setAttribute('class', 'direct');

	tooltipBody   = document.createElement('DIV');
	tooltipBody.setAttribute('id', 'tooltipBody');

	tooltipFooter = document.createElement('DIV');
	tooltipFooter.setAttribute('id', 'tooltipFooter');

	tooltipBody.innerText = 'tooltip';

	tooltip.appendChild(tooltipHeader);
	tooltip.appendChild(tooltipBody);
	tooltip.appendChild(tooltipFooter);
	tooltipcanclose=true;

	tooltip.onmouseover   = function(e) { this.style.filter = "Alpha(Opacity='100')"; this.style.MozOpacity = '1';  tooltipcanclose=false;}
	tooltip.onmouseout    = function(e) { this.style.filter = "Alpha(Opacity='85')"; this.style.MozOpacity = '0.85'; tooltipcanclose=true; setTimeout("CloseTooltip();", 1000);}

	tooltip.onclick       = function(e) {   tooltipcanclose=true; CloseTooltip(); }

	document.body.appendChild(tooltip);

	window.onresize      = function(e) {  tooltipcanclose=true; CloseTooltip(); }
	document.body.onclick= function(e) {  tooltipcanclose=true; CloseTooltip(); }
	
	adminpanel = document.createElement('div');
	adminpanel.style.cssText = "position: absolute; left: 3%; top: 0px; z-index: 1000;";
	adminpanel.setAttribute('style', 'position: absolute; left: 3%; top: 0px; z-index: 1000;');
	adminpanel.innerHTML = "<a href='admin/'><img  title='Перейти в панель управления' alt='Перейти в панель управления' border=0 src='js/admintooltip/i/bookmark.gif'></a>";
    document.body.appendChild(adminpanel);
    
	
}

function CloseTooltip()
{
  if(tooltipcanclose)
    document.getElementById('tooltip').style.display = 'none';
}

function SetTooltips() {
	elements = document.getElementsByTagName("body")[0].getElementsByTagName("*");

	for (i = 0; i <elements.length; i++)
	{
		tooltip = elements[i].getAttribute('tooltip');
		if(tooltip)
		{
		    elements[i].onmouseout = function(e) {tooltipcanclose=true;setTimeout("CloseTooltip();", 1000);};		
			switch(tooltip)
			{	
				case 'product':					   			   
				   elements[i].onmouseover = function(e) {AdminProductTooltip(this,  this.getAttribute('product_id'));tooltipcanclose=false;}
				break;
				case 'hit':					   			   
				   elements[i].onmouseover = function(e) {AdminHitTooltip(this,  this.getAttribute('product_id'));tooltipcanclose=false;}
				break;
				case 'category':					   				   
				   elements[i].onmouseover = function(e) {AdminCategoryTooltip(this,  this.getAttribute('category_id'));tooltipcanclose=false;}
				break;
				case 'brand':					   				   
				   elements[i].onmouseover = function(e) {AdminBrandTooltip(this,  this.getAttribute('brand_id'));tooltipcanclose=false;}
				break;
				case 'news':					   				   
				   elements[i].onmouseover = function(e) {AdminNewsTooltip(this,  this.getAttribute('news_id'));tooltipcanclose=false;}
				break;
				case 'article':					   				   
				   elements[i].onmouseover = function(e) {AdminArticleTooltip(this,  this.getAttribute('article_id'));tooltipcanclose=false;}
				break;
				case 'section':					   				   
				   elements[i].onmouseover = function(e) {AdminSectionTooltip(this,  this.getAttribute('section_id')); tooltipcanclose=false;}
				break;
				case 'currency':					   				   
				   elements[i].onmouseover = function(e) {AdminCurrencyTooltip(this); tooltipcanclose=false;}
				break;
				case 'comment':					   				   
				   elements[i].onmouseover = function(e) {AdminCommentTooltip(this, this.getAttribute('comment_id')); tooltipcanclose=false;}
				break;
			}


		}
		
	}

}


function ShowTooltip(i, content) {

	tooltip = document.getElementById('tooltip');

	document.getElementById('tooltipBody').innerHTML = content;
	tooltip.style.display = 'block';

	var xleft=0;
	var xtop=0;
	o = i;

	do {
		xleft += o.offsetLeft;
		xtop  += o.offsetTop;

	} while (o=o.offsetParent);

	xwidth  = i.offsetWidth  ? i.offsetWidth  : i.style.pixelWidth;
	xheight = i.offsetHeight ? i.offsetHeight : i.style.pixelHeight;

	bwidth =  tooltip.offsetWidth  ? tooltip.offsetWidth  : tooltip.style.pixelWidth;

	w = window;

	xbody  = document.compatMode=='CSS1Compat' ? w.document.documentElement : w.document.body;
	dwidth = xbody.clientWidth  ? xbody.clientWidth   : w.innerWidth;
	bwidth = tooltip.offsetWidth ? tooltip.offsetWidth  : tooltip.style.pixelWidth;

	flip = !( 25 + xleft + bwidth < dwidth);

	tooltip.style.top  = xheight - 3 + xtop + 'px';
	tooltip.style.left = (xleft - (flip ? bwidth : 0)  + 25) + 'px';

	document.getElementById('tooltipHeader').className = flip ? 'tooltipHeaderFlip' : 'tooltipHeaderDirect';

	return false;
}

function AdminProductTooltip(element, object_id)
{
  from = encodeURIComponent(window.location); 
  content = "<p><a href='admin/index.php?section=Product&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_edit>Редактировать</a></p>";
  content += "<p><a href='admin/index.php?section=Storefront&action=move_up&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_up>Поднять</a></p>";
  content += "<p><a href='admin/index.php?section=Storefront&action=move_down&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_down>Опустить</a></p>";
  content += "<p><a href='admin/index.php?section=Storefront&set_hit="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_hit>Хит</a></p>";
  content += "<p><a href='admin/index.php?section=Storefront&action=copy&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_edit>Создать копию</a></p>";
  content += "<p><a href='admin/index.php?section=Storefront&set_enabled="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_delete>Скрыть</a></p>";
  ShowTooltip(element, content);
}

function AdminHitTooltip(element, object_id)
{
  from =  encodeURIComponent(window.location); 
  content = "<p><a href='admin/index.php?section=Product&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_edit>Редактировать</a></p>";
  content += "<p><a href='admin/index.php?section=Storefront&set_hit="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_hit>Не хит</a></p>";
  content += "<p><a href='admin/index.php?section=Storefront&set_enabled="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_delete>Скрыть</a></p>";
  ShowTooltip(element, content);
}

function AdminCategoryTooltip(element, object_id)
{
  from =  encodeURIComponent(window.location); 
  content = "<p><a href='admin/index.php?section=Category&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_edit>Редактировать</a></p>";
  content += "<p><a href='admin/index.php?section=Product&category="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_add>Добавить товар</a></p>";
  content += "<p><a href='admin/index.php?section=Categories&set_enabled="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_delete>Скрыть</a></p>";
  ShowTooltip(element, content);
}

function AdminBrandTooltip(element, object_id)
{
  from =  encodeURIComponent(window.location); 
  content = "<p><a href='admin/index.php?section=Brand&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_edit>Редактировать</a></p>";
  ShowTooltip(element, content);
}

function AdminNewsTooltip(element, object_id)
{
  from =  encodeURIComponent(window.location); 
  content = "<p><a href='admin/index.php?section=NewsItem&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_edit>Редактировать</a></p>";
  content += "<p><a href='admin/index.php?section=NewsItem&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_add>Добавить новость</a></p>";
  content += "<p><a href='admin/index.php?section=NewsLine&set_enabled="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_delete>Скрыть</a></p>";
  ShowTooltip(element, content);
}

function AdminArticleTooltip(element, object_id)
{
  from =  encodeURIComponent(window.location); 
  content = "<p><a href='admin/index.php?section=Article&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_edit>Редактировать</a></p>";
  content += "<p><a href='admin/index.php?section=Article&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_add>Добавить новость</a></p>";
  content += "<p><a href='admin/index.php?section=Articles&set_enabled="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_delete>Скрыть</a></p>";
  ShowTooltip(element, content);
}

function AdminSectionTooltip(element, object_id)
{
  from =  encodeURIComponent(window.location); 
  content = "<p><a href='admin/index.php?section=Section&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_edit>Редактировать</a></p>";
  content += "<p><a href='admin/index.php?section=Sections&action=move_up&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_up>Поднять</a></p>";
  content += "<p><a href='admin/index.php?section=Sections&action=move_down&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_down>Опустить</a></p>";
  content += "<p><a href='admin/index.php?section=Sections&set_enabled="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_delete>Скрыть</a></p>";
  ShowTooltip(element, content);
}

function AdminCurrencyTooltip(element)
{
  from =  encodeURIComponent(window.location); 
  content = "<p><a href='admin/index.php?section=Currency&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_edit>Изменить курсы валют</a></p>";
  ShowTooltip(element, content);
}

function AdminCommentTooltip(element, object_id)
{
  from =  encodeURIComponent(window.location); 
  content = "<p><a href='admin/index.php?section=Comments&act=delete&item_id="+object_id+"&from="+from+"&token=<?php print $token; ?>' class=admin_tooltip_delete>Удалить</a></p>";
  ShowTooltip(element, content);
}
