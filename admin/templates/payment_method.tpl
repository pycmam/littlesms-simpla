<SCRIPT src="../js/baloon/js/default.js" language="JavaScript" type="text/javascript"></SCRIPT>
<SCRIPT src="../js/baloon/js/validate.js" language="JavaScript" type="text/javascript"></SCRIPT>
<SCRIPT src="../js/baloon/js/baloon.js" language="JavaScript" type="text/javascript"></SCRIPT>
<LINK href="../js/baloon/css/baloon.css" rel="stylesheet" type="text/css" />

{include file='tinymce_init.tpl'}

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Setup" class="off">параметры</a></li>
      <li><a href="index.php?section=Currency" class="off">валюты</a></li>
      <li><a href="index.php?section=DeliveryMethods" class="off">доставка</a></li>
      <li><a href="index.php?section=PaymentMethods" class="on">оплата</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          <a href='index.php?section=PaymentMethods'>Формы оплаты</a> →
      {if $Item->delivery_method_id}
         {$Item->name}
      {else}
        Новая форма оплаты
      {/if}          
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
	    <img src="./images/icon_card.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">
      {if $Item->delivery_method_id}
        {$Item->name}
      {else}
        Новая форма оплаты
      {/if}        
        </h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=payment" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
      </div>

      <div id="cont_center">
        
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

				<FORM name=payment_method METHOD=POST enctype='multipart/form-data'>
					<div id="over">		
					<div id="over_left">	
							<table>
								<tr>
									<td class="model">Название</td>
									<td class="m_t"><p><input name="name" type="text" class="input3" value='{$Item->name|escape}' format='.+' notice='{$Lang->ENTER_NAME}''/>
									</p></td>
								</tr>

								<tr>
									<td class="model">Валюта</td>
									<td class="m_t"><p>
            
              <select name=currency_id class='select1'>
                {foreach from=$Currencies item=currency}
                  <OPTION value='{$currency->currency_id}' {if $currency->currency_id == $Item->currency_id}selected{/if}>{$currency->name} ({$currency->rate_from*1} {$currency->sign} = {$currency->rate_to*1} {$MainCurrency->sign})</OPTION>
                  
                {/foreach}
              </select>									
									<nobr><input name=enabled type="checkbox" class="checkbox" {if $Item->enabled}checked{/if} value='1'/><span class="akt">Активна</span></nobr> &nbsp; &nbsp;
									</p></td>
								</tr>
								<tr>
									<td class="model">Модуль</td>
									<td class="m_t"><p>

									  <select name='module' class='select1' onchange='show_params(window.document.payment_method.module.value);'>
										<OPTION value=''>Ручная обработка</OPTION>
										<OPTION value='webmoney' {if $Item->module=='webmoney'}selected{/if}>Webmoney</OPTION>
										<OPTION value='robokassa' {if $Item->module=='robokassa'}selected{/if}>Robokassa</OPTION>
										<OPTION value='activepay' {if $Item->module=='activepay'}selected{/if}>Activepay</OPTION>
										<OPTION value='rbkmoney' {if $Item->module=='rbkmoney'}selected{/if}>RBK Money</OPTION>
										<OPTION value='assist' {if $Item->module=='assist'}selected{/if}>Assist</OPTION>
										<OPTION value='upc' {if $Item->module=='upc'}selected{/if}>Украинский процессинговый центр</OPTION>
										<OPTION value='receipt' {if $Item->module=='receipt'}selected{/if}>Формирование квитанции</OPTION>
									  </select>
								
									</p></td>
								</tr>

							</table>
							
							<div class="gray_block" id='webmoney' name='params_div' style='display:none;'>
								<table>
								<tr>
									<td class="model2">Кошелек:</td>
									<td class="m_t"><p><input name=params[wm_merchant_purse] type="text" class="input6" value='{$Item->params.wm_merchant_purse}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Секретный ключ:</td>
									<td class="m_t"><p><input name=params[wm_secret_key] type="text" class="input6" value='{$Item->params.wm_secret_key}'/></p></td>
								</tr>
								<tr>
									<td class="model2">Станица успеха:</td>
									<td class="m_t"><p><input name=params[wm_success_url] type="text" class="input6" value='{$Item->params.wm_success_url}'/></p></td>
								</tr>
								<tr>
									<td class="model2">Станица неудачи:</td>
									<td class="m_t"><p><input name=params[wm_fail_url] type="text" class="input6" value='{$Item->params.wm_fail_url}'/></p></td>
								</tr>
								</table>
							</div>							

							<div class="gray_block" id='robokassa' name='params_div' style='display:none;'>
								<table>
								<tr>
									<td class="model2">Логин:</td>
									<td class="m_t"><p><input name=params[robokassa_login] type="text" class="input6" value='{$Item->params.robokassa_login}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Пароль 1:</td>
									<td class="m_t"><p><input name=params[robokassa_password1] type="text" class="input6" value='{$Item->params.robokassa_password1}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Пароль 2:</td>
									<td class="m_t"><p><input name=params[robokassa_password2] type="text" class="input6" value='{$Item->params.robokassa_password2}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Язык шлюза:</td>
									<td class="m_t"><p>
									<select name=params[robokassa_language]>
									   <option value='ru' {if $Item->params.robokassa_language=='ru'}selected{/if}>Русский</option>
									   <option value='en' {if $Item->params.robokassa_language=='en'}selected{/if}>Английский</option>
									</select>
									</td>
								</tr>							
								</table>
							</div>							

							<div class="gray_block" id='activepay' name='params_div' style='display:none;'>
								<table>
								<tr>
									<td class="model2">ID магазина:</td>
									<td class="m_t"><p><input name=params[activepay_id] type="text" class="input6" value='{$Item->params.activepay_id}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Секретный ключ:</td>
									<td class="m_t"><p><input name=params[activepay_secret_key] type="text" class="input6" value='{$Item->params.activepay_secret_key}'/></p></td>
								</tr>							
								</table>
							</div>							


							<div class="gray_block" id='rbkmoney' name='params_div' style='display:none;'>
								<table>
								<tr>
									<td class="model2">ID магазина:</td>
									<td class="m_t"><p><input name=params[rbkmoney_id] type="text" class="input6" value='{$Item->params.rbkmoney_id}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Секретный ключ:</td>
									<td class="m_t"><p><input name=params[rbkmoney_secret_key] type="text" class="input6" value='{$Item->params.rbkmoney_secret_key}'/></p></td>
								</tr>							
								</table>
							</div>							

							<div class="gray_block" id='assist' name='params_div' style='display:none;'>
								<table>
								<tr>
									<td class="model2">Код магазина:</td>
									<td class="m_t"><p><input name=params[assist_shop_id] type="text" class="input6" value='{$Item->params.assist_shop_id}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Списание с карты:</td>
									<td class="m_t"><p>
									<select name=params[assist_delay]>
									   <option value='0' {if $Item->params.assist_delay=='0'}selected{/if}>Сразу</option>
									   <option value='1' {if $Item->params.assist_delay=='1'}selected{/if}>После подтверждения</option>
									</select>
									</p></td>
								</tr>							
								<tr>
									<td class="model2">Язык шлюза:</td>
									<td class="m_t"><p>
									<select name=params[assist_language]>
									   <option value='0' {if $Item->params.assist_language=='0'}selected{/if}>Русский</option>
									   <option value='1' {if $Item->params.assist_language=='1'}selected{/if}>Английский</option>
									</select>
									</td>
								</tr>							
								<tr>
									<td class="model2">Принимать:</td>
									<td class="m_t"><p>
									<input type=checkbox name=params[assist_card_payments] {if $Item->params.assist_card_payments=='1'}checked{/if} value=1> Банковские карты
									<br>
									{*
									<select name=params[assist_card_types]>
									   <option value='0' {if $Item->params.assist_card_types=='0'}selected{/if}>Любые</option>
									   <option value='1' {if $Item->params.assist_card_types=='1'}selected{/if}>VISA</option>
									   <option value='2' {if $Item->params.assist_card_types=='2'}selected{/if}>EC/MC</option>
									   <option value='3' {if $Item->params.assist_card_types=='3'}selected{/if}>DCL</option>
									   <option value='4' {if $Item->params.assist_card_types=='4'}selected{/if}>JCB</option>
									   <option value='5' {if $Item->params.assist_card_types=='5'}selected{/if}>AMEX</option>
									</select>
									<br>
									*}
									<input type=checkbox name=params[assist_webmoney_payments] {if $Item->params.assist_webmoney_payments=='1'}checked{/if} value=1> Webmoney<br>
									<input type=checkbox name=params[assist_paycash_payments] {if $Item->params.assist_paycash_payments=='1'}checked{/if} value=1> PayCash<br>
									<input type=checkbox name=params[assist_eport_payments] {if $Item->params.assist_eport_payments=='1'}checked{/if} value=1> EPort<br>
									<input type=checkbox name=params[assist_epbeeline_payments] {if $Item->params.assist_epbeeline_payments=='1'}checked{/if} value=1> Eport Билайн<br>
									<input type=checkbox name=params[assist_assist_payments] {if $Item->params.assist_assist_payments=='1'}checked{/if} value=1> Assist ID<br>
									</p></td>
								</tr>							
								</table>
							</div>							


							<div class="gray_block" id='upc' name='params_div' style='display:none;'>
								<table>
								<tr>
									<td class="model2">Merchannt id:</td>
									<td class="m_t"><p><input name=params[merchant_id] type="text" class="input6" value='{$Item->params.merchant_id}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Terminal id:</td>
									<td class="m_t"><p><input name=params[terminal_id] type="text" class="input6" value='{$Item->params.terminal_id}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">URL шлюза:</td>
									<td class="m_t"><p><input name=params[gate_url] type="text" class="input6" value='{$Item->params.gate_url}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Язык шлюза:</td>
									<td class="m_t"><p>
									<select name=params[locale]>
									   <option value='ru' {if $Item->params.locale=='ru'}selected{/if}>Русский</option>
									   <option value='en' {if $Item->params.locale=='en'}selected{/if}>Английский</option>
									   <option value='uk' {if $Item->params.locale=='uk'}selected{/if}>Украинский</option>
									   <option value='fr' {if $Item->params.locale=='fr'}selected{/if}>Французский</option>
									   <option value='de' {if $Item->params.locale=='de'}selected{/if}>Немецкий</option>
									   <option value='hr' {if $Item->params.locale==''}selected{/if}>Хорватский</option>
									</select>
									</td>
								</tr>							
								<tr>
									<td class="model2">SSL ключ:</td>
									<td class="m_t"><p><input name=params[ssl_key_file] type="text" class="input6" value='{$Item->params.ssl_key_file}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Сертификат УПЦ:</td>
									<td class="m_t"><p><input name=params[ssl_cert_file] type="text" class="input6" value='{$Item->params.ssl_cert_file}'/></p></td>
								</tr>							
								</table>
							</div>

							<div class="gray_block" id='receipt' name='params_div' style='display:none;'>
								<table>
								<tr>
									<td class="model2">Получатель:</td>
									<td class="m_t"><p><input name=params[recipient] type="text" class="input6" value='{$Item->params.recipient}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">ИНН получателя:</td>
									<td class="m_t"><p><input name=params[inn] type="text" class="input6" value='{$Item->params.inn}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Счет получателя:</td>
									<td class="m_t"><p><input name=params[account] type="text" class="input6" value='{$Item->params.account}'/></p></td>
								</tr>							
							    <tr>
									<td class="model2">Банк получателя:</td>
									<td class="m_t"><p><input name=params[bank] type="text" class="input6" value='{$Item->params.bank}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">БИК:</td>
									<td class="m_t"><p><input name=params[bik] type="text" class="input6" value='{$Item->params.bik}'/></p></td>
								</tr>							
								<tr>
									<td class="model2">Кор. счет:</td>
									<td class="m_t"><p><input name=params[correspondent_account] type="text" class="input6" value='{$Item->params.correspondent_account}'/></p></td>
								</tr>													
								<tr>
									<td class="model2">Денежный знак:</td>
									<td class="m_t"><p><input name=params[banknote] type="text" class="input6" value='{$Item->params.banknote}'/></p></td>
								</tr>													
								<tr>
									<td class="model2">Копейка:</td>
									<td class="m_t"><p><input name=params[pense] type="text" class="input6" value='{$Item->params.pense}'/></p></td>
								</tr>													
								</table>
							</div>


							<p><input type="submit" value="Сохранить" class="submit"/></p>
					</div>
					
				
					<div id="over_right">
					
				
						<div class="gray_block1">
							<span class="model">Возможные способы доставки</span>
							<br>

									
              {foreach from=$DeliveryMethods item=delivery_method}
                <input type=checkbox name=delivery_methods[{$delivery_method->delivery_method_id}] value='1' {if $delivery_method->enabled}checked{/if}> {$delivery_method->name} &nbsp;
                <br>
              {/foreach}									
								
						</div>
					</div>
					
					
				</div>
				
				
				
			
				<div class="area">
					<span class="model4">Описание</span>
					<p><textarea name="description" class="editor_small">{$Item->description}</textarea></p>
				  <p>
				  <INPUT NAME=section_id TYPE=HIDDEN VALUE='{$Section->section_id}'>
				  <input type="submit" value="Сохранить" class="submitx"/></p>
				</div>


				</div>
				<br/><br/>
			</div>
			</form>
			
	 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 


          {* JAVASCRIPT *}
          <script>
          {literal} 
            function show_params(div_id)
            {
              all_divs = new Array('webmoney', 'robokassa', 'activepay', 'rbkmoney', 'assist', 'upc', 'receipt');
		      for(i=0; i<all_divs.length; i++) {
			    document.getElementById(all_divs[i]).style.display='none';
		      }
              div = window.document.getElementById(div_id);
              div.style.display='block';
            }
            show_params(window.document.payment_method.module.value);
          {/literal}
          </script>
          {* /JAVASCRIPT *}

