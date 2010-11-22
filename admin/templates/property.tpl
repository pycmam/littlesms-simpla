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
          <a href="./">Simpla</a>
          {if $Menu} → <a href='index.php?section=Sections&menu={$Menu->menu_id}'>{$Menu->name}</a>{/if}
           → {if $Section->section_id}{$Section->name}{else}Новая страница{/if}
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
        <h1 id="headline">{if $Property->property_id}{$Property->name}{else}Новое свойство{/if}</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=section" title="Помощь" class="thickbox">Помощь</a>
        </div>
        <!-- /Помощь /-->
      </div>

      <div id="cont_center">

     
        <div class="clear">&nbsp;</div>	  
        {if $Error}
        <!-- Error #Begin /-->
        <div id="error_minh">
          <div id="error">
            <img src="./images/error.jpg" alt=""/><p>{$Error}</p>					
          </div>
        </div>
        <!-- Error #End /-->
        {/if}
          



        <!-- Форма #Begin /-->

				<form name=property METHOD=POST>
					<div id="over">		
					<div id="over_left">	
							<table>
								<tr>
									<td class="model">Название</td>
									<td class="m_t"><p><input name="name" type="text" class="input3" value='{$Property->name|escape}' format='.+' notice='{$Lang->ENTER_NAME}'/></p></td>
								</tr>
								<tr>
									<td class="model"></td>
									<td class="model"><p>
									 <input name=enabled type="checkbox" class="checkbox" {if $Property->enabled || !$Property->property_id}checked{/if} value='1'/> Активно<br>
									 <input name=in_product type="checkbox" class="checkbox" {if $Property->in_product || !$Property->property_id}checked{/if} value='1'/> Отображать в товаре<br>
									 <input name=in_compare type="checkbox" class="checkbox" {if $Property->in_compare || !$Property->property_id}checked{/if} value='1'/> Использовать в сравнении товаров										
									 <input name=in_filter type="checkbox" class="checkbox" {if $Property->in_filter || !$Property->property_id}checked{/if} value='1'/> Использовать в фильтре<br>
									</p>
									</td>
								</tr>																
							</table>


							<div class="gray_block">
								<table>
								<tr>
									<td class="model2">
									<p>Возможные значения:</p>
									<p><textarea style='width:410px; height:150px' name=options>{foreach name=options item=option from=$Property->options}{$option|escape}{if !$smarty.foreach.options.last}&#10;{/if}{/foreach}</textarea></p>
									</td>
								</tr>														
								</table>
							</div>							


							
							<input name=property_id type=hidden value='{$Property->property_id}'>
							<p><input type="submit" value="Сохранить" class="submit"/></p>
					</div>
					
					
				
					<div id="over_right">
					
				
						<div class="gray_block1">
							<span class="model">Использовать в категориях:</span>
							<br>

							<select name=categories[] class="select3" multiple size=15>
								{include file=cat_option.tpl Categories=$Categories SelectedCategories=$Property->categories level=0}
							</select>
								
								
						</div>
					</div>
					

				</div>

			</div>
			</form>
			
	 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 

{include file='tinymce_init.tpl'}


{* JAVASCRIPT *}
<script>
{literal} 
function show_params(div_id)
{
  all_divs = window.document.getElementsByName('params_div');
  for(i = 0; i < all_divs.length; i++) {
	all_divs[i].style.display='none';
  }
  div = window.document.getElementById(div_id);
  div.style.display='block';
}
show_params(window.document.property.type.value);
{/literal}
</script>
{* /JAVASCRIPT *}



