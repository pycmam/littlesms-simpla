{* Хлебные крошки *}
<div class=path>
    <a href="./">Главная</a>&nbsp;/
    {$user->name|escape}
</div>
{* END Хлебные крошки *}

<h1>{$user->name|escape}</h1>

{if $error}
<div class="block">{$error}</div>
{/if}

<form method=post>
Имя, фамилия<br>
<input value='{$name|escape}' name=name type="text"/><br>
Email (логин)<br>
<input value='{$email|escape}' name=email type="text"/><br>
Новый пароль<br>
<input value='{$password|escape}' name='password' maxlength='25' type="password"/><br>
<input type=submit value='Готово'>
</form>


{if $orders}
<h1>Ваши заказы ↓</h1>
{foreach name=orders item=order from=$orders}
<p>
<a href='order/{$order->code}'>Заказ №{$order->order_id}</a> на {$order->total_amount*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign}
{if $order->payment_status == 1}оплачен,{/if} 
{if $order->status == 0}ждет обработки{elseif $order->status == 1}в обработке{elseif $order->status == 2}выполнен{/if}
</p>
{/foreach}
{/if}