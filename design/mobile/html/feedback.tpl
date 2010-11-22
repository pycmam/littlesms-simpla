{* END Хлебные крошки *}
<div class=path>
    <a href="./">Главная</a>&nbsp;/
    {if $section->header}{$section->header}{else}Обратная связь{/if}
</div>
{* END Хлебные крошки *}


{* Заголовок *}
<h1>{$section->header}</h1>
{* END Заголовок *}

<p>
{$section->body}
</p>

{if $accepted}
<h1>Ваше сообщение принято</h1>
{else}
<h1>Ответим на ваш вопрос:</h1>
{/if}

{if $error}
<div class=error>{$error}</div>
{/if}

{if $accepted}
<p>Спасибо за ваш вопрос, мы ответим на него в ближайшее время.</p>
{else}

{* Форма обратной связи *}
<form method=post action='http://{$root_url}/feedback/'>
Имя<br>
<input value='{$name|escape}' name=name type="text"/><br>
Email<br>
<input value='{$email|escape}' name=email type="text"/><br>
Сообщение<br>
<input value='{$message|escape}' name=message type="text"/><br>

{if $gd_loaded}
<img src="captcha/image.php?t={math equation='rand(10,10000)'}" alt=""/><br>
Число: <input type="text" name=captcha_code size=5/>
<input class=submit type=submit value='Отправить'>
{/if}
</form>
<br>
{* END Форма обратной связи *}
{/if}

