{*
  Template name: Письмо о заказе

  Шаблон письма пользователю о состоянии заказа 
*}
<h1 style='font-weight:normal;font-family:arial;'><a href='http://{$root_url}/order/{$order->code}'>Заказ №{$order->order_id}</a> на сумму {$order->total_amount} {$main_currency->sign}</h1>
<table cellpadding=6 cellspacing=0 style='border-collapse: collapse;'>
  <tr>
    <td style='padding:6px; width:170; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      Статус
    </td>
    <td style='padding:6px; width:330; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {if $order->status == 0}
        ждет обработки      
      {elseif $order->status == 1}
        в обработке
      {elseif $order->status == 2}
        выполнен
      {/if}
    </td>
  </tr>
  <tr>
    <td style='padding:6px; width:170; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      Оплата
    </td>
    <td style='padding:6px; width:330; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {if $order->payment_status == 1}
        <font color='green'>оплачен</font>
      {else}
        не оплачен
      {/if}
    </td>
  </tr>
  {if $order->name}
  <tr>
    <td style='padding:6px; width:170; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      Имя, фамилия
    </td>
    <td style='padding:6px; width:330; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {if $user}
      <a href='http://{$root_url}/admin/index.php?section=Orders&user_id={$user->user_id}'>{$order->name|escape}</a>
      {else}
      {$order->name|escape}
      {/if}
    </td>
  </tr>
  {/if}
  {if $order->email}
  <tr>
    <td style='padding:6px; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      Email
    </td>
    <td style='padding:6px; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {$order->email|escape}
    </td>
  </tr>
  {/if}
  {if $order->phone}
  <tr>
    <td style='padding:6px; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      Телефон
    </td>
    <td style='padding:6px; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {$order->phone|escape}
    </td>
  </tr>
  {/if}
  {if $order->address}
  <tr>
    <td style='padding:6px; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      Адрес доставки
    </td>
    <td style='padding:6px; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {$order->address|escape}
    </td>
  </tr>
  {/if}
  {if $order->comment}
  <tr>
    <td style='padding:6px; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      Комментарий
    </td>
    <td style='padding:6px; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {$order->comment|escape|nl2br}
    </td>
  </tr>
  {/if}
  <tr>
    <td style='padding:6px; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      Время
    </td>
    <td style='padding:6px; width:170; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {$order->date}
    </td>
  </tr>
</table>
<br>
<h1 style='font-weight:normal;font-family:arial;'>Вы заказали:</h1>
<table cellpadding=6 cellspacing=0 style='border-collapse: collapse;'>
  {foreach name=products from=$order->products item=product}
  {if $product->download != ''}{assign var=digital_products value=1}{/if}
  <tr>
    <td style='padding:6px; width:250; padding:6px; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      <a href="http://{$root_url}/products/{$product->url}">{$product->product_name}</a> {$product->variant_name}
    </td>
    <td align=right style='padding:6px; text-align:right; width:150; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {$product->quantity} &times; {$product->price*$main_currency->rate_from/$main_currency->rate_to|string_format:"%.2f"}&nbsp;{$main_currency->sign}
    </td>
  </tr>
  {/foreach}
  {if $order->delivery_method}
  <tr>
    <td  style='padding:6px; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      {$order->delivery_method}
    </td>
    <td align=right style='padding:6px; text-align:right; width:170; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {if $order->delivery_price>0}
      {$order->delivery_price*$main_currency->rate_from/$main_currency->rate_to|string_format:"%.2f"} {$main_currency->sign}
      {else}
      бесплатно
      {/if}
    </td>
  </tr>
  {/if}
</table>

{if $order->payment_status == 1 && $digital_products == 1}
<br>
<h1 style='font-weight:normal;font-family:arial;'>Скачать файлы:</h1>
<table cellpadding=6 cellspacing=0 style='border-collapse: collapse;'>
  {foreach from=$order->products item=product}
  <tr>
    <td style='padding:6px; width:250; padding:6px; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
    <a href='http://{$root_url}/order/{$order->code}/{$product->download}'>{$product->product_name}</a>
  </td>
    <td align=right style='padding:6px; text-align:right; width:150; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
        <a href='http://{$root_url}/order/{$order->code}/{$product->download}'>скачать</a>
    </td>
  </tr>
  {/foreach}
</table>
{/if}


<br>
Вы всегда можете проверить состояние заказа по ссылке:<br>
<a href='http://{$root_url}/order/{$order->code}'>http://{$root_url}/order/{$order->code}</a>
<br><br>
<a href='http://{$root_url}'>{$settings->site_name}</a>