<script type="text/javascript" language="JavaScript" src="http://reformal.ru/tab.js?title=Simpla&domain=simpla&color=ff367d&align=left&charset=utf-8"></script>


<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Storefront" class="on">simpla</a></li>
  </ul>
  <!-- /Вкладки /-->

</div>	


<!-- Content #Begin /-->
<div id="content">
  <div id="cont_border">
    <div id="cont">
     
      <div id="cont_top">
        <!-- Иконка раздела /--> 
	    <img src="./images/icon_main.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Главная страница</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=adminpanel" title="Помощь" class="thickbox">Помощь</a>
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
          
        <div class="clear">&nbsp;</div>	
        
        
        
					<div id="over">		
					<div id="main_left">	

           
                    
							<div class="main_yellow">
                    <span class="model6">Новые заказы</span>
                    {if $Orders}
                               <table width=100%>
								  {foreach from=$Orders item=order}
									<tr>
										<td class="tovar_on">
										  <a class="tovar_on" href="index.php?section=Order&order_id={$order->order_id}&token={$Token}">№{$order->order_id}</a>
										</td>
										<td>
										  <a class="tovar_on" href="index.php?section=Order&order_id={$order->order_id}&token={$Token}">{$order->name|escape}</a>
										  <br><br>
										 </td>										
										<td class="tovar_on">
										  {$order->total_amount|escape}&nbsp;{$MainCurrency->sign}
										 </td>										
									</tr>
								  {/foreach}
								</table>
					{else}
					 <p class="tovar_on">Нет новых заказов</p>
					{/if}
							</div>
							
            

					</div>
					
					
					<div id="main_right">
					

            <div class="main_gray">
              
              <span class=model><img src="./images/add.jpg" alt="" class="fl"/> Добавьте <a href='index.php?section=Product&token={$Token}' style='color:black;'>товар</a>, <a href='index.php?section=Section&token={$Token}' style='color:black;'>страницу</a>, <a href='index.php?section=NewsItem&token={$Token}' style='color:black;'>новость</a>, <a href='index.php?section=Article&token={$Token}' style='color:black;'>статью</a></span>
  
            </div>
            
            <span class="model" style='padding-left:32px;'>Последние измененные товары</span>
            <br><br>
		
            <table>
            
              {* Список товаров *}
              {foreach item=item from=$Products}
              <tr>
                <td>
                  <div class="list_left">
                    <a href="index.php{$item->set_enabled_get}" class="fl"><img src="./images/{if $item->enabled}lamp_on.jpg{else}lamp_off.jpg{/if}" alt="Активность" title="Активность"/></a>
                    <div class="flxc">
                      <p>
                        <a href="index.php{$item->edit_get}" class="{if $item->enabled}tovar_on{else}tovar_off{/if}">{if $item->category_single_name}{$item->category_single_name|escape}{else}{$item->category_name|escape}{/if} {$item->brand|escape} {$item->model|escape}</a>
                      </p>
                      <p>
                        {if $item->enabled}
                        <a href="http://{$root_url}/products/{$item->url}" class="tovar_min">http://{$root_url}/products/{$item->url}</a>
                        {else}
                        <span class="tovar_min">http://{$root_url}/products/{$item->url}</span>
                        {/if}
                      </p>
                    </div>
			      </div>
                </td>
                <td>
                  <div class="list_right">
                    <a href="index.php{$item->set_hit_get}" class="fl"><img src="./images/{if $item->hit}star_on.jpg{else}star_off.jpg{/if}" alt="Хит" title="Хит"/></a>{if $item->comments_num}<a href="index.php?section=Comments&keyword={$item->category_single_name|escape}+{$item->category_name|escape}+{$item->brand|escape}+{$item->model|escape}" class="fl"><img alt='{$item->comments_num} комментариев' title='{$item->comments_num} комментариев' src="./images/q_on.jpg"/></a>{else}<img alt='Нет комментариев' title='Нет комментариев' class=fl src="./images/q_off.jpg"/>{/if}

             
                  </div>
                </td>
              </tr>
              {/foreach}
              {* /Список товаров *}
            </table>
							
					</div>
				</div>
   
        
          
          
	  </div>  
    </div>
  </div>	    
</div>
<!-- Content #End /--> 


