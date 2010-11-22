<!-- Управление товарами /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Sections" class="on">страницы</a></li>
      <li><a href="index.php?section=NewsLine" class="off">новости</a></li>
      <li><a href="index.php?section=Articles" class="off">статьи</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          {$Menu->name}</a>
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
        <h1 id="headline">{$Menu->name}</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=sections" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
		 <!-- Помощь2 /-->
        <div class="help2">
			<a href="index.php?section=Section&menu={$Menu->menu_id}&token={$Token}" class="fl"><img src="./images/add.jpg" class="fl"/>Добавить страницу</a>  
        </div>
        <!-- /Помощь2 /-->        
        
      </div>

      <div id="cont_center">
      
      
        <!-- Левое меню /-->
        <div id="cont_left">
        
          <ul>
            {foreach name=menulist key=key item=item from=$Menus}
            <li {if $item->menu_id == $Menu->menu_id}class="li_on"{else}class="li_off"{/if}>
              <a href="index.php?section=Sections&menu={$item->menu_id}">{$item->name}</a>
            </li>
          {/foreach}
          </ul>
          

        </div>
        <!-- /Левое меню /-->
      
	    <!-- Right Side #Begin/-->
        <div id="cont_right">

        
          {if $Error}
          <!-- Error #Begin /-->
          <div id="error_minh">
            <div id="error">
              <img src="./images/error.jpg" alt=""/><p>{$Error}</p>					
            </div>
          </div>
          <!-- Error #End /-->
          {/if}
          
          <div class="clear">&nbsp;</div>	
          
          {$PagesNavigation}
  
          {if $Sections}

          <!-- Форма товаров #Begin /-->
          <form name='products' method="post">
            <table id="list2">
            
              {* Список разделов *}
              {foreach item=section from=$Sections}
              <tr>
                <td>
                  <div class="list_left">
                    <a href="index.php{$section->move_up_get}" class="fl"><img src="./images/up.jpg"  alt="Поднять" title="Поднять"/></a><a href="index.php{$section->move_down_get}" class="fl"><img src="./images/down.jpg"  alt="Опустить" title="Опустить"/></a><a href="index.php{$section->enable_get}" class="fl"><img src="./images/{if $section->enabled}lamp_on.jpg{else}lamp_off.jpg{/if}"  alt="Активность" title="Активность"/></a>
                    <div class="flxc">
                      <p>
                        <a href="index.php{$section->edit_get}" class="{if $section->enabled}tovar_on{else}tovar_off{/if}">{$section->name|escape}</a>
                      </p>
                      <p>
                        {if $section->enabled}
                        <a class="tovar_min" href='http://{$root_url}/sections/{$section->url}'>http://{$root_url}/sections/{$section->url}</a>
                        {else}
                        <span class="tovar_min">http://{$root_url}/sections/{$section->url}</span>
                        {/if}
                      </p>
                    </div>
			      </div>
                  <a href="index.php{$section->delete_get}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" alt="Удалить" title="Удалить"/></a>
                </td>
              </tr>
              {/foreach}
              {* /Список разделов *}
            </table>
            </form>
            <!-- Форма Товаров #End /-->
            {else}
              <div class="emptylist">Нет страниц</div>
            {/if}

            {$PagesNavigation}

        </div>
	    <!-- Right side #End/-->
 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 