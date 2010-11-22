{*
  Template name: Список статей
  Вывод списка статей.
  Используется классом Articles.class.php

  Передаваемые параметры:
  $articles - список статей
*}

<!-- Заголовок /-->
<div id="page_title">      
  <h1 tooltip='newsline' class="float_left">{if $section->header}{$section->header|escape}{else}Статьи{/if}</h1>
  <!-- Хлебные крошки /-->
  <div id="path">
    <a href="./">Главная</a>
    → {if $section->header}{$section->header|escape}{else}Статьи{/if}
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

<!-- Статьи /-->
<ul id="news">
  {foreach name=articles from=$articles item=a}
  <li>
    <h2 class="h2"><a tooltip=article article_id="{$a->article_id}"  href="articles/{$a->url}">{$a->header|escape}</a></h2>
    <p>
    {$a->annotation}
    </p>
    <div class="clear"><!-- /--></div>
  </li>
  {/foreach}
</ul>
<!-- Статьи #End /-->              