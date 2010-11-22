<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$title|escape}</title>
    <base href="http://{$root_url}/">
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="description" content="{$description|escape}" />
	<meta name="keywords" content="{$keywords|escape}" />
	<style type="text/css">
	{literal}


	{* Общее *}
	body,form,table,td,input,img{margin:0px;padding:0px;}
	img{border:0}
	body{font-family:Arial,sans-serif;background-color:#fff}
	a{color:#0074c5}
	a:visited{color:#7a8e9c} 
	td{vertical-align:top}
	h1{font-size:xx-large;margin-top:0.5em;margin-bottom:0.5em;padding:0px;}
	h2{font-size:x-large;margin-top:0.5em;margin-bottom:0.5em;padding:0px;}
	h3{font-size:large;margin-top:0.5em;margin-bottom:0.5em;padding:0px;}
	{* END Общее *}
	
	{* Блок залогиненого пользователя *}
	div.user{color:#247f0c;background-color:#a8ff90; font-size:x-small; padding:0.3em;}
	div.user a{color:#165605; font-size:x-small;}
	{* END Блок залогиненого пользователя *}

	{* Блок корзины *}
	div.cart{color:#000000;background-color:#ffed76; font-size:x-small; padding:0.3em;}
	div.cart a{color:#000000; font-size:x-small;}
	{* END Блок корзины *}

	{* Хлебные крошки *}
	div.path{color:#818181;background-color:#e0e0e0; font-size:x-small; padding:0.3em;}
	div.path a{color:#606060; font-size:x-small;}
	{* END Хлебные крошки *}

	{* Бренды *}
	div.brands{color:#000000;}
	div.brands a{color:#ff5a00;}
	{* END Бренды *}

	{* Цена *}
	div.price{font-size:x-large;}
	div.price a{font-size:large;}
	{* END Цена *}
	
	{* Комментарии к товару *}
	span.comment_date{color:#707070; font-size:xx-small;}
	span.comment_name{color:#707070; font-size:medium;}
	span.comment_text{color:#000000;}	
	div.comment_form{background-color:#F0F0F0;}	
	div.error{color:red;}
	{* END Комментарии к товару *}
	
	{* Нижнее меню *}
	div.bottom_menu{color:#606060;background-color:#e0e0e0; font-size:x-small; padding:0.3em;}
	div.bottom_menu a{color:#606060; font-size:x-small;}
	{* END Нижнее меню *}
	
	{* Новости *}
	p.news_date{color:#606060; font-size:x-small; padding:0; margin:0;}
	{* END Новости *}
	
	{* Корзина *}
	a.delete{color:#ff0000; font-size:x-small;}
	{* END Корзина *}
	
	{* Заказ *}
	span.order_fieldname{color:#707070; font-size:x-small;}
	{* END Заказ *}
	
	
	{/literal}
	</style>
</head>
<body>	
	
{* Блок залогиненого пользователя *}
{if $user}
<div class=user>
   <a href="account/">{$user->name|escape}</a>
   {if $user->discount>0}(скидка&nbsp;{$user->discount*1}%){/if} 
   <a href="logout/">выйти</a>
</div>
{/if}
{* END Блок залогиненого пользователя *}


{* Блок корзины *}
{if $cart_products_num}
                {assign var=ptext value='товаров'}
                {* как правильно написать ТОВАР(-ОВ, -А) *}
                {assign var=p1 value=$cart_products_num%10}
                {assign var=p2 value=$cart_products_num%100}
                {if $p1==1 && !($p2>=11 && $p2<=19)}{assign var=ptext value='товар'}{/if}
                {if $p1>=2 && $p1<=4 && !($p2>=11 && $p2<=19)}{assign var=ptext value='товара'}{/if}

<div class=cart><a href="cart/">Корзина:</a> {$cart_products_num} {$ptext}
на {$cart_total_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}
</div>
{/if}
{* END Блок корзины *}
	
	
{$content}

{* Нижнее меню *}
<div class=bottom_menu>
{foreach name=sections from=$sections item=s}
<a href='sections/{$s->url}'>{$s->name|escape}</a>{if !$smarty.foreach.sections.last}&nbsp;| {/if}
{/foreach}                
</div>
{* END Нижнее меню *}


</body>
</html>