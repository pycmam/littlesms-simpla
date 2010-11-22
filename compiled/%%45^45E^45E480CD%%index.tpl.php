<?php /* Smarty version 2.6.25, created on 2010-11-21 21:29:31
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'index.tpl', 11, false),array('modifier', 'string_format', 'index.tpl', 113, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <title><?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</title>
    <base href="http://<?php echo $this->_tpl_vars['root_url']; ?>
/">
    <meta name="description" content="<?php echo ((is_array($_tmp=$this->_tpl_vars['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
    <meta name="keywords" content="<?php echo ((is_array($_tmp=$this->_tpl_vars['keywords'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
    <meta http-equiv="Content-Language" content="ru" />
    <meta name="robots" content="all" />
    <link rel="stylesheet" type="text/css" href="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/css/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/css/forms.css" media="screen" />
    
    <link rel="icon" href="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="design/<?php echo ((is_array($_tmp=$this->_tpl_vars['settings']->theme)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
/images/favicon.ico" type="image/x-icon">
        
        <?php if ($this->_supers['session']['admin'] == 'admin'): ?>
    <script src="js/admintooltip/php/admintooltip.php" language="JavaScript" type="text/javascript"></script>    
    <link href="js/admintooltip/css/admintooltip.css" rel="stylesheet" type="text/css" /> 
    <?php endif; ?>
       
</head>
<body>
<div id="wrap_top_bg"><div id="wrap_bottom_bg">

<!-- Вся страница /-->
<div id="wrap">

    <!-- Шапка /-->
    <div id="header"> 
            
        <!-- Логотип /-->   
        <div id="logo"> 
             <a href="./" title="Simpla" class="image"></a><a href="./" title="Simpla" class="link"></a>          
        </div>
        <!-- Логотип #End /-->
                
        <!-- Основная часть заголовка /-->
        <div id="header_menu">
        
            <!-- Верхняя панель заголовка /-->
            <div id="top_panel">
            
                <!-- Вход пользователя /-->  
                <div id="top_panel_left">
                
                <!-- Если пользователь не залогинен /-->  
                <?php if (! $this->_tpl_vars['user']): ?>
                   <a href="login/" id="user_login_link" class="black_link" >вход</a>
                   | <a href="registration/" class="black_link">регистрация</a>                
                <!-- Если пользователь не залогинен /-->  
                <?php else: ?>
                   <a href="account/" class="black_link"><?php echo ((is_array($_tmp=$this->_tpl_vars['user']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><?php if ($this->_tpl_vars['user']->discount > 0): ?>,
                   ваша скидка <?php echo $this->_tpl_vars['user']->discount; ?>
%<?php endif; ?> 
                   <a href="logout/" class="black_link" id="user_exit_link">выйти</a>
                <?php endif; ?>
                
                </div>
                <!-- Вход пользователя #End /-->  
                
                <!-- Выбор валюты /--> 
                <div id="top_panel_right">
                
                    <form name=currency method=post>
                        <p>валюта магазина:
                        <select  tooltip=currency name="currency_id" onchange="window.document.currency.submit();">
                            <?php $_from = $this->_tpl_vars['currencies']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>                            
                            <option value="<?php echo $this->_tpl_vars['c']->currency_id; ?>
" <?php if ($this->_tpl_vars['c']->currency_id == $this->_tpl_vars['currency']->currency_id): ?>selected<?php endif; ?>>
                                 <?php echo ((is_array($_tmp=$this->_tpl_vars['c']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

                            </option>
                            <?php endforeach; endif; unset($_from); ?>
                        </select>
                        </p>
                    </form>
                    
                </div>
                <!-- Выбор валюты #End /-->                 
                
            </div>
            <!-- Верхняя панель заголовка #End /-->
            
            <!-- Верхнее меню /-->
            <ul id="top_header_menu">
                <?php $_from = $this->_tpl_vars['sections']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sections'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sections']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['s']):
        $this->_foreach['sections']['iteration']++;
?>
                <li>
                  <?php if ($this->_tpl_vars['section']->section_id == $this->_tpl_vars['s']->section_id): ?>                  
                  <span tooltip='section' section_id='<?php echo $this->_tpl_vars['s']->section_id; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['s']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
                  <?php else: ?>
                  <a tooltip='section' section_id='<?php echo $this->_tpl_vars['s']->section_id; ?>
' href='sections/<?php echo $this->_tpl_vars['s']->url; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['s']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
                  <?php endif; ?>
                </li>
                <?php endforeach; endif; unset($_from); ?>                
            </ul>
            <!-- Верхнее меню #end /-->     
                
            <!-- Информер корзины /-->  
            <?php if ($this->_tpl_vars['cart_products_num']): ?>
                <?php $this->assign('ptext', 'товаров'); ?>
                                <?php $this->assign('p1', $this->_tpl_vars['cart_products_num']%10); ?>
                <?php $this->assign('p2', $this->_tpl_vars['cart_products_num']%100); ?>
                <?php if ($this->_tpl_vars['p1'] == 1 && ! ( $this->_tpl_vars['p2'] >= 11 && $this->_tpl_vars['p2'] <= 19 )): ?><?php $this->assign('ptext', 'товар'); ?><?php endif; ?>
                <?php if ($this->_tpl_vars['p1'] >= 2 && $this->_tpl_vars['p1'] <= 4 && ! ( $this->_tpl_vars['p2'] >= 11 && $this->_tpl_vars['p2'] <= 19 )): ?><?php $this->assign('ptext', 'товара'); ?><?php endif; ?>
                <p id="cart_info">В <a href="cart/" class="black_link" onclick="document.cookie='from='+location.href+';path=/';">корзинe</a> <?php echo $this->_tpl_vars['cart_products_num']; ?>
 <?php echo $this->_tpl_vars['ptext']; ?>

                на сумму <?php echo ((is_array($_tmp=$this->_tpl_vars['cart_total_price']*$this->_tpl_vars['currency']->rate_from/$this->_tpl_vars['currency']->rate_to)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['currency']->sign)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</p>
            <?php else: ?>
                <p id="cart_info">Корзина пуста</p>
            <?php endif; ?>
            <!-- Информер корзины #End /-->         
            
            
        </div>  
    </div>
    <!-- Шапка #End /-->
    
    
    <!-- Основная часть страницы /-->
    <div id="main_part">
    
        <!-- Левая часть страницы /-->
        <div id="left_side">
        
            <!-- Меню каталога /-->
            <div id="catalog_menu">
			<?php if (!function_exists('smarty_fun_categories_tree')) { function smarty_fun_categories_tree(&$smarty, $params) { $_fun_tpl_vars=$smarty->_tpl_vars; $smarty->assign($params);  ?>
			<?php if ($smarty->_tpl_vars['categories']): ?>
			<ul class="catalog_menu">
			<?php $_from = $smarty->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $smarty->_tpl_vars['c']):
?>
				<?php if ($smarty->_tpl_vars['category']->category_id != $smarty->_tpl_vars['c']->category_id): ?>
				<li><a href='catalog/<?php echo $smarty->_tpl_vars['c']->url; ?>
' tooltip='category' category_id='<?php echo $smarty->_tpl_vars['c']->category_id; ?>
'><?php echo $smarty->_tpl_vars['c']->name; ?>
</a></li>
				<?php else: ?>
				<li><span tooltip='category' category_id='<?php echo $smarty->_tpl_vars['c']->category_id; ?>
'><?php echo $smarty->_tpl_vars['c']->name; ?>
</span></li>
				<?php endif; ?>
								<?php smarty_fun_categories_tree($smarty, array('categories'=>$smarty->_tpl_vars['c']->subcategories));  ?>        
							<?php endforeach; endif; unset($_from); ?>  
			</ul>
			<?php endif; ?>    
			<?php  $smarty->_tpl_vars=$_fun_tpl_vars; }} smarty_fun_categories_tree($this, array('categories'=>$this->_tpl_vars['categories']));  ?>
            </div>
            <!-- Меню каталога #End /-->

            <?php if ($this->_tpl_vars['all_brands']): ?>
            <!-- Список брендов /-->
            <div id="brands_menu">
				<?php $_from = $this->_tpl_vars['all_brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['brands'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['brands']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['b']):
        $this->_foreach['brands']['iteration']++;
?>
                 <a href='brands/<?php echo $this->_tpl_vars['b']->url; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['b']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
                <?php endforeach; endif; unset($_from); ?>  
            </div>
            <!-- Список брендов #End /-->
            <?php endif; ?>
            
            <!-- Поиск /-->
            <div id="search">
                <form name=search method=get action="index.php"  onsubmit="window.location='http://<?php echo $this->_tpl_vars['root_url']; ?>
/search/'+encodeURIComponent(encodeURIComponent(this.keyword.value)); return false;">
                    <input type=hidden name=module value=Search>
                    <p><input type="text" name=keyword value="<?php echo ((is_array($_tmp=$this->_tpl_vars['keyword'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="search_input_text"/><input type="submit" value="Найти" class="search_input_submit"/></p>
                </form>
            </div>
            <!-- Поиск #End /-->

                                    
            <?php if ($this->_tpl_vars['news']): ?>
            <!-- Новости /-->
            <ul id="news">
            <?php $_from = $this->_tpl_vars['news']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['news'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['news']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['n']):
        $this->_foreach['news']['iteration']++;
?>
                <li>
                    <p class="news_date"><?php echo $this->_tpl_vars['n']->date; ?>
</p>
                    <p tooltip="news" news_id="<?php echo $this->_tpl_vars['n']->news_id; ?>
"><a href="news/<?php echo $this->_tpl_vars['n']->url; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['n']->header)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></p>
                    <p class="news_annotation">
                        <?php echo $this->_tpl_vars['n']->annotation; ?>

                    </p>
                </li>
            <?php endforeach; endif; unset($_from); ?>         
                <li><a href="news/">архив новостей →</a></li>
            </ul>
            <!-- Новости #End /-->
            <?php endif; ?>
            
        </div>
        <!-- Левая часть страницы #End /-->
        
        
        <!-- Правая часть страницы #Begin /-->
        <div id="right_side">
                    
            <?php echo $this->_tpl_vars['content']; ?>

            
        </div>
        <!-- Правая часть страницы #End /-->
    </div>
    <!-- Основная часть страницы #End /-->
    
    <!-- Подвал #Begin /-->
    <div id="footer">
        <ul id="syst">
            <li><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/visa.jpg" alt=""/></li>
            <li><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/master_card.jpg" alt=""/></li>
            <li><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/web_money.jpg" alt=""/></li>
        </ul>
        <?php echo $this->_tpl_vars['settings']->counters; ?>

        <p id="copyright">© Интернет-магазин 2005-2010</p>
    </div>
    <!-- Подвал #End /-->
    
</div>
<!-- Вся страница #End /-->

</div></div>
</body>
</html>