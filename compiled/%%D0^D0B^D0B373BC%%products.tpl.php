<?php /* Smarty version 2.6.25, created on 2010-11-21 21:29:56
         compiled from products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'products.tpl', 18, false),array('modifier', 'string_format', 'products.tpl', 117, false),array('modifier', 'count', 'products.tpl', 124, false),)), $this); ?>

<?php if ($this->_tpl_vars['category']->image): ?>
<!-- Баннер  /-->
<div id="banner"><img src="files/categories/<?php echo $this->_tpl_vars['category']->image; ?>
" alt="Banner image"/></div>
<!-- Баннер #End /-->
<?php endif; ?>


<!-- Заголовок  /-->
<div id="page_title"> 
	<?php if ($this->_tpl_vars['category']): ?>     
    <h1  tooltip='category' category_id='<?php echo $this->_tpl_vars['category']->category_id; ?>
' class="float_left"><?php echo ((is_array($_tmp=$this->_tpl_vars['category']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['brand']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>
    <?php elseif ($this->_tpl_vars['brand']): ?>
    <h1  tooltip='brand' brand_id='<?php echo $this->_tpl_vars['brand']->brand_id; ?>
' class="float_left"><?php echo ((is_array($_tmp=$this->_tpl_vars['brand']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>
    <?php endif; ?>
    

    <!-- Хлебные крошки /-->
    <div id="path">
      <a href="./">Главная</a>
      <?php $_from = $this->_tpl_vars['category']->path; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cat']):
?>
      → <a href="catalog/<?php echo $this->_tpl_vars['cat']->url; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['cat']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
      <?php endforeach; endif; unset($_from); ?>  
      <?php if ($this->_tpl_vars['brand']): ?>
      → <?php echo ((is_array($_tmp=$this->_tpl_vars['brand']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

      <?php endif; ?>
    </div>
    <!-- Хлебные крошки #End /-->
</div>      

<?php if ($this->_tpl_vars['category']->description): ?>
<?php echo $this->_tpl_vars['category']->description; ?>

<br/>
<?php elseif ($this->_tpl_vars['brand']->description): ?>
<?php echo $this->_tpl_vars['brand']->description; ?>

<br/>
<?php endif; ?>

<!-- Фильтр по брендам /-->
<?php if ($this->_tpl_vars['brands']): ?>
<div id="category_description">
  <?php $_from = $this->_tpl_vars['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['brands'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['brands']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['b']):
        $this->_foreach['brands']['iteration']++;
?>
    <?php if ($this->_tpl_vars['b']->brand_id == $this->_tpl_vars['brand']->brand_id): ?>
      <?php echo ((is_array($_tmp=$this->_tpl_vars['b']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

    <?php else: ?>
      <a href='catalog/<?php echo $this->_tpl_vars['category']->url; ?>
/<?php echo $this->_tpl_vars['b']->url; ?>
<?php echo $this->_tpl_vars['filter_params']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['b']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
    <?php endif; ?>
    <?php if (! ($this->_foreach['brands']['iteration'] == $this->_foreach['brands']['total'])): ?>
    |
    <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>
<!-- Фильтр по брендам #End /-->

<!-- Фильтр по свойствам /-->
<?php if ($this->_tpl_vars['properties']): ?>
<div id="filter_params">
<table>
  <?php $_from = $this->_tpl_vars['properties']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['properties'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['properties']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['property']):
        $this->_foreach['properties']['iteration']++;
?>
  <?php $this->assign('property_id', $this->_tpl_vars['property']->property_id); ?>
  <tr>
  <td><?php echo $this->_tpl_vars['property']->name; ?>
:</td>
  <td>
  	<?php if ($this->_supers['get'][$this->_tpl_vars['property_id']]): ?><a href='catalog/<?php echo $this->_tpl_vars['category']->url; ?>
<?php echo $this->_tpl_vars['property']->clear_url; ?>
'>все</a><?php else: ?>все<?php endif; ?>
  	<?php $_from = $this->_tpl_vars['property']->options; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['options'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['options']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['option']):
        $this->_foreach['options']['iteration']++;
?>
  		<?php if ($this->_supers['get'][$this->_tpl_vars['property_id']] == $this->_tpl_vars['option']->value): ?>
  		<span><?php echo $this->_tpl_vars['option']->value; ?>
</span>
  		<?php else: ?>
  		<span><a href='catalog/<?php echo $this->_tpl_vars['category']->url; ?>
<?php if ($this->_tpl_vars['brand']): ?>/<?php echo $this->_tpl_vars['brand']->url; ?>
<?php endif; ?><?php echo $this->_tpl_vars['option']->url; ?>
'><?php echo $this->_tpl_vars['option']->value; ?>
</a></span>
  		<?php endif; ?>  		
  	<?php endforeach; endif; unset($_from); ?>
  	</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  </table>
</div>
<?php endif; ?>
<!-- Фильтр по свойствам  #End /-->

<?php if ($this->_tpl_vars['products']): ?>
<!-- Список товаров  /-->
<div id="products_list">

    <?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['products'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['products']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['products']['iteration']++;
?>    
    <!-- Товар /-->
    <div class="product_block">
    
        <!-- Картинка товара /-->
        <div class="product_block_img">
            <p>
              <a href="products/<?php echo $this->_tpl_vars['product']->url; ?>
">
                <img src="<?php if ($this->_tpl_vars['product']->small_image): ?>files/products/<?php echo $this->_tpl_vars['product']->small_image; ?>
<?php else: ?>design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/no_foto.gif<?php endif; ?>" alt=""/>
              </a>
              </p>
        </div>
        <!-- Картинка товара #End /-->
        
        <!-- Информация о товаре /-->
        <div class="product_block_annotation" >
        
            <!-- Название /-->
            <p tooltip='product' product_id='<?php echo $this->_tpl_vars['product']->product_id; ?>
'><a href="products/<?php echo $this->_tpl_vars['product']->url; ?>
" <?php if ($this->_tpl_vars['product']->hit): ?>class="product_name_link_hit"<?php else: ?>class="product_name_link"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['product']->category)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['product']->brand)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['product']->model)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></p>
            <!-- Название #End /-->

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

            <!-- Описание товара /-->
            <p class="product_annotation">
                <?php echo $this->_tpl_vars['product']->description; ?>

            </p>
            <!-- Описание товара #End /-->
            <p><a href='compare/<?php echo $this->_tpl_vars['product']->url; ?>
'>Сравнить</a></p>
        </div>
        <!-- Информация о товаре #End /-->
        
    </div>
    <!-- Товар #End /-->
    <?php if ($this->_foreach['products']['iteration']%2 == 0): ?>
      <div class="clear"><!-- /--></div>
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    
    <div class="clear"><!-- /--></div>
    
</div>
<!-- Список товаров #End /-->
<?php else: ?>
Товары не найдены
<?php endif; ?>

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

<!-- Постраничная навигация /-->
<?php if ($this->_tpl_vars['total_pages'] > 1): ?>
<script type="text/javascript" src="js/ctrlnavigate.js"></script>           
<div id="paging">

  <?php unset($this->_sections['pages']);
$this->_sections['pages']['name'] = 'pages';
$this->_sections['pages']['loop'] = is_array($_loop=$this->_tpl_vars['total_pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['pages']['show'] = true;
$this->_sections['pages']['max'] = $this->_sections['pages']['loop'];
$this->_sections['pages']['step'] = 1;
$this->_sections['pages']['start'] = $this->_sections['pages']['step'] > 0 ? 0 : $this->_sections['pages']['loop']-1;
if ($this->_sections['pages']['show']) {
    $this->_sections['pages']['total'] = $this->_sections['pages']['loop'];
    if ($this->_sections['pages']['total'] == 0)
        $this->_sections['pages']['show'] = false;
} else
    $this->_sections['pages']['total'] = 0;
if ($this->_sections['pages']['show']):

            for ($this->_sections['pages']['index'] = $this->_sections['pages']['start'], $this->_sections['pages']['iteration'] = 1;
                 $this->_sections['pages']['iteration'] <= $this->_sections['pages']['total'];
                 $this->_sections['pages']['index'] += $this->_sections['pages']['step'], $this->_sections['pages']['iteration']++):
$this->_sections['pages']['rownum'] = $this->_sections['pages']['iteration'];
$this->_sections['pages']['index_prev'] = $this->_sections['pages']['index'] - $this->_sections['pages']['step'];
$this->_sections['pages']['index_next'] = $this->_sections['pages']['index'] + $this->_sections['pages']['step'];
$this->_sections['pages']['first']      = ($this->_sections['pages']['iteration'] == 1);
$this->_sections['pages']['last']       = ($this->_sections['pages']['iteration'] == $this->_sections['pages']['total']);
?>
  <a <?php if ($this->_sections['pages']['index'] == $this->_tpl_vars['page']): ?>class="current_page" <?php endif; ?>href="<?php if ($this->_tpl_vars['category']): ?>catalog/<?php echo $this->_tpl_vars['category']->url; ?>
/<?php elseif ($this->_tpl_vars['brand']): ?>brands/<?php endif; ?><?php if ($this->_tpl_vars['brand']): ?><?php echo $this->_tpl_vars['brand']->url; ?>
/<?php endif; ?><?php if ($this->_sections['pages']['index']): ?>page_<?php echo $this->_sections['pages']['index']+1; ?>
<?php endif; ?><?php echo $this->_tpl_vars['filter_params']; ?>
"><?php echo $this->_sections['pages']['index']+1; ?>
</a>
  <?php endfor; endif; ?>
  
  <?php if ($this->_tpl_vars['page'] > 0): ?>
  <a id="PrevLink" href="<?php if ($this->_tpl_vars['category']): ?>catalog/<?php echo $this->_tpl_vars['category']->url; ?>
/<?php elseif ($this->_tpl_vars['brand']): ?>brands/<?php endif; ?><?php if ($this->_tpl_vars['brand']): ?><?php echo $this->_tpl_vars['brand']->url; ?>
/<?php endif; ?>page_<?php echo $this->_tpl_vars['page']; ?>
<?php echo $this->_tpl_vars['filter_params']; ?>
" class="all_pages">←&nbsp;назад</a>
  <?php endif; ?>
  
  <?php if ($this->_tpl_vars['page'] < $this->_tpl_vars['total_pages']-1): ?>
  <a id="NextLink" href="<?php if ($this->_tpl_vars['category']): ?>catalog/<?php echo $this->_tpl_vars['category']->url; ?>
/<?php elseif ($this->_tpl_vars['brand']): ?>brands/<?php endif; ?><?php if ($this->_tpl_vars['brand']): ?><?php echo $this->_tpl_vars['brand']->url; ?>
/<?php endif; ?>page_<?php echo $this->_tpl_vars['page']+2; ?>
<?php echo $this->_tpl_vars['filter_params']; ?>
" class="all_pages">вперед&nbsp;→</a>
  <?php endif; ?>

</div>          
<?php endif; ?>
<!-- Постраничная навигация #End /-->