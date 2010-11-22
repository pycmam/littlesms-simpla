{*
  Template name: Лучшие товары
  Used by: Strefront.class.php   
  Assigned vars: $products
*}

<!-- Баннер  /-->
<div id="catalog_image"><img src="design/{$settings->theme}/images/header.jpg" alt="интернет-магазин"/></div>
<!-- Баннер #End /-->


<!-- Заголовок страницы  /-->
{if $section->header}
<div id="page_title">      
  <h1>{$section->header|escape}</h1>
</div>      
{/if}
<!-- Заголовок страницы #End /-->

<!-- Текст раздела /-->
{if $section->body}
<div id="category_description">
  {$section->body}
</div>
{/if}
<!-- Текст раздела #End /-->

{if $products}
<div id="page_title">      
  <h1>Лучшие товары</h1>
</div>      
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
  <input type=button class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  <br>
  {elseif $product->variants|@count == 1}
  <input type=hidden name=variant_id value='{$product->variants[0]->variant_id}'>
  <input type=button class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  {/if}  
  <!-- Варианты товара #END /-->  

  </p>
  </form> 

            <!-- Описание товара /-->
            <p class="product_annotation">
                {$product->description}
            </p>
            <p><a href='compare/{$product->url}'>Сравнить</a></p>
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
<!-- Список товаров #End  /-->

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


{/if}

{if $articles}
<!-- Статьи /-->
<div id="articles">

  <!-- Левая колонка статей /-->
  <div id="articles_left">

    {foreach name=articles from=$articles item=article}
    {if $smarty.foreach.articles.iteration <= $smarty.foreach.articles.total/2+0.7}
    <!-- Блок статьи /-->
    <div class="article">
      <h2 class="h2"><a tooltip="article" article_id="{$article->article_id}" href="articles/{$article->url}">{$article->header}</a></h2>
      <p>
        {$article->annotation}
      </p>
    </div>
    <!-- Блок статьи #End /-->
    {/if}
    {/foreach}
  
  </div>
  <!-- Левая колонка статей # /-->
  
  <!-- Правая колонка статей /-->        
  <div id="articles_right">

    {foreach name=articles from=$articles item=article}
    {if $smarty.foreach.articles.iteration > $smarty.foreach.articles.total/2+0.7}
    <!-- Блок статьи /-->
    <div class="article">
      <h2 class="h2"><a tooltip="article" article_id="{$article->article_id}" href="articles/{$article->url}">{$article->header|escape}</a></h2>
      <p>
        {$article->annotation}
      </p>
    </div>
    <!-- Блок статьи #End /-->
    {/if}
    {/foreach}

  </div>
  <!-- Правая колонка статей #End /-->        
  
  <div class="clear"><!-- /--></div>
  <h2 class="h2"><a href="articles/">все статьи →</a></h2>
</div>
{/if}