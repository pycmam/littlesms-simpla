<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Sections&menu={$smarty.get.menu}" class="on">cтраницы</a></li>
      <li><a href="index.php?section=NewsLine" class="off">новости</a></li>
      <li><a href="index.php?section=Articles" class="off">статьи</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a>
          {if $Menu} → <a href='index.php?section=Sections&menu={$Menu->menu_id}'>{$Menu->name}</a>{/if}
           → {if $Section->section_id}{$Section->name}{else}Новая страница{/if}
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
        <h1 id="headline">{if $Section->section_id}{$Section->name}{else}Новая страница{/if}</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=section" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
      </div>

      <div id="cont_center">

     
        <div class="clear">&nbsp;</div>	  
        {if $Error}
        <!-- Error #Begin /-->
        <div id="error_minh">
          <div id="error">
            <img src="./images/error.jpg" alt=""/><p>{$Error}</p>					
          </div>
        </div>
        <!-- Error #End /-->
        {/if}
          



        <!-- Форма #Begin /-->

				<form name=section METHOD=POST>
					<div id="over">		
					<div id="over_left">	
							<table>
								<tr>
									<td class="model">Название</td>
									<td class="m_t"><p><input name="name" type="text" class="input3" value='{$Section->name|escape}' format='.+' notice='{$Lang->ENTER_NAME}'/></p></td>
								</tr>
								<tr>
									<td class="model">Заголовок</td>
									<td class="m_t"><p><input name="header" type="text" class="input3" value='{$Section->header|escape}'/></p></td>
								</tr>
								<tr>
									<td class="model">Меню</td>
									<td class="m_t"><p>
									
										<select name=menu_id class="select2">
                                         {foreach name=menu key=key item=item from=$Menus}
                                            {if $Section->menu_id EQ $item->menu_id}
                                              <OPTION VALUE='{$item->menu_id}' SELECTED>{$item->name|escape}</OPTION>
                                            {else}
                                              <OPTION VALUE='{$item->menu_id}'>{$item->name|escape}</OPTION>
                                            {/if}
                                          {/foreach}
										</select>
										<nobr><input name=enabled type="checkbox" class="checkbox" {if $Section->enabled}checked{/if} value='1'/><span class="akt">Активна</span></nobr> &nbsp; &nbsp;
										
									</p>
									</td>
								</tr>
								<tr>
									<td class="model">Тип контента</td>
									<td class="m_t"><p>
									
										<select name=module_id class="select2">
                                         {foreach name=service_type key=key item=item from=$Modules}
                                            {if $Section->module_id EQ $item->module_id}
                                              <OPTION VALUE='{$item->module_id}' SELECTED>{$item->name|escape}</OPTION>
                                            {else}
                                              <OPTION VALUE='{$item->module_id}'>{$item->name|escape}</OPTION>
                                            {/if}
                                          {/foreach}
										</select>
									</p>
									</td>
								</tr>

							</table>

							
							<div class="gray_block">
								<table>
								<tr>
									<td class="model2">URL</td>
									<td class="m_t"><p><input name="url" type="text" class="input6" value='{$Section->url}'/></p></td>
								</tr>
								<tr>
									<td class="model2">Meta Title</td>
									<td class="m_t"><p><input name="meta_title"  type="text" class="input6" value='{$Section->meta_title}'/></p></td>
								</tr>
								<tr>
									<td class="model2">Meta Keywords</td>
									<td class="m_t"><p><input name="meta_keywords" type="text" class="input6" value='{$Section->meta_keywords}'/></p></td>
								</tr>
								<tr>
									<td class="model2">Meta Description</td>
									<td class="m_t"><p><input name="meta_description" type="text" class="input6" value='{$Section->meta_description}'/></p></td>
								</tr>
							</table>
							</div>
							<p><input type="submit" value="Сохранить" class="submit"/></p>
					</div>
					

			
				<div class="area">
					<span class="model4">Текст страницы</span>
					<p><textarea id="body" name="body" class="editor_big">{$Section->body|escape}</textarea></p>
				  <p>
				  <INPUT NAME=section_id TYPE=HIDDEN VALUE='{$Section->section_id}'>
				  <input type="submit" value="Сохранить" class="submitx"/></p>
				</div>


				</div>

			</div>
			</form>
			
	 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 

{include file='tinymce_init.tpl'}

{if $Settings->meta_autofill}
<!-- Autogenerating meta tags -->
{literal}
<script>

// Templates
var meta_title_template = '%name';
var meta_keywords_template = '%name';
var meta_description_template = '%text';

var item_form = document.section;

var meta_title_touched = true;
var meta_keywords_touched = true;
var meta_description_touched = true;
var url_touched = true;
	
// generating meta_title
function generate_title(template, name, text)
{
	return template.replace('%name', name).replace('%text', text).replace(/^(,\s)+|\s+$/g,"");
}	

// generating meta_keywords
function generate_keywords(template, name, text)
{	
	return template.replace('%name', name).replace('%text', text).replace(/^(,\s)+|\s+$/g,"");
}	

// generating meta_description
function generate_description(template, name,  text)
{	
	return template.replace('%name', name).replace('%text', text).replace(/^\s+|\s+$/g,"");
}	

// generating meta_title
function generate_url(name)
{
	url = name;
	return translit(url);
}	


// sel all metatags
function set_meta()
{	
	var name = item_form.header.value;

	var text = tinyMCE.get("body").getContent().replace(/(<([^>]+)>)/ig," ").replace(/(\&nbsp;)/ig," ");

	// Meta Title
	if(!meta_title_touched)
		item_form.meta_title.value = generate_title(meta_title_template, name, text);		

	// Meta Keywords
	if(!meta_keywords_touched)
		item_form.meta_keywords.value = generate_keywords(meta_keywords_template, name, text);		

	// Meta Description
	if(!meta_description_touched)
		item_form.meta_description.value = generate_description(meta_description_template, name, text);		

	// Url
	if(!url_touched)
		item_form.url.value = generate_url(name);		

}

function translit(url){
	url = url.replace(/[\s]+/gi, '_');
	return url.replace(/[^0-9a-zа-я_]+/gi, '');
}

function autometageneration_init()
{ 
	tinyMCE.get("body").onChange.add(set_meta);
	tinyMCE.get("body").onKeyUp.add(set_meta);
	
	var name = item_form.header.value;

	var text = tinyMCE.get("body").getContent().replace(/(<([^>]+)>)/ig," ").replace(/(\&nbsp;)/ig," ");

	if(item_form.meta_title.value == '' || item_form.meta_title.value == generate_title(meta_title_template, name, text))
		meta_title_touched=false;
	if(item_form.meta_keywords.value == '' || item_form.meta_keywords.value == generate_keywords(meta_keywords_template, name, text))
		meta_keywords_touched=false;
	if(item_form.meta_description.value == '' || item_form.meta_description.value == generate_description(meta_description_template, name, text))
		meta_description_touched=false;
	if(item_form.url.value == '' || item_form.url.value == generate_url(name))
		url_touched=false;
}

// Attach events
function myattachevent(target, eventName, func)
{
    if ( target.addEventListener )
        target.addEventListener(eventName, func, false);
    else if ( target.attachEvent )
        target.attachEvent("on" + eventName, func);
    else
        target["on" + eventName] = func;
}

if (window.attachEvent) {
	window.attachEvent("onload", function(){setTimeout("autometageneration_init();", 1000)});
} else if (window.addEventListener) {
	window.addEventListener("DOMContentLoaded", autometageneration_init, false);
} else {
	document.addEventListener("DOMContentLoaded", autometageneration_init, false);
}



myattachevent(item_form.url, 'change', function(){url_touched = true});
myattachevent(item_form.meta_title, 'change', function(){meta_title_touched = true});
myattachevent(item_form.meta_keywords, 'change', function(){meta_keywords_touched = true});
myattachevent(item_form.meta_description, 'change', function(){meta_description_touched = true});
myattachevent(item_form.header, 'keyup',  set_meta);
myattachevent(item_form.header, 'change', set_meta);


</script>
{/literal}
<!-- END Autogenerating meta tags -->
{/if}

