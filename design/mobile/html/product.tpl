{* END Хлебные крошки *}
<div class=path>
    <a href="catalog/">Каталог</a>
    {foreach from=$category->path item=cat}
    / <a href="catalog/{$cat->url}">{$cat->name|escape}</a>
    {/foreach}
    {if $product->brand}
    / <a href="catalog/{$cat->url}/{$product->brand_url}">{$product->brand|escape}</a>
    {/if}
</div>
{* END Хлебные крошки *}


{* Заголовок *}
<h1>{$product->category|escape} {$product->brand|escape} {$product->model|escape}</h1>
{* END Заголовок *}

{* Картинки товара *}
{if $product->small_image}<a href="files/products/{$product->large_image}"><img border=0 src="files/products/{$product->small_image}" alt="{$product->model}"/></a>
<br>
{/if}

{if $product->fotos}
<p>
	Еще фото:
	{foreach name=fotos from=$product->fotos item=foto}                      
	<a href="files/products/{$foto->filename}">{$smarty.foreach.fotos.iteration}</a>{if !$smarty.foreach.fotos.last} {/if}
	{/foreach}
</p>
{/if}
{* END Картинки товара *}


<form class=price action='cart' method=get>
{foreach name=variants item=variant from=$product->variants}
<input type=radio name=variant_id value='{$variant->variant_id}' {if $smarty.foreach.variants.first}checked{/if}>{$variant->name} {$variant->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}
<br>
{/foreach}
 
{if $product->variants|@count>0}
<input type=submit href="cart/add/{$product->product_id}" value='в корзину'>
{/if}
</form>


<p class=product_description>
{$product->body}
<p>

<table>
{foreach from=$product->properties item=property}
{if $property->in_product}
<tr><td>{$property->name|escape}:</td><td>{$property->value|escape}</td></tr>
{/if}
{/foreach}
</table>

<a name=comments></a>

<h2>Отзывы об этом товаре</h2>
{if $comments}
{foreach from=$comments item=c}
<p>
<span class=comment_date>{$c->date|escape}</span><br>
<span class=comment_name>{$c->name|escape}</span><br>
<span class=comment_text>{$c->comment|escape|nl2br}</span>
</p>
{/foreach}
{else}
	Пока нет ни одного отзыва
{/if}

<h2>Оставить свой отзыв</h2>

{* Форма отзыва *} 
<div class=comment_form>
<form class=comment action='{$smarty.server.REQUEST_URI}#comments' method=post>

{if $error}
<div class=error>{$error}</div>
{/if}

Ваше имя<br>                    
<input type="text" name=name value="{$name|escape}"/>
<br>
Отзыв<br>
<input type="text" name=comment value="{$comment|escape}"/>
<br>

{* Капча *} 
{if $gd_loaded}
<p>
<img src="captcha/image.php?t={math equation='rand(10,10000)'}" alt=""/><br>
Число: 
<input size=5 type="text" name=captcha_code /><br>
{/if}
{* END Капча *} 

<input type="submit" value="Отправить"/>
</form>
</div>
{* END Форма отзыва *} 


{* Рекомендуемые товары *}

{if $product->related_products}
<h2>Так же советуем посмотреть:</h2>
<ul>
{foreach name=products item=product from=$product->related_products}    
<li><a href="products/{$product->url}">{$product->category|escape} {$product->brand|escape} {$product->model|escape}</a>
{if $product->old_price>0}
<s>{$product->old_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}</s>
{/if}
{if $product->variants[0]->discount_price}
{$product->variants[0]->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}
{/if}
</li>           
{/foreach}
</ul>
{/if}

{* END Рекомендуемые товары *}