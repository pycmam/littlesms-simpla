{*
  Template name: Напоминание пароля
  Used by: Login.class.php   
  Assigned vars: $email, $error
*}

{* Подключаем js-проверку формы *}
<script src="js/baloon/js/default.js"
        language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/validate.js"
        language="JavaScript" type="text/javascript"></script>
<script src="js/baloon/js/baloon.js"
        language="JavaScript" type="text/javascript"></script>
<link href="js/baloon/css/baloon.css"
      rel="stylesheet" type="text/css" /> 
    
{if $accepted}
<h1>Вам отправлен новый пароль</h1>

<p>На {$email|escape} отправлено письмо с новым паролем</p>
{else}
<h1>Напоминание пароля</h1>

{if $error}
<div id='error_block'><p>{$error}</p></div>
{/if}

<form method=post>
  <table class='login_table'>
    <tr>
      <td>Email</td>
      <td>
        <input type='text' name='email'
               format='email' notice='Введите email' 
               value='{$email|escape}'  maxlength='100' />
        (который вы указывали при регистрации)
      </td>
    </tr>
    <tr>
      <td></td>
      <td><input type=submit value='Вспомнить' /></td>
  </tr>
  </table>
</form>
{/if}