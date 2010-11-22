<?php /* Smarty version 2.6.25, created on 2010-11-21 21:30:00
         compiled from section.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'section.tpl', 72, false),)), $this); ?>
<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Sections&menu=<?php echo $this->_supers['get']['menu']; ?>
" class="on">cтраницы</a></li>
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
          <?php if ($this->_tpl_vars['Menu']): ?> → <a href='index.php?section=Sections&menu=<?php echo $this->_tpl_vars['Menu']->menu_id; ?>
'><?php echo $this->_tpl_vars['Menu']->name; ?>
</a><?php endif; ?>
           → <?php if ($this->_tpl_vars['Section']->section_id): ?><?php echo $this->_tpl_vars['Section']->name; ?>
<?php else: ?>Новая страница<?php endif; ?>
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
        <h1 id="headline"><?php if ($this->_tpl_vars['Section']->section_id): ?><?php echo $this->_tpl_vars['Section']->name; ?>
<?php else: ?>Новая страница<?php endif; ?></h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=section" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
      </div>

      <div id="cont_center">

     
        <div class="clear">&nbsp;</div>	  
        <?php if ($this->_tpl_vars['Error']): ?>
        <!-- Error #Begin /-->
        <div id="error_minh">
          <div id="error">
            <img src="./images/error.jpg" alt=""/><p><?php echo $this->_tpl_vars['Error']; ?>
</p>					
          </div>
        </div>
        <!-- Error #End /-->
        <?php endif; ?>
          



        <!-- Форма #Begin /-->

				<form name=section METHOD=POST>
					<div id="over">		
					<div id="over_left">	
							<table>
								<tr>
									<td class="model">Название</td>
									<td class="m_t"><p><input name="name" type="text" class="input3" value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Section']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' format='.+' notice='<?php echo $this->_tpl_vars['Lang']->ENTER_NAME; ?>
'/></p></td>
								</tr>
								<tr>
									<td class="model">Заголовок</td>
									<td class="m_t"><p><input name="header" type="text" class="input3" value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Section']->header)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'/></p></td>
								</tr>
								<tr>
									<td class="model">Меню</td>
									<td class="m_t"><p>
									
										<select name=menu_id class="select2">
                                         <?php $_from = $this->_tpl_vars['Menus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['menu']['iteration']++;
?>
                                            <?php if ($this->_tpl_vars['Section']->menu_id == $this->_tpl_vars['item']->menu_id): ?>
                                              <OPTION VALUE='<?php echo $this->_tpl_vars['item']->menu_id; ?>
' SELECTED><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</OPTION>
                                            <?php else: ?>
                                              <OPTION VALUE='<?php echo $this->_tpl_vars['item']->menu_id; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</OPTION>
                                            <?php endif; ?>
                                          <?php endforeach; endif; unset($_from); ?>
										</select>
										<nobr><input name=enabled type="checkbox" class="checkbox" <?php if ($this->_tpl_vars['Section']->enabled): ?>checked<?php endif; ?> value='1'/><span class="akt">Активна</span></nobr> &nbsp; &nbsp;
										
									</p>
									</td>
								</tr>
								<tr>
									<td class="model">Тип контента</td>
									<td class="m_t"><p>
									
										<select name=module_id class="select2">
                                         <?php $_from = $this->_tpl_vars['Modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['service_type'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['service_type']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['service_type']['iteration']++;
?>
                                            <?php if ($this->_tpl_vars['Section']->module_id == $this->_tpl_vars['item']->module_id): ?>
                                              <OPTION VALUE='<?php echo $this->_tpl_vars['item']->module_id; ?>
' SELECTED><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</OPTION>
                                            <?php else: ?>
                                              <OPTION VALUE='<?php echo $this->_tpl_vars['item']->module_id; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</OPTION>
                                            <?php endif; ?>
                                          <?php endforeach; endif; unset($_from); ?>
										</select>
									</p>
									</td>
								</tr>

							</table>

							
							<div class="gray_block">
								<table>
								<tr>
									<td class="model2">URL</td>
									<td class="m_t"><p><input name="url" type="text" class="input6" value='<?php echo $this->_tpl_vars['Section']->url; ?>
'/></p></td>
								</tr>
								<tr>
									<td class="model2">Meta Title</td>
									<td class="m_t"><p><input name="meta_title"  type="text" class="input6" value='<?php echo $this->_tpl_vars['Section']->meta_title; ?>
'/></p></td>
								</tr>
								<tr>
									<td class="model2">Meta Keywords</td>
									<td class="m_t"><p><input name="meta_keywords" type="text" class="input6" value='<?php echo $this->_tpl_vars['Section']->meta_keywords; ?>
'/></p></td>
								</tr>
								<tr>
									<td class="model2">Meta Description</td>
									<td class="m_t"><p><input name="meta_description" type="text" class="input6" value='<?php echo $this->_tpl_vars['Section']->meta_description; ?>
'/></p></td>
								</tr>
							</table>
							</div>
							<p><input type="submit" value="Сохранить" class="submit"/></p>
					</div>
					

			
				<div class="area">
					<span class="model4">Текст страницы</span>
					<p><textarea id="body" name="body" class="editor_big"><?php echo ((is_array($_tmp=$this->_tpl_vars['Section']->body)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea></p>
				  <p>
				  <INPUT NAME=section_id TYPE=HIDDEN VALUE='<?php echo $this->_tpl_vars['Section']->section_id; ?>
'>
				  <input type="submit" value="Сохранить" class="submitx"/></p>
				</div>


				</div>

			</div>
			</form>
			
	 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'tinymce_init.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['Settings']->meta_autofill): ?>
<!-- Autogenerating meta tags -->
<?php echo '
<script>

// Templates
var meta_title_template = \'%name\';
var meta_keywords_template = \'%name\';
var meta_description_template = \'%text\';

var item_form = document.section;

var meta_title_touched = true;
var meta_keywords_touched = true;
var meta_description_touched = true;
var url_touched = true;
	
// generating meta_title
function generate_title(template, name, text)
{
	return template.replace(\'%name\', name).replace(\'%text\', text).replace(/^(,\\s)+|\\s+$/g,"");
}	

// generating meta_keywords
function generate_keywords(template, name, text)
{	
	return template.replace(\'%name\', name).replace(\'%text\', text).replace(/^(,\\s)+|\\s+$/g,"");
}	

// generating meta_description
function generate_description(template, name,  text)
{	
	return template.replace(\'%name\', name).replace(\'%text\', text).replace(/^\\s+|\\s+$/g,"");
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

	var text = tinyMCE.get("body").getContent().replace(/(<([^>]+)>)/ig," ").replace(/(\\&nbsp;)/ig," ");

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
	url = url.replace(/[\\s]+/gi, \'_\');
	return url.replace(/[^0-9a-zа-я_]+/gi, \'\');
}

function autometageneration_init()
{ 
	tinyMCE.get("body").onChange.add(set_meta);
	tinyMCE.get("body").onKeyUp.add(set_meta);
	
	var name = item_form.header.value;

	var text = tinyMCE.get("body").getContent().replace(/(<([^>]+)>)/ig," ").replace(/(\\&nbsp;)/ig," ");

	if(item_form.meta_title.value == \'\' || item_form.meta_title.value == generate_title(meta_title_template, name, text))
		meta_title_touched=false;
	if(item_form.meta_keywords.value == \'\' || item_form.meta_keywords.value == generate_keywords(meta_keywords_template, name, text))
		meta_keywords_touched=false;
	if(item_form.meta_description.value == \'\' || item_form.meta_description.value == generate_description(meta_description_template, name, text))
		meta_description_touched=false;
	if(item_form.url.value == \'\' || item_form.url.value == generate_url(name))
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



myattachevent(item_form.url, \'change\', function(){url_touched = true});
myattachevent(item_form.meta_title, \'change\', function(){meta_title_touched = true});
myattachevent(item_form.meta_keywords, \'change\', function(){meta_keywords_touched = true});
myattachevent(item_form.meta_description, \'change\', function(){meta_description_touched = true});
myattachevent(item_form.header, \'keyup\',  set_meta);
myattachevent(item_form.header, \'change\', set_meta);


</script>
'; ?>

<!-- END Autogenerating meta tags -->
<?php endif; ?>
