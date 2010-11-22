{*
  Template name: Товар
  Отображение отдельного товара
  Used by: Strefront.class.php   
  Assigned vars: $product, $comments, $category
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
  <h1  tooltip='product' product_id='{$product->product_id}' {if $product->hit}id="hit_header"{/if} class="float_left">{$product->category|escape} {$product->brand|escape} {$product->model|escape}</h1>

  <!-- Хлебные крошки /-->
  <div id="path">
    <a href="./">Главная</a>
    {foreach from=$category->path item=cat}
    → <a href="catalog/{$cat->url}">{$cat->name|escape}</a>
    {/foreach}
    {if $product->brand}
    → <a href="catalog/{$cat->url}/{$product->brand_url}">{$product->brand|escape}</a>
    {/if}
    →  {$product->category|escape} {$product->brand|escape} {$product->model|escape}                
  </div>
<!-- Хлебные крошки #End /-->
</div>

<!-- Описание товара /-->
<div id="goods_main">
  <div id="goods_main_img">
    <div id="img_bg">
      {if $product->hit}<div class="hit2"><!-- /--></div>{/if}
        {if $product->large_image}
        <img src="files/products/{$product->large_image}" alt=""/>
        {/if}
	  </div>
      {if $product->fotos}
      <ul>
      {foreach from=$product->fotos item=foto}                      
      <li><a href="files/products/{$foto->foto_id}" onclick='return false;'><img id="files/products/{$foto->filename}" onclick="enlarge(this);" longdesc="files/products/{$foto->filename}" width=80 src="files/products/{$foto->filename}" alt=""/></a></li>
      {/foreach}
      </ul>
      {/if}
  </div>
  <div id="goods_main_description">
   {$product->body}   

  </div>
  
{if $product->properties}
<!-- Характеристики товара /-->  
 
  <a name=params></a>
  <table id=product_params>
  {foreach from=$product->properties item=property}
   {if $property->in_product}
	<tr><td>{$property->name|escape}</td><td>{$property->value|escape}</td></tr>
   {/if}
  {/foreach}
  </table>
  <br>
  <p><a href='compare/{$product->url}'>Сравнить</a></p>
  <br>
 
<!-- Характеристики товара #END /-->  
{/if}


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


<!-- Соседние товары  /-->
{if $prev_product}
<a href='products/{$prev_product->url}'>←&nbsp;{$prev_product->category|escape} {$prev_product->brand|escape} {$prev_product->model|escape}</a></nobr>
{/if}
{if $next_product}
&nbsp;&nbsp;&nbsp;
<a href='products/{$next_product->url}'>{$next_product->category|escape} {$next_product->brand|escape} {$next_product->model|escape}&nbsp;→</a>
{/if}
<!-- Соседние товары  #End/-->
<br><br>


{if $product->related_products}
<div id="merch">
	<h2 class="h2_bl">Так же советуем посмотреть:</h2>
	
	{foreach name=products item=product from=$product->related_products}

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
	

    <!-- Товар #End /-->
    {if $smarty.foreach.products.iteration%2 == 0}
      <div class="clear"><!-- /--></div>
    {/if}
    {/foreach}
</div>
{/if}


<div class="clear"><!-- /--></div>


<!-- Комментарии к товару  /-->  
<div id="comments">
  <a name=comments></a>

  <!-- Список каментов  /-->  
  <h2 class="h2_bl">Отзывы об этом товаре</h2>
  {if $comments}
  {foreach from=$comments item=c}
  
  <!-- Отдельный камент  /-->  
  <div class="comment_pack">
    <p><span class="comment_name">{$c->name|escape}</span> <span class="comment_date">{$c->date|escape}</span></p>
    <p class="comment_text" tooltip=comment comment_id={$c->comment_id}>{$c->comment|escape|nl2br}</p>
  </div>
  <!-- Отдельный камент #End  /-->  
  
  {/foreach}
  {else}
    Пока нет ни одного отзыва
  {/if}
  <!-- Список каментов #End  /-->  

  <h2>Оставить свой отзыв</h2>

  {if $error}
  <div id="error_block"><p>{$error}</p></div>
  {/if}


  <!-- Форма отзыва /-->  
  <form action='{$smarty.server.REQUEST_URI}#comments' method=post>

    <!--  Текст камента /-->  
    <p><textarea class="comment_textarea" format='.+' notice='Введите комментарий' name=comment>{$comment|escape}</textarea></p>
    <!--  Имя комментатора /-->  
    <p class="comment_username">Ваше имя                    
      <input type="text" class="comment_username" name=name value="{$name|escape}" format='.+' notice='Введите имя' />
    </p>

    <!--  Капча /-->  
    {if $gd_loaded}
    <div class="captcha">
      <img src="captcha/image.php?t={math equation='rand(10,10000)'}" alt=""/>
      <p>Число:</p>
      <p><input type="text" name=captcha_code format='.+' notice='Введите число с картинки' /></p>
    </div>
    {/if}
    
    <p><input type="submit" value="Отправить" class="comment_submit"/></p>
  </form>
  <!-- Форма отзыва #End  /-->  
  
</div>
<!-- Комментарии к товару #End  /-->

<!-- Товар  #End /-->