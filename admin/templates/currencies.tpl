<!-- Управление статьями /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Setup" class="off">параметры</a></li>
      <li><a href="index.php?section=Currency" class="on">валюты</a></li>
      <li><a href="index.php?section=DeliveryMethods" class="off">доставка</a></li>
      <li><a href="index.php?section=PaymentMethods" class="off">оплата</a></li>
      <li><a href="index.php?section=SmsNotify" class="off">SMS уведомления</a></li>
  </ul>
  <!-- /Вкладки /-->

  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          Валюты</a>
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
	    <img src="./images/icon_currencies.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->

	    <!-- Заголовок раздела /-->
        <h1 id="headline">Валюты</h1>
        <!-- /Заголовок раздела /-->

        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=currencies" title="Помощь" class="thickbox">Помощь</a>
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




				<FORM name=currency METHOD=POST>

  {if $Items}
							<table>
								<tr>
								    <td></td>
									<td class="small_text">Название</td>
									<td class="small_text">Знак</td>
									<td class="small_text">Код ISO</td>
									<td class="small_text" colspan=2  style='padding-left:20px;'>Курс</td>
									<td></td>
								</tr>

     {foreach item=item from=$Items}
								<tr>
								    <td>
								       {if $item->def}<img alt='По умолчанию на сайте' title='По умолчанию на сайте' src='images/magnet_on.jpg'>
								       {else}<a href='index.php?section=Currency&set_default={$item->currency_id}&token={$Token}'><img alt='Сделать валютой по умолчанию' title='Сделать валютой по умолчанию' src='images/magnet_off.jpg'>{/if}</a>

								       {if $item->main}<img alt='Базовая валюта' title='Базовая валюта' src='images/pin_on.jpg'>
								       {else}<a href='index.php?section=Currency&set_main={$item->currency_id}&token={$Token}'
								       onclick='if(confirm("Пересчитать курсы валют и цены?")) this.href="index.php?section=Currency&recalculate=1&set_main={$item->currency_id}&token={$Token}";'><img alt='Сделать базовой валютой' title='Сделать базовой валютой' src='images/pin_off.jpg'>{/if}</a>
								    </td>
									<td class="td_padding">
									  <input type="text" class="input7" name=names[{$item->currency_id}] value='{$item->name|escape}'/>
									</td>
									<td class="td_padding">
									  <input type="text" class="input5" name=signs[{$item->currency_id}] value='{$item->sign|escape}'/>
									</td>
									<td class="td_padding">
									  <input type="text" class="input5" name=codes[{$item->currency_id}] value='{$item->code|escape}'/>
									</td>
									<td class="td_padding" style='padding-left:20px;'>
									  <input type="text" class="input5" name=rates_from[{$item->currency_id}] value='{$item->rate_from|escape}'/>
									  {$item->sign}
									</td>
									<td class="td_padding">
									  =
									  <input type="text" class="input5" name=rates_to[{$item->currency_id}] value='{$item->rate_to|escape}'/>
									  {$MainCurrency->sign}
									</td>
									<td>
									  <a href="index.php?section=Currency&delete_id={$item->currency_id}&token={$Token}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" alt=""/></a>
									</td>
								</tr>
	  {/foreach}
	                        <tr>
	                          <td colspan=4></td>
	                          <td colspan=3>
	                            <input type=hidden name='token' value='{$Token}'>
	                            <input type="submit" value="Сохранить" class="submit"/>
	                          </td>
	                        </tr>
							</table>

{else}
  Нет валют
{/if}
</form>

<div class="new_currency_block">
<span class="model4">Новая валюта</span>
<form method=post>

							<table>
								<tr>
									<td class="small_text">Название</td>
									<td class="small_text">Знак</td>
									<td class="small_text">Код ISO</td>
									<td class="small_text" colspan=2  style='padding-left:20px;'>Курс</td>
									<td></td>
								</tr>


								<tr>
									<td class="td_padding">
									  <input type="text" class="input7" name='name' />
									</td>
									<td class="td_padding">
									  <input type="text" class="input5" name='sign' onchange='window.document.getElementById("sign").innerText=this.value' />
									</td>
									<td class="td_padding">
									  <input type="text" class="input5" name='code' />
									</td>
									<td class="td_padding" style='padding-left:20px;'>
									  <input type="text" class="input5" name=rate_from value='1.00' />
									  <span id=sign></span>
									</td>
									<td class="td_padding">
									  =
									  <input type="text" class="input5" name=rate_to value='1.00'/>
									  {$MainCurrency->sign}
								    </td>
								    <td class="td_padding">
	                                  <input type="submit" value="Добавить" class="submit11"/>
	                                  <input type=hidden name='token' value='{$Token}'>
									  <input type=hidden name='act' value='add'>
									</td>
								</tr>

							</table>

</form>
</div>

    </div>
  </div>
</div>
<!-- Content #End /-->










