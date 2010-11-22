<?php /* Smarty version 2.6.25, created on 2010-11-21 21:30:04
         compiled from setup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'setup.tpl', 68, false),)), $this); ?>
<!-- Управление статьями /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Setup" class="on">параметры</a></li>
      <li><a href="index.php?section=Currency" class="off">валюты</a></li>
      <li><a href="index.php?section=DeliveryMethods" class="off">доставка</a></li>
      <li><a href="index.php?section=PaymentMethods" class="off">оплата</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> → Параметры
          </a>
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
	    <img src="./images/icon_setup.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Параметры</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=settings" title="Помощь" class="thickbox">Помощь</a>  
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
         
        <!-- Форма товара #Begin /-->

				<FORM  METHOD=POST enctype='multipart/form-data'>
					<div id="over">		
							<table>
								<tr>
									<td class="td_padding"><?php echo $this->_tpl_vars['Lang']->SITE_NAME; ?>
</td>
									<td class="td_padding"><p><input type="text" class="input3" name=site_name value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->site_name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'/></p></td>
								</tr>
								<tr>
									<td class="td_padding"><?php echo $this->_tpl_vars['Lang']->COMPANY_NAME; ?>
</td>
									<td class="td_padding"><p><input type="text" class="input3" name=company_name value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->company_name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' /></p></td>
								</tr>
								<tr>
									<td class="td_padding"><?php echo $this->_tpl_vars['Lang']->ADMIN_EMAIL; ?>
</td>
									<td class="td_padding"><p><input type="text" class="input3" name=admin_email value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->admin_email)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' /></p></td>
								</tr>
								<tr>
									<td class="td_padding">Обратный email уведомлений</td>
									<td class="td_padding"><p><input type="text" class="input3" name=notify_from_email value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->notify_from_email)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' /></p></td>
								</tr>
								<tr>
									<td class="td_padding"><?php echo $this->_tpl_vars['Lang']->MAIN_PAGE; ?>
</td>
									<td class="td_padding"><p>
										
									    Полная версия:<br>
										<select name=main_section style='font-size:17px;'>
                                          <?php $_from = $this->_tpl_vars['Sections']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sections'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sections']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['section']):
        $this->_foreach['sections']['iteration']++;
?>
                                          	<?php if ($this->_tpl_vars['section']->menu_id == 2): ?>
                                            <option value='<?php echo $this->_tpl_vars['section']->url; ?>
' <?php if ($this->_tpl_vars['Settings']->main_section == $this->_tpl_vars['section']->url): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['section']->name; ?>
</option>
                                            <?php endif; ?>
                                          <?php endforeach; endif; unset($_from); ?>
										</select>
										<br>
									    Мобильная версия:<br>
										<select name=main_mobile_section style='font-size:17px;'>
                                          <?php $_from = $this->_tpl_vars['Sections']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sections'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sections']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['section']):
        $this->_foreach['sections']['iteration']++;
?>
                                          	<?php if ($this->_tpl_vars['section']->menu_id == 3): ?>
                                            <option value='<?php echo $this->_tpl_vars['section']->url; ?>
' <?php if ($this->_tpl_vars['Settings']->main_mobile_section == $this->_tpl_vars['section']->url): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['section']->name; ?>
</option>
                                            <?php endif; ?>
                                          <?php endforeach; endif; unset($_from); ?>
										</select>
										
									</p>
									</td>
								</tr>
								<tr>
									<td class="td_padding"><?php echo $this->_tpl_vars['Lang']->COUNTERS_CODE; ?>
</td>
									<td class="td_padding"><p><textarea style='width:350px;height:150px;' name=counters><?php echo $this->_tpl_vars['Settings']->counters; ?>
</textarea></p></td>
								</tr>

								<tr>
									<td class="td_padding">Размер иконки товара</td>
									<td class="td_padding"><p>
									                         <input type="text" class="input4" name=product_thumbnail_width value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->product_thumbnail_width)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' />
									                         &times;
									                         <input type="text" class="input4" name=product_thumbnail_height value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->product_thumbnail_height)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' />
									                         пикселей
									                       </p></td>
								</tr>
								<tr>
									<td class="td_padding">Размер картинки товара</td>
									<td class="td_padding"><p>
									                        <input type="text" class="input4" name=product_image_width value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->product_image_width)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' />
								                             &times;	 
									                        <input type="text" class="input4" name=product_image_height value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->product_image_height)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' />
									                        пикселей
									                       </p></td>
									                        
								</tr>
								<tr>
									<td class="td_padding">Доп. картинки товара</td>
									<td class="td_padding"><p>
									                        <input type="text" class="input4" name=product_adimage_width value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->product_adimage_width)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' />
								                             &times;	 
									                        <input type="text" class="input4" name=product_adimage_height value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->product_adimage_height)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' />
									                        пикселей
									                       </p></td>
									                        
								</tr>
								<tr>
									<td class="td_padding">Качество изображения</td>
									<td class="td_padding"><p><input type="text" class="input4" name=image_quality value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->image_quality)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' /> (1-100)</p></td>
								</tr>
								
								<tr>
									<td class="td_padding">Товаров на странице</td>
									<td class="td_padding"><p>на сайте: <input type="text" class="input5" name=products_num value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->products_num)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' /> в админке: <input type="text" class="input5" name=products_num_admin value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Settings']->products_num_admin)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' /></p></td>
								</tr>
								
								
								<tr>
									<td class="td_padding">Автозаполнение метатегов</td>
									<td class="td_padding"><p>
									<input type=checkbox name=meta_autofill value='1' <?php if ($this->_tpl_vars['Settings']->meta_autofill == 1): ?>checked<?php endif; ?>> Заполнять мета-теги автоматически
									</p></td>
								</tr>
								
								<tr>
									<td class="td_padding"></td>
									<td class="td_padding"><p>
									<input id=change_password style='width:20;' type=checkbox name=change_admin_password value='1' <?php echo 'onclick=\'window.document.getElementById("admin_login").disabled=!this.checked;window.document.getElementById("admin_password").disabled=!this.checked;\''; ?>
>
                                    Изменить логин и пароль администратора
									</p></td>
								</tr>
								<tr>
									<td class="td_padding">Новый логин</td>
									<td class="td_padding"><p><input class='input3' disabled id=admin_login type=text name=admin_login value=''/></p></td>
								</tr>
								<tr>
									<td class="td_padding">Новый пароль</td>
									<td class="td_padding"><p><input class="input3" disabled id=admin_password type=text name=admin_password value='' /></p></td>
								</tr>
								<tr>
									<td class="td_padding"></td>
									<td class="td_padding"><p>
									<input type='hidden' name='token' value='<?php echo $this->_tpl_vars['Token']; ?>
'>
									<input type="submit" value="Сохранить" class="submit"/></p></td>
								</tr>
							</table>


			  </div>
			</form>
			
	 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 