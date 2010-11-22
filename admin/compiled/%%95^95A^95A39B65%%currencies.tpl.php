<?php /* Smarty version 2.6.25, created on 2010-11-21 21:30:53
         compiled from currencies.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'currencies.tpl', 88, false),)), $this); ?>
<!-- Управление статьями /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Setup" class="off">параметры</a></li>
      <li><a href="index.php?section=Currency" class="on">валюты</a></li>
      <li><a href="index.php?section=DeliveryMethods" class="off">доставка</a></li>
      <li><a href="index.php?section=PaymentMethods" class="off">оплата</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          Валюты</a>
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
	    <img src="./images/icon_currencies.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Валюты</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=currencies" title="Помощь" class="thickbox">Помощь</a>
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
          
          


				<FORM name=currency METHOD=POST>

  <?php if ($this->_tpl_vars['Items']): ?>						
							<table>
								<tr>
								    <td></td>
									<td class="small_text">Название</td>
									<td class="small_text">Знак</td>
									<td class="small_text">Код ISO</td>
									<td class="small_text" colspan=2  style='padding-left:20px;'>Курс</td>
									<td></td>
								</tr>

     <?php $_from = $this->_tpl_vars['Items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
								<tr>
								    <td>
								       <?php if ($this->_tpl_vars['item']->def): ?><img alt='По умолчанию на сайте' title='По умолчанию на сайте' src='images/magnet_on.jpg'>
								       <?php else: ?><a href='index.php?section=Currency&set_default=<?php echo $this->_tpl_vars['item']->currency_id; ?>
&token=<?php echo $this->_tpl_vars['Token']; ?>
'><img alt='Сделать валютой по умолчанию' title='Сделать валютой по умолчанию' src='images/magnet_off.jpg'><?php endif; ?></a>

								       <?php if ($this->_tpl_vars['item']->main): ?><img alt='Базовая валюта' title='Базовая валюта' src='images/pin_on.jpg'>
								       <?php else: ?><a href='index.php?section=Currency&set_main=<?php echo $this->_tpl_vars['item']->currency_id; ?>
&token=<?php echo $this->_tpl_vars['Token']; ?>
'
								       onclick='if(confirm("Пересчитать курсы валют и цены?")) this.href="index.php?section=Currency&recalculate=1&set_main=<?php echo $this->_tpl_vars['item']->currency_id; ?>
&token=<?php echo $this->_tpl_vars['Token']; ?>
";'><img alt='Сделать базовой валютой' title='Сделать базовой валютой' src='images/pin_off.jpg'><?php endif; ?></a>
								    </td>
									<td class="td_padding">
									  <input type="text" class="input7" name=names[<?php echo $this->_tpl_vars['item']->currency_id; ?>
] value='<?php echo ((is_array($_tmp=$this->_tpl_vars['item']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'/>
									</td>
									<td class="td_padding">
									  <input type="text" class="input5" name=signs[<?php echo $this->_tpl_vars['item']->currency_id; ?>
] value='<?php echo ((is_array($_tmp=$this->_tpl_vars['item']->sign)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'/>
									</td>
									<td class="td_padding">
									  <input type="text" class="input5" name=codes[<?php echo $this->_tpl_vars['item']->currency_id; ?>
] value='<?php echo ((is_array($_tmp=$this->_tpl_vars['item']->code)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'/>
									</td>
									<td class="td_padding" style='padding-left:20px;'>
									  <input type="text" class="input5" name=rates_from[<?php echo $this->_tpl_vars['item']->currency_id; ?>
] value='<?php echo ((is_array($_tmp=$this->_tpl_vars['item']->rate_from)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'/>
									  <?php echo $this->_tpl_vars['item']->sign; ?>

									</td>
									<td class="td_padding">
									  =
									  <input type="text" class="input5" name=rates_to[<?php echo $this->_tpl_vars['item']->currency_id; ?>
] value='<?php echo ((is_array($_tmp=$this->_tpl_vars['item']->rate_to)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'/>
									  <?php echo $this->_tpl_vars['MainCurrency']->sign; ?>

									</td>
									<td>
									  <a href="index.php?section=Currency&delete_id=<?php echo $this->_tpl_vars['item']->currency_id; ?>
&token=<?php echo $this->_tpl_vars['Token']; ?>
" class="fl" onclick='if(!confirm("<?php echo $this->_tpl_vars['Lang']->ARE_YOU_SURE_TO_DELETE; ?>
")) return false;'><img src="./images/delete.jpg" alt=""/></a>
									</td>
								</tr>
	  <?php endforeach; endif; unset($_from); ?>				
	                        <tr>
	                          <td colspan=4></td>
	                          <td colspan=3>
	                            <input type=hidden name='token' value='<?php echo $this->_tpl_vars['Token']; ?>
'>
	                            <input type="submit" value="Сохранить" class="submit"/>
	                          </td>
	                        </tr>			
							</table>

<?php else: ?>
  Нет валют
<?php endif; ?>
</form>

<div class="new_currency_block">
<span class="model4">Новая валюта</span>
<form method=post>
						
							<table>
								<tr>
									<td class="small_text">Название</td>
									<td class="small_text">Знак</td>
									<td class="small_text">Код ISO</td>
									<td class="small_text" colspan=2  style='padding-left:20px;'>Курс</td>
									<td></td>
								</tr>


								<tr>
									<td class="td_padding">
									  <input type="text" class="input7" name='name' />
									</td>
									<td class="td_padding">
									  <input type="text" class="input5" name='sign' onchange='window.document.getElementById("sign").innerText=this.value' />
									</td>
									<td class="td_padding">
									  <input type="text" class="input5" name='code' />
									</td>
									<td class="td_padding" style='padding-left:20px;'>
									  <input type="text" class="input5" name=rate_from value='1.00' />
									  <span id=sign></span>
									</td>
									<td class="td_padding">
									  =
									  <input type="text" class="input5" name=rate_to value='1.00'/>
									  <?php echo $this->_tpl_vars['MainCurrency']->sign; ?>

								    </td>
								    <td class="td_padding"> 	  
	                                  <input type="submit" value="Добавить" class="submit11"/>
	                                  <input type=hidden name='token' value='<?php echo $this->_tpl_vars['Token']; ?>
'>
									  <input type=hidden name='act' value='add'>
									</td>
								</tr>
		
							</table>

</form>
</div>
 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 









