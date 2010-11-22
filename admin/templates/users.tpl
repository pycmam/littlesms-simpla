<!-- Управление товарами /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Users" class="on">покупатели</a></li>
    <li><a href="index.php?section=Groups" class="off">группы</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          Покупатели
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
	    <img src="./images/icon_users.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Покупатели</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=users" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
      </div>

      <div id="cont_center">
    
        <!-- Левое меню /-->
        <div id="cont_left">
        
          <ul>
            {if !$smarty.get.group}
            <li class="li_on" style='padding-left:{$level*5+15}px;'>
              <a href="index.php?section=Users">Не определена</a>
            </li>
        	{* /Текущая категория *}
        	{* Категория *}
        	{else}
            <li class="li_off" style='padding-left:{$level*5+15}px;'>
              <a href="index.php?section=Users">Не определена</a>
            </li>
            {/if}
          
            {foreach from=$Groups item=group}
            {* Выводим категории *}
            
            {if $group->group_id == $smarty.get.group}
            <li class="li_on" style='padding-left:{$level*5+15}px;'>
              <a href="index.php?section=Users&group={$group->group_id}">{$group->name}</a>
            </li>
        	{* /Текущая категория *}
        	{* Категория *}
        	{else}
            <li class="li_off" style='padding-left:{$level*5+15}px;'>
              <a href="index.php?section=Users&group={$group->group_id}">{$group->name}</a>
            </li>
            {/if}
            {/foreach}
      		{* /Выводим категории *}
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
          
          <div class=filter>
            <form method=get>
              <input name=keyword type=text  class="input3" value='{$smarty.get.keyword|escape}'>
              <input name=section type=hidden value='{$smarty.get.section}'>
              <input name=group type=hidden value='{$smarty.get.group}'>
              <input type='submit' value='Найти' class="submit10">
            </form>
          </div>

          <div class="clear">&nbsp;</div>          
          {$PagesNavigation}
          <div class="clear">&nbsp;</div>
  
          {if $Users}



            <table id="list">
            
              {* Список товаров *}
              {foreach item=item from=$Users}
              <tr>
                <td>
                  <div class="list_left">
                    <a href="index.php{$item->enable_get}" class="fl"><img src="./images/{if $item->enabled}lamp_on.jpg{else}lamp_off.jpg{/if}" alt=""/></a>
                    <div class="flxc">
                      <p>
                        <a href="index.php{$item->edit_get}" class="{if $item->enabled}tovar_on{else}tovar_off{/if}">{$item->name|escape}</a>
                      </p>
                      <p>
                        <span class="tovar_min">{$item->email|escape}</span>
                      </p>
                    </div>
			      </div>
                </td>
                <td>
                  <div class="list_right">
                    {if $item->orders_num>0}<a href="index.php?section=Orders&view=search&keyword=user:{$item->user_id}" class="fl"><img src="./images/card_on.jpg" title="{$item->orders_num} заказов"/></a>{else}<img  class="fl" src="./images/card_off.jpg" title="нет заказов"/>{/if}
                    <a href="index.php{$item->delete_get}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" title="Удалить"/></a>
                  </div>
                </td>
              </tr>
              {/foreach}
              {* /Список товаров *}
            </table>
            <input type=submit value='Сохранить изменения' style='display:none;'>


            {else}
              <div class="emptylist">Нет покупателей</div>
            {/if}

            {$PagesNavigation}
            <div class="clear">&nbsp;</div>

        </div>
	    <!-- Right side #End/-->
	  </div>  
    </div>
  </div>	    
</div>
<!-- Content #End /--> 
 