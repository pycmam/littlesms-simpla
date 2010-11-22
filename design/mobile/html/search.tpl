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

{* Форма поиска *}
<form action="index.php">
<input type=hidden name=module value=Search>
<input type="text" name=keyword value="{$keyword|escape}"/><input type="submit" value="Найти"/>	
</form>
{* END Форма поиска *}

<h1>Поиск &laquo;{$keyword|escape}&raquo;</h1>


{if $products}

{* Список товаров *}
{foreach name=products item=product from=$products}    
<div class=product>
<strong><a href="products/{$product->url}">{$product->category|escape} {$product->brand|escape} {$product->model|escape}</a>
{if $product->old_price>0}
<s>{$product->old_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}</s>
{/if}
{if $product->price>0}
{$product->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}
{/if}
<br>
{if $product->small_image}<a href="products/{$product->url}"><img border=0 src="files/products/{$product->small_image}" alt="{$product->model}"/>{/if}</a></strong>
<p>
{$product->description}
</p>
</div>
{/foreach}
{* END Список товаров *}


{else}
<p>По запросу &laquo;{$keyword|escape}&raquo; ничего не найдено</p><br>
{/if}
