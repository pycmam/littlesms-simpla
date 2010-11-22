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
      <li><a href="index.php?section=DeliveryMethods" class="on">доставка</a></li>
      <li><a href="index.php?section=PaymentMethods" class="off">оплата</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          <a href="index.php?section=DeliveryMethods">Способы доставки</a> →
      {if $Item->delivery_method_id}
         {$Item->name}
      {else}
        Новый способ доставки
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
	    <img src="./images/icon_truck.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">
      {if $Item->delivery_method_id}
        {$Item->name}
      {else}
        Новый способ доставки
      {/if}        
        </h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=delivery" title="Помощь" class="thickbox">Помощь</a>
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

				<FORM name=product METHOD=POST enctype='multipart/form-data'>
					<div id="over">		
					<div id="over_left">	
							<table>
								<tr>
									<td class="model">Название</td>
									<td class="m_t"><p><input name="name" type="text" class="input3" value='{$Item->name|escape}' format='.+' notice='{$Lang->ENTER_NAME}'/>
									<nobr><input name=enabled type="checkbox" class="checkbox" {if $Item->enabled}checked{/if} value='1'/><span class="akt">Активна</span></nobr> &nbsp; &nbsp;
									</p></td>
								</tr>

							</table>

							
							<div class="yellow_block">
								<table width=100%>
									<tr>
										<td><span class="akt1">Стоимость доставки,</span></td><td><span class="akt1p">Бесплатна от</span></td>									
									</tr>
									<tr>																
										<td class='td_padding'><input name=price value='{$Item->price|escape}' type="text" class="input4"/> {$MainCurrency->sign}</td>
										<td class='td_padding'><input name=free_from value='{$Item->free_from|escape}' type="text" class="input4"/> {$MainCurrency->sign}</td>
									</tr>
								</table>
							</div>

							<p><input type="submit" value="Сохранить" class="submit"/></p>
					</div>
					
				
					<div id="over_right">
					
				
						<div class="gray_block1">
							<span class="model">Возможные формы оплаты</span>
							<br>

									
              {foreach from=$PaymentMethods item=payment_method}
                <input type=checkbox name=payment_methods[{$payment_method->payment_method_id}] value='1' {if $payment_method->enabled}checked{/if}> {$payment_method->name} &nbsp;
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

			</div>
			</form>
			
	 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 


