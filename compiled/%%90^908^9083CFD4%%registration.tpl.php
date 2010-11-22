<?php /* Smarty version 2.6.25, created on 2010-11-21 21:30:44
         compiled from registration.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'registration.tpl', 27, false),array('function', 'math', 'registration.tpl', 43, false),)), $this); ?>

<script src="js/baloon/js/default.js"
        language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/validate.js"
        language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/baloon.js"
        language="JavaScript" type="text/javascript"></script>
<link href="js/baloon/css/baloon.css"
      rel="stylesheet" type="text/css" /> 
    
<H1>Регистрация</H1>

<?php if ($this->_tpl_vars['error']): ?>
<div id="error_block"><p><?php echo $this->_tpl_vars['error']; ?>
</p></div>
<?php endif; ?>

<form method=post>
  <table class="login_table">
    <tr>
      <td>Имя, фамилия</td>
      <td><input format='.+' notice='Введите имя' value='<?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' name=name maxlength=25 type="text"/></td>
    </tr>
    <tr>
      <td>Email</td>
      <td><input format=email notice='Введите email' value='<?php echo ((is_array($_tmp=$this->_tpl_vars['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' name=email maxlength=100 type="text"/> (используется как логин)</td>
    </tr>
    <tr>
      <td>Пароль</td>
      <td><input format='.+' notice='Введите пароль' value='<?php echo ((is_array($_tmp=$this->_tpl_vars['password'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' name=password maxlength=25 type="password"/></td>
    </tr>
    <tr>
      <td></td>
      <td>
      <!--  Капча /-->  
      <?php if ($this->_tpl_vars['gd_loaded']): ?>
	    <div class="captcha">
	      <img src="captcha/image.php?t=<?php echo smarty_function_math(array('equation' => 'rand(10,10000)'), $this);?>
" alt=""/>
          <p>Число:</p>
          <p><input type="text" name=captcha_code format='.+' notice='Введите чисто с картинки' /></p>
        </div>
      <?php endif; ?>
      <input type=submit value='Готово'>
	</tr>
  </table>
</form>
