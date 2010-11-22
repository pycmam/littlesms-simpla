<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Storefront" class="off">товары</a></li>
    <li><a href="index.php?section=Categories" class="on">категории</a></li>
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
          <a href="index.php?section=Storefront">Категории товаров</a>
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
	    <img src="./images/icon_categories.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">Категории товаров</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=categories" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
		 <!-- Помощь2 /-->
        <div class="help2">
            <a href="index.php?section=Category&token={$Token}" class="fl"><img src="./images/add.jpg" alt="" class="fl"/>Добавить категорию</a>
        </div>
        <!-- /Помощь2 /-->

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
          
        {$PagesNavigation}
  
        {if $Categories}

        <!-- Форма товаров #Begin /-->
        <form name='products' method="post">
          <table id="list2">
            
            {* Список товаров *}
            {include file=cat.tpl Categories=$Categories level=0}
            {* /Список товаров *}
          </table>
          </form>
          <!-- Форма Товаров #End /-->
          {else}
            <div class="emptylist">Нет категорий</div>
          {/if}

          {$PagesNavigation}
         

	  </div>  
    </div>
  </div>	    
</div>
<!-- Content #End /--> 

