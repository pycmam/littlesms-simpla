<H1>Регистрация</H1>

{if $error}
<div class="error"><p>{$error}</p></div>
{/if}

<p>
<form method=post>
Имя, фамилия<br>
<input value='{$name|escape}' name=name type="text"/><br>
Email (будет логином)<br>
<input value='{$email|escape}' name=email type="text"/><br>
Пароль<br>
<input value='{$password|escape}' name=password type="password"/><br>

{if $gd_loaded}
<img src="captcha/image.php?t={math equation='rand(10,10000)'}" alt=""/><br>
Число:
<input type="text" size=5 name=captcha_code /> 
{/if}
<br>
<input type=submit value='Зарегистрироваться'>

</form>
</p>

