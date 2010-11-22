{*
  Template name: Карта сайта
  Вывод новостной ленты.
  Используется классом NewsLine.class.php

  Передаваемые параметры:
  $news - список новостей
*}

<!-- Заголовок  /-->
<div id="page_title">      
  <h1 class="float_left">{if $section->header}{$section->header|escape}{else}Карта сайта{/if}</h1>
  <!-- Хлебные крошки /-->
  <div id="path">
    <a href="./">Главная</a>
    → {if $section->header}{$section->header|escape}{else}Карта сайта{/if}
  </div>
  <!-- Хлебные крошки #End /-->
</div> 

<!-- Текст раздела /-->
{if $section->body}
<div id="category_description">
  {$section->body}
</div>
{/if}
<!-- Текст раздела #End /-->

<ul class="catalog_menu">
<li><a href='/'>{$settings->site_name}</a></li>
<ul class="catalog_menu">

{foreach from=$sections item=section}
<li><a href='sections/{$section->url}'>{$section->name|escape}</a></li>
{/foreach}
</ul>
</ul>

<ul class="catalog_menu">
<li><a href='catalog/'>Каталог</a></li>
{defun name=cats_tree categories=$catalog}
{if $categories}
<ul class="catalog_menu">
{foreach item=c from=$categories}
	<li><a href='catalog/{$c->url}' tooltip='category' category_id='{$c->category_id}'>{$c->name|escape}</a></li>
	{fun name=cats_tree categories=$c->subcategories}        
	{if $c->products}
	<ul>
	{foreach from=$c->products item=product}
	  <li><a href='products/{$product->url}'>{$product->category_name|escape} {$product->model|escape}</a></li>
	{/foreach}
	</ul>
	{/if}
{/foreach}  
</ul>
{/if}    
{/defun}
</ul>

{if $news}
<ul class="catalog_menu">
<li><a href='news/'>Новости</a></li>
<ul class="catalog_menu">
{foreach from=$news item=n}
<li><a href='news/{$n->url}'>{$n->header|escape}</a></li>
{/foreach}
</ul>
</ul>
{/if}

{if $articles}
<ul class="catalog_menu">
<li><a href='articles/'>Статьи</a></li>
<ul class="catalog_menu">
{foreach from=$articles item=a}
<li><a href='articles/{$a->url}'>{$a->header|escape}</a></li>
{/foreach}
</ul>
</ul>
{/if}

