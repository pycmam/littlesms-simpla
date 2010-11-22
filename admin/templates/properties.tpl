<!-- Управление товарами /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Storefront" class="off">товары</a></li>
    <li><a href="index.php?section=Categories" class="off">категории</a></li>
    <li><a href="index.php?section=Brands" class="off">бренды</a></li>
    <li><a href="index.php?section=Properties" class="on">свойства</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> → Свойства товаров
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
	    <img src="./images/properties.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Свойства товаров</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=sections" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
		 <!-- Помощь2 /-->
        <div class="help2">
			<a href="index.php?section=Property&category={$CurrentCategory->category_id}&token={$Token}" class="fl"><img src="./images/add.jpg" class="fl"/>Добавить свойство</a>  
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
              <a href="index.php?section=Properties">Все категории</a>
            </li>
            {else}
            {* Если категория  выбрана, пункт "все" не активен *}
            <li class="li_off">
              <a href="index.php?section=Properties">Все категории</a>
            </li>
            {/if}
            {* Список брендов *}
            
            {defun name=categories_tree categories=$Categories level=0} 
            {foreach from=$Categories item=category}
            {if $category->category_id == $CurrentCategory->category_id}
            <li class="li_on" style='padding-left:{$level*10+15}px;'>
              <a href="index.php?section=Properties&category={$category->category_id}">{$category->name}</a>
            </li>
        	{* /Текущая категория *}
        	{* Категория *}
        	{else}
            <li class="li_off" style='padding-left:{$level*10+15}px;'>
              <a href="index.php?section=Properties&category={$category->category_id}">{$category->name}</a>
            </li>
            {/if}
			{fun name=categories_tree Categories=$category->subcategories level=$level+1}  
			{/foreach}
			{/defun}

      		{* /Выводим категории *}
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
          
          <div class="clear">&nbsp;</div>	

          {if $Properties}

          <form name='products' method="post">
            <table id="list2">
            
              {* Список разделов *}
              {foreach item=property from=$Properties}
              <tr>
                <td>
                  <div class="list_left">
                    <a href="index.php{$property->move_up_get}" class="fl"><img src="./images/up.jpg"  alt="Поднять" title="Поднять"/></a><a href="index.php{$property->move_down_get}" class="fl"><img src="./images/down.jpg"  alt="Опустить" title="Опустить"/></a><a href="index.php{$property->enable_get}" class="fl"><img src="./images/{if $property->enabled}lamp_on.jpg{else}lamp_off.jpg{/if}"  alt="Активность" title="Активность"/></a>

                    <div class="flxc">
                      <p>
                        <a href="index.php{$property->edit_get}" class="{if $property->enabled}tovar_on{else}tovar_off{/if}">{$property->name|escape}</a>
                      </p>
                      <p>
                        {$property->type}
                      </p>
                    </div>
			      </div>
			      <a href="index.php{$property->in_product_get}" class="fl"><img src="./images/{if $property->in_product}product_small.jpg{else}product_small_off.jpg{/if}"  alt="Отображать в товаре" title="Отображать в товаре"/></a>
			      <a href="index.php{$property->in_compare_get}" class="fl"><img src="./images/{if $property->in_compare}ruler.jpg{else}ruler_off.jpg{/if}"  alt="Использовать в сравнении товаров" title="Использовать в сравнении товаров"/></a>
			      <a href="index.php{$property->in_filter_get}" class="fl"><img src="./images/{if $property->in_filter}filter.jpg{else}filter_off.jpg{/if}"  alt="Использовать в фильтре" title="Использовать в фильтре"/></a>
                  <a href="index.php{$property->delete_get}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" alt="Удалить" title="Удалить"/></a>
                </td>
              </tr>
              {/foreach}
              {* /Список разделов *}
            </table>
            </form>
            <!-- Форма Товаров #End /-->
            {else}
              <div class="emptylist">Нет свойств</div>
            {/if}

        </div>
	    <!-- Right side #End/-->
 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 