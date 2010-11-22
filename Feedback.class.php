<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Класс для обратной связи
 * Этот класс использует шаблон feedback.tpl, с формой для обратной связи,
 * а так же шаблон письма email_feedback.tpl
 *
 */

require_once('Widget.class.php');

class Feedback extends Widget
{	 
	// Шаблоны для проверки корректности вводимых данных
	var $pattern_name = '/^([А-яA-z0-9\s\-_]){1,25}$/iu';
	var $pattern_email = '/^[a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,6})$/i';
	 
	/**
	 *
	 * Конструктор
	 *
	 */	 
	function Feedback(&$parent)
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
		if(isset($_POST['email']) && isset($_POST['name']))
		{
			$name = $_POST['name'];
			$email  = $_POST['email'];
			$message  = $_POST['message'];

			// Проверка возможных ошибок
			$error = null;


			// Проверка на правильность заполнения формы
			if(!preg_match($this->pattern_name, $name))
				$error = "Введите имя";
			elseif(!preg_match($this->pattern_email, $email))
				$error = "Введите правильный email";
			elseif($this->gd_loaded && ($_SESSION["captcha_code"] != $_POST['captcha_code'] || empty($_POST['captcha_code']))) // только при включенной капче
				$error = "Введите число с картинки";

			// Приберем сохраненную капчу, иначе можно отключить загрузку рисунков и постить старую
			unset($_SESSION["captcha_code"]); 
			
			// Возврящаем в шаблон введенные данные
			$this->smarty->assign('error', $error);
			$this->smarty->assign('name', $name);
			$this->smarty->assign('email', $email);
			$this->smarty->assign('message', $message);
			$this->smarty->assign('ip', $_SERVER['REMOTE_ADDR']);

			if(!empty($error))
			{
				// Если возникла ошибка, выводим заново форму регистрации
				$this->smarty->assign('error', $error);
				$this->body = $this->smarty->fetch('feedback.tpl');
			}else
			{
				//Если все хорошо, добавляем вопрос в базу
				$query = sql_placeholder("INSERT INTO feedback
                                    (email, name, message, ip, date)
                                    values(?, ?, ?, ?, now())",
                                    $email, $name, $message, $_SERVER['REMOTE_ADDR']);
				$this->db->query($query);
				
				$this->smarty->assign('accepted', true);
				
    			// Письмо администратору
    			$message = $this->smarty->fetch('../../../admin/templates/email_feedback.tpl');
    			$this->email($this->settings->admin_email, 'Вопрос от пользователя '.strip_tags($name), $message);

			}
		}else
		{
		  if(isset($this->user))
		  {
			$this->smarty->assign('name', isset($this->user->name)?$this->user->name:'');
			$this->smarty->assign('email', isset($this->user->email)?$this->user->email:'');
		  }
		
		}

		// Если ничего не постили, просто выводим форму регистрации
		$this->body = $this->smarty->fetch('feedback.tpl');

		return $this->body;
	}
	
}
