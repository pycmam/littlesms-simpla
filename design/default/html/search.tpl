{*
  Template name: Поиск
  Результаты поиска
  Used by: Search.class.php   
  Assigned vars: $products, $keyword
*}

<!-- Заголовок  /-->
<div id="page_title">      
    <h1 class="float_left">Поиск {$keyword|escape}</h1>

    <!-- Хлебные крошки /-->
    <div id="path">
      <a href="./">Главная</a>
      → Поиск {$keyword|escape}
    </div>
    <!-- Хлебные крошки #End /-->
</div>      

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
  <input type=image class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.submit();">
  <br>
  {elseif $product->variants|@count == 1}
  <input type=hidden name=variant_id value='{$product->default_variant_id}'>
  <input type=image class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.submit();">
  {/if}  
  <!-- Варианты товара #END /-->  

  </p>
  </form> 

            <!-- Описание товара /-->
            <p class="product_annotation">
                {$product->description}
            </p>
            <!-- Описание товара #End /-->
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
<p>
  По запросу &laquo;{$keyword|escape}&raquo; ничего не найдено
</p>
<br>
{/if}
