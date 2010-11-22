<?php /* Smarty version 2.6.25, created on 2010-11-21 21:29:31
         compiled from static_page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'static_page.tpl', 7, false),)), $this); ?>
<div id="page_title">      
  <h1  tooltip='section' section_id='<?php echo $this->_tpl_vars['page']->section_id; ?>
' class="float_left"><?php echo ((is_array($_tmp=$this->_tpl_vars['page']->header)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>
  <!-- Хлебные крошки /-->
  <div id="path">
    <a href="./">Главная</a> → <?php echo ((is_array($_tmp=$this->_tpl_vars['page']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

  </div>
  <!-- Хлебные крошки #End /-->
</div>      

<p>
<?php echo $this->_tpl_vars['page']->body; ?>

</p>
<!-- Добавление в закладки -->
  <br>
  <p style='text-align:right'>
  <noindex>
  <script>
  var url = location.href;
  var title = document.title;
  var tags = '';
  var desc = '';
  var url2 = location.href;
  m = document.getElementsByTagName('meta'); 
  for(var i in m)
    if(m[i].name == 'keywords') tags = m[i].content;
      else if(m[i].name == 'description') desc = m[i].content;
  document.write('<a rel="nofollow" href="http://www.memori.ru/link/?sm=1&u_data[url]='+url+'&u_data[name]='+title+'&u_data[descr]='+desc+'" title="Добавить закладку в Memori"><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/memori.gif" alt="Добавить закладку в Memori" border="0"></a>');
  document.write('<a rel="nofollow" href="http://www.google.com/bookmarks/mark?op=add&bkmk='+url+'&title='+title+'&labels='+tags+'&annotation='+desc+'" title="Добавить закладку в Google"><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/google.gif" alt="Добавить закладку в Google" border="0"></a>');
  document.write('<a rel="nofollow" href="http://www.bobrdobr.ru/addext.html?url='+url+'&title='+title+'&desc='+desc+'&tags='+tags+'" title="Добавить закладку в Бобрдобр"><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/bobrdobr.gif" alt="Добавить закладку в Бобрдобр" border="0"></a>');
  document.write('<a rel="nofollow" href="http://twitter.com/home?status='+title+' '+url+'" title="Опубликовать в Твиттер"><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/twitter.gif" alt="Опубликовать в Твиттер" border="0"></a>');
  </script>
  </noindex>
  </p>
<!-- Добавление в закладки #End -->