<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Панель зарегистрированного пользователя
 * Этот класс использует шаблон account.tpl
 *
 */

require_once( 'Widget.class.php' );

class Account extends Widget
{
	var $salt = 'simpla'; // Соль для шифрования пароля
	
	// Шаблоны для проверки корректности вводимых данных
	var $pattern_name = '/^([А-яA-z0-9\s\-_]){1,25}$/iu';
	var $pattern_email = '/^[a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,6})$/i';
  
  	var $name = '';
	var $email = '';
	var $password = '';

	/**
	 *
	 * Конструктор
	 *
	 */	
	function Account(&$parent)
	{
		Widget::Widget( $parent );
		$this->prepare();
	}
	
	/**
	 *
	 * Подготовка
	 *
	 */
	function prepare()
	{
		if(!$this->user)
		{
			header('Location: http://'.$this->root_url.'/login');
			exit();		
		}
		
		// Выбираем из базы пользователя с таки логином и паролем
		$query = sql_placeholder('select * from users where email=? and password=? limit 1', $this->user->email, $this->user->password);
		$this->db->query($query);
		$user = $this->db->result();
		if(empty($user))
		{
			header('Location: http://'.$this->root_url.'/login');
			exit();		
		}
		
		$this->name = $this->user->name;
		$this->email = $this->user->email;
		$this->encpassword = $this->user->password;
		$this->password = '';
		
		if(isset($_POST['email']))
		{
			$this->name = $_POST['name'];
			$this->email = $_POST['email'];
			$this->password = $_POST['password'];
			
			// Проверка возможных ошибок
			$error = null;
	
			// Не занят ли такой email?
			$query = sql_placeholder("select * from users where email=? and user_id!=?", $this->email, $this->user->user_id);
			$this->db->query($query);
			$num = $this->db->num_rows();
			if($num>0)
				$error = "Email $this->email уже используется";
	
			// Проверка на правильность заполнения формы
			if(!preg_match($this->pattern_name, $this->name))
				$error = "Введите имя";
			elseif(!preg_match($this->pattern_email, $this->email))
				$error = "Введите правильный email";
			
			if(!empty($error))
			{
				$this->smarty->assign('error', $error);
			}
			else
			{				
				// Если все хорошо, изменяем данные пользователя
				
				if(!empty($this->password))
				{
					$this->encpassword = md5($this->password.$this->salt);				
				}		
						
				$query = sql_placeholder("UPDATE users SET email=?, name=?, password=? WHERE user_id=? LIMIT 1",
											$this->email, $this->name, $this->encpassword, $this->user->user_id);
				$this->db->query($query);

				// А так же сразу залогиниваем
				$_SESSION['user_email'] = $this->email;
				$_SESSION['user_password'] = $this->encpassword;
				
				header('Location: http://'.$this->root_url.'/account');
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
		// Выбираем все заказы пользователя
		$query = sql_placeholder("SELECT orders.*,
									 SUM(orders_products.price*orders_products.quantity)+orders.delivery_price as total_amount,
									 DATE_FORMAT(orders.date, '%d.%m.%Y %H:%i') as date,
									 delivery_methods.name as delivery_method
							  	FROM orders
								   LEFT JOIN orders_products ON orders.order_id = orders_products.order_id
								   LEFT JOIN delivery_methods ON orders.delivery_method_id = delivery_methods.delivery_method_id
							  	WHERE orders.user_id=?
							 	GROUP BY orders.order_id
							 	ORDER BY orders.order_id DESC", $this->user->user_id);
		$this->db->query($query);
		$orders = $this->db->results();
		
		foreach($orders as $k=>$order)
		{
			$query = sql_placeholder("SELECT orders_products.*, products.url as url
										FROM orders_products LEFT JOIN products ON products.product_id = orders_products.product_id
										WHERE orders_products.order_id = ?", $order->order_id);
			$this->db->query($query);
			$products = $this->db->results();
			$orders[$k]->products = $products;
		}
		
		// И передаем их в шаблон
		$this->smarty->assign('orders', $orders);

		$this->smarty->assign('name', $this->name);
		$this->smarty->assign('email', $this->email);
		$this->smarty->assign('password', $this->password);
		
		$this->body = $this->smarty->fetch('account.tpl');
		
		return $this->body;				
	}
}
