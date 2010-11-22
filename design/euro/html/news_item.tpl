{*
  Template name: Новость
  Вывод новости.
  Используется классом NewsLine.class.php

  Передаваемые параметры:
  $news_item - новость
*}

<!-- Заголовок /-->
<div id="page_title">  
  <h1  tooltip='news' news_id='{$news_item->news_id}' class="float_left">{$news_item->header|escape}</h1>
  <!-- Хлебные крошки /-->
  <div id="path">
    <a href="./">Главная</a>
    → <a href='news/'>Новости</a>
    → {$news_item->header|escape}                
  </div>
  <!-- Хлебные крошки #End /-->
</div>      

<p class="news_date">{$news_item->date}</p>
<br>
<p>
   {$news_item->body}
</p>
<br>

<p>
  <a href='news/'>← все новости</a>
</p>
