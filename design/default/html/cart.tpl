{*
  Template name: Корзина

  Выводит корзину с формой заказа
  Используется классом Cart.class.php
  
*}
<div id="page_title">  
    <h1 class="float_left">Корзина</h1>
    <!-- Хлебные крошки /-->
    <div id="path">
    <a href="./">Главная</a> → Корзина        
    </div>
    <!-- Хлебные крошки #End /-->
</div>      

{if $variants}
<!-- Корзина /-->
<form method=post name=cart>
    <table id="cart_products">
        <tr>
            <td class="td_1">
                <span>Товар</span>
            </td>
            <td class="td_2">
                <span>Цена</span>
            </td>
            <td class="td_3">
                <span>Количество</span>
            </td>
            <td class="td_4">
                <span>Итого</span>
            </td>
        </tr>
        {foreach from=$variants item=variant}
        <tr>
            <td class="td_1">
              <a href="products/{$variant->url}">{$variant->category|escape} {$variant->brand|escape} {$variant->model|escape}</a> {$variant->name}
            </td>
            <td class="td_2">{$variant->discount_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign}</td>
            <td class="td_3">
                <p><select name=amounts[{$variant->variant_id}] onchange="document.cart.submit_order.value='0'; document.cart.submit();">
                  {section name=amounts start=1 loop=$variant->stock+1 step=1 max=100}
                    <option value="{$smarty.section.amounts.index}" {if $variant->amount==$smarty.section.amounts.index}selected{/if}>{$smarty.section.amounts.index}</option>
                  {/section}
                </select> шт.
                <a href='cart/delete/{$variant->variant_id}' title='убрать из корзины'><img src='design/{$settings->theme}/images/delete.png' alt='убрать из корзины' align="absmiddle"></a>
                </p>
            </td>
            <td class="td_4">
            {$variant->discount_price*$variant->amount*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}&nbsp;{$currency->sign}
            </td>
        </tr>
        {/foreach}
    </table>
    
    <div class="line"><!-- /--></div>
    
    <!-- Итого /-->
    <div class="total_line">
    
        {* Если есть печенька с обратным путем - выводим ссылку "вернуться" *}
        {if $smarty.cookies.from}
        <a href="{$smarty.cookies.from|escape}" class="return_from_cart" onclick="document.cookie='from=;path=/';">← продолжить выбор товаров</a>
        {/if}
        
        <span class=total_sum>Итого: <span id=subtotal_price>{$total_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}</span>&nbsp;{$currency->sign}</span>
    </div>
    
    <br><br>
    <h1>Оформить заказ ↓</h1>
    
    {if $delivery_methods}
    <!-- Способы доставки /-->
    <div class="billet">
        <table>
            {foreach name=delivery from=$delivery_methods item=delivery_method}
            <tr>
                <td class="delivery_select">
                  <p>
                    <input type=radio id=delivery_radio_{$delivery_method->delivery_method_id} name=delivery_method_id value='{$delivery_method->delivery_method_id}' {if $delivery_method->delivery_method_id == $delivery_method_id}checked{/if}  onclick="select_delivery_method({$delivery_method->delivery_method_id});">
                  </p>
                </td>
                <td class="delivery_text">
                    <h3 onclick="select_delivery_method({$delivery_method->delivery_method_id});">{$delivery_method->name} ({if $delivery_method->final_price>0}<span id=delivery_price_{$delivery_method->delivery_method_id}>{$delivery_method->final_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}</span>  {$currency->sign}{else}бесплатно{/if})</h3>
                    {$delivery_method->description}
                </td>
            </tr>
            {/foreach}
        </table>
    </div>          
    
    <div class="total_line">
        <span class=total_sum>Итого с доставкой: <span id=total_price>{$total_price*$currency->rate_from/$currency->rate_to|string_format:"%.2f"}</span>&nbsp;{$currency->sign}</span>
    </div>
    

    {literal}
    <script>
      function select_delivery_method(method_id)
      {
        radiobuttons = document.getElementsByName('delivery_method_id');
        for(var i=0;i<radiobuttons.length;i++)
        {
          if(radiobuttons[i].value == method_id)
          {
            radiobuttons[i].checked = 1;
          }
        }
    
      var subtotal = parseFloat(document.getElementById('subtotal_price').innerHTML);
      var delivery = 0;
      if(document.getElementById('delivery_price_'+method_id))
        delivery = parseFloat(document.getElementById('delivery_price_'+method_id).innerHTML);
      total = subtotal+delivery;
      document.getElementById('total_price').innerHTML = total.toFixed(2);
      }
    </script>
    {/literal}              
    
    <script>
      select_delivery_method({$delivery_method_id});
    </script>
    
    <!-- Способы доставки #End /-->
    {/if}
    
    <h1>Адрес получателя</h1>

    {if $error}
    <div id="error_block"><p>{$error}</p></div>
    {/if}
                    
    <div class="billet">
        <table class=order_form>
            <tr><td>Имя, фамилия</td><td><input name="name" type="text" value="{$name|escape}" /></td></tr>
            <tr><td>Email</td><td><input name="email" type="text" value="{$email|escape}" /></td></tr>
            <tr><td>Телефон</td><td><input name="phone" type="text" value="{$phone|escape}" /></td></tr>
            <tr><td>Адрес доставки</td><td><input name="address" type="text" class="address" value="{$address|escape}"/></td></tr>
            <tr>
                <td>Комментарий к&nbsp;заказу</td>
                <td>
                    <textarea name="comment" id="order_comment">{$comment|escape}</textarea>
        
                    {if $gd_loaded}                             
                    <div class="captcha">
                        <img src="captcha/image.php?t={math equation='rand(10,10000)'}" alt=""/>
                        <p>Число:</p>
                        <p><input type="text" name="captcha_code" /></p>
                    </div>
                    {/if}
                    
                    <p>
                    <input type="hidden" name="submit_order" value="1">
                    <input type="submit" value="Заказать" id="order_button"/>
                    </p>

                </td>
            </tr>
        </table>
    </div>
</form>     
<!-- Корзина #End /-->
{else}
  Корзина пуста
{/if}
