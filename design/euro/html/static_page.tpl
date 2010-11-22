{*
  Template name: Статическая страница
  Used by: StaticPage.class.php   
  Assigned vars: $page
*}

<div id="page_title">      
  <h1  tooltip='section' section_id='{$page->section_id}' class="float_left">{$page->header|escape}</h1>
  <!-- Хлебные крошки /-->
  <div id="path">
    <a href="./">Главная</a> → {$page->name|escape}
  </div>
  <!-- Хлебные крошки #End /-->
</div>      

<p>
{$page->body}
</p>