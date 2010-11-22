<?PHP

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * Класс для отображения заказа
 * Этот класс использует шаблон order.tpl
 *
 */

require_once('Widget.class.php');
class Order extends Widget
{
	var $title = 'Заказ';
	/**
	 *
	 * Конструктор
	 *
	 */
	function Order(&$parent)
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
		if (!$this->param('order_code'))
		{
			if(!isset($_SESSION['order_code']))
				return false;
			$code = $_SESSION['order_code'];
		}
		else
		{
			$code = $this->param('order_code');
		}
		
		// Получаем наш заказ из базы
		$order = Order::get_order_by_code($code);
		
		// Если заказ не существует
		if (!$order)
		{
			return false;
		}
		$this->smarty->assign('order', $order);
		
		// Сформируем массив способов оплаты
		if (!empty($order->delivery_method_id))
		{
			// Если указан способ доставки - выберем соответствующие ему варианты оплаты
			$query = sql_placeholder("SELECT payment_methods.*, currencies.rate_from as currency_rate_from, currencies.rate_to as currency_rate_to, currencies.sign as currency_sign, currencies.code as currency_code
  								   FROM payment_methods, delivery_payment, currencies
  								   WHERE payment_methods.enabled
  								   AND delivery_payment.payment_method_id = payment_methods.payment_method_id
  								   AND (delivery_payment.delivery_method_id=?)
  								   AND currencies.currency_id = payment_methods.currency_id
  								   ORDER BY payment_method_id", $order->delivery_method_id);
		}
		else
		{
			// Иначе - все варианты оплаты
			$query = sql_placeholder("SELECT payment_methods.*, currencies.rate_from as currency_rate_from, currencies.rate_to as currency_rate_to, currencies.sign as currency_sign, currencies.code as currency_code 
  								   FROM payment_methods, currencies
  								   WHERE payment_methods.enabled
  								   AND currencies.currency_id = payment_methods.currency_id
  								   ORDER BY payment_method_id");
		}
		$this->db->query($query);
		$payment_methods = $this->db->results();
		foreach ($payment_methods as $k=>$payment_method)
		{
			$payment_methods[$k]->amount = round($order->total_amount*$payment_method->currency_rate_from/$payment_method->currency_rate_to, 2);
			$payment_methods[$k]->payment_button = $this->payment_button($payment_method, $order);
		}
		$this->smarty->assign('PaymentMethods', $payment_methods);
		return $this->body = $this->smarty->fetch('order.tpl');
	}
	

	/**
	 *
	 * Кнопка для оплаты определенного заказа определенным способом
	 *
	 */
	function payment_button($method, $order)
	{
		switch ($method->module)
		{
		case 'webmoney':
		// Вебмани
			$params = unserialize($method->params);
			
			$success_url = 'http://'.$this->root_url.'/order/'.$order->code;

			$fail_url = 'http://'.$this->root_url.'/order/'.$order->code;
			
			$button = "<form accept-charset='cp1251' method='POST' action='https://merchant.webmoney.ru/lmi/payment.asp'>
						<input type='hidden' name='LMI_PAYMENT_AMOUNT' value='".$method->amount."'>
						<input type='hidden' name='LMI_PAYMENT_DESC' value='Оплата заказа №$order->order_id'>
						<input type='hidden' name='LMI_PAYMENT_NO' value='$order->order_id'>
						<input type='hidden' name='LMI_PAYEE_PURSE' value='".$params['wm_merchant_purse']."'>
						<input type='hidden' name='LMI_SIM_MODE' value='0'>
						<input type='hidden' name='PAYMENT_METHOD_ID' value='$method->payment_method_id'>
						<input type='hidden' name='LMI_SUCCESS_URL' value='$success_url'>
						<input type='hidden' name='LMI_FAIL_URL' value='$fail_url'>
						<input class=payment_button type='submit' value='Перейти к оплате &#8594;' />
						</form>";
			break;
		case 'robokassa':

			$params = unserialize($method->params);
			
			// регистрационная информация (логин, пароль #1)
			// registration info (login, password #1)
			$mrh_login = $params['robokassa_login'];
			$mrh_pass1 = $params['robokassa_password1'];
			
			// номер заказа
			// number of order
			$inv_id = $order->order_id;
			
			// описание заказа
			// order description
			$inv_desc = 'Оплата заказа №'.$order_id;;
			
			// сумма заказа
			// sum of order
			$out_summ = $method->amount;
			
			// метод оплаты - текущий
			$shp_item = $method->payment_method_id;
			
			// предлагаемая валюта платежа
			// default payment e-currency
			$in_curr = "PCR";
			
			// язык
			// language
			$culture = $params['robokassa_language'];;
			
			// формирование подписи
			// generate signature
			$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			
			$button =	"<form accept-charset='cp1251' action='https://merchant.roboxchange.com/Index.aspx' method=POST>".
						"<input type=hidden name=MrchLogin value=$mrh_login>".
						"<input type=hidden name=OutSum value=$out_summ>".
						"<input type=hidden name=InvId value=$inv_id>".
						"<input type=hidden name=Desc value='$inv_desc'>".
						"<input type=hidden name=SignatureValue value=$crc>".
						"<input type=hidden name=Shp_item value='$shp_item'>".
						"<input type=hidden name=IncCurrLabel value=$in_curr>".
						"<input type=hidden name=Culture value=$culture>".
						"<input type=submit class=payment_button value='Перейти к оплате &#8594;'>".
						"</form>";
			break;
		case 'activepay':

			$params = unserialize($method->params);
			
			// номер заказа
			// number of order
			$merchant_data = $order->order_id;
			
			// описание заказа
			// order description
			$merchant_description = 'Заказ_'.$order->order_id;
			
			// сумма заказа
			// sum of order
			$amount = $method->amount;
			
			// адрес, на который попадет пользователь по окончанию продажи в случае успеха
			$redirect_url_ok = 'http://'.$this->root_url.'/order/'.$order->code;
			
			// адрес, на который попадет пользователь по окончанию продажи в случае неудачи
			$redirect_url_failed = 'http://'.$this->root_url.'/order/'.$order->code;
			
			// ID вашего подключения к системе
			$merchant_contract = $params['activepay_id'];
			
			// ID вашего подключения к системе
			$secret_key = $params['activepay_secret_key'];
			
			
			
			// подпись запроса

 			$url_domain = "activepay.ru";
 			$url_uri = "/merchant_pages/create/";
 			$method = "GET";
 			
			$query_string = 'amount='.rawurlencode($amount).'&'.
                			'currency='.rawurlencode($currency).'&'.
                			'merchant_contract='.rawurlencode($merchant_contract).'&'.
							'merchant_data='.rawurlencode($merchant_data).'&'.
                			'merchant_description='.rawurlencode($merchant_description).'&'.
                			'redirect_url_failed='.rawurlencode($redirect_url_failed).'&'.
                			'redirect_url_ok='.rawurlencode($redirect_url_ok);
			$string_to_sign = "$method\n$url_domain\n$url_uri\n$query_string";
			
			$hmac_sha1_hash = hash_hmac("sha1", $string_to_sign, $secret_key, true);
			
			$signature = base64_encode($hmac_sha1_hash);
 

			
			$button =	"<form action='http://activepay.ru/merchant_pages/create/' method=GET>".
						"<input type=hidden name=amount value=$amount>".
						"<input type=hidden name=currency value=$currency>".
						"<input type=hidden name=merchant_contract value='$merchant_contract'>".
						"<input type=hidden name=merchant_data value=$merchant_data>".
						"<input type=hidden name=merchant_description value=$merchant_description>".
						"<input type=hidden name=redirect_url_failed value='$redirect_url_failed'>".
						"<input type=hidden name=redirect_url_ok value='$redirect_url_ok'>".
						"<input type=hidden name=signature value='$signature'>".
						"<input type=submit class=payment_button value='Перейти к оплате &#8594;'>".
						"</form>";
			break;
		case 'rbkmoney':

			$params = unserialize($method->params);
			
			$shop_id = $params['rbkmoney_id'];
			
			// номер заказа
			// number of order
			$order_id = $order->order_id;
			
			// описание заказа
			// order description
			$order_description = 'Оплата заказа №'.$order->order_id;
			
			// сумма заказа
			// sum of order
			$amount = $method->amount;
			
			$currency_code = $method->currency_code;
			
			// адрес, на который попадет пользователь по окончанию продажи в случае успеха
			$redirect_url_ok = 'http://'.$this->root_url.'/order/'.$order->code;
			
			// адрес, на который попадет пользователь по окончанию продажи в случае неудачи
			$redirect_url_failed = 'http://'.$this->root_url.'/order/'.$order->code;

			// ID вашего подключения к системе
			$secret_key = $params['activepay_secret_key'];

			// ID вашего подключения к системе
			$user_email = $order->email;

			
			$button =	"<form action='https://rbkmoney.ru/acceptpurchase.aspx' method=POST>".
						"<input type=hidden name=eshopId value='$shop_id'>".
						"<input type=hidden name=orderId value='$order_id'>".
						"<input type=hidden name=serviceName value='$order_description'>".
						"<input type=hidden name=recipientAmount value='$amount'>".
						"<input type=hidden name=recipientCurrency value='$currency_code'>".
						"<input type=hidden name=successUrl value='$redirect_url_ok'>".
						"<input type=hidden name=failUrl value='$redirect_url_failed'>".
						"<input type=hidden name=user_email value='$user_email'>".
						"<input type=hidden name=userField_1 value='$method->payment_method_id'>".
						"<input type=submit class=payment_button value='Перейти к оплате &#8594;'>".
						"</form>";
			break;
		case 'assist':

			$params = unserialize($method->params);
			
			$shop_id = $params['assist_shop_id'];
			$delay = $params['assist_delay'];
			$language = $params['assist_language'];
			
			// номер заказа
			// number of order
			$order_id = $order->order_id;
			
			// описание заказа
			// order description
			$comment = 'Оплата заказа №'.$order_id;;
			
			// сумма заказа
			// sum of order
			$amount = $method->amount;
			
			// код валюты
			$currency_code = $method->currency_code;
			
			//$payment_url = 'https://test.assist.ru/shops/purchase.cfm';
			$payment_url = 'https://secure.assist.ru/shops/purchase.cfm';
			
			$success_url = 'http://'.$this->root_url.'/order/'.$order->code;
			$fail_url = 'http://'.$this->root_url.'/order/'.$order->code;
			
			
			$button = "<FORM  accept-charset='cp1251' ACTION='".$payment_url."' METHOD='POST'>
					<INPUT TYPE='HIDDEN' NAME='Shop_IDP' VALUE='$shop_id'>
					<INPUT TYPE='HIDDEN' NAME='Order_IDP' VALUE='$order_id'>
					<INPUT TYPE='HIDDEN' NAME='Subtotal_P' VALUE='$amount'>
					<INPUT TYPE='HIDDEN' NAME='Delay' VALUE='$delay'>
					<INPUT TYPE='HIDDEN' NAME='Language' VALUE='$language'>
					<INPUT TYPE='HIDDEN' NAME='URL_RETURN_OK' VALUE='$success_url'>
					<INPUT TYPE='HIDDEN' NAME='URL_RETURN_NO' VALUE='$fail_url'>
					<INPUT TYPE='HIDDEN' NAME='Currency' VALUE='$currency_code'>
					<INPUT TYPE='HIDDEN' NAME='Comment' VALUE='$comment'>

					<INPUT TYPE='HIDDEN' NAME='CardPayment' VALUE='".intval($params['assist_card_payments'])."'>
					<INPUT TYPE='HIDDEN' NAME='WebMoneyPayment' VALUE='".intval($params['assist_webmoney_payments'])."'>
					<INPUT TYPE='HIDDEN' NAME='PayCashPayment' VALUE='".intval($params['assist_paycash_payments'])."'>
					<INPUT TYPE='HIDDEN' NAME='EPortPayment' VALUE='".intval($params['assist_eport_payments'])."'>
					<INPUT TYPE='HIDDEN' NAME='EPBeelinePayment' VALUE='".intval($params['assist_epbeeline_payments'])."'>
					<INPUT TYPE='HIDDEN' NAME='AssistIDCCPayment' VALUE='".intval($params['assist_assist_payments'])."'>
					
					<!--INPUT TYPE='HIDDEN' NAME='DemoResult' VALUE='AS000'-->
					
					<input type=submit  class=payment_button value='Перейти к оплате &#8594;'>
					</FORM>";
			break;
		case 'upc': 
		// Украинский Процессинговый Центр
			if(function_exists('openssl_get_privatekey'))
			{
				$params = unserialize($method->params);
				
				//	подготовить данные
				$merchant_id = $params['merchant_id'];
				$terminal_id = $params['terminal_id'];
				$purchase_time = date("ymdHisO");
				$order_id = $order->order_id;
				$order_desc = 'Оплата заказа №'.$order_id;
				$currency_id = '980';// Гривна. Другую и нельзя
				$amount = round($method->amount*100);// сумма платежа в основной валюте, в копейках
				$session_data = $method->payment_method_id;
				$data = "$merchant_id;$terminal_id;$purchase_time;$order_id;$currency_id;$amount;$session_data;";
				
				// прочитать наш RSA ключ
				if (!is_readable($params['ssl_key_file']))
					return 'ошибка чтения файла ключа';
				$fp = fopen($params['ssl_key_file'], "r");
				$private_key = fread($fp, 8192);
				fclose($fp);
				$pkeyid = openssl_get_privatekey($private_key);
				//	получить подпись
				openssl_sign($data, $signature, $pkeyid);
				// free the key from memory
				openssl_free_key($pkeyid);
				// закодировать значение в BASE64 , так как $signature имеет бинарный формат
				$b64sign = base64_encode($signature);
				$button = "<FORM ACTION='".$params['gate_url']."' METHOD='POST'>
							<INPUT TYPE='HIDDEN' NAME='Version' VALUE='1'>
							<INPUT TYPE='HIDDEN' NAME='MerchantID' VALUE='$merchant_id'>
							<INPUT TYPE='HIDDEN' NAME='TerminalID' VALUE='$terminal_id'>
							<INPUT TYPE='HIDDEN' NAME='TotalAmount' VALUE='$amount'>
							<INPUT TYPE='HIDDEN' NAME='Currency' VALUE='$currency_id'>
							<INPUT TYPE='HIDDEN' NAME='locale' VALUE='".$params['locale']."'>
							<INPUT TYPE='HIDDEN' NAME='OrderID' VALUE='$order_id'>
							<INPUT TYPE='HIDDEN' NAME='SD' VALUE='$session_data'>
							<INPUT TYPE='HIDDEN' NAME='PurchaseTime' VALUE='$purchase_time'>
							<INPUT TYPE='HIDDEN' NAME='PurchaseDesc' VALUE='$order_desc'>
							<INPUT TYPE='HIDDEN' NAME='Signature' VALUE='$b64sign'>
							<INPUT class=payment_button TYPE='submit' VALUE='Оплатить картой &#8594;'>
							</FORM>";
			}else
			{
				$button = "Не установлен модуль open_ssl.";			
			}
			break;
			
		case 'cyberplat': 
		// Cyberplat

				$params = unserialize($method->params);
				
				//	подготовить данные
				$merchant_id = $params['merchant_id'];
				$terminal_id = $params['terminal_id'];
				$purchase_time = date("ymdHisO");
				$order_id = $order->order_id;
				$order_desc = 'Оплата заказа №'.$order_id;
				$currency_id = '980';// Гривна. Другую и нельзя
				$amount = round($method->amount*100);// сумма платежа в основной валюте, в копейках
				$session_data = $method->payment_method_id;
				$data = "$merchant_id;$terminal_id;$purchase_time;$order_id;$currency_id;$amount;$session_data;";
				
				$button = "<FORM ACTION='https://card.cyberplat.ru/cgi-bin/getform.cgi' METHOD='POST'>
							<INPUT TYPE='HIDDEN' NAME='Version' VALUE='1'>
							<INPUT TYPE='HIDDEN' NAME='MerchantID' VALUE='$merchant_id'>
							<INPUT TYPE='HIDDEN' NAME='TerminalID' VALUE='$terminal_id'>
							<INPUT TYPE='HIDDEN' NAME='TotalAmount' VALUE='$amount'>
							<INPUT TYPE='HIDDEN' NAME='Currency' VALUE='$currency_id'>
							<INPUT TYPE='HIDDEN' NAME='locale' VALUE='".$params['locale']."'>
							<INPUT TYPE='HIDDEN' NAME='OrderID' VALUE='$order_id'>
							<INPUT TYPE='HIDDEN' NAME='SD' VALUE='$session_data'>
							<INPUT TYPE='HIDDEN' NAME='PurchaseTime' VALUE='$purchase_time'>
							<INPUT TYPE='HIDDEN' NAME='PurchaseDesc' VALUE='$order_desc'>
							<INPUT TYPE='HIDDEN' NAME='Signature' VALUE='$b64sign'>
							<INPUT class=payment_button TYPE='submit' VALUE='Оплатить картой &#8594;'>
							</FORM>";

			break;
			
		case 'receipt': 
		// Квитанция
			$params = unserialize($method->params);
			
			//	подготовить данные
			$recipient = $params['recipient'];
			$inn = $params['inn'];
			$account = $params['account'];
			$bank = $params['bank'];
			$bik = $params['bik'];
			$correspondent_account = $params['correspondent_account'];		

			$button = "<FORM ACTION='connectors/receipt.php' METHOD='POST'>
						<INPUT TYPE='HIDDEN' NAME='recipient' VALUE='".$params['recipient']."'>
						<INPUT TYPE='HIDDEN' NAME='inn' VALUE='".$params['inn']."'>
						<INPUT TYPE='HIDDEN' NAME='account' VALUE='".$params['account']."'>
						<INPUT TYPE='HIDDEN' NAME='bank' VALUE='".$params['bank']."'>
						<INPUT TYPE='HIDDEN' NAME='bik' VALUE='".$params['bik']."'>
						<INPUT TYPE='HIDDEN' NAME='correspondent_account' VALUE='".$params['correspondent_account']."'>
						<INPUT TYPE='HIDDEN' NAME='banknote' VALUE='".$params['banknote']."'>
						<INPUT TYPE='HIDDEN' NAME='pence' VALUE='".$params['pense']."'>
						<INPUT TYPE='HIDDEN' NAME='order_id' VALUE='$order->order_id'>
						<INPUT TYPE='HIDDEN' NAME='amount' VALUE='".$method->amount."'>
						<INPUT class=payment_button TYPE='submit' VALUE='Сформировать квитанцию  &#8594;'>
						</FORM>";
			break;
			
		default:
			$button = '';
		}
		return $button;
	}
	
	/**
	 *
	 * Возвращает заказ по коду
	 *
	 */
	function get_order_by_code($code)
	{
		$query = sql_placeholder("SELECT * FROM orders WHERE code=? LIMIT 1", $code);
		$this->db->query($query);
		$order = $this->db->result();
		if ($order)
		{
			return Order::get_order_by_id($order->order_id);
		}
		else
		{
			return false;
		}
	}
	
	/**
	 *
	 * Возвращает заказ по id
	 *
	 */
	function get_order_by_id($order_id)
	{
		// На всякий случай приводим к числу
		$order_id = intval($order_id);
		$query = sql_placeholder("SELECT orders.*,
									 SUM(orders_products.price*orders_products.quantity)+orders.delivery_price as total_amount,
									 DATE_FORMAT(orders.date, '%d.%m.%Y %H:%i') as date,
									 DATE_FORMAT(orders.payment_date, '%d.%m.%Y %H:%i') as payment_date,									 									 
									 delivery_methods.name as delivery_method
							  	FROM orders
								   LEFT JOIN orders_products ON orders.order_id = orders_products.order_id
								   LEFT JOIN delivery_methods ON orders.delivery_method_id = delivery_methods.delivery_method_id
							  	WHERE orders.order_id=?
							 	GROUP BY orders.order_id
							 	LIMIT 1", $order_id);
		$this->db->query($query); 
		$order = $this->db->result();

		if ($order)
		{
			// Все товары в этом заказе
			$query = sql_placeholder("SELECT orders_products.*, products.url as url, products.download as download
										FROM orders_products LEFT JOIN products ON products.product_id=orders_products.product_id WHERE orders_products.order_id=?", $order_id);
			$this->db->query($query);
			$order->products = $this->db->results();
		}
		return $order;
	}
}
