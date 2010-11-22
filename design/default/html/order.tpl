{*
  Template name: Заказ
  Вывод состояния заказа.
  Используется классом  Order.class.php

  Передаваемые параметры:
  $order - заказ
*}


<h1>Ваш заказ №{$order->order_id}
{if $PaymentMethods}
{if $order->payment_status == 1}оплачен{else}еще не оплачен{/if},
{/if}
{if $order->status == 0}ждет обработки{elseif $order->status == 1}в обработке{elseif $order->status == 2}выполнен{/if}
</h1>

  
<div class='order_products'>
<table class="order_products">
  {foreach from=$order->products item=product}
  {if $product->download != ''}{assign var=digital_products value=1}{/if}
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
<div class="total_line">
   {if $order->payment_status == 1}
   <span class=total_sum>Оплачено: {$order->total_amount*$currency->rate_from/$currency->rate_to|string_format:"%.2f"} {$currency->sign}</span>
   {else}
   <span class=total_sum>К оплате: {$order->total_amount*$currency->rate_from/$currency->rate_to|string_format:"%.2f"} {$currency->sign}</span>
   {/if}
</div>
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

{if $PaymentMethods && $order->payment_status != 1}
<br>
<H1>Оплата заказа</H1>
<div class="billet">
  <table>
    {foreach name=payment from=$PaymentMethods item=payment_method}
    <tr>
      <td class="delivery_text">
      <h3>{$payment_method->name}  {$payment_method->amount} {$payment_method->currency_sign}</h3>
        {$payment_method->description}
        {if $payment_method->payment_button}
         {$payment_method->payment_button}
        {/if}     
        <br>   
        <div class="line"><!-- /--></div>
      </td>
    </tr>
    {/foreach}
  </table>
</div>			
{/if}

{if $order->payment_status == 1 && $digital_products == 1}
<br>
<h1>Скачать файлы:</h1>
{foreach from=$order->products item=product}
<a href='http://{$root_url}/order/{$order->code}/{$product->download}'>{$product->product_name}</a><br><br>
{/foreach}
{/if}



 