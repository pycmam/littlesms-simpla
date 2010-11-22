<?php /* Smarty version 2.6.25, created on 2010-11-21 21:29:45
         compiled from catalog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'catalog.tpl', 15, false),array('modifier', 'string_format', 'catalog.tpl', 59, false),array('modifier', 'count', 'catalog.tpl', 66, false),)), $this); ?>

<!-- Баннер  /-->
<div id="catalog_image"><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/header.jpg" alt="интернет-магазин"/></div>
<!-- Баннер #End /-->


<!-- Заголовок страницы  /-->
<?php if ($this->_tpl_vars['section']->header): ?>
<div id="page_title">      
  <h1><?php echo ((is_array($_tmp=$this->_tpl_vars['section']->header)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>
</div>      
<?php endif; ?>
<!-- Заголовок страницы #End /-->

<!-- Текст раздела /-->
<?php if ($this->_tpl_vars['section']->body): ?>
<div id="category_description">
  <?php echo $this->_tpl_vars['section']->body; ?>

</div>
<?php endif; ?>
<!-- Текст раздела #End /-->

<?php if ($this->_tpl_vars['products']): ?>
<div id="page_title">      
  <h1>Лучшие товары</h1>
</div>      
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
  <input type=button class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  <br>
  <?php elseif (count($this->_tpl_vars['product']->variants) == 1): ?>
  <input type=hidden name=variant_id value='<?php echo $this->_tpl_vars['product']->variants[0]->variant_id; ?>
'>
  <input type=button class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  <?php endif; ?>  
  <!-- Варианты товара #END /-->  

  </p>
  </form> 

            <!-- Описание товара /-->
            <p class="product_annotation">
                <?php echo $this->_tpl_vars['product']->description; ?>

            </p>
            <p><a href='compare/<?php echo $this->_tpl_vars['product']->url; ?>
'>Сравнить</a></p>
            <!-- Описание товара #End /-->
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
<!-- Список товаров #End  /-->

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


<?php endif; ?>

<?php if ($this->_tpl_vars['articles']): ?>
<!-- Статьи /-->
<div id="articles">

  <!-- Левая колонка статей /-->
  <div id="articles_left">

    <?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['articles'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['articles']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['article']):
        $this->_foreach['articles']['iteration']++;
?>
    <?php if ($this->_foreach['articles']['iteration'] <= $this->_foreach['articles']['total']/2+0.7): ?>
    <!-- Блок статьи /-->
    <div class="article">
      <h2 class="h2"><a tooltip="article" article_id="<?php echo $this->_tpl_vars['article']->article_id; ?>
" href="articles/<?php echo $this->_tpl_vars['article']->url; ?>
"><?php echo $this->_tpl_vars['article']->header; ?>
</a></h2>
      <p>
        <?php echo $this->_tpl_vars['article']->annotation; ?>

      </p>
    </div>
    <!-- Блок статьи #End /-->
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  
  </div>
  <!-- Левая колонка статей # /-->
  
  <!-- Правая колонка статей /-->        
  <div id="articles_right">

    <?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['articles'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['articles']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['article']):
        $this->_foreach['articles']['iteration']++;
?>
    <?php if ($this->_foreach['articles']['iteration'] > $this->_foreach['articles']['total']/2+0.7): ?>
    <!-- Блок статьи /-->
    <div class="article">
      <h2 class="h2"><a tooltip="article" article_id="<?php echo $this->_tpl_vars['article']->article_id; ?>
" href="articles/<?php echo $this->_tpl_vars['article']->url; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['article']->header)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></h2>
      <p>
        <?php echo $this->_tpl_vars['article']->annotation; ?>

      </p>
    </div>
    <!-- Блок статьи #End /-->
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>

  </div>
  <!-- Правая колонка статей #End /-->        
  
  <div class="clear"><!-- /--></div>
  <h2 class="h2"><a href="articles/">все статьи →</a></h2>
</div>
<?php endif; ?>