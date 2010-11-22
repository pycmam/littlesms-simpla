<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Users" class="off">покупатели</a></li>
    <li><a href="index.php?section=Groups" class="on">группы</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          Группы покупателей
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
        <h1 id="headline">Группы покупателей</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=users" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
		 <!-- Помощь2 /-->
        <div class="help2">
            <a href="index.php?section=Group&token={$Token}" class="fl"><img src="./images/add.jpg" alt="" class="fl"/>Добавить группу</a>
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
          
        <div class="clear">&nbsp;</div>	  
          
        {$PagesNavigation}
  
        {if $Groups}

        <!-- Форма товаров #Begin /-->
        <form name='products' method="post">
          <table id="list2">
            
{foreach item=group from=$Groups}
<tr>
  <td>
    <div class="list_left">
      <div class="padding">
        <div style='padding-left:{$level*18}px;'>
          <p><a href="index.php{$group->edit_get}" class="tovar_on">{$group->name|escape}</a></p>
          Скидка: {$group->discount} % 
        </div>
      </div>
      <a href="index.php?section=Groups&delete_id={$group->group_id}&token={$Token}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" alt=""/></a>
    </div>
  </td>
</tr>				
{/foreach}

          </table>
          </form>
          <!-- Форма Товаров #End /-->
          {else}
            <div class="emptylist">Нет групп</div>
          {/if}

          {$PagesNavigation}
         
          <!-- Добавить / Сохранить /-->


	  </div>  
    </div>
  </div>	    
</div>
<!-- Content #End /--> 


