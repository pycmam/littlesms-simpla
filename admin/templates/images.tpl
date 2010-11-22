<!-- Управление товарами /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Themes" class="off">темы</a></li>
      <li><a href="index.php?section=Templates" class="off">шаблоны</a></li>
      <li><a href="index.php?section=Styles" class="off">стили</a></li>
      <li><a href="index.php?section=Images" class="on">картинки</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          Картинки</a>
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
	    <img src="./images/icon_images.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Картинки</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=images" title="Помощь" class="thickbox">Помощь</a>
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

          {foreach from=$Images item=image}
		  <div class="theme">
		  
	
		  
		    <div class=image_thumbnail>
              <div style="display: table;  height:135px; width:135px; #position: relative; overflow: hidden;">
                <div style="#position: absolute; #top: 50%; #left: 50%; display: table-cell; vertical-align: middle; text-align:center;"><a href='../design/{$Settings->theme}/images/{$image->filename}'><img style="float:middle; #position: relative; #top: -50%; #left: -50%;" width={$image->scale_to_width} height={$image->scale_to_height} src='../design/{$Settings->theme}/images/{$image->filename}'></a></div> 
              </div>
            </div>
            
       
     
           <div style='float:left; width:130px; font-size:12px; color:#000000;'>{$image->filename}</div>
           <div style='float:left; text-align:right; width:5px;'><a href='index.php{$image->delete_url}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img align=absmiddle vspace=5 src='images/del_mini.gif'></a></div>
           <br>
           <div style='width:135; color:#b0b0b0;font-size:11px;'>
           {if 1 || $image->width && $image->height}
           {$image->width}&times;{$image->height}
           {/if}
           </div>
           
          
           
	      </div>
          {/foreach}
        </div>
      
        <div class="gray_block2"> 
	      <span class=model>Загрузить картинку</span>
	      <br>
	      <form method=post enctype='multipart/form-data'>
	      <input name=image type=file class="input3">
	      <span class=model>&nbsp;&nbsp;или </span>
	      <input type='hidden' name='token' value='{$Token}'>
	      <input name="image_url" value="{if $smarty.post.image_url}{$smarty.post.image_url}{else}http://{/if}" type=text class="input7">
	      <input  type=submit class=submit4 value='Загрузить'>
	      </form>
	    </div>
 
        
             
      </div>

    </div>
  </div>	    
</div>
<!-- Content #End /--> 