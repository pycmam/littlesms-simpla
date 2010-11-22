<!-- Управление товарами /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Storefront" class="on">товары</a></li>
    <li><a href="index.php?section=Categories" class="off">категории</a></li>
    <li><a href="index.php?section=Brands" class="off">бренды</a></li>
    <li><a href="index.php?section=Properties" class="off">свойства</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          <a href="index.php?section=Storefront">Товары</a>

          {* Текущая категория *}
          {if $CurrentCategory}
          →  <a href="index.php?section=Storefront&category={$CurrentCategory->category_id}">{$CurrentCategory->name}</a>
          {/if}

          {* Текущий бренд (если выбран) *}
          {if $CurrentBrand}
             → {$CurrentBrand->name}
          {/if}
          {* /Текущий бренд*}
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
	    <img src="./images/icon_products.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">
          {if $CurrentCategory}
            {$CurrentCategory->name}
          {else}
            Товары
          {/if} {$CurrentBrand->name}</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=products" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->

		 <!-- Помощь2 /-->
        <div class="help2">
              <a href="index.php{$CreateGoodURL}" class="fl"><img src="./images/add.jpg" alt="" class="fl"/>Добавить товар</a>
        </div>
        <!-- /Помощь2 /-->


      </div>

      <div id="cont_center">
    
        <!-- Левое меню /-->
        <div id="cont_left">
        
          <ul>
            {* Выводим категории *}
            {* Если категория не выбрана, активен пункт "все" *}
            {if !$CurrentCategory}
            <li class="li_on">
              <a href="index.php?section=Storefront">Все категории</a>
            </li>
            {else}
            {* Если категория  выбрана, пункт "все" не активен *}
            <li class="li_off">
              <a href="index.php?section=Storefront">Все категории</a>
            </li>
            {/if}
           
            {defun name=categories_tree categories=$Categories level=0} 
            {foreach from=$Categories item=category}
            {if $category->category_id == $CurrentCategory->category_id}
            <li class="li_on" style='padding-left:{$level*10+15}px;'>
              <a href="index.php?section=Storefront&category={$category->category_id}">{$category->name}</a>
            </li>
        	{* /Текущая категория *}
        	{* Категория *}
        	{else}
            <li class="li_off" style='padding-left:{$level*10+15}px;'>
              <a href="index.php?section=Storefront&category={$category->category_id}">{$category->name}</a>
            </li>
            {/if}
			{fun name=categories_tree Categories=$category->subcategories level=$level+1}  
			{/foreach}
			{/defun}

      		{* /Выводим категории *}
          </ul>
          
          <!-- Горизонтальная линия -->
          <img src="./images/pol.jpg" alt=""/>
          <!-- /Горизонтальная линия -->
          
          <ul>
            {* Если бренд не выбран, активен пункт "все" *}
            {if !$CurrentBrand}
            <li class="li_on">
              <a href="index.php?section=Storefront&category={$CurrentCategory->category_id}">Все бренды</a>
            </li>
            {else}
            {* Если бренд выбран, пункт "все" не активен *}
            <li class="li_off">
              <a href="index.php?section=Storefront&category={$CurrentCategory->category_id}">Все бренды</a>
            </li>
            {/if}
            {* Список брендов *}
            {foreach name=brands item=brand from=$Brands}
            {if $brand->brand_id == $CurrentBrand->brand_id}
            <li class="li_on">
                <a href="index.php{$brand->brand_url}">{$brand->name}</a>
            </li>
            {else}
            <li class="li_off">
              <a href="index.php{$brand->brand_url}">{$brand->name}</a>
            </li>
            {/if} 
            {/foreach}
            {* /Список брендов *}
          </ul>
        </div>
        <!-- /Левое меню /-->
	
	    <!-- Right Side #Begin/-->
        <div id="cont_right">
        
          {if $Error}
          <!-- Error #Begin /-->
          <div id="error_minh">
            <div id="error">
              <img src="./images/error.jpg" alt=""/><p>{$Error}</p>					
            </div>
          </div>
          <!-- Error #End /-->
          {/if}
          
          <div class=filter>
            <form method=get>
              <input name=section type=hidden value='{$smarty.get.section}'>
              <input name=category type=hidden value='{$smarty.get.category}'>
              <input name=brand type=hidden value='{$smarty.get.brand}'>
              <input name=keyword type=text  class="input3" value='{$smarty.get.keyword|escape}'>
              <input type='submit' value='Найти' class="submit10">
            </form>
          </div>          
          
          <div class="clear">&nbsp;</div>
          {$PagesNavigation}
          <div class="clear">&nbsp;</div>
  
          {if $Items}
          <table class="price">
            <tr>
              <td class=th1>
                Цена, {$MainCurrency->sign}
              </td>
              <td class=th2>
                На складе
              </td>
              <td class=th3>
                <a href='#' title='Выбрать все для удаления' onmouseover="$('.deletebutton').hide(0);$('.del').show(0);" onclick="check_all();return false;">↓</a>
                <script>
                {literal}
                function check_all()
                {
                	boxes = document.getElementsByName('delete_items[]');
                	check = false;
					for (i = 0; i < boxes.length; i++)
						if(!boxes[i].checked) check=true;
	
					for (i = 0; i < boxes.length; i++)
						boxes[i].checked=check;                	
                }
                {/literal}
                </script>
              </td>
            </tr>
          </table>
          
          <!-- Форма товаров #Begin /-->
          <form name='products' method="post">
            <table id="list">
            
              {* Список товаров *}
              {foreach item=item from=$Items}
              <tr>
                <td>
                  <div class="list_left">
                    <a href="index.php{$item->move_up_get}" class="fl"><img src="./images/up.jpg" alt="Поднять" title="Поднять"/></a><a href="index.php{$item->move_down_get}" class="fl"><img src="./images/down.jpg" alt="Опустить" title="Опустить"/></a><a href="index.php{$item->set_enabled_get}" class="fl"><img src="./images/{if $item->enabled}lamp_on.jpg{else}lamp_off.jpg{/if}" alt="Активность" title="Активность"/></a>
                    <div class="flxc">
                      <p>
                        <a href="index.php{$item->edit_get}" class="{if $item->enabled}tovar_on{else}tovar_off{/if}" title='{$item->sku}'>{if $item->category_single_name}{$item->category_single_name|escape}{else}{$item->category_name|escape}{/if} {$item->brand|escape} {$item->model|escape}</a>
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
                    <a href="index.php{$item->copy_get}" class="fl"><img src="./images/copy.jpg" alt="Создать копию и перейти к ее редактированию" title="Создать копию и перейти к ее редактированию"/></a><a href="index.php{$item->set_hit_get}" class="fl"><img src="./images/{if $item->hit}star_on.jpg{else}star_off.jpg{/if}" alt="Хит" title="Хит"/></a>{if $item->comments_num}<a href="index.php?section=Comments&keyword={$item->brand|escape}+{$item->model|escape}" class="fl"><img alt='{$item->comments_num} комментариев' title='{$item->comments_num} комментариев' src="./images/q_on.jpg"/></a>{else}<img alt='Нет комментариев' title='Нет комментариев' class=fl src="./images/q_off.jpg"/>{/if}
				    <div class="form2">
				    
				      {if $item->variants|@count == 1}
				    
                      <p class="m_top">
                        <input type="text" class="input1" name="prices[{$item->variants[0]->variant_id}]" value="{$item->variants[0]->price|escape}"/>
                        <input type="text" class="input2" name="stock[{$item->variants[0]->variant_id}]" value="{$item->variants[0]->stock|escape}"/>
                      </p>

                      {elseif $item->variants|@count > 1}
                      <a href='#' onclick="if($('#variants_{$item->product_id}').is(':visible')) $('#variants_{$item->product_id}').hide('fast'); else $('#variants_{$item->product_id}').show('fast'); return false;"><img border=0 style='padding-top:15px;' src='images/openvariants.gif'></a>
                      {else}
                       &nbsp;
                      {/if}
                      
                    </div>
                    <div style='display:none;' class=del><input type=checkbox name=delete_items[] value='{$item->product_id}' title='Удалить товар'></div>
                    <a href="index.php{$item->delete_get}" class="deletebutton fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" alt="Удалить" title="Удалить"/></a>
                  </div>
                  
                  {if $item->variants|@count >1}
                  <div id='variants_{$item->product_id}' style='display:none;' class=variants>
                  {foreach item=variant from=$item->variants}
                  	<div class=variant>
                  		<span>
                  		   {$variant->name}
                  		</span>
                        <input type="text" class="input1" name="prices[{$variant->variant_id}]" value="{$variant->price|escape}"/>
                        <input type="text" class="input2" name="stock[{$variant->variant_id}]" value="{$variant->stock|escape}"/>
                    </div>
                  {/foreach}
                  </div>
                  {/if}
                </td>
              </tr>
              {/foreach}
              {* /Список товаров *}
            </table>
            <input type=submit value='Сохранить изменения' style='display:none;'>
            <input type=hidden name=token value='{$Token}'>
            </form>
            <!-- Форма Товаров #End /-->
            {else}
              <div class="emptylist">Нет товаров</div>
            {/if}

            {$PagesNavigation}
			<!-- Добавить / Сохранить /-->
			<div class="add">
              <a href="index.php{$CreateGoodURL}" class="fl"><img src="./images/add.jpg" alt="" class="fl"/>Добавить товар</a>
              <a href="javascript: this.document.products.submit();" class="flx"><input type=image src="./images/save.jpg" alt="" class="fl"/>Применить</a>
            </div>

        </div>
	    <!-- Right side #End/-->
	  </div>  
    </div>
  </div>	    
</div>
<!-- Content #End /--> 
 