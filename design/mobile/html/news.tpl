{* Хлебные крошки *}
<div class=path>
    <a href="./">Главная</a>&nbsp;/
    {if $section->header}{$section->header|escape}{else}Новости{/if}
</div>
{* END Хлебные крошки *}


{if $section->body}
<p>
  {$section->body}
</p>
{/if}


{* Новости *}
{foreach name=news from=$news item=news_item}
<p>
<p class=news_date>{$news_item->date}</p>
<a href="news/{$news_item->url}">{$news_item->header|escape}</a>
<br>
{$news_item->annotation}
</p>
{/foreach}
{* END Новости *}
