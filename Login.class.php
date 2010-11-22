<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Класс для входа пользователя на сайте
 * Этот класс использует шаблон login.tpl, с формой для логина,
 * а так же шаблоны для напоминания пароля password_remind.tpl, password_remind_ok.tpl, email_password_remind.tpl
 *
 */

require_once('Widget.class.php');

class Login extends Widget
{
	var $salt = 'simpla'; // Соль для шифрования пароля
	
	/**
	 *
	 * Конструктор
	 *
	 */
	function Login(&$parent)
	{
		Widget::Widget($parent);
		$this->prepare();
	}
	
	/**
	 *
	 * Подготовка
	 *
	 */
	function prepare()
	{
	    // Разлогинить если передан соответствующий параметр
		if (isset($_GET['action']) && $_GET['action']=='logout')
		{
			unset($_SESSION['user_email']);
			unset($_SESSION['user_password']);
			header("Location: http://$this->root_url/");
			exit();
		}
		
		// Если запостили email и пароль, попытаемся залогинить
		if (isset($_POST['email']) && isset($_POST['password']))
		{
		    // Берем "голые" данные, но это безопасно
			$email = substr($_POST['email'], 0, 100);
			$password = substr($_POST['password'], 0, 25);
			$encpassword = md5($password.$this->salt);
			
			// Выбираем из базы пользователя с таки логином и паролем
			$query = sql_placeholder('select * from users where email=? and password=? limit 1', $email, $encpassword);
			$this->db->query($query);
			$user = $this->db->result();
			
			if (empty($user))
			{
				// Если такого нет
				$error = 'Неверный email или пароль';
			}
			elseif ($user->enabled == 0)
			{	
				// Если пользователь не активен
				$error = 'Ваша учетная запись отключена. Пожалуйста, обратитесь к администратору';
			}
			if (!empty($error))
			{
			    // В случае ошибки предлагаем опять форму логина
				$this->smarty->assign('error', $error);
				$this->smarty->assign('email', $email);
				$this->smarty->assign('password', $password);
			}
			else
			{
			    // Если все хорошо - залогиниваем, и переходим на главную
				$_SESSION['user_email'] = $email;
				$_SESSION['user_password'] = $encpassword;
				header("Location: http://$this->root_url/");
				exit();
			}
		}
	}
	
	/**
	 *
	 * Отображение
	 *
	 */
	function fetch()
	{
		if ($this->param('remind') == 1)
		{
			// Если запрашивается страница восстановления пароля
			return $this->fetch_password_remind();
		}
		else
		{
			// Иначе просто выводим форму логина
			$this->body = $this->smarty->fetch('login.tpl');
			$this->title = 'Вход на сайт';
			return $this->body;
		}
	}
	
	/**
	 *
	 * Отображение формы напоминания пароля
	 *
	 */
	function fetch_password_remind()
	{
		$email = '';
		$error = '';
		
		if (isset($_POST['email']))
		{
			$email = $_POST['email'];
			$query = sql_placeholder('select * from users where email=?', $email);
			$this->db->query($query);
			$user = $this->db->result();
			
			if (empty($user))
			{
				// Если не найден email
				$error = 'Неверный email';
			}
			else
			{
				// Сгенерируем новый пароль, чередуя гласные и согласные
				$new_password = '';
				$letters[0] = array('e', 'y', 'u', 'i', 'o', 'a');
				$letters[1] = array('q', 'w', 'r', 't', 'p', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm');
				$spin = rand(0, 1);				
				for ($i=0; $i<4; $i++)
				{
					// Пароль из 4 пар символов
					$l1 = $letters[$spin][rand(0, count($letters[$spin])-1)];
					$l2 = $letters[1-$spin][rand(0, count($letters[1-$spin])-1)];
					$new_password .= $l1.$l2;
				}
				// Плюс 2 цифры
				$new_password .= rand(10, 99);
				$new_encpassword = md5($new_password.$this->salt);
				
				// Изменим пароль в базе
				$query = sql_placeholder('UPDATE users SET password=? WHERE user_id=? LIMIT 1', $new_encpassword, $user->user_id);
				$this->db->query($query);
				
				// Письмо пользователю с паролем
				$this->smarty->assign('new_password', $new_password);
				$this->smarty->assign('username', $user->name);
				$message = $this->smarty->fetch('email_password_remind.tpl');
				$this->email($email, 'Новый пароль', $message);
				
				// Отправилось - поздравим пользователя
				$this->smarty->assign('email', $email);
				$this->smarty->assign('accepted', 1);
				$this->title = 'Пароль отправлен на почту';
				$this->body = $this->smarty->fetch('password_remind.tpl');
				return $this->body;

			}
		}
		$this->smarty->assign('email', $email);
		$this->smarty->assign('error', $error);
		$this->title = 'Напоминание пароля';
		$this->body = $this->smarty->fetch('password_remind.tpl');
		return $this->body;
	}
}
