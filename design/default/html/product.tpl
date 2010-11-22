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
<div id="product_main">

  <!-- Картинки товара /-->
  <div id="product_main_img">
    <img src="{if $product->large_image}files/products/{$product->large_image}{elseif $product->small_image}files/products/{$product->small_image}{else}design/{$settings->theme}/images/no_foto.gif{/if}" alt=""/>
    <ul>
      {if $product->fotos}
      {foreach from=$product->fotos item=foto}                      
      <li><a href="files/products/{$foto->foto_id}" onclick='return false;'><img id="files/products/{$foto->filename}" onclick="enlarge(this);" longdesc="files/products/{$foto->filename}" width=80 src="files/products/{$foto->filename}" alt=""/></a></li>
      {/foreach}
      {/if}
    </ul>
  </div>
  <!-- Картинки товара #End /-->
  
  <!-- Основное описание товара /-->
  <div id="product_main_description">
    
  <!-- Цена /-->
  <p>
  {if $product->variants[0]->discount_price>0}
  <span class="price"><span id=variant_price>{$product->variants[0]->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}</span>&nbsp;{$currency->sign|escape}</span>
  {/if}
  </p>
  <!-- Цена #End /-->
	
  <form action=cart method=get>
  <p>
  {if $product->variants|@count > 1}
  <!-- Варианты товара /--> 
  <select name=variant_id onchange="display_variant(this.value);return false;"> 
  {foreach from=$product->variants item=variant}
  <option value='{$variant->variant_id|escape}'>{$variant->name|escape}<strong></strong><br>
  {/foreach}
  </select>
  <input type=button class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  <script>
  var variants_prices = new Array;
  {foreach from=$product->variants item=variant}
  variants_prices[{$variant->variant_id|escape}] = '{$variant->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}';
  {/foreach}
  {literal}
  function display_variant(variant)
  {
  	document.getElementById('variant_price').innerHTML = variants_prices[variant];
  }
  {/literal}
  </script>  
  {elseif $product->variants|@count == 1}
  <input type=hidden name=variant_id value='{$product->variants[0]->variant_id}'>
  <input type=button class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  {/if}  
  <!-- Варианты товара #END /-->  

  </p>
  </form> 

  {$product->body}

  {if $product->properties}
  <!-- Характеристики товара /-->  
  <div id="product_params">
    <a name=params></a>

    <table>
    {foreach from=$product->properties item=property}
      {if $property->in_product}
	  <tr><td>{$property->name|escape}</td><td>{$property->value|escape}</td></tr>
	  {/if}
    {/foreach}
    </table>
  </div>
  <!-- Характеристики товара #END /-->  
  {/if}
  <p><a href='compare/{$product->url}'>Сравнить</a></p>
  
  
<!-- Добавление в закладки -->
  <p style='text-align:right'>
  <noindex>
  <script>
  var url = location.href;
  var title = document.title;
  var tags = '';
  var desc = '';
  var url2 = location.href;
  m = document.getElementsByTagName('meta'); 
  for(var i in m)
    if(m[i].name == 'keywords') tags = m[i].content;
      else if(m[i].name == 'description') desc = m[i].content;
  document.write('<a rel="nofollow" href="http://www.memori.ru/link/?sm=1&u_data[url]='+url+'&u_data[name]='+title+'&u_data[descr]='+desc+'" title="Добавить закладку в Memori"><img src="design/{$settings->theme}/images/memori.gif" alt="Добавить закладку в Memori" border="0"></a>');
  document.write('<a rel="nofollow" href="http://www.google.com/bookmarks/mark?op=add&bkmk='+url+'&title='+title+'&labels='+tags+'&annotation='+desc+'" title="Добавить закладку в Google"><img src="design/{$settings->theme}/images/google.gif" alt="Добавить закладку в Google" border="0"></a>');
  document.write('<a rel="nofollow" href="http://www.bobrdobr.ru/addext.html?url='+url+'&title='+title+'&desc='+desc+'&tags='+tags+'" title="Добавить закладку в Бобрдобр"><img src="design/{$settings->theme}/images/bobrdobr.gif" alt="Добавить закладку в Бобрдобр" border="0"></a>');
  document.write('<a rel="nofollow" href="http://twitter.com/home?status='+title+' '+url+'" title="Опубликовать в Твиттер"><img src="design/{$settings->theme}/images/twitter.gif" alt="Опубликовать в Твиттер" border="0"></a>');
  </script>
  </noindex>
  </p>
<!-- Добавление в закладки #End -->
  
  
  </div>  
  
<div class="clear"><!-- /--></div>
<!-- Основное описание товара #End /-->


<!-- Соседние товары  /-->
{if $prev_product}
<a href='products/{$prev_product->url}'>←&nbsp;{$prev_product->category|escape} {$prev_product->brand|escape} {$prev_product->model|escape}</a></nobr>
{/if}
{if $next_product}
&nbsp;&nbsp;&nbsp;
<a href='products/{$next_product->url}'>{$next_product->category|escape} {$next_product->brand|escape} {$next_product->model|escape}&nbsp;→</a>
{/if}
<!-- Соседние товары  #End/-->


</div>
<!-- Описание товара #End/-->



{if $product->related_products}
<h2>Так же советуем посмотреть:</h2>
<!-- Список связанных товаров  /-->
<div id="products_list">

    {foreach name=products item=product from=$product->related_products}    
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
{/if}

<!-- Комментарии к товару  /-->  
<div id="comments">
  <a name=comments></a>

  <!-- Список каментов  /-->  
  <h2>Отзывы об этом товаре</h2>
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