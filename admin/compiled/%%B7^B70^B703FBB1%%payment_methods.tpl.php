<?php /* Smarty version 2.6.25, created on 2010-11-21 21:30:55
         compiled from payment_methods.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'payment_methods.tpl', 81, false),)), $this); ?>
<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Setup" class="off">параметры</a></li>
      <li><a href="index.php?section=Currency" class="off">валюты</a></li>
      <li><a href="index.php?section=DeliveryMethods" class="off">доставка</a></li>
      <li><a href="index.php?section=PaymentMethods" class="on">оплата</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          Формы оплаты</a>
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
	    <img src="./images/icon_card.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Формы оплаты</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=payment" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
		 <!-- Помощь2 /-->
        <div class="help2">
              <a href="index.php?section=PaymentMethod&token=<?php echo $this->_tpl_vars['Token']; ?>
" class="fl"><img src="./images/add.jpg" alt="" class="fl"/>Добавить форму</a>              
        </div>
        <!-- /Помощь2 /-->
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
          
          <?php echo $this->_tpl_vars['PagesNavigation']; ?>

  
          <?php if ($this->_tpl_vars['Items']): ?>

          <!-- Форма товаров #Begin /-->
          <form name='products' method="post">
            <table id="list2">
            
                            <?php $_from = $this->_tpl_vars['Items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
              <tr>
                <td>
                  <div class="list_left">
                    <a href="index.php?section=PaymentMethods&enable_id=<?php echo $this->_tpl_vars['item']->payment_method_id; ?>
&token=<?php echo $this->_tpl_vars['Token']; ?>
" class="fl"><img src="./images/<?php if ($this->_tpl_vars['item']->enabled): ?>lamp_on.jpg<?php else: ?>lamp_off.jpg<?php endif; ?>" alt=""/></a>
                    <div class="flxc">
                      <p>
                        <a href="index.php<?php echo $this->_tpl_vars['item']->edit_get; ?>
" class="<?php if ($this->_tpl_vars['item']->enabled): ?>tovar_on<?php else: ?>tovar_off<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
                      </p>
                      <p class=tovar_min>
                        Валюта: <?php echo ((is_array($_tmp=$this->_tpl_vars['item']->currency)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 (<?php echo $this->_tpl_vars['item']->rate_from*1; ?>
 <?php echo $this->_tpl_vars['item']->sign; ?>
 = <?php echo $this->_tpl_vars['item']->rate_to*1; ?>
 <?php echo $this->_tpl_vars['MainCurrency']->sign; ?>
)
                      </p>
                    </div>
			      </div>
                  <a href="index.php?section=PaymentMethods&act=delete&item_id=<?php echo $this->_tpl_vars['item']->payment_method_id; ?>
&token=<?php echo $this->_tpl_vars['Token']; ?>
" class="fl" onclick='if(!confirm("<?php echo $this->_tpl_vars['Lang']->ARE_YOU_SURE_TO_DELETE; ?>
")) return false;'><img src="./images/delete.jpg" alt=""/></a>
                </td>
              </tr>
              <?php endforeach; endif; unset($_from); ?>
                          </table>
            </form>
            <!-- Форма Товаров #End /-->
            <?php else: ?>
              Список пуст
            <?php endif; ?>

            <?php echo $this->_tpl_vars['PagesNavigation']; ?>


        </div>
	    <!-- Right side #End/-->
 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 