{* Хлебные крошки *}
<div class=path>
    <a href="./">Главная</a>&nbsp;/
    {if $section->header}{$section->header|escape}{else}Статьи{/if}
</div>
{* END Хлебные крошки *}


{if $section->body}
<p>
  {$section->body}
</p>
{/if}

{* Статьи *}
{foreach name=articles from=$articles item=a}
<h2 class="h2"><a href="articles/{$a->url}">{$a->header|escape}</a></h2>
{$a->annotation}
{/foreach}
{* END Статьи *}
