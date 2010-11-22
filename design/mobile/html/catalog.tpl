<img src="design/{$settings->theme}/images/logo.gif" alt="{$settings->site_name}"/>


{* Форма поиска *}
<form action="index.php">
<input type=hidden name=module value=Search>
<input type="text" name=keyword value="{$keyword|escape}"/><input type="submit" value="Найти"/>	
</form>
{* END Форма поиска *}

{if $section->body}
<p>
  {$section->body}
</p>
{/if}

{* Меню категорий *}
{defun name=categories_tree categories=$categories}
{if $categories}
<ul>
{foreach item=c from=$categories}
<li><a href='catalog/{$c->url}'>{$c->name}</a></li>
{fun name=categories_tree categories=$c->subcategories}        
{/foreach}  
</ul>
{/if}    
{/defun}
{*  END Меню категорий *}