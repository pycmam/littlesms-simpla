<h1 style='font-weight:normal;font-family:arial;'><a href='http://{$root_url}/admin/index.php?section=Order&order_id={$order->order_id}'>Заказ №{$order->order_id}</a> на сумму {$order->total_amount} {$main_currency->sign}</h1>
<table cellpadding=6 cellspacing=0 style='border-collapse: collapse;'>
  {if $order->name}
  <tr>
    <td style='padding:6px; width:170; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      Имя, фамилия
    </td>
    <td style='padding:6px; width:330; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {if $user}
      <a href='http://{$root_url}/admin/index.php?section=Orders&view=search&keyword=user:{$order->user_id}'>{$order->name|escape}</a>
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
  <tr>
    <td style='padding:6px; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      Скидка
    </td>
    <td style='padding:6px; width:170; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {if $user}{if $user->discount>0}{$user->discount}&nbsp;%{else}нет{/if}{else}пользователь не зарегистрирован{/if}
    </td>
  </tr>
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
</table>
<br>
<h1 style='font-weight:normal;font-family:arial;'>Товары:</h1>
<table cellpadding=6 cellspacing=0 style='border-collapse: collapse;'>
  {foreach name=products from=$order->products item=product}
  <tr>
    <td style='padding:6px; width:250; padding:6px; background-color:#f0f0f0; border:1px solid #e0e0e0;font-family:arial;'>
      <a href="http://{$root_url}/products/{$product->url}">{$product->product_name}</a> {$product->variant_name}
    </td>
    <td align=right style='padding:6px; text-align:right; width:150; background-color:#ffffff; border:1px solid #e0e0e0;font-family:arial;'>
      {$product->quantity} &times; {$product->price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign}
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
      {$order->delivery_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"} {$currency->sign}
      {else}
      бесплатно
      {/if}
    </td>
  </tr>
  {/if}
</table>
<br>
Цены указаны на момент заказа с учетом скидки пользователя
<br><br>
Приятной работы с <a href='http://simp.la'>Simpla</a>!