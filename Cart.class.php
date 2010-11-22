<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Корзина покупок
 * Этот класс использует шаблон cart.tpl
 *
 */
 
require_once('Widget.class.php');
require_once('Order.class.php');

class Cart extends Widget
{
  var $single = false;
  var $title = 'Корзина';
    
  //////////////////////////////////////////
  // Конструктор
  //////////////////////////////////////////

  function Cart(&$parent)
  {
    Widget::Widget($parent);
    // Вызовем фунцию, обрататывающую действия с товарами в корзине
    $this->prepare();
  }
  
  
  //////////////////////////////////////////
  // Изменения товаров в корзине
  //////////////////////////////////////////
  function prepare()
  {
    // Если передан url товара, добавим его в корзину
    if($variant_id = intval($this->param('variant_id')))
    {
      // Возможно нам передали и количество товара
      // Если не указано количество, считаем что один
      $amount = max(1, intval($this->param('amount')));

      // Выберем товар из базы, заодно убедившись в его существовании
      $variant = Storefront::get_variant($variant_id);

      // Если товар существует, добавим его в корзину
      if(!empty($variant) && $variant->stock>0)
      {
    	 // Не дадим больше чем на складе
	     $amount = min($amount, $variant->stock);
        
         $this->update($variant_id, $amount, true);
         
         if(!isset($_POST['submit_order']) || $_POST['submit_order']!=1)
           header("Location: http://$this->root_url/cart/");
      }
    }

    // Удаление товара из корзины
    if($delete_product_id = intval($this->param('delete_product_id')))
    {
      $this->update($delete_product_id, 0);
      if(!isset($_POST['submit_order']) || $_POST['submit_order']!=1)
        header("Location: http://$this->root_url/cart/");
   }

    // Если нам запостили amounts, обновляем их
    if(isset($_POST['amounts']))
    {
      foreach($_POST['amounts'] as $variant_id=>$amount)
      {
        // мало ли что могли запостить
        $amount = intval($amount);
        $variant_id = intval($variant_id);
        
        // Выберем товар из базы, заодно убедившись в его существовании
        $query = sql_placeholder('SELECT * FROM products_variants WHERE variant_id=? LIMIT 1', $variant_id);
        $this->db->query($query);
        $variant = $this->db->result();

        if($amount > 0 && !empty($variant))
        {  
          // Не дадим больше чем на складе
          $amount = min($amount, $variant->stock);

          // Обновляем количество товара
          $this->update($variant_id, $amount);
        }
        // Если товара больше нет на складе - уберем из корзины
        if($variant->stock <= 0)
           $this->update($variant_id, 0);         
      }
      if(!isset($_POST['submit_order']) || $_POST['submit_order']!=1)
        header("Location: http://$this->root_url/cart/");
      
    }       
  }

  //////////////////////////////////////////
  // Основная функция
  //////////////////////////////////////////
  function fetch()
  {  
    // Если запрос на поздравление с успешным заказом  
    if(isset($_GET['finish']))
    {
      return $this->show_finish();
    }
    // Если нажали кнопку "оформить заказ"
    elseif(isset($_POST['submit_order']) && $_POST['submit_order']==1)
    {
      return $this->save_order();
    }
    // Иначе просто выведем корзину на экран
    else
    {
      return $this->show_cart();
    }
  }
  

  //////////////////////////////////////////
  // Функция отображения корзины
  //////////////////////////////////////////
  function show_cart()
  {
    $total_price = 0;
    $products = array();
    
    // Сформируем массив товаров в корзине
    if(is_array($_SESSION['shopping_cart']))
    {
        $variants_ids = array_keys($_SESSION['shopping_cart']);
          
		$discount=isset($this->user->discount)?$this->user->discount:0;
		$query = sql_placeholder("SELECT products_variants.*, products.model as model, products.url as url, brands.name as brand, categories.single_name as category,
                products_variants.price*(100-$discount)/100 as discount_price
                FROM products_variants, categories, products LEFT JOIN brands ON brands.brand_id=products.brand_id WHERE categories.category_id=products.category_id AND products.product_id=products_variants.product_id AND products_variants.variant_id in (?@) ORDER BY products.order_num, products_variants.position", $variants_ids);
		$this->db->query($query);
		$variants = $this->db->results();


      if(!empty($variants))
      {
  	    foreach($variants as $k=>$variant)
        {
          // А количество товара - не должно превысить количество на складе
          // (мало ли, может пока юзер играется с корзиной, кто-то купил уже эти товары)
          $variants[$k]->amount = $_SESSION['shopping_cart'][$variant->variant_id] = min($_SESSION['shopping_cart'][$variant->variant_id], $variant->stock);        
          // Добавим стоимость к общей сумме
          $total_price += $variant->discount_price*$variants[$k]->amount;   
        }
      }
  	}
    // Передаем товары в шаблон
    $this->smarty->assign('variants', $variants);
    
    // Передаем общую стоимость в шаблон
    $this->smarty->assign('total_price', $total_price);
  	
    // Сформируем массив способов доставки и тоже в шаблон
  	$query = "SELECT * FROM delivery_methods WHERE enabled ORDER BY delivery_method_id";
  	$this->db->query($query);
  	$delivery_methods = $this->db->results();
  	foreach($delivery_methods as $k=>$method)
  	{
  	  $delivery_methods[$k]->final_price = $method->price;
  	  if($method->free_from <= $total_price)
  	    $delivery_methods[$k]->final_price = 0;
  	}
    $this->smarty->assign('delivery_methods', $delivery_methods);
      
    // Передаем параметры заказа по умолчанию.
    // Если постили форму, передаем то что запостили,
    if(isset($_POST['submit_order']) && $_POST['submit_order']==1)
    {
      $this->smarty->assign('name', $_POST['name']);
      $this->smarty->assign('email', $_POST['email']);
      $this->smarty->assign('phone', $_POST['phone']);
      $this->smarty->assign('address', $_POST['address']); 
      $this->smarty->assign('comment', $_POST['comment']); 
      $this->smarty->assign('delivery_method_id', $_POST['delivery_method_id']); 
    }
    // Иначе берем из профиля пользователя
    else
    { 
      if(isset($this->user))
      {
        $this->smarty->assign('name', isset($this->user->name)?$this->user->name:'');
        $this->smarty->assign('email', isset($this->user->email)?$this->user->email:'');

  		$query = sql_placeholder("SELECT * FROM orders WHERE user_id=? ORDER BY order_id DESC LIMIT 1", $this->user->user_id);
  		$this->db->query($query);
  		$last_order = $this->db->result();
        
        $this->smarty->assign('phone', isset($last_order->phone)?$last_order->phone:'');
        $this->smarty->assign('address', isset($last_order->address)?$last_order->address:''); 
      }
      // Способ доставки установим по умолчанию первым элементом массива
      if(is_array($delivery_methods))
        $this->smarty->assign('delivery_method_id', $delivery_methods[0]->delivery_method_id);       
    }
        
    // Выводим корзину
    return $this->body = $this->smarty->fetch('cart.tpl');
  }




  //////////////////////////////////////////
  // Сохранение заказа
  //////////////////////////////////////////
  function save_order()
  {

    // Проверим капчу
    if($this->gd_loaded && ( empty($_POST['captcha_code']) || $_SESSION['captcha_code'] != $_POST['captcha_code']))
    {
      $this->smarty->assign('error', "Введите число с картинки");
      return $this->show_cart();
    } 
     
    // Приберем сохраненную капчу, иначе можно отключить загрузку рисунков и постить старую
    unset($_SESSION["captcha_code"]);      
    
    // Параметры заказа
    if(isset($_POST['name']))
      $name = $_POST['name'];
    else
      $name = '';
    if(isset($_POST['email']))
      $email = $_POST['email'];
    else
      $email = '';
    if(isset($_POST['phone']))
      $phone = $_POST['phone'];
    else
      $phone = '';
    if(isset($_POST['address']))
      $address = $_POST['address'];
    else
      $address = '';
    if(isset($_POST['comment']))
      $comment = $_POST['comment'];
    else
      $comment = '';       
    // Если залогинены, добавим пользователя в заказ  
    if($this->user)
      $user_id = $this->user->user_id;
    else
      $user_id = 0;
       
    // Генерируем уникальный код заказа
    // по которому пользователь сможет посмотреть заказ
    $code = md5(uniqid('', true)); 
    
    $ip = $_SERVER['REMOTE_ADDR'];
       
    // Формируем запрос на добавление заказа
    $query = sql_placeholder("INSERT INTO orders(order_id, delivery_method_id, date, user_id, name, email, address, phone, comment, status, code, ip)
                              VALUES(NULL, NULL, NOW(), ?, ?, ?, ?, ?, ?, 0, ?, ?)",
                              $user_id, $name, $email, $address, $phone, $comment, $code, $ip);
  	                            
    $this->db->query($query);
    $order_id = $this->db->insert_id();
   
    // Если заказ не добавился в базу
    if(!$order_id)
    {
      $this->smarty->assign('error', "Внутренняя ошибка при сохранении заказа");
      return $this->show_cart();
    }

    // Добавим все товары в базу к этому заказу
    // Попутно вычислим сумму заказа для определения стоимости доставки
    $total_price = 0;
    $variants = array();
    if(!is_array($_SESSION['shopping_cart']))
    {
      $this->smarty->assign('error', "Пустой заказ");
      return $this->show_cart();
    }
     
	$variants_ids = array_keys($_SESSION['shopping_cart']);
	  
	$discount=isset($this->user->discount)?$this->user->discount:0;
	$query = sql_placeholder("SELECT products_variants.*, products.model as model, products.url as url, products.product_id as product_id,
			products_variants.price*(100-$discount)/100 as discount_price
			FROM products_variants, products WHERE products.product_id=products_variants.product_id AND products_variants.variant_id in (?@) ORDER BY products.order_num, products_variants.position", $variants_ids);
	$this->db->query($query);
	$variants = $this->db->results();
    if(empty($variants))
    {
      $this->smarty->assign('error', "Товары отсутствуют на складе");
      return $this->show_cart();
    }
  	
  	foreach($variants as $k=>$variant)
    {
           // А количество товара - не должно превысить количество на складе
          // (мало ли, может пока юзер играется с корзиной, кто-то купил уже эти товары)
          if($_SESSION['shopping_cart'][$variant->variant_id] > $variant->stock)
          {
            $this->smarty->assign('error', "Нехватает товаров на складе");
            return $this->show_cart();          
          }
          $amount = $_SESSION['shopping_cart'][$variant->variant_id];     
          // Добавим стоимость к общей сумме
          $total_price += $variant->discount_price*$amount;   
  	    
  	      $query = sql_placeholder('INSERT INTO orders_products(order_id, product_id, variant_id, product_name, variant_name, price, quantity) VALUES(?, ?, ?, ?, ?, ?, ?)',
                         	      $order_id, $variant->product_id, $variant->variant_id,  $variant->model,  $variant->name, $variant->discount_price, $amount);
          $this->db->query($query);
    }

    // Если указан способ доставки, добавим в заказ это 
    if(isset($_POST['delivery_method_id']))
    {
      $delivery_method_id = intval($_POST['delivery_method_id']);
  	  $query = sql_placeholder("SELECT * FROM delivery_methods WHERE enabled AND delivery_method_id=? LIMIT 1", $delivery_method_id);
  	  $this->db->query($query);
  	  $delivery_method = $this->db->result();
      $this->smarty->assign('delivery_method', $delivery_method); 

      // Вычислим стоимость доставки
      $delivery_price = 0;
      if($delivery_method->free_from > $total_price)
      {
        $delivery_price = $delivery_method->price; 
      }  	

      $query = sql_placeholder("UPDATE orders SET delivery_method_id=?, delivery_price=? WHERE order_id=?",
  	                            $delivery_method_id, $delivery_price, $order_id); 
  	  $this->db->query($query);   
    }

    // Теперь нам нужно всех поздравить с заказом 
        
    // Получаем наш заказ из базы
    // (он у нас и так есть, но для надежности берем из базы)
    $order = Order::get_order_by_code($code);
    if(empty($order))
    {
      $this->smarty->assign('error', "Ошибка сохранения заказа");
      return $this->show_cart();
    }

    $this->smarty->assign('order', $order);
 
    // Сформируем массив способов оплаты
  	$query = "SELECT * FROM payment_methods ORDER BY payment_method_id";
  	$this->db->query($query);
  	$payment_methods = $this->db->results();
    $this->smarty->assign('payment_methods', $payment_methods);
       
    // Письмо администратору
    $message = $this->smarty->fetch('../../../admin/templates/email_order_admin.tpl');
    $this->email($this->settings->admin_email, 'Заказ №'.$order->order_id, $message);
    
    // Письмо пользователю
    if(!empty($order->email))
    {
    	$message = $this->smarty->fetch('email_order.tpl');
    	$this->email($order->email, 'Заказ №'.$order->order_id, $message);
    }

    unset($_SESSION['shopping_cart']);
    $_SESSION['order_code'] = $code;

    header("Location: http://$this->root_url/order/$code");
    exit();
  } 


  //////////////////////////////////////////
  // Функция для обновления товаров в корзине
  // $product_id - id товара
  // $quantity - количество товара
  // $add - флаг, определяющий дополнять количество или изменять
  //////////////////////////////////////////
  function update($variant_id, $amount = 1, $add = false)
  {
  	 if($amount == 0 && !$add)
  	 {
  	   // Если количества товара 0, удаляем его из корзины
  	   unset($_SESSION['shopping_cart'][$variant_id]);
  	 }
  	 else
  	 {
  	   if($add)
  	   {
  	     if(!isset($_SESSION['shopping_cart'][$variant_id]))
  	       $_SESSION['shopping_cart'][$variant_id] = 0;
         $_SESSION['shopping_cart'][$variant_id] += intval($amount);
       }
       else
       {
         $_SESSION['shopping_cart'][$variant_id] = intval($amount);
       }    
     }
  }
  
  

}