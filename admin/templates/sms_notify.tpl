<SCRIPT src="../js/baloon/js/default.js" language="JavaScript" type="text/javascript"></SCRIPT>
<SCRIPT src="../js/baloon/js/validate.js" language="JavaScript" type="text/javascript"></SCRIPT>
<SCRIPT src="../js/baloon/js/baloon.js" language="JavaScript" type="text/javascript"></SCRIPT>
<LINK href="../js/baloon/css/baloon.css" rel="stylesheet" type="text/css" />

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Setup" class="off">параметры</a></li>
      <li><a href="index.php?section=Currency" class="off">валюты</a></li>
      <li><a href="index.php?section=DeliveryMethods" class="off">доставка</a></li>
      <li><a href="index.php?section=PaymentMethods" class="off">оплата</a></li>
      <li><a href="index.php?section=SmsNotify" class="on">SMS уведомления</a></li>
  </ul>
  <!-- /Вкладки /-->

  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> → Настройка SMS-уведомлений
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
	    <img src="./images/icon_notify.png" alt="" class="line"/>
	    <!-- /Иконка раздела /-->

	    <!-- Заголовок раздела /-->
        <h1 id="headline">Настройка SMS-уведомлений</h1>
        <!-- /Заголовок раздела /-->

        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=notify" title="Помощь" class="thickbox">Помощь</a>
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

          <form method="post">
            <div id="over">
              <table>
                <tr>
                  <td class="td_padding">Имя пользователя</td>
                  <td class="td_padding"><p><input type="text" class="input3" name="sms[sms_username]" value='{$Settings->sms_username|escape}' /></p></td>
                </tr>

                <tr>
                  <td class="td_padding">API KEY</td>
                  <td class="td_padding"><p><input type="text" class="input3" name="sms[sms_apikey]" value='{$Settings->sms_apikey|escape}' /></p></td>
                </tr>

                <tr>
                  <td class="td_padding">Отправитель</td>
                  <td class="td_padding"><p><input type="text" class="input3" name="sms[sms_sender]" value='{$Settings->sms_sender|escape}' /> до 11 символов, латиница, цифры</p></td>
                </tr>

                <tr>
                  <td class="td_padding">Номера телефонов</td>
                  <td class="td_padding"><p><input type="text" class="input3" name="sms[sms_phones]" value='{$Settings->sms_phones|escape}' /> для уведомлений, через запятую</p></td>
                </tr>

                <tr>
                  <td class="td_padding"></td>
                  <td class="td_padding"><p><input type="checkbox" name="sms[sms_enabled]" {if $Settings->sms_enabled==1}checked{/if}> Использовать SMS-уведомления</p></td>
                </tr>

                <tr>
                  <td class="td_padding"></td>
                  <td class="td_padding">
                    <p><input type='hidden' name='token' value='{$Token}'>
                       <input type="submit" value="Сохранить" class="submit"/>
                    </p>
                  </td>
                </tr>
              </table>
            </div>
          </form>
    </div>
  </div>
</div>
<!-- Content #End /-->

