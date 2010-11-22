<!-- Управление товарами /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Users" class="off">покупатели</a></li>
    <li><a href="index.php?section=Groups{if $smarty.get.page}&page={$smarty.get.page}{/if}" class="on">группы</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          <a href='index.php?section=Groups'>Группы покупателей</a> →
          {$Group->name}
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
	    <img src="./images/icon_users.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">{if $Group->group_id}{$Group->name}{else}Новая группа{/if}</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=users" title="Помощь" class="thickbox">Помощь</a>
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
          



        <!-- Форма товара #Begin /-->

				<FORM name=group METHOD=POST>
					<div id="over">		
					<div id="over_left">	
							<table>
								<tr>
									<td class="model">Название</td>
									<td class="m_t"><p><input name="name" type="text" class="input3" value='{$Group->name|escape}'  {literal}pattern='.{1,}'{/literal} notice='{$Lang->ENTER_NAME}'/></p></td>
								</tr>
								<tr>
									<td class="model">Скидка</td>
									<td class="model"><p><input name="discount"  type="text" class="input5" value='{$Group->discount|escape}'/> %
									</p></td>
								</tr>
							</table>


							<p><input type="submit" value="Сохранить" class="submit"/></p>
					</div>
					


				</div>
				<br/><br/>
			</div>
			</form>
			
	 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 
