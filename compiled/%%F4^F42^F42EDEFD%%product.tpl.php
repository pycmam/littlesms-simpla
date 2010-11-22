<?php /* Smarty version 2.6.25, created on 2010-11-21 21:30:34
         compiled from product.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'product.tpl', 19, false),array('modifier', 'string_format', 'product.tpl', 57, false),array('modifier', 'count', 'product.tpl', 64, false),array('modifier', 'nl2br', 'product.tpl', 217, false),array('function', 'math', 'product.tpl', 247, false),)), $this); ?>

<script src="js/baloon/js/default.js" language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/validate.js" language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/baloon.js" language="JavaScript" type="text/javascript"></script>
<link href="js/baloon/css/baloon.css" rel="stylesheet" type="text/css" /> 

<script type="text/javascript" src="js/enlargeit/enlargeit.js"></script>

<!-- Товар  /-->
<div id="page_title">      
  <h1  tooltip='product' product_id='<?php echo $this->_tpl_vars['product']->product_id; ?>
' <?php if ($this->_tpl_vars['product']->hit): ?>id="hit_header"<?php endif; ?> class="float_left"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']->category)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['product']->brand)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['product']->model)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>

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
    <?php if ($this->_tpl_vars['product']->brand): ?>
    → <a href="catalog/<?php echo $this->_tpl_vars['cat']->url; ?>
/<?php echo $this->_tpl_vars['product']->brand_url; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']->brand)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
    <?php endif; ?>
    →  <?php echo ((is_array($_tmp=$this->_tpl_vars['product']->category)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['product']->brand)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['product']->model)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
                
  </div>
<!-- Хлебные крошки #End /-->
</div>

<!-- Описание товара /-->
<div id="product_main">

  <!-- Картинки товара /-->
  <div id="product_main_img">
    <img src="<?php if ($this->_tpl_vars['product']->large_image): ?>files/products/<?php echo $this->_tpl_vars['product']->large_image; ?>
<?php elseif ($this->_tpl_vars['product']->small_image): ?>files/products/<?php echo $this->_tpl_vars['product']->small_image; ?>
<?php else: ?>design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/no_foto.gif<?php endif; ?>" alt=""/>
    <ul>
      <?php if ($this->_tpl_vars['product']->fotos): ?>
      <?php $_from = $this->_tpl_vars['product']->fotos; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['foto']):
?>                      
      <li><a href="files/products/<?php echo $this->_tpl_vars['foto']->foto_id; ?>
" onclick='return false;'><img id="files/products/<?php echo $this->_tpl_vars['foto']->filename; ?>
" onclick="enlarge(this);" longdesc="files/products/<?php echo $this->_tpl_vars['foto']->filename; ?>
" width=80 src="files/products/<?php echo $this->_tpl_vars['foto']->filename; ?>
" alt=""/></a></li>
      <?php endforeach; endif; unset($_from); ?>
      <?php endif; ?>
    </ul>
  </div>
  <!-- Картинки товара #End /-->
  
  <!-- Основное описание товара /-->
  <div id="product_main_description">
    
  <!-- Цена /-->
  <p>
  <?php if ($this->_tpl_vars['product']->variants[0]->discount_price > 0): ?>
  <span class="price"><span id=variant_price><?php echo ((is_array($_tmp=$this->_tpl_vars['product']->variants[0]->discount_price*$this->_tpl_vars['currency']->rate_from/$this->_tpl_vars['currency']->rate_to)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</span>&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['currency']->sign)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
  <?php endif; ?>
  </p>
  <!-- Цена #End /-->
	
  <form action=cart method=get>
  <p>
  <?php if (count($this->_tpl_vars['product']->variants) > 1): ?>
  <!-- Варианты товара /--> 
  <select name=variant_id onchange="display_variant(this.value);return false;"> 
  <?php $_from = $this->_tpl_vars['product']->variants; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['variant']):
?>
  <option value='<?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->variant_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<strong></strong><br>
  <?php endforeach; endif; unset($_from); ?>
  </select>
  <input type=button class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  <script>
  var variants_prices = new Array;
  <?php $_from = $this->_tpl_vars['product']->variants; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['variant']):
?>
  variants_prices[<?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->variant_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
] = '<?php echo ((is_array($_tmp=$this->_tpl_vars['variant']->discount_price*$this->_tpl_vars['currency']->rate_from/$this->_tpl_vars['currency']->rate_to)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
';
  <?php endforeach; endif; unset($_from); ?>
  <?php echo '
  function display_variant(variant)
  {
  	document.getElementById(\'variant_price\').innerHTML = variants_prices[variant];
  }
  '; ?>

  </script>  
  <?php elseif (count($this->_tpl_vars['product']->variants) == 1): ?>
  <input type=hidden name=variant_id value='<?php echo $this->_tpl_vars['product']->variants[0]->variant_id; ?>
'>
  <input type=button class="link_to_cart" onclick="document.cookie='from='+location.href+';path=/';this.form.submit();">
  <?php endif; ?>  
  <!-- Варианты товара #END /-->  

  </p>
  </form> 

  <?php echo $this->_tpl_vars['product']->body; ?>


  <?php if ($this->_tpl_vars['product']->properties): ?>
  <!-- Характеристики товара /-->  
  <div id="product_params">
    <a name=params></a>

    <table>
    <?php $_from = $this->_tpl_vars['product']->properties; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['property']):
?>
      <?php if ($this->_tpl_vars['property']->in_product): ?>
	  <tr><td><?php echo ((is_array($_tmp=$this->_tpl_vars['property']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['property']->value)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
	  <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    </table>
  </div>
  <!-- Характеристики товара #END /-->  
  <?php endif; ?>
  <p><a href='compare/<?php echo $this->_tpl_vars['product']->url; ?>
'>Сравнить</a></p>
  
  
<!-- Добавление в закладки -->
  <p style='text-align:right'>
  <noindex>
  <script>
  var url = location.href;
  var title = document.title;
  var tags = '';
  var desc = '';
  var url2 = location.href;
  m = document.getElementsByTagName('meta'); 
  for(var i in m)
    if(m[i].name == 'keywords') tags = m[i].content;
      else if(m[i].name == 'description') desc = m[i].content;
  document.write('<a rel="nofollow" href="http://www.memori.ru/link/?sm=1&u_data[url]='+url+'&u_data[name]='+title+'&u_data[descr]='+desc+'" title="Добавить закладку в Memori"><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/memori.gif" alt="Добавить закладку в Memori" border="0"></a>');
  document.write('<a rel="nofollow" href="http://www.google.com/bookmarks/mark?op=add&bkmk='+url+'&title='+title+'&labels='+tags+'&annotation='+desc+'" title="Добавить закладку в Google"><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/google.gif" alt="Добавить закладку в Google" border="0"></a>');
  document.write('<a rel="nofollow" href="http://www.bobrdobr.ru/addext.html?url='+url+'&title='+title+'&desc='+desc+'&tags='+tags+'" title="Добавить закладку в Бобрдобр"><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/bobrdobr.gif" alt="Добавить закладку в Бобрдобр" border="0"></a>');
  document.write('<a rel="nofollow" href="http://twitter.com/home?status='+title+' '+url+'" title="Опубликовать в Твиттер"><img src="design/<?php echo $this->_tpl_vars['settings']->theme; ?>
/images/twitter.gif" alt="Опубликовать в Твиттер" border="0"></a>');
  </script>
  </noindex>
  </p>
<!-- Добавление в закладки #End -->
  
  
  </div>  
  
<div class="clear"><!-- /--></div>
<!-- Основное описание товара #End /-->


<!-- Соседние товары  /-->
<?php if ($this->_tpl_vars['prev_product']): ?>
<a href='products/<?php echo $this->_tpl_vars['prev_product']->url; ?>
'>←&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['prev_product']->category)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['prev_product']->brand)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['prev_product']->model)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></nobr>
<?php endif; ?>
<?php if ($this->_tpl_vars['next_product']): ?>
&nbsp;&nbsp;&nbsp;
<a href='products/<?php echo $this->_tpl_vars['next_product']->url; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['next_product']->category)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['next_product']->brand)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['next_product']->model)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;→</a>
<?php endif; ?>
<!-- Соседние товары  #End/-->


</div>
<!-- Описание товара #End/-->



<?php if ($this->_tpl_vars['product']->related_products): ?>
<h2>Так же советуем посмотреть:</h2>
<!-- Список связанных товаров  /-->
<div id="products_list">

    <?php $_from = $this->_tpl_vars['product']->related_products; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['products'] = array('total' => count($_from), 'iteration' => 0);
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

            <!-- Описание товара /-->
            <p class="product_annotation">
                <?php echo $this->_tpl_vars['product']->description; ?>

            </p>
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
<!-- Список товаров #End /-->
<?php endif; ?>

<!-- Комментарии к товару  /-->  
<div id="comments">
  <a name=comments></a>

  <!-- Список каментов  /-->  
  <h2>Отзывы об этом товаре</h2>
  <?php if ($this->_tpl_vars['comments']): ?>
  <?php $_from = $this->_tpl_vars['comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
  
  <!-- Отдельный камент  /-->  
  <div class="comment_pack">
    <p><span class="comment_name"><?php echo ((is_array($_tmp=$this->_tpl_vars['c']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span> <span class="comment_date"><?php echo ((is_array($_tmp=$this->_tpl_vars['c']->date)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></p>
    <p class="comment_text" tooltip=comment comment_id=<?php echo $this->_tpl_vars['c']->comment_id; ?>
><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['c']->comment)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>
  </div>
  <!-- Отдельный камент #End  /-->  
  
  <?php endforeach; endif; unset($_from); ?>
  <?php else: ?>
    Пока нет ни одного отзыва
  <?php endif; ?>
  <!-- Список каментов #End  /-->  

  <h2>Оставить свой отзыв</h2>

  <?php if ($this->_tpl_vars['error']): ?>
  <div id="error_block"><p><?php echo $this->_tpl_vars['error']; ?>
</p></div>
  <?php endif; ?>


  <!-- Форма отзыва /-->  
  <form action='<?php echo $this->_supers['server']['REQUEST_URI']; ?>
#comments' method=post>

    <!--  Текст камента /-->  
    <p><textarea class="comment_textarea" format='.+' notice='Введите комментарий' name=comment><?php echo ((is_array($_tmp=$this->_tpl_vars['comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea></p>
    <!--  Имя комментатора /-->  
    <p class="comment_username">Ваше имя                    
      <input type="text" class="comment_username" name=name value="<?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" format='.+' notice='Введите имя' />
    </p>

    <!--  Капча /-->  
    <?php if ($this->_tpl_vars['gd_loaded']): ?>
    <div class="captcha">
      <img src="captcha/image.php?t=<?php echo smarty_function_math(array('equation' => 'rand(10,10000)'), $this);?>
" alt=""/>
      <p>Число:</p>
      <p><input type="text" name=captcha_code format='.+' notice='Введите число с картинки' /></p>
    </div>
    <?php endif; ?>
    
    <p><input type="submit" value="Отправить" class="comment_submit"/></p>
  </form>
  <!-- Форма отзыва #End  /-->  
  
</div>
<!-- Комментарии к товару #End  /-->

<!-- Товар  #End /-->