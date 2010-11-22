<?php

/**
 * Simpla CMS
 *
 * @copyright 	2009 Denis Pikusov
 * @link 		http://simp.la
 * @author 		Denis Pikusov
 *
 * К этому скрипту обращается webmoney в процессе оплаты
 *
 */
 
// Работаем в корневой директории
chdir ('../');

require_once('Widget.class.php');
require_once('Order.class.php');

class Webmoney extends Widget
{   
	function Webmoney(&$parent)
	{
	    // Вызываем конструктор базового класса
		Widget::Widget($parent);			
	}
	
	function fetch()
	{
      return '';
	}
	
	
	function process()
	{
	  
       // Это предварительный запрос?
       if(isset($_POST['LMI_PREREQUEST']) && $_POST['LMI_PREREQUEST']==1)
       {
          $pre_request = 1;
       }
       else
       {
          $pre_request = 0;
       }

       // Кошелек продавца
       // Кошелек продавца, на который покупатель совершил платеж. Формат - буква и 12 цифр.
       $merchant_purse = $_POST['LMI_PAYEE_PURSE'];

       // Сумма платежа
       // Сумма, которую заплатил покупатель. Дробная часть отделяется точкой.
       $amount = $_POST['LMI_PAYMENT_AMOUNT'];
       
       // Внутренний номер покупки продавца
       // В этом поле передается id заказа в нашем магазине.
       $order_id = $_POST['LMI_PAYMENT_NO'];
       
       // Флаг тестового режима
       // Указывает, в каком режиме выполнялась обработка запроса на платеж. Может принимать два значения: 
       // 0: Платеж выполнялся в реальном режиме, средства переведены с кошелька покупателя на кошелек продавца; 
       // 1: Платеж выполнялся в тестовом режиме, средства реально не переводились.
       $test_mode = $_POST['LMI_MODE'];
       
       // Внутренний номер счета в системе WebMoney Transfer
       // Номер счета в системе WebMoney Transfer, выставленный покупателю от имени продавца
       // в процессе обработки запроса на выполнение платежа сервисом Web Merchant Interface.
       // Является уникальным в системе WebMoney Transfer.
       $wm_order_id = $_POST['LMI_SYS_INVS_NO'];
       
       // Внутренний номер платежа в системе WebMoney Transfer
       // Номер платежа в системе WebMoney Transfer, выполненный в процессе обработки запроса
       // на выполнение платежа сервисом Web Merchant Interface.
       // Является уникальным в системе WebMoney Transfer.
       $wm_transaction_id = $_POST['LMI_SYS_TRANS_NO'];
       
       // Кошелек покупателя
       // Кошелек, с которого покупатель совершил платеж.
       $payer_purse = $_POST['LMI_PAYER_PURSE'];
       
       // WMId покупателя
       // WM-идентификатор покупателя, совершившего платеж.
       $payer_wmid = $_POST['LMI_PAYER_WM'];
       
       // Номер ВМ-карты (электронного чека)
       // Номер чека Paymer.com или номер ВМ-карты, присутствует только в случае,
       // если покупатель производит оплату чеком Пеймер или ВМ-картой.
       $paymer_number = $_POST['LMI_PAYMER_NUMBER'];
       
       
       // Paymer.com e-mail покупателя
       // Email указанный покупателем, присутствует только в случае,
       // если покупатель производит оплату чеком Paymer.com или ВМ-картой.
       $paymer_email = $_POST['LMI_PAYMER_EMAIL'];
       
       // Номер телефона покупателя
       // Номер телефона покупателя, присутствует только в случае,
       // если покупатель производит оплату с телефона в Keeper Mobile.
       $mobile_keeper_phone = $_POST['LMI_TELEPAT_PHONENUMBER'];
       
       // Номер платежа в Keeper Mobile
       // Номер платежа в Keeper Mobile, присутствует только в случае,
       // если покупатель производит оплату с телефона в Keeper Mobile.
       $mobile_keeper_order_id = $_POST['LMI_TELEPAT_ORDERID'];
       
       // Срок кредитования	LMI_PAYMENT_CREDITDAY
       // В случае если покупатель платит с своего кошелька типа C на кошелек продавца типа D
       // (вариант продажи продавцом своих товаров или услуг в кредит), в данном параметре указывается срок кредитования в днях.
       // Настоятельно рекомендуем обязательно проверять сооветствие данного параметра
       // в форме оповещения о платеже значению параметра в форме запроса платежа.
       $credit_days = $_POST['LMI_PAYMENT_CREDITDAYS'];

       // Контрольная подпись
       $hash = $_POST['LMI_HASH'];
       
       // Дата и время выполнения платежа
       // Дата и время реального прохождения платежа в системе WebMoney Transfer в формате "YYYYMMDD HH:MM:SS"
       $date = $_POST['LMI_SYS_TRANS_DATE'];


       // Метод оплаты
       $payment_method_id = $_POST['PAYMENT_METHOD_ID'];


       ////////////////////////////////////////////////
       // Выберем заказ из базы
       ////////////////////////////////////////////////
       $order = Order::get_order_by_id($order_id);
       if(empty($order))
         return 'Оплачеваемый заказ не найден';
         
       // Нельзя оплатить уже оплаченный заказ  
       if($order->payment_status == 1)  
         return 'Этот заказ уже оплачен';
         
         
       ////////////////////////////////////////////////
       // Выбираем из базы соответствующий типу кошелька метод оплаты
       // и его курс к основной валюте
       ////////////////////////////////////////////////
       $query = sql_placeholder("SELECT payment_methods.*, currencies.rate_from as rate_from, currencies.rate_to as rate_to FROM payment_methods, currencies
                                 WHERE payment_methods.enabled=1 AND payment_methods.currency_id = currencies.currency_id AND payment_methods.payment_method_id=? LIMIT 1", $payment_method_id);
       $this->db->query($query);
       $method = $this->db->result();
       if(empty($method))
         return "Неизвестный метод оплаты";
         
       $payment_params = unserialize($method->params);
       
       
       ////////////////////////////////////
       // Проверка контрольной подписи (только для оповещения об успешной оплате, не для pre_request)
       ////////////////////////////////////
       // Контрольная подпись данных о платеже позволяет продавцу проверять как источник данных,
       // так и целостность данных, переданных на Result URL через "Форму оповещения о платеже".
       // При формировании контрольной подписи сервис Web Merchant Interface "склеивает" значения полей,
       // передаваемых "Формой оповещения о платеже"
       if($pre_request != 1)
       {
         $str = $merchant_purse.$amount.$order_id.$test_mode.$wm_order_id.$wm_transaction_id.$date.$payment_params['wm_secret_key'].$payer_purse.$payer_wmid;
         $md5 = strtoupper(md5($str));
         if($md5 !== $hash)
         {
           return "Контрольная подпись не верна";
         }
       }

       ////////////////////////////////////
       // Проверка суммы платежа
       ////////////////////////////////////
       
       //  Первые буквы кошельков
       $merchant_purse_first_letter = strtoupper($merchant_purse[0]);
       $payer_purse_first_letter = strtoupper($payer_purse[0]);
       
       // Если первые буквы кошельков не совпадают - ошибка
       if(($first_purse_letter = $merchant_purse_first_letter) != $payer_purse_first_letter)
         return "Типы кошельков продавца и покупателя не совпадают";

       // Сумма заказа у нас в магазине
       $order_amount = round($order->total_amount*$method->rate_from/$method->rate_to,2);
       
       // Должна быть равна переданной сумме
       if($order_amount != $amount || $amount<=0)
         return "Неверная сумма оплаты";

       ////////////////////////////////////
       // Проверка кошелька продавца
       ////////////////////////////////////
       if($merchant_purse != $payment_params['wm_merchant_purse'])
         return "Неверный кошелек продавца";
  
       ////////////////////////////////////
       // Проверка наличия товара
       ////////////////////////////////////
       
       foreach($order->products as $order_product)
       {
          $query = sql_placeholder("SELECT * FROM products_variants WHERE product_id=? LIMIT 1", $order_product->product_id);
          $this->db->query($query);
          $variant = $this->db->result();
          if($variant->stock < $order_product->quantity)
            return "Нехватка товара $product->model";     
       }
       
       
       // Запишем, запишем (c) antimult
       if(!$pre_request)
       {
         // Установим статус оплачен
         $query = sql_placeholder('UPDATE orders SET payment_status=1, payment_date=NOW(), payment_method_id=?, payment_details=? WHERE order_id=? LIMIT 1', $payment_method_id, var_export($_POST, true), $order->order_id);
         $this->db->query($query);  
         
         // Спишем товары  
         foreach($order->products as $order_product)
         {
           $query = sql_placeholder("UPDATE products_variants SET stock=stock-? WHERE product_id=? LIMIT 1", $order_product->quantity, $order_product->product_id);
           $this->db->query($query);

           $query = sql_placeholder("UPDATE orders SET written_off=1 WHERE order_id=? LIMIT 1", $order->order_id);
           $this->db->query($query);       
         }     
       }
       
       if(!$pre_request)
       {
		 $order = Order::get_order_by_id($order_id);	
		 $this->smarty->assign('order', $order);
    	 // Письмо администратору
    	 $message = $this->smarty->fetch('../../../admin/templates/email_order_admin.tpl');
		 $this->email($this->settings->admin_email, 'Оплачен заказ №'.$order->order_id, $message);
		
		 // Письмо пользователю
		 if(!empty($order->email))
		 {
			$message = $this->smarty->fetch('email_order.tpl');
			$this->email($order->email, 'Принята оплата заказа №'.$order->order_id, $message);
		 }
       }
       
       return "Yes"; 
  	}		
}

// Собсвенно скрипт

$wm = new Webmoney($a = 0);

// Нельзя выводить ошибки, иначе они засветятся в merchant webmoney
$result = @$wm->process();
  
print $result;
?>