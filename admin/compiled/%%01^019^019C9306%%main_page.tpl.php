<?php /* Smarty version 2.6.25, created on 2010-11-21 21:29:52
         compiled from main_page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'main_page.tpl', 66, false),)), $this); ?>
<script type="text/javascript" language="JavaScript" src="http://reformal.ru/tab.js?title=Simpla&domain=simpla&color=ff367d&align=left&charset=utf-8"></script>


<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Storefront" class="on">simpla</a></li>
  </ul>
  <!-- /Вкладки /-->

</div>	


<!-- Content #Begin /-->
<div id="content">
  <div id="cont_border">
    <div id="cont">
     
      <div id="cont_top">
        <!-- Иконка раздела /--> 
	    <img src="./images/icon_main.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Главная страница</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=adminpanel" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
      </div>

      <div id="cont_center">
     
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
          
        <div class="clear">&nbsp;</div>	
        
        
        
					<div id="over">		
					<div id="main_left">	

           
                    
							<div class="main_yellow">
                    <span class="model6">Новые заказы</span>
                    <?php if ($this->_tpl_vars['Orders']): ?>
                               <table width=100%>
								  <?php $_from = $this->_tpl_vars['Orders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['order']):
?>
									<tr>
										<td class="tovar_on">
										  <a class="tovar_on" href="index.php?section=Order&order_id=<?php echo $this->_tpl_vars['order']->order_id; ?>
&token=<?php echo $this->_tpl_vars['Token']; ?>
">№<?php echo $this->_tpl_vars['order']->order_id; ?>
</a>
										</td>
										<td>
										  <a class="tovar_on" href="index.php?section=Order&order_id=<?php echo $this->_tpl_vars['order']->order_id; ?>
&token=<?php echo $this->_tpl_vars['Token']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['order']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
										  <br><br>
										 </td>										
										<td class="tovar_on">
										  <?php echo ((is_array($_tmp=$this->_tpl_vars['order']->total_amount)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;<?php echo $this->_tpl_vars['MainCurrency']->sign; ?>

										 </td>										
									</tr>
								  <?php endforeach; endif; unset($_from); ?>
								</table>
					<?php else: ?>
					 <p class="tovar_on">Нет новых заказов</p>
					<?php endif; ?>
							</div>
							
            

					</div>
					
					
					<div id="main_right">
					

            <div class="main_gray">
              
              <span class=model><img src="./images/add.jpg" alt="" class="fl"/> Добавьте <a href='index.php?section=Product&token=<?php echo $this->_tpl_vars['Token']; ?>
' style='color:black;'>товар</a>, <a href='index.php?section=Section&token=<?php echo $this->_tpl_vars['Token']; ?>
' style='color:black;'>страницу</a>, <a href='index.php?section=NewsItem&token=<?php echo $this->_tpl_vars['Token']; ?>
' style='color:black;'>новость</a>, <a href='index.php?section=Article&token=<?php echo $this->_tpl_vars['Token']; ?>
' style='color:black;'>статью</a></span>
  
            </div>
            
            <span class="model" style='padding-left:32px;'>Последние измененные товары</span>
            <br><br>
		
            <table>
            
                            <?php $_from = $this->_tpl_vars['Products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
              <tr>
                <td>
                  <div class="list_left">
                    <a href="index.php<?php echo $this->_tpl_vars['item']->set_enabled_get; ?>
" class="fl"><img src="./images/<?php if ($this->_tpl_vars['item']->enabled): ?>lamp_on.jpg<?php else: ?>lamp_off.jpg<?php endif; ?>" alt="Активность" title="Активность"/></a>
                    <div class="flxc">
                      <p>
                        <a href="index.php<?php echo $this->_tpl_vars['item']->edit_get; ?>
" class="<?php if ($this->_tpl_vars['item']->enabled): ?>tovar_on<?php else: ?>tovar_off<?php endif; ?>"><?php if ($this->_tpl_vars['item']->category_single_name): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->category_single_name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->category_name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['item']->brand)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['item']->model)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
                      </p>
                      <p>
                        <?php if ($this->_tpl_vars['item']->enabled): ?>
                        <a href="http://<?php echo $this->_tpl_vars['root_url']; ?>
/products/<?php echo $this->_tpl_vars['item']->url; ?>
" class="tovar_min">http://<?php echo $this->_tpl_vars['root_url']; ?>
/products/<?php echo $this->_tpl_vars['item']->url; ?>
</a>
                        <?php else: ?>
                        <span class="tovar_min">http://<?php echo $this->_tpl_vars['root_url']; ?>
/products/<?php echo $this->_tpl_vars['item']->url; ?>
</span>
                        <?php endif; ?>
                      </p>
                    </div>
			      </div>
                </td>
                <td>
                  <div class="list_right">
                    <a href="index.php<?php echo $this->_tpl_vars['item']->set_hit_get; ?>
" class="fl"><img src="./images/<?php if ($this->_tpl_vars['item']->hit): ?>star_on.jpg<?php else: ?>star_off.jpg<?php endif; ?>" alt="Хит" title="Хит"/></a><?php if ($this->_tpl_vars['item']->comments_num): ?><a href="index.php?section=Comments&keyword=<?php echo ((is_array($_tmp=$this->_tpl_vars['item']->category_single_name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
+<?php echo ((is_array($_tmp=$this->_tpl_vars['item']->category_name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
+<?php echo ((is_array($_tmp=$this->_tpl_vars['item']->brand)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
+<?php echo ((is_array($_tmp=$this->_tpl_vars['item']->model)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="fl"><img alt='<?php echo $this->_tpl_vars['item']->comments_num; ?>
 комментариев' title='<?php echo $this->_tpl_vars['item']->comments_num; ?>
 комментариев' src="./images/q_on.jpg"/></a><?php else: ?><img alt='Нет комментариев' title='Нет комментариев' class=fl src="./images/q_off.jpg"/><?php endif; ?>

             
                  </div>
                </td>
              </tr>
              <?php endforeach; endif; unset($_from); ?>
                          </table>
							
					</div>
				</div>
   
        
          
          
	  </div>  
    </div>
  </div>	    
</div>
<!-- Content #End /--> 

