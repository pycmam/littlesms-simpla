<!-- Управление товарами /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Themes" class="on">темы</a></li>
      <li><a href="index.php?section=Templates" class="off">шаблоны</a></li>
      <li><a href="index.php?section=Styles" class="off">стили</a></li>
      <li><a href="index.php?section=Images" class="off">картинки</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          Темы</a>
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
	    <img src="./images/icon_design.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Темы</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=themes" title="Помощь" class="thickbox">Помощь</a>
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
        
	    <div class="gray_block2">        

          {foreach from=$Themes item=theme}
		  <div class="theme">
            <table>
              <tr>
                <td>			    
                  <img alt='{$theme->name}' title='{$theme->name}' src='../design/{$theme->dir}/images/thumbnail.jpg' class=theme_thumbnail>
                  <br><a href='index.php{$theme->download_url}' class="link_sv">скачать</a>
                </td>
                <td class="pad_l"  style='width:210px;'>
                    <span class="model5">{$theme->name|escape}</span>
                     <p class=tovar_on>
                      {if $Settings->theme == $theme->dir}
                       <img align=absmiddle src='images/check_on.jpg'> выбрана
                      {else}
                       <img align=absmiddle src='images/check_off.jpg'> <a href='index.php{$theme->activate_url}' class=tovar_on>выбрать</a>
                      {/if}
                    </p>
                    <br>
                    <p class=tovar_on>
                      {$theme->description}
                    </p>
       
                </td>
              </tr>
		    </table>							
	      </div>
          {/foreach}
        </div>
      
        
        <div class="gray_block2"> 
	      <span class=model5>Загрузить тему</span>
	      <br>
	      <form method=post enctype='multipart/form-data'>
	      <input name=theme type=file class="input3">
	      <span class=model5>&nbsp;&nbsp;или </span>
	      <input name="theme_url" value="{if $smarty.post.image_url}{$smarty.post.image_url}{else}http://{/if}" type=text class="input7">
	      <input type=hidden name=token value='{$Token}'>
	      <input  type=submit class=submit4 value='Загрузить'>
	      </form>
	    </div>
             
      </div>

    </div>
  </div>	    
</div>
<!-- Content #End /--> 