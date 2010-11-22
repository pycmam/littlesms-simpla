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
    <link rel="stylesheet" type="text/css" href="design/{$settings->theme}/css/reset.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="design/{$settings->theme}/css/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="design/{$settings->theme}/css/forms.css" media="screen" />
    
    <link rel="icon" href="design/{$settings->theme}design/{$settings->theme}/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="design/{$settings->theme|escape}design/{$settings->theme}/images/favicon.ico" type="image/x-icon">
        
    {* Всплывающие подсказки для администратора *}
    {if $smarty.session.admin == 'admin'}
    <script src="js/admintooltip/php/admintooltip.php" language="JavaScript" type="text/javascript"></script>    
    <link href="js/admintooltip/css/admintooltip.css" rel="stylesheet" type="text/css" /> 
    {/if}
       
</head>
<body>

<!-- PAGE #Begin /-->
<div id="wrap">

  <!-- Header #Begin /-->
  <div id="header">
    <h1 id="logo"><a href="./" title="Logo"><img src="design/{$settings->theme}/images/logo.jpg" alt=""/></a></h1>
    <div id="phone">095 123-456-78</div>
    <div id="reg">
    
                <!-- Если пользователь не залогинен /-->  
                {if !$user}
                   <a href="login/" id="user_login_link" class="black_link" >вход</a>
                   | <a href="registration/" class="black_link">регистрация</a>                
                <!-- Если пользователь не залогинен /-->  
                {else}
                   <a href="account/" >{$user->name|escape}</a>{if $user->discount>0},
                   ваша скидка {$user->discount}%{/if} 
                   <a href="logout/" id="user_exit_link">выйти</a>
                {/if}
    
    </div>
  </div>
  <!-- Header #End /-->
  <div id="menu">
    <ul>
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
    <form id=form name=search method=get action="index.php" onsubmit="window.location='http://{$root_url}/search/'+this.keyword.value; return false;">
        <input type=hidden name=module value=Search>
      <p><input type="text" class="input" name=keyword value="{$keyword|escape}"/> <input type="submit" value="найти" class="submit"/></p>  
    </form>
  </div>
  
  <!-- Content #Begin /-->
  <div id="content">
    <div id="left-side">
      <div id="bask"><div id="bask_bottom"><div id="bask_top">
        <form action="">
          <h2 class="h2">Корзина</h2>
          
            <!-- Информер корзины /-->  
            {if $cart_products_num}
                {assign var=ptext value='товаров'}
                {* как правильно написать ТОВАР(-ОВ, -А) *}
                {assign var=p1 value=$cart_products_num%10}
                {assign var=p2 value=$cart_products_num%100}
                {if $p1==1 && !($p2>=11 && $p2<=19)}{assign var=ptext value='товар'}{/if}
                {if $p1>=2 && $p1<=4 && !($p2>=11 && $p2<=19)}{assign var=ptext value='товара'}{/if}
                <p>В <a href="cart/" class="black_link" onclick="document.cookie='from='+location.href+';path=/';">корзинe</a> {$cart_products_num} {$ptext}</p>
                <p>на сумму {$cart_total_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}</p>
                <p class="border"><a href="cart/" onclick="document.cookie='from='+location.href+';path=/';" class="execute">Оформить заказ</a></p>
            {else}
                <p class="border">Корзина пуста</p>
                
            {/if}
            </form>
            <!-- Информер корзины #End /-->         
          

          
          <p class="curren">валюта:</p>
          <p class="select">
          <form name=currency method=post>
                        <select  tooltip=currency name="currency_id" onchange="window.document.currency.submit();">
                            {foreach from=$currencies item=c}                            
                            <option value="{$c->currency_id}" {if $c->currency_id==$currency->currency_id}selected{/if}>
                                 {$c->name|escape}
                            </option>
                            {/foreach}
                        </select>
                    </form>
          </p>
        
      </div> </div> </div>


            <!-- Меню каталога /-->
            <div class="block"><div class="block_top"><div class="block_bottom">
			{defun name=categories_tree categories=$categories}
			{if $categories}
			<ul class="catalog_menu">
			{foreach item=c from=$categories}
				{if $category->category_id != $c->category_id}
				<li><a href='catalog/{$c->url}' tooltip='category' category_id='{$c->category_id}'>{$c->name}</a></li>
				{else}
				<li><span tooltip='category' category_id='{$c->category_id}'>{$c->name}</span></li>
				{/if}
				{fun name=categories_tree categories=$c->subcategories}        
			{/foreach}  
			</ul>
			{/if}    
			{/defun}
            </div></div></div>
            <!-- Меню каталога #End /-->
            

            
            {if $all_brands}
            <!--  Список брендов /-->
            <div class="block"><div class="block_top"><div class="block_bottom">
            <div id="brands_menu">
            	{* Расчет размеров брендов и вывод их *}
                {assign var=min_size value=10}
                {assign var=max_size value=25}
                {assign var=max_count value=0}
                {assign var=min_count value=$all_brands.0->products_num}
                {foreach name=brands from=$all_brands item=b}
                	{if $b->products_num >= $max_count}{assign var=max_count value=$b->products_num}{/if}
                	{if $b->products_num <= $min_count}{assign var=min_count value=$b->products_num}{/if}
                {/foreach}

                {foreach name=brands from=$all_brands item=b}
                {if $max_count>$min_count}
                {math assign=coef equation="(count-min_count)/(max_count-min_count)" max_count=$max_count min_count=$min_count count=$b->products_num}
                {else}
                {assign var=coef value=0.5}
                {/if}
                {math assign=size equation="min_size+(max_size-min_size)*coef" max_size=$max_size min_size=$min_size coef=$coef}
                 <a style='font-size:{$size}px;' href='brands/{$b->url}'>{$b->name|escape}</a>
                {/foreach}  
            	{* END Расчет размеров брендов и вывод их *}
 
            </div>
            </div></div></div>
            <!-- Боковое меню #End /-->
            {/if}
                              
            
            {if $news}
            <!-- Новости /-->
            <div class="news">
            {foreach  name=news from=$news item=n}
                <div class="news_block">
          <p class="date">{$n->date}</p>
          <p tooltip="news" news_id="{$n->news_id}"><a href="news/{$n->url}">{$n->header|escape}</a></p>
          <p>{$n->annotation}</p>
                </div>
            {/foreach}         
            <p><a href="news/">архив новостей →</a></p>
            </div>
            <!-- Новости #End /-->
            {/if}


      

    </div>
    
    <div id="right-side">
    
    {$content}
    
    </div>
  </div>
  <!-- Content #End /-->
</div>
<div id="end_bg">
  <p id="partners">
  {$settings->counters}
  </p>
  <p id="copy">© <a href="http://simp.la">Скрипт интернет-магазина Simpla</a> 2010</p>
</div>
<!-- PAGE #End /-->

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