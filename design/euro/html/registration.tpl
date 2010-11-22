{*
  Template name: Регистрация
  Форма регистрации пользователя
  Used by: Registration.class.php   
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
    
<H1>Регистрация</H1>

{if $error}
<div id="error_block"><p>{$error}</p></div>
{/if}

<form method=post>
  <table class="login_table">
    <tr>
      <td>Имя, фамилия</td>
      <td><input format='.+' notice='Введите имя' value='{$name|escape}' name=name maxlength=25 type="text"/></td>
    </tr>
    <tr>
      <td>Email</td>
      <td><input format=email notice='Введите email' value='{$email|escape}' name=email maxlength=100 type="text"/> (используется как логин)</td>
    </tr>
    <tr>
      <td>Пароль</td>
      <td><input format='.+' notice='Введите пароль' value='{$password|escape}' name=password maxlength=25 type="password"/></td>
    </tr>
    <tr>
      <td></td>
      <td>
      <!--  Капча /-->  
      {if $gd_loaded}
	    <div class="captcha">
	      <img src="captcha/image.php?t={math equation='rand(10,10000)'}" alt=""/>
          <p>Число:</p>
          <p><input type="text" name=captcha_code format='.+' notice='Введите чисто с картинки' /></p>
        </div>
      {/if}
      <input type=submit value='Готово'>
	</tr>
  </table>
</form>

