<?php /* Smarty version 2.6.25, created on 2010-11-21 21:30:45
         compiled from login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'login.tpl', 32, false),)), $this); ?>

<script src="js/baloon/js/default.js"
        language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/validate.js"
        language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/baloon.js"
        language="JavaScript" type="text/javascript"></script>
<link href="js/baloon/css/baloon.css"
      rel="stylesheet" type="text/css" /> 
    
<h1>Вход</h1>

<?php if ($this->_tpl_vars['error']): ?>
<div id="error_block"><p><?php echo $this->_tpl_vars['error']; ?>
</p></div>
<?php endif; ?>

<form method=post>
  <table class="login_table">
    <tr>
      <td>
        Email
      </td>
      <td>
        <input type='text' name='email'
               format='email' notice='Введите email'
               value='<?php echo ((is_array($_tmp=$this->_tpl_vars['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' maxlength='100' />
      </td>
    </tr>
    <tr>
      <td>
        Пароль
      </td>
      <td>
        <input type='password' name='password'
               format='.+' notice='Введите пароль'
               value='<?php echo ((is_array($_tmp=$this->_tpl_vars['password'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
' maxlength='25' />
        <a href="login/remind/">Забыли пароль?</a>
      </td>
    </tr>
    <tr>
      <td></td>
      <td><input type=submit value='Войти'></td>
  </tr>
  </table>
</form>