{* END Хлебные крошки *}
<div class=path>
    <a href="catalog/">Каталог</a>&nbsp;/
    Заказ №{$order->order_id}
</div>
{* END Хлебные крошки *}

<h1>Ваш заказ №{$order->order_id}
{if $PaymentMethods}
{if $order->payment_status == 1}оплачен{else}еще не оплачен{/if},
{/if}
{if $order->status == 0}ждет обработки{elseif $order->status == 1}в обработке{elseif $order->status == 2}выполнен{/if}
</h1>

{foreach from=$order->products item=product}
{if $product->download != ''}{assign var=digital_products value=1}{/if}
<p>
<a href="products/{$product->url}">{$product->product_name}</a><br>
{$product->quantity} шт. по {$product->price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign}
</p>
{/foreach}


{if $order->delivery_method}
<p>
{$order->delivery_method}<br>
      {if $order->delivery_price>0}
      {$order->delivery_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"} {$currency->sign}
      {else}
      бесплатно
      {/if}
</p>
{/if}

{if $order->payment_status == 1}
<strong>Оплачено: {$order->total_amount*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign}</strong>
{else}
<strong>К оплате: {$order->total_amount*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign}</strong>
{/if}

<h1>Информация о заказе↓</h1>

<span class=order_fieldname>Дата:</span><br>
{$order->date|escape}<br>
{if $order->name}
<span class=order_fieldname>Имя:</span><br>
{$order->name|escape}<br>
{/if}
{if $order->email}
<span class=order_fieldname>Email:</span><br>
{$order->email|escape}<br>
{/if}
{if $order->phone}
<span class=order_fieldname>Телефон:</span><br>
{$order->phone|escape}<br>
{/if}
{if $order->address}
<span class=order_fieldname>Адрес доставки:</span><br>
{$order->address|escape}<br>
{/if}
{if $order->comment}
<span class=order_fieldname>Комментарий:</span><br>
{$order->comment|escape|nl2br}<br>
{/if}


{if $PaymentMethods && $order->payment_status != 1}
<H1>Оплата заказа</H1>
{foreach name=payment from=$PaymentMethods item=payment_method}
<h2>{$payment_method->name} ({$payment_method->amount}&nbsp;{$payment_method->currency_sign})</h2>
<p>
{$payment_method->description}
{if $payment_method->payment_button}
{$payment_method->payment_button}
{/if}     
</p>
{/foreach}			
{/if}

{if $order->payment_status == 1 && $digital_products == 1}
<br>
<h1>Скачать файлы:</h1>
{foreach from=$order->products item=product}
<p><a href='http://{$root_url}/order/{$order->code}/{$product->download}'>{$product->product_name}</a></p>
{/foreach}
{/if}



 