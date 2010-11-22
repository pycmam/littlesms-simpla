{*
  Template name: Панель пользователя
  Панель пользователя с его настройками и историей заказов
  Используется классом Account.class.php
*}


{* Подключаем js-проверку формы *}
<script src="js/baloon/js/default.js"
        language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/validate.js"
        language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/baloon.js"
        language="JavaScript" type="text/javascript"></script>
<link href="js/baloon/css/baloon.css"
      rel="stylesheet" type="text/css" /> 

<h1>{$user->name}</h1>

{if $error}
<div id="error_block"><p>{$error}</p></div>
{/if}

<div class="billet">
<form method=post>
  <table class="login_table">
    <tr>
      <td>Имя, фамилия</td>
      <td><input format='.+' notice='Введите имя' value='{$name|escape}' name=name maxlength='25' type="text"/></td>
    </tr>
    <tr>
      <td>Email</td>
      <td><input format='email' notice='Введите email' value='{$email|escape}' name=email maxlength='100' type="text"/> (используется как логин)</td>
    </tr>
    <tr>
      <td>Пароль</td>
      <td><a href='#' onclick="this.style.display='none';document.getElementById('pass').style.display='inline';return false;">изменить</a><span id='pass' style='display:none;'><input value='{$password|escape}' name='password' maxlength='25' type="password"/></span></td>
    </tr>
    <tr>
      <td></td>
      <td>
      <input type=submit value='Готово'>
	</tr>
  </table>
</form>
</div>
{if $orders}
<h1>Ваши заказы ↓</h1>
{foreach name=orders item=order from=$orders}
<br>
<div class='order_products'>
<h2><a href='order/{$order->code}'>Заказ №{$order->order_id}</a>
{if $order->payment_status == 1}оплачен,{/if} 
{if $order->status == 0}ждет обработки{elseif $order->status == 1}в обработке{elseif $order->status == 2}выполнен{/if}
</h2>
  
<table class="order_products">
  {foreach from=$order->products item=product}
  <tr>
    <td class="td_1">
      <a href="products/{$product->url}">{$product->product_name} {$product->variant_name}</a>
    </td>
    <td class="td_2">
      {$product->quantity} &times; {$product->price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign}
    </td>
  </tr>
  {/foreach}
  {if $order->delivery_method}
  <tr>
    <td class="td_1">
      {$order->delivery_method}
    </td>
    <td class="td_2">
      {if $order->delivery_price>0}
      {$order->delivery_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"} {$currency->sign}
      {else}
      бесплатно
      {/if}
    </td>
  </tr>
  {/if}
</table>
<div class="line"><!-- /--></div>

<!-- Итого /-->
</div>

<table class="order_info">
  <tr>
    <td>
       Дата:
    </td>
    <td>
      {$order->date|escape}
    </td>
  </tr>
  {if $order->name}
  <tr>
    <td>
       Имя:
    </td>
    <td>
      {$order->name|escape}
    </td>
  </tr>
  {/if}
  {if $order->email}
  <tr>
    <td>
      Email:
    </td>
    <td>
      {$order->email|escape}
    </td>
  </tr>
  {/if}
  {if $order->phone}
  <tr>
    <td>
      Телефон:
    </td>
    <td>
      {$order->phone|escape}
    </td>
  </tr>
  {/if}
  {if $order->address}
  <tr>
    <td>
      Адрес доставки:
    </td>
    <td>
      {$order->address|escape}
    </td>
  </tr>
  {/if}
  {if $order->comment}
  <tr>
    <td colspan=2>
      Комментарий:
    </td>
  </tr>
  <tr>
    <td colspan=2>
      {$order->comment|escape|nl2br}
    </td>
  </tr>
  {/if}
</table>

<div class="clear"><!-- /--></div>
<br><br>
{/foreach}
{/if}