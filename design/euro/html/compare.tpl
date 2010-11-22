{*
  Template name: Сравнение товаров
  Used by: Compare.class.php   
  Assigned vars: $product, $comments
*}

{* Подключаем js-проверку формы *}
<script src="js/baloon/js/default.js" language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/validate.js" language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/baloon.js" language="JavaScript" type="text/javascript"></script>
<link href="js/baloon/css/baloon.css" rel="stylesheet" type="text/css" /> 

{* Увеличитель картинок *}
<script type="text/javascript" src="js/enlargeit/enlargeit.js"></script>

<!-- Товар  /-->
<div id="page_title">      
  <h1 class="float_left">Сравнение товаров</h1>

  <!-- Хлебные крошки /-->
  <div id="path">
    <a href="./">Главная</a>
    → Сравнение товаров           
  </div>
<!-- Хлебные крошки #End /-->
</div>

{if $products}
<div id="product_params">
<table>
<tr>
{foreach from=$products item=product}
<td>
<!-- Описание товара /-->
    <p tooltip='product' product_id='{$product->product_id}'><a href="products/{$product->url}" {if $product->hit}class="product_name_link_hit"{else}class="product_name_link"{/if}>{$product->category|escape} {$product->brand|escape} {$product->model|escape}</a></p>
    <!-- Картинки товара /-->
    <img src="{if $product->small_image}files/products/{$product->small_image}{else}design/{$settings->theme}/images/no_foto.gif{/if}" alt=""/>
    <br>
    <a href='compare/remove/{$product->url}'>Убрать</a>

  <!-- Картинки товара #End /-->

</td>
{/foreach} 
</tr>
{foreach from=$properties key=k item=property}
<tr>
{foreach from=$products item=product}
{assign var=product_id value=$product->product_id}
<td> 
  {if $property[$product_id]}
 <b>{$k|escape}</b><br>
 {$property[$product_id]|escape}
 {else}
 &mdash;
 {/if}
</td>
{/foreach} 
</tr>
{/foreach} 
<tr>
{foreach from=$products item=product}
<td> 


{$product->body}

</td>
{/foreach} 
</tr>
<tr>
{foreach from=$products item=product}
<td> 
 
<form class=price action='cart' method=get style='float:left; '>
{foreach name=variants item=variant from=$product->variants}
<input {if $product->variants|@count<2}style='display:none;'{/if} type=radio name=variant_id value='{$variant->variant_id}' {if $smarty.foreach.variants.first}checked{/if}>{$variant->name} <b>{$variant->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign|escape}</b>
<br>
{/foreach}
 
{if $product->variants|@count>0}
<input class="execute" type=submit href="cart/add/{$product->product_id}" value='в корзину' onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
{/if}
</form>
      
<!-- Основное описание товара #End /-->
</td>
{/foreach}
</tr>
</table>
</div>

{else}
Нет товаров для сравнения
{/if}
<!-- Описание товара #End/-->
<!-- Товар  #End /-->