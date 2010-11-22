{*
  Template name: Форма логина
  Used by: Login.class.php   
  Assigned vars: $email, $password, $error
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
    
<h1>Вход</h1>

{if $error}
<div id="error_block"><p>{$error}</p></div>
{/if}

<form method=post>
  <table class="login_table">
    <tr>
      <td>
        Email
      </td>
      <td>
        <input type='text' name='email'
               format='email' notice='Введите email'
               value='{$email|escape}' maxlength='100' />
      </td>
    </tr>
    <tr>
      <td>
        Пароль
      </td>
      <td>
        <input type='password' name='password'
               format='.+' notice='Введите пароль'
               value='{$password|escape}' maxlength='25' />
        <a href="login/remind/">Забыли пароль?</a>
      </td>
    </tr>
    <tr>
      <td></td>
      <td><input type=submit value='Войти'></td>
  </tr>
  </table>
</form>
