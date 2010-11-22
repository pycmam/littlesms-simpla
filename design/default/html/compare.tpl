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
  <!-- Картинки товара /-->
<p tooltip='product' product_id='{$product->product_id}'><a href="products/{$product->url}" {if $product->hit}class="product_name_link_hit"{else}class="product_name_link"{/if}>{$product->category|escape} {$product->brand|escape} {$product->model|escape}</a></p>
    <img src="{if $product->small_image}files/products/{$product->small_image}{else}design/{$settings->theme}/images/no_foto.gif{/if}" alt=""/>
  <!-- Картинки товара #End /-->
  <br>
  <a href='compare/remove/{$product->url}'>Убрать</a>

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
 
  <!-- Основное описание товара /-->
    
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
