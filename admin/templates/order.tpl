<SCRIPT src="../js/baloon/js/default.js" language="JavaScript" type="text/javascript"></SCRIPT>
<SCRIPT src="../js/baloon/js/validate.js" language="JavaScript" type="text/javascript"></SCRIPT>
<SCRIPT src="../js/baloon/js/baloon.js" language="JavaScript" type="text/javascript"></SCRIPT>
<LINK href="../js/baloon/css/baloon.css" rel="stylesheet" type="text/css" />

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Orders" class="{if $Order->status==0}on{else}off{/if}">новые</a></li>
      <li><a href="index.php?section=Orders&view=process" class="{if $Order->status==1}on{else}off{/if}">в обработке</a></li>
      <li><a href="index.php?section=Orders&view=done" class="{if $Order->status==2}on{else}off{/if}">выполнены</a></li>
      <li><a href="index.php?section=Orders&view=search" class="off">поиск</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
            {if $Order->status==0}<a href='index.php?section=Orders&view=new'>Новые заказы</a>{/if}
            {if $Order->status==1}<a href='index.php?section=Orders&view=process'>Заказы в обработке</a>{/if}
            {if $Order->status==2}<a href='index.php?section=Orders&view=done'>Выполненные заказы</a>{/if}
             → Заказ №{$Order->order_id}
          </a>
        </p>
      </td>
    </tr>
  </table>
  <!-- /Путь /-->
</div>	
 
<!-- Content #Begin /-->
<div id="content">
  <div id="cont_border">
    <div id="cont">
     
      <div id="cont_top">
        <!-- Иконка раздела /--> 
	    <img src="./images/icon_orders.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Заказ №{$Order->order_id}</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=orders" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
      </div>

      <div id="cont_center">

     
        <div class="clear">&nbsp;</div>	  
        {if $Error}
        <!-- Error #Begin /-->
        <div id="error_minh">
          <div id="error">
            <img src="./images/error.jpg" alt=""/><p>{$Error}</p>					
          </div>
        </div>
        <!-- Error #End /-->
        {/if}
          



        <!-- Форма товара #Begin /-->

				<FORM name=product METHOD=POST enctype='multipart/form-data'>
					<div id="over">		
					<div id="over_left">	


												
							<table>
								<tr>
									<td class="model">Дата</td>
									<td class="model"><p>{$Order->date}</p></td>
								</tr>
								<tr>
									<td class="model">IP-адрес</td>
									<td class="model"><p>{$Order->ip} (<a href='http://www.ip-adress.com/ip_tracer/{$Order->ip}/'>где это?</a>)</p></td>
								</tr>
								<tr>
									<td class="model">Имя</td>
									<td class="m_t"><p><input name="name" type="text" class="input3" value='{$Order->name|escape}' /></p></td>
								</tr>
								<tr>
									<td class="model">Email</td>
									<td class="m_t"><p><input name="email" type="text" class="input3" value='{$Order->email|escape}' /></p></td>
								</tr>
								<tr>
									<td class="model">Телефон</td>
									<td class="m_t"><p><input name="phone" type="text" class="input3" value='{$Order->phone|escape}' /></p></td>
								</tr>
								<tr>
									<td class="model">Адрес</td>
									<td class="m_t"><p><input id=address name="address" type="text" class="input3" value='{$Order->address|escape}' />
									<br>
									<a id=maplink href='http://maps.yandex.ru/' onclick='window.document.getElementById("maplink").href="http://maps.yandex.ru/?text="+window.document.getElementById("address").value;'>найти адрес на карте</a>
									</p></td>
								</tr>
								<tr>
									<td class="model">Доставка</td>
									<td class="m_t"><p>
										<script language='javascript'>
										var order_amount = {$Order->amount};
										var delivery_prices = new Array();
										delivery_prices[0]=0;
										{foreach from=$DeliveryMethods item=delivery_method}
										  delivery_prices[{$delivery_method->delivery_method_id}]={$delivery_method->final_price};
										{/foreach}

										{literal}
										function change_delivery_method(delivery_method_id)
										{
											var price = delivery_prices[delivery_method_id];
											this.document.getElementById('delivery_price_input').value=price.toFixed(2);
											this.document.getElementById('delivery_price').innerHTML=price.toFixed(2);
											this.document.getElementById('total_sum').innerHTML=(order_amount+price).toFixed(2);
											
										}
										{/literal}
										</script>
						
										<select name=delivery_method_id class="select2" onchange="change_delivery_method(this.value);">
											<option value=0>Не указан</option>
  											{foreach name=delivery_methods from=$DeliveryMethods item=delivery_method}
  											<option value='{$delivery_method->delivery_method_id}' {if $delivery_method->delivery_method_id == $Order->delivery_method_id}selected{/if}>{$delivery_method->name}</option>
											{/foreach}
										</select>
																	
										<input name="delivery_price" id="delivery_price_input" type="text" class="input4" value='{$Order->delivery_price|escape}' />
										<span class=model>{$MainCurrency->sign}</span>
									</td>
								</tr>
								<tr>
									<td class="model">Комментарий</td>
									<td class="m_t"><p><textarea name="comment" class='textarea2'>{$Order->comment|escape}</textarea></p></td>
								</tr>
							</table>

							<div class="yellow_block">
							<table>
								<tr>
									<td class="model">Статус </td>
									<td class="m_t"><p>
										<select name=status class="select2" onchange='document.getElementById("notify_user").checked=1;'>
											<option value=0 {if $Order->status==0}selected{/if}>Новый</option>
											<option value=1 {if $Order->status==1}selected{/if}>В обработке</option>
											<option value=2 {if $Order->status==2}selected{/if}>Выполнен</option>
										</select>
									</p></td>
								</tr>
								<tr>
									<td class="model">Форма оплаты </td>
									<td class="m_t"><p>
										<select name=payment_method_id class="select2" {literal}onchange="if(this.value>0){val=1;}else{val=0};document.getElementById('payment_status').checked=val;"{/literal}>
  											<option value='0'>Не определена</option>
  											{foreach name=payment from=$PaymentMethods item=payment_method}
  											<option value='{$payment_method->payment_method_id}' {if $payment_method->payment_method_id == $Order->payment_method_id}selected{/if}>{$payment_method->name}</option>
											{/foreach}
										</select>
										<input name=payment_status id=payment_status type="checkbox" class="checkbox" {if $Order->payment_status==1}checked{/if} value='1' onchange='document.getElementById("notify_user").checked=1;'/><span class="akt">Оплачен</span>
									</p>
									</td>
								</tr>
								{if $Order->payment_status}
								<tr>
									<td class="model">Дата оплаты</td>
									<td class="m_t"><p>
									  <span class=model>{$Order->payment_date}</span>
									</p></td>
								</tr>

								{/if}
								</table>
							</div>

							<p >
							<input type='checkbox' name='notify_user' id='notify_user' value='1'> Уведомить пользователя о состоянии заказа
							<input type="submit" value="Сохранить" class="submit"/></p>
					</div>
					
					
					<div id="over_right">
						<div class="gray_block1">

<span class="model">Что заказано:</span>

<table class="order_products">
  {foreach from=$Order->products item=product}
  <tr>
    <td class="td_1">
      <a href="http://{$root_url}/products/{$product->url}">{$product->product_name}</a>{if $product->variant_name}<br>{$product->variant_name}{/if}
    </td>
    <td class="td_2">
      {$product->quantity} &times; {$product->price*$MainCurrency->rate_from/$MainCurrency->rate_to|string_format:"%.2f"}&nbsp;{$MainCurrency->sign}
    </td>
  </tr>
  {/foreach}
  {if $Order->delivery_method}
  <tr>
    <td class="td_1">
      {$Order->delivery_method}
    </td>
    <td class="td_2">
       <span id='delivery_price'>{$Order->delivery_price*$MainCurrency->rate_from/$MainCurrency->rate_to|string_format:"%.2f"}</span> {$MainCurrency->sign}
    </td>
  </tr>
  {/if}
  <tr>
    <td class="td_3">
      Итого
    </td>
    <td class="td_4">
      <span id="total_sum">{$Order->amount+$Order->delivery_price}</span> {$MainCurrency->sign}
    </td>
  </tr>
</table>

						</div>
					</div>
				</div>
				
	
			</div>
			</form>
			
	 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 

