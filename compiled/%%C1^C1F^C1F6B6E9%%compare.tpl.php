<?php /* Smarty version 2.6.25, created on 2010-11-21 21:30:38
         compiled from compare.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'compare.tpl', 36, false),array('modifier', 'string_format', 'compare.tpl', 79, false),array('modifier', 'count', 'compare.tpl', 86, false),)), $this); ?>

<script src="js/baloon/js/default.js" language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/validate.js" language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/baloon.js" language="JavaScript" type="text/javascript"></script>
<link href="js/baloon/css/baloon.css" rel="stylesheet" type="text/css" /> 

<script type="text/javascript" src="js/enlargeit/enlargeit.js"></script>

<!-- Товар  /-->
<div id="page_title">      
  <h1 class="float_left">Сравнение товаров</h1>

  <!-- Хлебные крошки /-->
  <div id="path">
    <a href="./">Главная</a>
    → Сравнение товаров           
  </div>
<!-- Хлебные крошки #End /-->
</div>

<?php if ($this->_tpl_vars['products']): ?>
<div id="product_params">
<table>
<tr>
<?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
<td>
<!-- Описание товара /-->
  <!-- Картинки товара /-->
<p tooltip='product' product_id='<?php echo $this->_tpl_vars['product']->product_id; ?>
'><a href="products/<?php echo $this->_tpl_vars['product']->url; ?>
" <?php if ($this->_tpl_vars['product']->hit): ?>class="product_name_link_hit"<?php else: ?>class="product_name_link"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['product']->category)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['product']->brand)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['product']->model)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></p>
    <img src="<?php if ($this->_tpl_vars['product']->small_image): ?>files/products/<?php echo $this->_tpl_vars['product']->small_image; ?>
<?php else: ?>design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/no_foto.gif<?php endif; ?>" alt=""/>
  <!-- Картинки товара #End /-->
  <br>
  <a href='compare/remove/<?php echo $this->_tpl_vars['product']->url; ?>
'>Убрать</a>

</td>
<?php endforeach; endif; unset($_from); ?> 
</tr>
<?php $_from = $this->_tpl_vars['properties']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['property']):
?>
<tr>
<?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
<?php $this->assign('product_id', $this->_tpl_vars['product']->product_id); ?>
<td> 
  <?php if ($this->_tpl_vars['property'][$this->_tpl_vars['product_id']]): ?>
 <b><?php echo ((is_array($_tmp=$this->_tpl_vars['k'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</b><br>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['property'][$this->_tpl_vars['product_id']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

 <?php else: ?>
 &mdash;
 <?php endif; ?>
</td>
<?php endforeach; endif; unset($_from); ?> 
</tr>
<?php endforeach; endif; unset($_from); ?> 
<tr>
<?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
<td> 


<?php echo $this->_tpl_vars['product']->body; ?>


</td>
<?php endforeach; endif; unset($_from); ?> 
</tr>
<tr>
<?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
<td> 
 
  <!-- Основное описание товара /-->
    
  <!-- Цена /-->
  <p>
  <?php if ($this->_tpl_vars['product']->variants[0]->discount_price > 0): ?>
  <span class="price"><span id=variant_price_<?php echo $this->_tpl_vars['product']->product_id; ?>
><?php echo ((is_array($_tmp=$this->_tpl_vars['product']->variants[0]->discount_price*$this->_tpl_vars['currency']->rate_from/$this->_tpl_vars['currency']->rate_to)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</span>&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['currency']->sign)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
  <?php endif; ?>
  </p>
  <!-- Цена #End /-->
	
  <form action=cart method=get>
  <p>
  <?php if (count($this->_tpl_vars['product']->variants) > 1): ?>
  <!-- Варианты товара /--> 
  <select name=variant_id onchange="display_variant(<?php echo $this->_tpl_vars['product']->product_id; ?>
, this.value);return false;"> 
  <?php $_from = $this->_tpl_vars['product']->variants; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['variant']):
?>
  <option value='<?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->variant_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<strong></strong><br>
  <?php endforeach; endif; unset($_from); ?>
  </select>
  <input type=button  value='' class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  <br>
  <?php elseif (count($this->_tpl_vars['product']->variants) == 1): ?>
  <input type=hidden name=variant_id value='<?php echo $this->_tpl_vars['product']->variants[0]->variant_id; ?>
'>
  <input type=button value='' class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  <?php endif; ?>  
  <!-- Варианты товара #END /-->  

  </p>
  </form> 
 
      
<!-- Основное описание товара #End /-->
</td>
<?php endforeach; endif; unset($_from); ?>
</tr>
</table>
</div>

<?php else: ?>
Нет товаров для сравнения
<?php endif; ?>
<!-- Описание товара #End/-->
<!-- Товар  #End /-->

<script>
var variants_prices = new Array;
<?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
variants_prices[<?php echo ((is_array($_tmp=$this->_tpl_vars['product']->product_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
] = new Array;
<?php $_from = $this->_tpl_vars['product']->variants; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['variant']):
?>
  variants_prices[<?php echo ((is_array($_tmp=$this->_tpl_vars['product']->product_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
][<?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->variant_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
] = '<?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->discount_price*$this->_tpl_vars['currency']->rate_from/$this->_tpl_vars['currency']->rate_to)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
';
<?php endforeach; endif; unset($_from); ?>
<?php endforeach; endif; unset($_from); ?>

  <?php echo '
  function display_variant(product, variant)
  { 
  	document.getElementById(\'variant_price_\'+product).innerHTML = variants_prices[product][variant];
  }
  '; ?>

</script>