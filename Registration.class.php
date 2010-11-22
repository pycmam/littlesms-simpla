<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Класс для регистрации пользователя на сайте
 * Этот класс использует шаблон registrotion.tpl, с формой регистрации,
 * а так же registration_done.tpl при успешной регистрации
 *
 */

require_once('Widget.class.php');


class Registration extends Widget
{
	var $title = 'Регистрация';
	// Соль для шифрования пароля
	var $salt = 'simpla';

	// Шаблоны для проверки корректности вводимых данных
	var $pattern_name = '/^([А-яA-z0-9\s\-_]){1,25}$/iu';
	var $pattern_email = '/^[a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,6})$/i';


	/**
	 *
	 * Конструктор
	 *
	 */
	function Registration(&$parent)
	{
		Widget::Widget($parent);
	}

	/**
	 *
	 * Отображение
	 *
	 */
	function fetch()
	{ 
		if(isset($_POST['email']) && isset($_POST['password']))
		{
			$name = $_POST['name'];
			$password  = $_POST['password'];
			$email  = $_POST['email'];
			$encpassword = md5($password.$this->salt);

			// Проверка возможных ошибок
			$error = null;

			// Не занят ли такой email?
			$query = sql_placeholder("select * from users where email=?", $email);
			$this->db->query($query);
			$num = $this->db->num_rows();
			if($num>0)
				$error = "Email $email уже используется";

			// Проверка на правильность заполнения формы
			if(!preg_match($this->pattern_name, $name))
				$error = "Введите имя";
			elseif(empty($password))
				$error = "Введите пароль";
			elseif(!preg_match($this->pattern_email, $email))
				$error = "Введите правильный email";
			elseif($this->gd_loaded && ($_SESSION["captcha_code"] != $_POST['captcha_code'] || empty($_POST['captcha_code']))) // только при включенной капче
				$error = "Введите число с картинки";

			// Приберем сохраненную капчу, иначе можно отключить загрузку рисунков и постить старую
			unset($_SESSION["captcha_code"]); 
			
			// Возврящаем в шаблон введенные данные
			$this->smarty->assign('error', $error);
			$this->smarty->assign('name', $name);
			$this->smarty->assign('password', $password);
			$this->smarty->assign('email', $email);


			if(!empty($error))
			{
				// Если возникла ошибка, выводим заново форму регистрации
				$this->smarty->assign('error', $error);
				$this->body = $this->smarty->fetch('registration.tpl');
			}else
			{
				// Если все хорошо, добавляем пользователя в базу
				$query = sql_placeholder("INSERT INTO users
                                    (email, name, password, enabled, group_id)
                                    values(?, ?, ?, 1, 0)",
                                    $email, $name, $encpassword);
				$this->db->query($query);

				// А так же сразу залогиниваем
				$_SESSION['user_email'] = $email;
				$_SESSION['user_password'] = $encpassword;
				
				// и выводим сообщение об успешной регистрации
				header("Location: http://$this->root_url/");
				exit();
			}
		}
		else
		{
			// Если ничего не постили, просто выводим форму регистрации
			$this->body = $this->smarty->fetch('registration.tpl');
		}
		return $this->body;
	}
}