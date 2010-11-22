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
	</div>
</div></div></div>		

{if $smarty.foreach.products.iteration%2 == 0}
  <div class="clear"><!-- /--></div>
{/if}

{/foreach}
</div>
{else}
  Ничего не найдено
{/if}
