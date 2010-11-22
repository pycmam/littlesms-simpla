{*
  Template name: Напоминание пароля
  Used by: Login.class.php   
  Assigned vars: $email, $error
*}
    
{if $accepted}
<h1>Вам отправлен новый пароль</h1>

<p>На {$email|escape} отправлено письмо с новым паролем</p>
{else}
<h1>Напоминание пароля</h1>

{if $error}
<div class="error">{$error}</div>
{/if}

<p>
<form method=post>
Email
<input type='text' name='email' value='{$email|escape}'  maxlength='100' />
(который вы указывали при регистрации)
<br>
<input type=submit value='Вспомнить' />
</form>
</p>
{/if}