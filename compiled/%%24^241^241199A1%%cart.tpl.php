<?php /* Smarty version 2.6.25, created on 2010-11-21 21:30:30
         compiled from cart.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'cart.tpl', 38, false),array('modifier', 'string_format', 'cart.tpl', 40, false),array('function', 'math', 'cart.tpl', 147, false),)), $this); ?>
<div id="page_title">  
    <h1 class="float_left">Корзина</h1>
    <!-- Хлебные крошки /-->
    <div id="path">
    <a href="./">Главная</a> → Корзина        
    </div>
    <!-- Хлебные крошки #End /-->
</div>      

<?php if ($this->_tpl_vars['variants']): ?>
<!-- Корзина /-->
<form method=post name=cart>
    <table id="cart_products">
        <tr>
            <td class="td_1">
                <span>Товар</span>
            </td>
            <td class="td_2">
                <span>Цена</span>
            </td>
            <td class="td_3">
                <span>Количество</span>
            </td>
            <td class="td_4">
                <span>Итого</span>
            </td>
        </tr>
        <?php $_from = $this->_tpl_vars['variants']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['variant']):
?>
        <tr>
            <td class="td_1">
              <a href="products/<?php echo $this->_tpl_vars['variant']->url; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->category)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->brand)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->model)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a> <?php echo $this->_tpl_vars['variant']->name; ?>

            </td>
            <td class="td_2"><?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->discount_price*$this->_tpl_vars['currency']->rate_from/$this->_tpl_vars['currency']->rate_to)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
&nbsp;<?php echo $this->_tpl_vars['currency']->sign; ?>
</td>
            <td class="td_3">
                <p><select name=amounts[<?php echo $this->_tpl_vars['variant']->variant_id; ?>
] onchange="document.cart.submit_order.value='0'; document.cart.submit();">
                  <?php unset($this->_sections['amounts']);
$this->_sections['amounts']['name'] = 'amounts';
$this->_sections['amounts']['start'] = (int)1;
$this->_sections['amounts']['loop'] = is_array($_loop=$this->_tpl_vars['variant']->stock+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['amounts']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['amounts']['max'] = (int)100;
$this->_sections['amounts']['show'] = true;
if ($this->_sections['amounts']['max'] < 0)
    $this->_sections['amounts']['max'] = $this->_sections['amounts']['loop'];
if ($this->_sections['amounts']['start'] < 0)
    $this->_sections['amounts']['start'] = max($this->_sections['amounts']['step'] > 0 ? 0 : -1, $this->_sections['amounts']['loop'] + $this->_sections['amounts']['start']);
else
    $this->_sections['amounts']['start'] = min($this->_sections['amounts']['start'], $this->_sections['amounts']['step'] > 0 ? $this->_sections['amounts']['loop'] : $this->_sections['amounts']['loop']-1);
if ($this->_sections['amounts']['show']) {
    $this->_sections['amounts']['total'] = min(ceil(($this->_sections['amounts']['step'] > 0 ? $this->_sections['amounts']['loop'] - $this->_sections['amounts']['start'] : $this->_sections['amounts']['start']+1)/abs($this->_sections['amounts']['step'])), $this->_sections['amounts']['max']);
    if ($this->_sections['amounts']['total'] == 0)
        $this->_sections['amounts']['show'] = false;
} else
    $this->_sections['amounts']['total'] = 0;
if ($this->_sections['amounts']['show']):

            for ($this->_sections['amounts']['index'] = $this->_sections['amounts']['start'], $this->_sections['amounts']['iteration'] = 1;
                 $this->_sections['amounts']['iteration'] <= $this->_sections['amounts']['total'];
                 $this->_sections['amounts']['index'] += $this->_sections['amounts']['step'], $this->_sections['amounts']['iteration']++):
$this->_sections['amounts']['rownum'] = $this->_sections['amounts']['iteration'];
$this->_sections['amounts']['index_prev'] = $this->_sections['amounts']['index'] - $this->_sections['amounts']['step'];
$this->_sections['amounts']['index_next'] = $this->_sections['amounts']['index'] + $this->_sections['amounts']['step'];
$this->_sections['amounts']['first']      = ($this->_sections['amounts']['iteration'] == 1);
$this->_sections['amounts']['last']       = ($this->_sections['amounts']['iteration'] == $this->_sections['amounts']['total']);
?>
                    <option value="<?php echo $this->_sections['amounts']['index']; ?>
" <?php if ($this->_tpl_vars['variant']->amount == $this->_sections['amounts']['index']): ?>selected<?php endif; ?>><?php echo $this->_sections['amounts']['index']; ?>
</option>
                  <?php endfor; endif; ?>
                </select> шт.
                <a href='cart/delete/<?php echo $this->_tpl_vars['variant']->variant_id; ?>
' title='убрать из корзины'><img src='design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/delete.png' alt='убрать из корзины' align="absmiddle"></a>
                </p>
            </td>
            <td class="td_4">
            <?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->discount_price*$this->_tpl_vars['variant']->amount*$this->_tpl_vars['currency']->rate_from/$this->_tpl_vars['currency']->rate_to)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
&nbsp;<?php echo $this->_tpl_vars['currency']->sign; ?>

            </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
    </table>
    
    <div class="line"><!-- /--></div>
    
    <!-- Итого /-->
    <div class="total_line">
    
                <?php if ($this->_supers['cookies']['from']): ?>
        <a href="<?php echo ((is_array($_tmp=$this->_supers['cookies']['from'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="return_from_cart" onclick="document.cookie='from=;path=/';">← продолжить выбор товаров</a>
        <?php endif; ?>
        
        <span class=total_sum>Итого: <span id=subtotal_price><?php echo ((is_array($_tmp=$this->_tpl_vars['total_price']*$this->_tpl_vars['currency']->rate_from/$this->_tpl_vars['currency']->rate_to)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</span>&nbsp;<?php echo $this->_tpl_vars['currency']->sign; ?>
</span>
    </div>
    
    <br><br>
    <h1>Оформить заказ ↓</h1>
    
    <?php if ($this->_tpl_vars['delivery_methods']): ?>
    <!-- Способы доставки /-->
    <div class="billet">
        <table>
            <?php $_from = $this->_tpl_vars['delivery_methods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['delivery'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['delivery']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['delivery_method']):
        $this->_foreach['delivery']['iteration']++;
?>
            <tr>
                <td class="delivery_select">
                  <p>
                    <input type=radio id=delivery_radio_<?php echo $this->_tpl_vars['delivery_method']->delivery_method_id; ?>
 name=delivery_method_id value='<?php echo $this->_tpl_vars['delivery_method']->delivery_method_id; ?>
' <?php if ($this->_tpl_vars['delivery_method']->delivery_method_id == $this->_tpl_vars['delivery_method_id']): ?>checked<?php endif; ?>  onclick="select_delivery_method(<?php echo $this->_tpl_vars['delivery_method']->delivery_method_id; ?>
);">
                  </p>
                </td>
                <td class="delivery_text">
                    <h3 onclick="select_delivery_method(<?php echo $this->_tpl_vars['delivery_method']->delivery_method_id; ?>
);"><?php echo $this->_tpl_vars['delivery_method']->name; ?>
 (<?php if ($this->_tpl_vars['delivery_method']->final_price > 0): ?><span id=delivery_price_<?php echo $this->_tpl_vars['delivery_method']->delivery_method_id; ?>
><?php echo ((is_array($_tmp=$this->_tpl_vars['delivery_method']->final_price*$this->_tpl_vars['currency']->rate_from/$this->_tpl_vars['currency']->rate_to)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</span>  <?php echo $this->_tpl_vars['currency']->sign; ?>
<?php else: ?>бесплатно<?php endif; ?>)</h3>
                    <?php echo $this->_tpl_vars['delivery_method']->description; ?>

                </td>
            </tr>
            <?php endforeach; endif; unset($_from); ?>
        </table>
    </div>          
    
    <div class="total_line">
        <span class=total_sum>Итого с доставкой: <span id=total_price><?php echo ((is_array($_tmp=$this->_tpl_vars['total_price']*$this->_tpl_vars['currency']->rate_from/$this->_tpl_vars['currency']->rate_to)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</span>&nbsp;<?php echo $this->_tpl_vars['currency']->sign; ?>
</span>
    </div>
    

    <?php echo '
    <script>
      function select_delivery_method(method_id)
      {
        radiobuttons = document.getElementsByName(\'delivery_method_id\');
        for(var i=0;i<radiobuttons.length;i++)
        {
          if(radiobuttons[i].value == method_id)
          {
            radiobuttons[i].checked = 1;
          }
        }
    
      var subtotal = parseFloat(document.getElementById(\'subtotal_price\').innerHTML);
      var delivery = 0;
      if(document.getElementById(\'delivery_price_\'+method_id))
        delivery = parseFloat(document.getElementById(\'delivery_price_\'+method_id).innerHTML);
      total = subtotal+delivery;
      document.getElementById(\'total_price\').innerHTML = total.toFixed(2);
      }
    </script>
    '; ?>
              
    
    <script>
      select_delivery_method(<?php echo $this->_tpl_vars['delivery_method_id']; ?>
);
    </script>
    
    <!-- Способы доставки #End /-->
    <?php endif; ?>
    
    <h1>Адрес получателя</h1>

    <?php if ($this->_tpl_vars['error']): ?>
    <div id="error_block"><p><?php echo $this->_tpl_vars['error']; ?>
</p></div>
    <?php endif; ?>
                    
    <div class="billet">
        <table class=order_form>
            <tr><td>Имя, фамилия</td><td><input name="name" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
            <tr><td>Email</td><td><input name="email" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
            <tr><td>Телефон</td><td><input name="phone" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['phone'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
            <tr><td>Адрес доставки</td><td><input name="address" type="text" class="address" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['address'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/></td></tr>
            <tr>
                <td>Комментарий к&nbsp;заказу</td>
                <td>
                    <textarea name="comment" id="order_comment"><?php echo ((is_array($_tmp=$this->_tpl_vars['comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
        
                    <?php if ($this->_tpl_vars['gd_loaded']): ?>                             
                    <div class="captcha">
                        <img src="captcha/image.php?t=<?php echo smarty_function_math(array('equation' => 'rand(10,10000)'), $this);?>
" alt=""/>
                        <p>Число:</p>
                        <p><input type="text" name="captcha_code" /></p>
                    </div>
                    <?php endif; ?>
                    
                    <p>
                    <input type="hidden" name="submit_order" value="1">
                    <input type="submit" value="Заказать" id="order_button"/>
                    </p>

                </td>
            </tr>
        </table>
    </div>
</form>     
<!-- Корзина #End /-->
<?php else: ?>
  Корзина пуста
<?php endif; ?>