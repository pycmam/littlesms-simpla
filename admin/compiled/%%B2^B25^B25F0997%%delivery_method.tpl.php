<?php /* Smarty version 2.6.25, created on 2010-11-21 21:35:02
         compiled from delivery_method.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'delivery_method.tpl', 86, false),)), $this); ?>
<SCRIPT src="../js/baloon/js/default.js" language="JavaScript" type="text/javascript"></SCRIPT>
<SCRIPT src="../js/baloon/js/validate.js" language="JavaScript" type="text/javascript"></SCRIPT>
<SCRIPT src="../js/baloon/js/baloon.js" language="JavaScript" type="text/javascript"></SCRIPT>
<LINK href="../js/baloon/css/baloon.css" rel="stylesheet" type="text/css" />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'tinymce_init.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Setup" class="off">параметры</a></li>
      <li><a href="index.php?section=Currency" class="off">валюты</a></li>
      <li><a href="index.php?section=DeliveryMethods" class="on">доставка</a></li>
      <li><a href="index.php?section=PaymentMethods" class="off">оплата</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          <a href="index.php?section=DeliveryMethods">Способы доставки</a> →
      <?php if ($this->_tpl_vars['Item']->delivery_method_id): ?>
         <?php echo $this->_tpl_vars['Item']->name; ?>

      <?php else: ?>
        Новый способ доставки
      <?php endif; ?>          
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
	    <img src="./images/icon_truck.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">
      <?php if ($this->_tpl_vars['Item']->delivery_method_id): ?>
        <?php echo $this->_tpl_vars['Item']->name; ?>

      <?php else: ?>
        Новый способ доставки
      <?php endif; ?>        
        </h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=delivery" title="Помощь" class="thickbox">Помощь</a>
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

				<FORM name=product METHOD=POST enctype='multipart/form-data'>
					<div id="over">		
					<div id="over_left">	
							<table>
								<tr>
									<td class="model">Название</td>
									<td class="m_t"><p><input name="name" type="text" class="input3" value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Item']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' format='.+' notice='<?php echo $this->_tpl_vars['Lang']->ENTER_NAME; ?>
'/>
									<nobr><input name=enabled type="checkbox" class="checkbox" <?php if ($this->_tpl_vars['Item']->enabled): ?>checked<?php endif; ?> value='1'/><span class="akt">Активна</span></nobr> &nbsp; &nbsp;
									</p></td>
								</tr>

							</table>

							
							<div class="yellow_block">
								<table width=100%>
									<tr>
										<td><span class="akt1">Стоимость доставки,</span></td><td><span class="akt1p">Бесплатна от</span></td>									
									</tr>
									<tr>																
										<td class='td_padding'><input name=price value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Item']->price)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' type="text" class="input4"/> <?php echo $this->_tpl_vars['MainCurrency']->sign; ?>
</td>
										<td class='td_padding'><input name=free_from value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Item']->free_from)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' type="text" class="input4"/> <?php echo $this->_tpl_vars['MainCurrency']->sign; ?>
</td>
									</tr>
								</table>
							</div>

							<p><input type="submit" value="Сохранить" class="submit"/></p>
					</div>
					
				
					<div id="over_right">
					
				
						<div class="gray_block1">
							<span class="model">Возможные формы оплаты</span>
							<br>

									
              <?php $_from = $this->_tpl_vars['PaymentMethods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['payment_method']):
?>
                <input type=checkbox name=payment_methods[<?php echo $this->_tpl_vars['payment_method']->payment_method_id; ?>
] value='1' <?php if ($this->_tpl_vars['payment_method']->enabled): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['payment_method']->name; ?>
 &nbsp;
                <br>
              <?php endforeach; endif; unset($_from); ?>									
								
						</div>
					</div>
					
					
				</div>
				
				
				
			
				<div class="area">
					<span class="model4">Описание</span>
					<p><textarea name="description" class="editor_small"><?php echo $this->_tpl_vars['Item']->description; ?>
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

