<!-- Управление товарами /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Storefront" class="off">товары</a></li>
    <li><a href="index.php?section=Categories" class="off">категории</a></li>
    <li><a href="index.php?section=Brands" class="on">бренды</a></li>
    <li><a href="index.php?section=Properties" class="off">свойства</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> → Бренды
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
	    <img src="./images/icon_brands.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Бренды</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=brands" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
		 <!-- Помощь2 /-->
        <div class="help2">
            <a href='index.php?section=Brand&token={$Token}' class="fl"><img src="./images/add.jpg" alt="" class="fl"/>Добавить бренд</a>
        </div>
        <!-- /Помощь2 /-->
      </div>

	  <!-- Right Side #Begin/-->
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
          
        {$PagesNavigation}
  
        {if $Brands}
          
        <!-- Форма товаров #Begin /-->
        <form name='brands' method="post">
            <table id="list2">
            
              {* Список товаров *}
              {foreach item=item from=$Brands}
              <tr>
                <td>
                  <div class="list_left">

                    <div class="flxc" >
                      <p>
                        <a href="index.php{$item->edit_get}" class="tovar_on">{$item->name|escape}</a>
                      </p>
                      <p>
                        <a class="tovar_min" href='http://{$root_url}/brands/{$item->url}'>http://{$root_url}/brands/{$item->url}</a>
                      </p>
                    </div>
                  </div>

                  <a href="index.php{$item->delete_get}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" alt="Удалить" title="Удалить"/></a>

                </div>
              </td>
            </tr>
            {/foreach}
            {* /Список товаров *}
          </table>
          <!-- Форма Товаров #End /-->
          {else}
            <div class="emptylist">Нет брендов</div>
          {/if}

          {$PagesNavigation}
        </form>
      </div>
	  <!-- center  #End/-->
	
    </div>
  </div>	   
</div>	   