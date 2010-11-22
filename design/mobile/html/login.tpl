{* Хлебные крошки *}
<div class=path>
    <a href="./">Главная</a>&nbsp;/
    Вход
</div>
{* END Хлебные крошки *}

<h1>Вход</h1>

{if $error}
<div class="error">{$error}</div>
{/if}

<form method=post>
<p>
Email<br>
<input type='text' name='email' value='{$email|escape}'/><br>
Пароль<br>
<input type='password' name='password' value='{$password|escape}'/><br>
<input type=submit value='Войти'>
</p>
</form>
<p>
<a href="login/remind/">Забыли пароль?</a><br>
<a href="registration/">Регистрация</a>
</p>
