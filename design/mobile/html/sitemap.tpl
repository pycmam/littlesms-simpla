{* Хлебные крошки *}
<div class=path>
    <a href="./">Главная</a>&nbsp;/
    {if $section->header}{$section->header|escape}{else}Карта сайта{/if}
</div>
{* END Хлебные крошки *}

<h1>{if $section->header}{$section->header|escape}{else}Карта сайта{/if}</h1>

{if $error}
<div class="error">{$error}</div>
{/if}


{if $section->body}
<p>
  {$section->body}
</p>
{/if}

<ul>
<li><a href='/'>{$settings->site_name}</a></li>
<ul>

{foreach from=$sections item=section}
{if $section->menu_id==2 || $section->menu_id==3}
<li><a href='sections/{$section->url}'>{$section->name|escape}</a></li>
{/if}
{/foreach}
</ul>
</ul>

<ul>
<li><a href='catalog/'>Каталог</a></li>
{defun name=cats_tree categories=$catalog}
{if $categories}
<ul>
{foreach item=c from=$categories}
	<li><a href='catalog/{$c->url}'>{$c->name|escape}</a></li>
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
<ul>
<li><a href='news/'>Новости</a></li>
<ul>
{foreach from=$news item=n}
<li><a href='news/{$n->url}'>{$n->header|escape}</a></li>
{/foreach}
</ul>
</ul>
{/if}

{if $articles}
<ul>
<li><a href='articles/'>Статьи</a></li>
<ul>
{foreach from=$articles item=a}
<li><a href='articles/{$a->url}'>{$a->header|escape}</a></li>
{/foreach}
</ul>
</ul>
{/if}

