{*
  Template name: Список товаров
  Вовод списка товаров в категории
  Used by: Strefront.class.php   
  Assigned vars: $products, $brands, $category, $total_pages, $page
*}

{if $category->image}
<!-- Баннер  /-->
<div id="banner"><img src="files/categories/{$category->image}" alt="Banner image"/></div>
<!-- Баннер #End /-->
{/if}


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

{* Описание категории *}
{if $category->description}
{$category->description}
<br/>
{elseif $brand->description}
{$brand->description}
<br/>
{/if}
{* END Описание категории *}

<!-- Фильтр по брендам /-->
{if $brands}
<div id="category_description">
  {foreach name=brands item=b from=$brands}
    {if $b->brand_id == $brand->brand_id}
      {$b->name|escape}
    {else}
      <a href='catalog/{$category->url}/{$b->url}{$filter_params}'>{$b->name|escape}</a>
    {/if}
    {if not $smarty.foreach.brands.last}
    |
    {/if}
  {/foreach}
</div>
{/if}
<!-- Фильтр по брендам #End /-->

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

{if $products}
<!-- Список товаров  /-->
<div id="products_list">

    {foreach name=products item=product from=$products}    
    <!-- Товар /-->
    <div class="product_block">
    
        <!-- Картинка товара /-->
        <div class="product_block_img">
            <p>
              <a href="products/{$product->url}">
                <img src="{if $product->small_image}files/products/{$product->small_image}{else}design/{$settings->theme}/images/no_foto.gif{/if}" alt=""/>
              </a>
              </p>
        </div>
        <!-- Картинка товара #End /-->
        
        <!-- Информация о товаре /-->
        <div class="product_block_annotation" >
        
            <!-- Название /-->
            <p tooltip='product' product_id='{$product->product_id}'><a href="products/{$product->url}" {if $product->hit}class="product_name_link_hit"{else}class="product_name_link"{/if}>{$product->category|escape} {$product->brand|escape} {$product->model|escape}</a></p>
            <!-- Название #End /-->

  <!-- Цена /-->
  <p>
  {if $product->variants[0]->discount_price>0}
  <span class="price"><span id=variant_price_{$product->product_id}>{$product->variants[0]->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}</span>&nbsp;{$currency->sign|escape}</span>
  {/if}
  </p>
  <!-- Цена #End /-->
	
  <form action=cart method=get>
  <p>
  {if $product->variants|@count > 1}
  <!-- Варианты товара /--> 
  <select name=variant_id onchange="display_variant({$product->product_id}, this.value);return false;"> 
  {foreach from=$product->variants item=variant}
  <option value='{$variant->variant_id|escape}'>{$variant->name|escape}<strong></strong><br>
  {/foreach}
  </select>
  <input type=button  value='' class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  <br>
  {elseif $product->variants|@count == 1}
  <input type=hidden name=variant_id value='{$product->variants[0]->variant_id}'>
  <input type=button value='' class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  {/if}  
  <!-- Варианты товара #END /-->  

  </p>
  </form> 

            <!-- Описание товара /-->
            <p class="product_annotation">
                {$product->description}
            </p>
            <!-- Описание товара #End /-->
            <p><a href='compare/{$product->url}'>Сравнить</a></p>
        </div>
        <!-- Информация о товаре #End /-->
        
    </div>
    <!-- Товар #End /-->
    {if $smarty.foreach.products.iteration%2 == 0}
      <div class="clear"><!-- /--></div>
    {/if}
    {/foreach}
    
    <div class="clear"><!-- /--></div>
    
</div>
<!-- Список товаров #End /-->
{else}
Товары не найдены
{/if}

<script>
var variants_prices = new Array;
{foreach from=$products item=product}
variants_prices[{$product->product_id|escape}] = new Array;
{foreach from=$product->variants item=variant}
  variants_prices[{$product->product_id|escape}][{$variant->variant_id|escape}] = '{$variant->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}';
{/foreach}
{/foreach}

  {literal}
  function display_variant(product, variant)
  { 
  	document.getElementById('variant_price_'+product).innerHTML = variants_prices[product][variant];
  }
  {/literal}
</script>

<!-- Постраничная навигация /-->
{if $total_pages>1}
<script type="text/javascript" src="js/ctrlnavigate.js"></script>           
<div id="paging">

  {section name=pages loop=$total_pages}
  <a {if $smarty.section.pages.index==$page}class="current_page" {/if}href="{if $category}catalog/{$category->url}/{elseif $brand}brands/{/if}{if $brand}{$brand->url}/{/if}{if $smarty.section.pages.index}page_{$smarty.section.pages.index+1}{/if}{$filter_params}">{$smarty.section.pages.index+1}</a>
  {/section}
  
  {if $page>0}
  <a id="PrevLink" href="{if $category}catalog/{$category->url}/{elseif $brand}brands/{/if}{if $brand}{$brand->url}/{/if}page_{$page}{$filter_params}" class="all_pages">←&nbsp;назад</a>
  {/if}
  
  {if $page<$total_pages-1}
  <a id="NextLink" href="{if $category}catalog/{$category->url}/{elseif $brand}brands/{/if}{if $brand}{$brand->url}/{/if}page_{$page+2}{$filter_params}" class="all_pages">вперед&nbsp;→</a>
  {/if}

</div>          
{/if}
<!-- Постраничная навигация #End /-->
