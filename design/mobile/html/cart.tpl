{* END Хлебные крошки *}
<div class=path>
    <a href="catalog/">Каталог</a>&nbsp;/
    Корзина
</div>
{* END Хлебные крошки *}

<h1>Корзина</h1>

{if $variants}
<!-- Корзина /-->
<form method=post name=cart>
{foreach from=$variants item=variant}
<p>
<a href="products/{$product->url}">{$variant->category|escape} {$variant->brand|escape} {$variant->model|escape}</a><br>
<select name=amounts[{$variant->variant_id}]>
{section name=amounts start=1 loop=$variant->stock+1 step=1 max=100}
<option value="{$smarty.section.amounts.index}" {if $variant->amount==$smarty.section.amounts.index}selected{/if}>{$smarty.section.amounts.index}</option>
{/section}
</select> шт. по {$variant->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign}
<br>
<a class=delete href='cart/delete/{$product->product_id}'>удалить</a>
</p>
{/foreach}

<h1>Оформить заказ ↓</h1>
    
{if $delivery_methods}
{* Способы доставки *}
{foreach name=delivery from=$delivery_methods item=delivery_method}
<p>
<input type=radio id=delivery_radio_{$delivery_method->delivery_method_id} name=delivery_method_id value='{$delivery_method->delivery_method_id}' {if $delivery_method->delivery_method_id == $delivery_method_id}checked{/if}">
{$delivery_method->name} ({if $delivery_method->final_price>0}{$delivery_method->final_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign}{else}бесплатно{/if})
</p>
{/foreach}
            
{/if}
    
<h1>Адрес получателя</h1>

{if $error}
<div class="error">{$error}</div>
{/if}
                    
Имя, фамилия<br>
<input name="name" type="text" value="{$name|escape}" /><br>
Email<br>
<input name="email" type="text" value="{$email|escape}" /><br>
Телефон<br>
<input name="phone" type="text" value="{$phone|escape}" /><br>
Адрес доставки<br>
<input name="address" type="text" class="address" value="{$address|escape}"/><br>
Комментарий к&nbsp;заказу<br>
<input type="text" name="comment"  value="{$comment|escape}"/><br>
        
{if $gd_loaded}                             
 <img src="captcha/image.php?t={math equation='rand(10,10000)'}" alt=""/><br>
Число:<input type="text" name="captcha_code" size=5/>
{/if}
<input type="hidden" name="submit_order" value="1">
<input type="submit" value="Заказать" id="order_button"/>

</form>     
<!-- Корзина #End /-->
{else}
  <p>Корзина пуста</p>
{/if}
