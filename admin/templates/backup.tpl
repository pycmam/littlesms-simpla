<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Import" class="off">импорт</a></li>
    <li><a href="index.php?section=Export" class="off">экспорт</a></li>
    <li><a href="index.php?section=Backup" class="on">бекап</a></li>

  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> → Бекап
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
	    <img src="./images/icon_secure.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Бекап</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
			<a href="usermanual.html?height=450&width=700&scrollto=backup" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
		
		 <!-- Помощь2 /-->
        <div class="help2">
			<a href="index.php?section=Backup&action=create_backup&token={$Token}" class="fl"><img src="./images/add.jpg" alt="" class="fl"/>Создать бекап</a>  
        </div>
        <!-- /Помощь2 /-->
        
        {*
		<!-- Помощь2 /-->
        <div class="help3">
			<a href="index.php?section=Backup&action=create_backup" class="fl"><img src="./images/add.jpg" alt="" class="fl"/>создать бекап</a>  
        </div>
        <!-- /Помощь2 /-->
        *}

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
          {if $Message}
          <!-- Error #Begin /-->
          <div id="message_minh">
            <div id="message">
              <img src="./images/info.png" alt=""/><p>{$Message}</p>					
            </div>
          </div>
          <!-- Error #End /-->
          {/if}
          
                   
          <div class="clear">&nbsp;</div>	

          {if $Backups}

          <!-- Форма товаров #Begin /-->
          <form name='products' method="post">
            <table id="list2">
            
              {* Список разделов *}
              {foreach item=backup from=$Backups}
              <tr>
                <td>
                  <div class="list_left">
                    <a href="index.php{$backup->restore_url}" class="fl"><img src="./images/restore_icon.jpg" title="Восстановить эту копию" alt="Восстановить эту копию"/></a>
                    <div class="flxc">
                      <p>
                        <a href="backups/{$backup->file}" class="tovar_on">{$backup->file}</a>
                      </p>
                      <p>
                        {if $backup->size>1024*1024}{$backup->size/1024/1024|round:'2'} МБ{else}{$backup->size/1024|round:'2'} КБ{/if}
                      </p>
                    </div>
			      </div>
                  <a href="index.php{$backup->delete_url}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" alt="Удалить эту копию" title="Удалить эту копию"/></a>
                </td>
              </tr>
              {/foreach}
              {* /Список разделов *}
            </table>
            </form>
            <!-- Форма Товаров #End /-->
            {else}
              <div class="emptylist">Нет сохраненных копий</div>
            {/if}
 
 
 
    </div>
          <div class=gray_block2>
	      <span class=model>Загрузить с компьютера</span>
	      <br>
	      <form method=post enctype='multipart/form-data'>
	      <input name=backup type=file class="input3">
	      <input type=hidden name=token value='{$Token}'>
	      <input  type=submit class=submit4 value='Загрузить'>
	      </form>
          </div>            
        
  </div>	      
</div>
<!-- Content #End /--> 

