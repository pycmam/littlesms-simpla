<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
      <li><a href="index.php?section=Orders" class="{if $View=='new'}on{else}off{/if}">новые</a></li>
      <li><a href="index.php?section=Orders&view=process" class="{if $View=='process'}on{else}off{/if}">в обработке</a></li>
      <li><a href="index.php?section=Orders&view=done" class="{if $View=='done'}on{else}off{/if}">выполнены</a></li>
      <li><a href="index.php?section=Orders&view=search" class="{if $View=='search'}on{else}off{/if}">поиск</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
            {if $View=='new'}Новые заказы{/if}
            {if $View=='process'}Заказы в обработке{/if}
            {if $View=='done'}Выполненные заказы{/if}
            {if $View=='search'}Поиск заказа{/if}
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
        {if $View == 'search'}
	    <img src="./images/icon_search.jpg" alt="" class="line"/>
	    {else}
	    <img src="./images/icon_orders.jpg" alt="" class="line"/>
	    {/if}
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">
            {if $View=='new'}Новые заказы{/if}
            {if $View=='process'}Заказы в обработке{/if}
            {if $View=='done'}Выполненные заказы{/if}
            {if $View=='search'}Поиск заказа{/if}        
        </h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=orders" title="Помощь" class="thickbox">Помощь</a>
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
          
          {if $View == 'search'}
          <div class="clear">&nbsp;</div>	
          <div class=filter>
            <form method=get>
              <input name=section type=hidden value='{$smarty.get.section}'>
              <input name=view type=hidden value='{$smarty.get.view}'>
              <input name=brand type=hidden value='{$smarty.get.brand}'>
              <input name=keyword type=text  class="input3" value='{$smarty.get.keyword|escape}'>
              <input type='submit' value='Найти' class="submit10">
            </form>
          </div>              
          {/if}
          <div class="clear">&nbsp;</div>	
          {$PagesNavigation}

          {if $Orders}

			{*
          <div id="excel">
            <a href="#">Загрузить в Excel</a>
          </div>
          *}
          
          <div class="clear">&nbsp;</div>
 
          <!-- Форма товаров #Begin /-->
          <form name='products' method="post">
          
            
              {* Список заказов *}
              {foreach item=order from=$Orders}
              
				<img src="./images/line.jpg" alt=""/>
              
				<!-- Block #Begin /-->
				<div class="info">
					<table><tr><td>
					<div class="info_left">
					    {if $order->payment_status == 1}<img class=near src="./images/pay_on.jpg" alt="Заказ оплачен ({$order->payment_method})"  title="Заказ оплачен ({$order->payment_method})"/>{else}<img class=near src="./images/pay_off.jpg" alt="Не оплачен" title="Не оплачен"/>{/if}
						<a href='index.php{$order->edit_url}' class='order_number'>Заказ №{$order->order_id}{if $View=='search'}{if $order->status == 0}(новый){elseif $order->status == 1}(в обработке){elseif $order->status == 2}(выполнен){/if}{/if}</a>
						<br>						

						<div class="contact">
						    <p class="contact_on">{$order->date}</p>
						    {if $order->user_id}
							  <p class="contact_on"><a href='index.php?section=Orders&view=search&keyword=user:{$order->user_id}' style='color:green;'>{if $order->name}{$order->name|escape}{else}Имя не указно{/if}</a></p>
							{else}
   							  {if $order->name}<p class="contact_on">{$order->name|escape}</p>{else}<p class="contact_off">Имя не указно</p>{/if}
							{/if}
							{if $order->email}<p class="contact_on">{$order->email|escape}</p>{else}<p class="contact_off">Email не узазан</p>{/if}
							{if $order->phone}<p class="contact_on">{$order->phone|escape}</p>{else}<p class="contact_off">Телефон не указан</p>{/if}
							{if $order->address}<p class="contact_on">{$order->address|escape}</p>{else}<p class="contact_off">Адрес не указан</p>{/if}
							{if $order->comment}<p class="contact_on">{$order->comment|escape|nl2br}</p>{else}<p class="contact_off">Комментарий не указан</p>{/if}
						</div>
					</div>
					</td><td valign="bottom">
					<div class="info_right">
						<div class="info_rl">
							<table id="table2">
							
							
                               {foreach item=product from=$order->products}
								<tr>
									<td class="td1"><a href="http://{$root_url}/products/{$product->url}" class="link">{$product->product_name}</a> {if $product->variant_name}<br>{$product->variant_name}{/if} (<span {if $product->stock < $product->quantity}class=few{/if}>{$product->stock*1}&nbsp;шт.&nbsp;на&nbsp;складе</span>)</td>
									<td class="td2">{$product->quantity}&nbsp;шт. &times; {$product->price} {$MainCurrency->sign}</td>
								</tr>      

                                {/foreach}

							
                                {if $order->delivery_method}
								<tr>
									<td class="td1"><p class="cur">{$order->delivery_method}</p></td>
									<td class="td2"><p class="cur">{$order->delivery_price}&nbsp;{$MainCurrency->sign}</p></td>
								</tr>      
                                {/if}

								<tr>
									<td class="td1"><p class="cur2">К оплате</p></td>
									<td class="td2"><p class="pay">{$order->total_amount} {$MainCurrency->sign}</p></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="desc">
						{if $order->payment_status != 1}
						<a href="index.php{$order->delete_url}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/cancel.jpg" alt="" class="fl"/>Удалить</a>
						{/if}
						{if $order->status==0}
						<a href="index.php{$order->set_to_process_url}" class="fl"><img src="./images/next.jpg" alt="" class="fl_ch"/>В обработку</a>
						{elseif $order->status==1}
						<a href="index.php{$order->set_done_url}" class="fl"><img src="./images/ok.jpg" alt="" class="fl_ch"/>Выполнен</a>
						{/if}
					</div>		
					</td></tr></table>
					<div class="clear">&nbsp;</div>
				</div>	
				<!-- Block #End /-->
				
              
              {/foreach}
              {* /Список заказов *}
    
            </form>
            <!-- Форма Товаров #End /-->
            {else}
              <div class="emptylist">Нет заказов</div>
            {/if}

            {$PagesNavigation}
            <div class="clear">&nbsp;</div>

        </div>
	    <!-- Right side #End/-->
 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 
