{* Хлебные крошки *}
<div class=path>
    <a href="./">Главная</a>&nbsp;/
    <a href='news/'>Новости</a>
</div>
{* END Хлебные крошки *}

<h1>{$news_item->header|escape}</h1>
<p class=news_date>{$news_item->date}</p>

<p>
   {$news_item->body}
</p>

<p>
  <a href='news/'>← все новости</a>
</p>
