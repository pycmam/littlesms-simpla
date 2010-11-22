{*
  Template name: Статья
  Вывод статьи.
  Используется классом Articles.class.php

  Передаваемые параметры:
  $article - статья
*}

<!-- Заголовок /-->
<div id="page_title">  
  <h1  tooltip='article' article_id='{$article->article_id}' class="float_left">{$article->header|escape}</h1>
  <!-- Хлебные крошки /-->
  <div id="path">
    <a href="./">Главная</a>
    → <a href='articles/'>Статьи</a>
    → {$article->header|escape}                
  </div>
  <!-- Хлебные крошки #End /-->
</div>      

<p>
   {$article->body}
</p>

<br>

<p>
  <a href='articles/'>← все статьи</a>
</p>
