{*
  Template name: Письмо с паролем

  Шаблон письма с напоминанием пароля
*}
<html>
  <body>
    <p>Уважаемый {$username},</p>
    <p>ваш новый пароль на сайте <a href='http://{$RootURL}/'>{$Settings->site_name}</a>:</p>
    <br>
    <p>{$new_password}</p>
    <br>
  </body>
</body>

