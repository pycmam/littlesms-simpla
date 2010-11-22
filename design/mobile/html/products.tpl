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


{* Фильтр по брендам *}
{if $brands}
<div class=brands>
  {foreach name=brands item=b from=$brands}
    {if $b->brand_id == $brand->brand_id}{$b->name|escape}{else}<a href='catalog/{$category->url}/{$b->url}'>{$b->name|escape}</a>{/if}{if not $smarty.foreach.brands.last}, {/if}
  {/foreach}
</div>
{/if}
{* END Фильтр по брендам *}

{if $category}     
<h1>{$category->name|escape} {$brand->name|escape}</h1>
{elseif $brand}
<h1>{$brand->name|escape}</h1>
{/if}
   

{* Описание категории *}
{if $category->description}
<p>
{$category->description}
</p>
{elseif $brand->description}
<p>
{$brand->description}
</p>
{/if}
{* END Описание категории *}

{* Список товаров *}
{foreach name=products item=product from=$products}    
<div class=product>
<strong><a href="products/{$product->url}">{$product->category|escape} {$product->brand|escape} {$product->model|escape}</a>
{if $product->variants[0]->discount_price}
{$product->variants[0]->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}
{/if}
<br>
{if $product->small_image}<a href="products/{$product->url}"><img border=0 src="files/products/{$product->small_image}" alt="{$product->model}"/>{/if}</a></strong>
<p>
{$product->description}
</p>
</div>
{/foreach}
{* END Список товаров *}


{* Постраничная навигация *}
{if $total_pages>1}
<p>
  Стр. {$page+1} из {$total_pages} 
  {if $page>0}
  <a id="PrevLink" href="{if $category}catalog/{$category->url}/{elseif $brand}brands/{/if}{$category->url}/{if $brand}{$brand->url}/{/if}page_{$page}/" class="all_pages">←&nbsp;назад</a>
  {/if}
  
  {if $page<$total_pages-1}
  <a id="NextLink" href="{if $category}catalog/{$category->url}/{elseif $brand}brands/{/if}{if $brand}{$brand->url}/{/if}page_{$page+2}/" class="all_pages">вперед&nbsp;→</a>
  {/if} 
</p>        
{/if}
{* END Постраничная навигация *}
