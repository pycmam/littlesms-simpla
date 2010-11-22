<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Comments" class="off">комментарии</a></li>
      <li><a href="index.php?section=Feedback" class="on">обратная связь</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          Обратная связь
          </a>
        </p>
      </td>
    </tr>
  </table>
  <!-- /Путь /-->
</div>	
 
<!-- Content #Begin /-->
<div id="content">
  <div id="cont_border">
    <div id="cont">
     
      <div id="cont_top">
        <!-- Иконка раздела /--> 
	    <img src="./images/icon_comments.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Обратная связь</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=comments" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
      </div>

      <div id="cont_center">
    
        
          {if $Error}
          <!-- Error #Begin /-->
          <div id="error_minh">
            <div id="error">
              <img src="./images/error.jpg" alt=""/><p>{$Error}</p>					
            </div>
          </div>
          <!-- Error #End /-->
          {/if}
          
          <div class=filter>
            <form method=get>
              <input name=section type=hidden value='{$smarty.get.section}'>
              <input name=keyword type=text  class="input3" value='{$smarty.get.keyword|escape}'>
              <input type='submit' value='Найти' class="submit10">
            </form>
          </div>           
                    
          {$PagesNavigation}
          <div class="clear">&nbsp;</div>	

          {if $Items}

          <!-- Форма товаров #Begin /-->
          <form name='products' method="post">
            <table id="list">
            
              {* Список новостей *}
              {foreach item=item from=$Items}
              <tr>
                <td class=tovar_on>
                  <div class="list_left" >
                  <a href="index.php{$item->delete_get}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" alt=""/></a>
                    <div class="flxc3"  style='background-color:#F5F5F5; border:1px dashed #E0E0E0;padding:4px;'>
                      <p>
                        <div style='float:right;'>
                        {$item->date|escape} 
                        </div>
                        {$item->name|escape}
                        <a href='mailto:{$item->email|escape}?subject={$Settings->site_name}&body={$item->message|urlencode}'>{$item->email|escape}</a>
                        <span style='color:#707070;'>({$item->ip})</span>
                        
                      </p>
                    </div>
                    <div class="flxc3">
                      <p class=comment>
                        {$item->message|escape|nl2br}
                      </p>
                    </div>
			      </div>
                </td>
              </tr>

              {/foreach}
              {* /Список новостей *}
            </table>
            </form>


            {$PagesNavigation}
            {*
			<!-- Добавить / Сохранить /-->
			<div class="add_left">
              <a href="index.php?section=NewsItem" class="fl"><img src="./images/delete.jpg" alt="" class="fl"/>удалить найденные</a>              
            </div>
            *}
            <!-- Форма Товаров #End /-->
            {else}
              Список пуст
            {/if}


        </div>
	    <!-- Right side #End/-->

    </div>
  </div>	    
</div>
<!-- Content #End /--> 

