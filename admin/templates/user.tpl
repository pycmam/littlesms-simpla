<!-- Управление товарами /-->

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Users{if $smarty.get.group}&group={$smarty.get.group}{/if}{if $smarty.get.page}&page={$smarty.get.page}{/if}{if $smarty.get.keyword}&keyword={$smarty.get.keyword}{/if}" class="on">покупатели</a></li>
    <li><a href="index.php?section=Groups" class="off">группы</a></li>
  </ul>
  <!-- /Вкладки /-->
   
  <!-- Путь /-->
  <table id="in_right">
    <tr>
      <td>
        <p>
          <a href="./">Simpla</a> →
          <a href='index.php?section=Users'>Покупатели</a> →
          {if $User->user_id}{$User->name}{else}Новый покупатель{/if}
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
	    <img src="./images/icon_content.jpg" alt="" class="line"/>
	    <!-- /Иконка раздела /-->
	    
	    <!-- Заголовок раздела /-->
        <h1 id="headline">{if $User->user_id}{$User->name}{else}Новый покупатель{/if}</h1>
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

				<FORM name=user METHOD=POST>
					<div id="over">		
					<div id="over_left">	
							<table>
								<tr>
									<td class="model">Имя</td>
									<td class="m_t"><p><input name="name" type="text" class="input3" value='{$User->name|escape}'  {literal}pattern='.{1,}'{/literal} notice='{$Lang->ENTER_NAME}'/></p></td>
								</tr>
								<tr>
									<td class="model">Email</td>
									<td class="m_t"><p><input name="email" type="text" class="input3" value='{$User->email|escape}' /></p></td>
								</tr>
								<tr>
									<td class="model">Группа</td>
									<td class="m_t"><p>
									
										<select name=group_id class="select2">
                                          <OPTION VALUE='' {if !$User->group_id}SELECTED{/if}>Не определена</OPTION>
                                         {foreach name=group key=key item=group from=$Groups}
                                            {if $User->group_id == $group->group_id}
                                              <OPTION VALUE='{$group->group_id}' SELECTED>{$group->name|escape}</OPTION>
                                            {else}
                                              <OPTION VALUE='{$group->group_id}'>{$group->name|escape}</OPTION>
                                            {/if}
                                          {/foreach}
										</select>
										<nobr><input name=enabled type="checkbox" class="checkbox" {if $User->enabled}checked{/if} value='1'/><span class="akt">Активен</span></nobr> &nbsp; &nbsp;
										
									</p>
									</td>
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
