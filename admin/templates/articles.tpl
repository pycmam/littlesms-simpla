<!-- Управление статьями /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Sections" class="off">страницы</a></li>
      <li><a href="index.php?section=NewsLine" class="off">новости</a></li>
      <li><a href="index.php?section=Articles" class="on">статьи</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          Статьи</a>
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
	    <img src="./images/icon_content.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Статьи</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=articles" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
		
		<!-- Помощь2 /-->
        <div class="help2">
              <a href="index.php?section=Article&token={$Token}" class="fl"><img src="./images/add.jpg" alt="" class="fl"/>Добавить статью</a>              
        </div>
        <!-- /Помощь2 /-->        

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
          
          
          {$PagesNavigation}
          <div class="clear">&nbsp;</div>
            
          {if $Items}

          <!-- Форма товаров #Begin /-->
          <form name='products' method="post">
            <table id="list">
            
              {* Список разделов *}
              {foreach item=item from=$Items}
              <tr>
                <td>
                  <div class="list_left">
                    <a href="index.php{$item->move_up_get}" class="fl"><img src="./images/up.jpg" alt="Поднять" title="Поднять"/></a><a href="index.php{$item->move_down_get}" class="fl"><img src="./images/down.jpg" alt="Опустить" title="Опустить"/></a>
                    <a href="index.php{$item->set_enabled_get}" class="fl"></a><a href="index.php{$item->enable_get}" class="fl"><img src="./images/{if $item->enabled}lamp_on.jpg{else}lamp_off.jpg{/if}"  alt="Активность" title="Активность"/></a>
                    <div class="flxc2">
                      <p>
                        <a href="index.php{$item->edit_get}" class="tovar_on">{$item->header|escape}</a>
                      </p>
                      <p>
                        {if $item->enabled}
                        <a class="tovar_min" href='http://{$root_url}/articles/{$item->url}'>http://{$root_url}/articles/{$item->url}</a>
                        {else}
                        <span class="tovar_min">http://{$root_url}/articles/{$item->url}</span>
                        {/if}
                      </p>
                    </div>
			      </div>
			    <a href="index.php{$item->delete_get}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" alt="Удалить" title="Удалить"/></a>
                </td>
              </tr>
              {/foreach}
              {* /Список разделов *}
            </table>
            </form>
            <!-- Форма Товаров #End /-->
            {else}
              Список пуст
            {/if}

            {$PagesNavigation}

        </div>
	    <!-- Right side #End/-->
 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 