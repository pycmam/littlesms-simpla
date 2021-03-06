<script src="js/codemirror/js/codemirror.js" type="text/javascript"></script>


<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Themes" class="off">темы</a></li>
      <li><a href="index.php?section=Templates" class="on">шаблоны</a></li>
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
          Шаблоны</a>
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
        <h1 id="headline">Шаблоны</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
               
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=templates" title="Помощь" class="thickbox">Помощь</a>
        </div>

        <!-- /Помощь /-->
      </div>

      <div id="cont_center">
      
      
        <!-- Левое меню /-->
        <div id="cont_left">
        
          <ul>
            {foreach from=$Templates item=template}
            <li class="filename">
              <a href="index.php{$template->edit_url}">{$template->name}</a>
              <br><span class=filename_small>{$template->filename}</span>
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

          <!-- Форма товаров #Begin /-->
          <form name='template' method="post">
            <input type=hidden name=filename value='{$CurrentTemplate->filename}'>
            <input type=hidden name=token value='{$Token}'>

            <div style="background-color:#f0f0f0; border: 1px solid #e0e0e0; padding: 3px; text-align:right;">
              <span class=tovar_on style='float:left'>{$CurrentTemplate->filename}</span>
             <input type=submit value='Сохранить' class=submit4>
            <div style='background-color:#ffffff; border: 1px solid #e0e0e0; padding: 0px;width:700px; height:1000px;'>
              <textarea id="code" name=content  style='word-wrap:normal;width:700px; height:1000px;'>{$Content|escape}</textarea>
            </div>      
            </div>
  

        </div>
	    <!-- Right side #End/-->
 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 

{literal}
<script type="text/javascript">
 
  var editor = CodeMirror.fromTextArea('code', {
    height: "350px",
    parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "parsehtmlmixed.js"],
    stylesheet: ["js/codemirror/css/xmlcolors.css", "js/codemirror/css/jscolors.css", "js/codemirror/css/csscolors.css"],
    path: "js/codemirror/js/",
    dumbTabs: true,
    saveFunction: function() { 
      window.document.template.submit();
    },
    textWrapping: true
  });
</script>
{/literal}