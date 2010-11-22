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

<!-- Добавление в закладки -->
  <br>
  <div style='text-align:right'>
  <noindex>
  <script>
  var url = location.href;
  var title = document.title;
  var tags = '';
  var desc = '';
  var url2 = location.href;
  m = document.getElementsByTagName('meta'); 
  for(var i in m)
    if(m[i].name == 'keywords') tags = m[i].content;
      else if(m[i].name == 'description') desc = m[i].content;
  document.write('<a rel="nofollow" href="http://www.memori.ru/link/?sm=1&u_data[url]='+url+'&u_data[name]='+title+'&u_data[descr]='+desc+'" title="Добавить закладку в Memori"><img src="design/{$settings->theme}/images/memori.gif" alt="Добавить закладку в Memori" border="0"></a>');
  document.write('<a rel="nofollow" href="http://www.google.com/bookmarks/mark?op=add&bkmk='+url+'&title='+title+'&labels='+tags+'&annotation='+desc+'" title="Добавить закладку в Google"><img src="design/{$settings->theme}/images/google.gif" alt="Добавить закладку в Google" border="0"></a>');
  document.write('<a rel="nofollow" href="http://www.bobrdobr.ru/addext.html?url='+url+'&title='+title+'&desc='+desc+'&tags='+tags+'" title="Добавить закладку в Бобрдобр"><img src="design/{$settings->theme}/images/bobrdobr.gif" alt="Добавить закладку в Бобрдобр" border="0"></a>');
  document.write('<a rel="nofollow" href="http://twitter.com/home?status='+title+' '+url+'" title="Опубликовать в Твиттер"><img src="design/{$settings->theme}/images/twitter.gif" alt="Опубликовать в Твиттер" border="0"></a>');
  </script>
  </noindex>
  </div>
<!-- Добавление в закладки #End -->

<br>

<p>
  <a href='news/'>← все новости</a>
</p>
