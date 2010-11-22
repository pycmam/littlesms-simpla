{*
  Template name: Лучшие товары
  Used by: Strefront.class.php   
  Assigned vars: $products
*}




<!-- Заголовок страницы  /-->
{if $section->header}
<div id="page_title">      
  <h1>{$section->header|escape}</h1>
</div>      
{/if}
<!-- Заголовок страницы #End /-->

<!-- Текст раздела /-->
{if $section->body}
<p>
  {$section->body}
</p>
<br>
{/if}
<!-- Текст раздела #End /-->
<a href='sections/delivery'><img src="design/{$settings->theme}/images/banner.jpg" alt="Banner image"/></a>
<br><br>

{if $products}
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
	
	
</div></div></div>		

{if $smarty.foreach.products.iteration%2 == 0}
  <div class="clear"><!-- /--></div>
{/if}

{/foreach}
</div>
{/if}



{if $articles}
<!-- Статьи /-->
<div id="articles">
  <ul>

    {foreach name=articles from=$articles item=article}
    <!-- Блок статьи /-->
    <li>
      <h2 class="h2"><a tooltip="article" article_id="{$article->article_id}" href="articles/{$article->url}">{$article->header}</a></h2>
      <p>
        {$article->annotation}
      </p>
    </li>
    <!-- Блок статьи #End /-->
    {/foreach}
  
  </ul>
  
  <div class="clear"><!-- /--></div>
  <p class="all"><a href="articles/">все статьи →</a></p>
</div>
{/if}

