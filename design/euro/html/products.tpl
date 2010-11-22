{*
  Template name: Список товаров
  Вовод списка товаров в категории
  Used by: Strefront.class.php   
  Assigned vars: $products, $brands, $category, $total_pages, $page
*}

<!-- Заголовок  /-->
<div id="page_title">      
	{if $category}     
    <h1  tooltip='category' category_id='{$category->category_id}' class="float_left">{$category->name|escape} {$brand->name|escape}</h1>
    {elseif $brand}
    <h1  tooltip='brand' brand_id='{$brand->brand_id}' class="float_left">{$brand->name|escape}</h1>
    {/if}

    <!-- Хлебные крошки /-->
    <div id="path">
      <a href="./">Главная</a>
      {foreach from=$category->path item=cat}
      → <a href="catalog/{$cat->url}">{$cat->name|escape}</a>
      {/foreach}  
      {if $brand}
      → {$brand->name|escape}
      {/if}
    </div>
    <!-- Хлебные крошки #End /-->

</div>    

{if $category->image}
<!-- Баннер  /-->
<div id="banner"><img src="files/categories/{$category->image}" alt="Banner image"/></div>
<!-- Баннер #End /-->
{/if}
  
{* Описание категории *}
{if $category->description}
{$category->description}
<br/>
{elseif $brand->description}
{$brand->description}
<br/>
{/if}
{* END Описание категории *}


<div id="tags">
	{if $brands}
	<ul>
	{foreach name=brands item=b from=$brands}
	{if $b->brand_id == $brand->brand_id}
	<li>{$b->name|escape}</li> 
	{else}
	<li><a href='catalog/{$category->url}/{$b->url}{$filter_params}'>{$b->name|escape}</a></li> 
	{/if}
	{/foreach}
	</ul>
	{/if}
</div>

<!-- Фильтр по свойствам /-->
{if $properties}
<div id="filter_params">
<table>
  {foreach name=properties item=property from=$properties}
  {assign var=property_id value=$property->property_id}
  <tr>
  <td>{$property->name}:</td>
  <td>
  	{if $smarty.get.$property_id}<a href='catalog/{$category->url}{$property->clear_url}'>все</a>{else}все{/if}
  	{foreach name=options from=$property->options item=option}
  		{if $smarty.get.$property_id == $option->value}
  		<span>{$option->value}</span>
  		{else}
  		<span><a href='catalog/{$category->url}{if $brand}/{$brand->url}{/if}{$option->url}'>{$option->value}</a></span>
  		{/if}  		
  	{/foreach}
  	</td>
  </tr>
  {/foreach}
  </table>
</div>
{/if}
<!-- Фильтр по свойствам  #End /-->



<div id="merch">
{foreach name=products item=product from=$products}
<div class="block2"><div class="block2_top"><div class="block2_bottom">
	{if $product->hit}
	<div class="hit"><!-- /--></div>
	{/if}
	<h2 tooltip='hit' product_id='{$product->product_id}'><a href="products/{$product->url}">{$product->category|escape} {$product->brand|escape} {$product->model|escape}</a></h2>
	<div class="price"><a href="products/{$product->url}"><img src="{if $product->small_image}files/products/{$product->small_image}{elseif $product->category_image}foto/categories/{$product->category_image}{else}images/no_foto.gif{/if}" alt=""/></a>
	
	</div>
	<div class="desc">
		<p>
		  {$product->description}
		</p>
		<br>
		<p><a href='compare/{$product->url}'>Сравнить</a></p>
	</div>

<form class=price action='cart' method=get style='float:left; width:300px;'>
{foreach name=variants item=variant from=$product->variants}
<input {if $product->variants|@count<2}style='display:none;'{/if} type=radio name=variant_id value='{$variant->variant_id}' {if $smarty.foreach.variants.first}checked{/if}>{$variant->name} <b>{$variant->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}</b>
<br>
{/foreach}
 
{if $product->variants|@count>0}
<input class="execute" type=submit href="cart/add/{$product->product_id}" value='в корзину' onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
{/if}
</form>

</div>
</div></div>		

{if $smarty.foreach.products.iteration%2 == 0}
  <div class="clear"><!-- /--></div>
{/if}

{/foreach}
</div>

<!-- Постраничная навигация /-->
{if $total_pages>1}
<script type="text/javascript" src="js/ctrlnavigate.js"></script>           
<div id="peid">
  <ul>
  {section name=pages loop=$total_pages}
  {if $smarty.section.pages.index==$page}
    <li><span class="on">{$smarty.section.pages.index+1}</span></li>
  {else}
    <li><a href="{if $category}catalog/{$category->url}/{elseif $brand}brands/{/if}{if $brand}{$brand->url}/{/if}{if $smarty.section.pages.index}page_{$smarty.section.pages.index+1}{$filter_params}{/if}">{$smarty.section.pages.index+1}</a></li>
  {/if}
  {/section}
  </ul>
  
  <p>
  {if $page>0}
  <a id="PrevLink" href="{if $category}catalog/{$category->url}/{elseif $brand}brands/{/if}{if $brand}{$brand->url}/{/if}page_{$page}{$filter_params}">←&nbsp;назад</a>
  {/if}
  
  {if $page<$total_pages-1}
  <a id="NextLink" href="{if $category}catalog/{$category->url}/{elseif $brand}brands/{/if}{if $brand}{$brand->url}/{/if}page_{$page+2}{$filter_params}">вперед&nbsp;→</a>
  {/if}
  </p>

</div>          
{/if}
<!-- Постраничная навигация #End /-->

