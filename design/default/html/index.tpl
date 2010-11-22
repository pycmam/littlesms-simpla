{* 
  template name: Общий вид страницы

  Этот шаблон отвечает за общий вид страниц.
  Используется классом Site.class.php
  Передаваемые в шаблон параметры смотрите в конце файла  
  
*}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <title>{$title|escape}</title>
    <base href="http://{$root_url}/">
    <meta name="description" content="{$description|escape}" />
    <meta name="keywords" content="{$keywords|escape}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
    <meta http-equiv="Content-Language" content="ru" />
    <meta name="robots" content="all" />
    <link rel="stylesheet" type="text/css" href="design/{$settings->theme}/css/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="design/{$settings->theme}/css/forms.css" media="screen" />
    
    <link rel="icon" href="design/{$settings->theme}/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="design/{$settings->theme|escape}/images/favicon.ico" type="image/x-icon">
        
    {* Всплывающие подсказки для администратора *}
    {if $smarty.session.admin == 'admin'}
    <script src="js/admintooltip/php/admintooltip.php" language="JavaScript" type="text/javascript"></script>    
    <link href="js/admintooltip/css/admintooltip.css" rel="stylesheet" type="text/css" /> 
    {/if}
       
</head>
<body>
<div id="wrap_top_bg"><div id="wrap_bottom_bg">

<!-- Вся страница /-->
<div id="wrap">

    <!-- Шапка /-->
    <div id="header"> 
            
        <!-- Логотип /-->   
        <div id="logo"> 
             <a href="./" title="Simpla" class="image"></a><a href="./" title="Simpla" class="link"></a>          
        </div>
        <!-- Логотип #End /-->
                
        <!-- Основная часть заголовка /-->
        <div id="header_menu">
        
            <!-- Верхняя панель заголовка /-->
            <div id="top_panel">
            
                <!-- Вход пользователя /-->  
                <div id="top_panel_left">
                
                <!-- Если пользователь не залогинен /-->  
                {if !$user}
                   <a href="login/" id="user_login_link" class="black_link" >вход</a>
                   | <a href="registration/" class="black_link">регистрация</a>                
                <!-- Если пользователь не залогинен /-->  
                {else}
                   <a href="account/" class="black_link">{$user->name|escape}</a>{if $user->discount>0},
                   ваша скидка {$user->discount}%{/if} 
                   <a href="logout/" class="black_link" id="user_exit_link">выйти</a>
                {/if}
                
                </div>
                <!-- Вход пользователя #End /-->  
                
                <!-- Выбор валюты /--> 
                <div id="top_panel_right">
                
                    <form name=currency method=post>
                        <p>валюта магазина:
                        <select  tooltip=currency name="currency_id" onchange="window.document.currency.submit();">
                            {foreach from=$currencies item=c}                            
                            <option value="{$c->currency_id}" {if $c->currency_id==$currency->currency_id}selected{/if}>
                                 {$c->name|escape}
                            </option>
                            {/foreach}
                        </select>
                        </p>
                    </form>
                    
                </div>
                <!-- Выбор валюты #End /-->                 
                
            </div>
            <!-- Верхняя панель заголовка #End /-->
            
            <!-- Верхнее меню /-->
            <ul id="top_header_menu">
                {foreach name=sections from=$sections item=s}
                <li>
                  {if $section->section_id == $s->section_id}                  
                  <span tooltip='section' section_id='{$s->section_id}'>{$s->name|escape}</span>
                  {else}
                  <a tooltip='section' section_id='{$s->section_id}' href='sections/{$s->url}'>{$s->name|escape}</a>
                  {/if}
                </li>
                {/foreach}                
            </ul>
            <!-- Верхнее меню #end /-->     
                
            <!-- Информер корзины /-->  
            {if $cart_products_num}
                {assign var=ptext value='товаров'}
                {* как правильно написать ТОВАР(-ОВ, -А) *}
                {assign var=p1 value=$cart_products_num%10}
                {assign var=p2 value=$cart_products_num%100}
                {if $p1==1 && !($p2>=11 && $p2<=19)}{assign var=ptext value='товар'}{/if}
                {if $p1>=2 && $p1<=4 && !($p2>=11 && $p2<=19)}{assign var=ptext value='товара'}{/if}
                <p id="cart_info">В <a href="cart/" class="black_link" onclick="document.cookie='from='+location.href+';path=/';">корзинe</a> {$cart_products_num} {$ptext}
                на сумму {$cart_total_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}</p>
            {else}
                <p id="cart_info">Корзина пуста</p>
            {/if}
            <!-- Информер корзины #End /-->         
            
            
        </div>  
    </div>
    <!-- Шапка #End /-->
    
    
    <!-- Основная часть страницы /-->
    <div id="main_part">
    
        <!-- Левая часть страницы /-->
        <div id="left_side">
        
            <!-- Меню каталога /-->
            <div id="catalog_menu">
			{defun name=categories_tree categories=$categories}
			{if $categories}
			<ul class="catalog_menu">
			{foreach item=c from=$categories}
				{if $category->category_id != $c->category_id}
				<li><a href='catalog/{$c->url}' tooltip='category' category_id='{$c->category_id}'>{$c->name}</a></li>
				{else}
				<li><span tooltip='category' category_id='{$c->category_id}'>{$c->name}</span></li>
				{/if}
				{*if in_array($category->category_id, $c->subcats_ids)*}
				{fun name=categories_tree categories=$c->subcategories}        
				{*/if*}
			{/foreach}  
			</ul>
			{/if}    
			{/defun}
            </div>
            <!-- Меню каталога #End /-->

            {if $all_brands}
            <!-- Список брендов /-->
            <div id="brands_menu">
				{foreach name=brands from=$all_brands item=b}
                 <a href='brands/{$b->url}'>{$b->name|escape}</a>
                {/foreach}  
            </div>
            <!-- Список брендов #End /-->
            {/if}
            
            <!-- Поиск /-->
            <div id="search">
                <form name=search method=get action="index.php"  onsubmit="window.location='http://{$root_url}/search/'+encodeURIComponent(encodeURIComponent(this.keyword.value)); return false;">
                    <input type=hidden name=module value=Search>
                    <p><input type="text" name=keyword value="{$keyword|escape}" class="search_input_text"/><input type="submit" value="Найти" class="search_input_submit"/></p>
                </form>
            </div>
            <!-- Поиск #End /-->

                                    
            {if $news}
            <!-- Новости /-->
            <ul id="news">
            {foreach  name=news from=$news item=n}
                <li>
                    <p class="news_date">{$n->date}</p>
                    <p tooltip="news" news_id="{$n->news_id}"><a href="news/{$n->url}">{$n->header|escape}</a></p>
                    <p class="news_annotation">
                        {$n->annotation}
                    </p>
                </li>
            {/foreach}         
                <li><a href="news/">архив новостей →</a></li>
            </ul>
            <!-- Новости #End /-->
            {/if}
            
        </div>
        <!-- Левая часть страницы #End /-->
        
        
        <!-- Правая часть страницы #Begin /-->
        <div id="right_side">
                    
            {$content}
            
        </div>
        <!-- Правая часть страницы #End /-->
    </div>
    <!-- Основная часть страницы #End /-->
    
    <!-- Подвал #Begin /-->
    <div id="footer">
        <ul id="syst">
            <li><img src="design/{$settings->theme}/images/visa.jpg" alt=""/></li>
            <li><img src="design/{$settings->theme}/images/master_card.jpg" alt=""/></li>
            <li><img src="design/{$settings->theme}/images/web_money.jpg" alt=""/></li>
        </ul>
        {$settings->counters}
        <p id="copyright">© Интернет-магазин 2005-2010</p>
    </div>
    <!-- Подвал #End /-->
    
</div>
<!-- Вся страница #End /-->

</div></div>
</body>
</html>
{*

  Передаваемые в шаблон параметры:
  
  $title - заголовок страницы
  $description - описание страницы
  $keywords - ключевые слова
  
  $sections - разделы меню
  $categories - категории товаров
  $content - основная часть страницы
  
  Параметры, передаваемые для всех страниц, и этой в том чисте:
  
  $root_url - корневой url сайта (без http://)
  $settings - настройки сайта, хранящиеся в базе
  $config - настройки сайта, хранящиеся в файле Config.class.php
  $currencies - валюты
  $currency - текущая валюта
  $main_currency - основная валюта
  $user - пользователь, если залогинен  
  
*}