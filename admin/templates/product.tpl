<SCRIPT src="../../js/baloon/js/default.js" language="JavaScript" type="text/javascript"></SCRIPT>
<SCRIPT src="../../js/baloon/js/validate.js" language="JavaScript" type="text/javascript"></SCRIPT>
<SCRIPT src="../../js/baloon/js/baloon.js" language="JavaScript" type="text/javascript"></SCRIPT>
<LINK href="../../js/baloon/css/baloon.css" rel="stylesheet" type="text/css" />

<div id="inserts_all">
  <!-- Вкладки /-->
  <ul id="inserts">
    <li><a href="index.php?section=Storefront&category={$Category->category_id}{if $smarty.get.brand_id}&brand_id={$smarty.get.brand_id}{/if}{if $smarty.get.page}&page={$smarty.get.page}{/if}" class="on">товары</a></li>
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
          <a href="index.php?section=Storefront">Товары</a> →
          {if $Item->product_id}
          {if $Category}<a href="index.php?section=Storefront&category={$Category->category_id}">{$Category->name}</a>  →{/if}
          {if $Item->category_single_name}{$Item->category_single_name|escape}{else}{$Item->category_name|escape}{/if} {$Item->brand} {$Item->model}
          {else}
            Новый товар
          {/if}

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
        <h1 id="headline" title='ID={$Item->product_id}' >{if $Item->category_single_name}{$Item->category_single_name|escape}{else}{$Item->category_name|escape}{/if} {if $Item->product_id}{$Item->brand} {$Item->model}{else}Новый товар{/if}</h1>
        <!-- /Заголовок раздела /-->
        
        <!-- Помощь /-->
        <div id="help">
          <a href="usermanual.html?height=450&width=700&scrollto=product" title="Помощь" class="thickbox">Помощь</a>
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

				<FORM name=product METHOD=POST enctype='multipart/form-data'>
					<div id="over">		
					<div id="over_left">	
							<table>
								<tr>
									<td class="model">Название</td>
									<td class="m_t"><p><input name="model" type="text" class="input3" value='{$Item->model|escape}' format='.+' notice='{$Lang->ENTER_NAME}'/></p></td>
								</tr>
								<tr>
									<td class="model">Бренд</td>
									<td class="m_t"><p>
										<select name=brand_id class="select2">
											<option value=0 brand_name=''>Не указан</option>
                                            {foreach from=$Brands item=brand}
                                                 <option value='{$brand->brand_id}' {if $Item->brand_id == $brand->brand_id}selected{/if} brand_name='{$brand->name|escape}'>{$brand->name|escape}</option>
                                            {/foreach}
										</select><nobr><input name=enabled type="checkbox" class="checkbox" {if $Item->enabled}checked{/if} value='1'/><span class="akt">{$Lang->ACTIVE}</span></nobr> &nbsp; 
										<nobr><input name=hit type="checkbox" class="checkbox" {if $Item->hit}checked{/if} value='1'/><span class="akt">{$Lang->HIT}</nobr></span>
									</p></td>
								</tr>
								<tr>
									<td class="model">Категория</td>
									<td class="m_t"><p>
										<select name=category_id class="select2" onchange='display_properties(this.value);'> 
										{defun name=categories_select Categories=$Categories level=0}
										{foreach from=$Categories item=category}
											{if $CurrentCategory != $category->category_id}
												<option value='{$category->category_id}' {if $category->category_id == $Item->category_id}selected{/if} category_name='{$category->single_name}'>{section name=sp loop=$level}&nbsp;&nbsp;&nbsp;&nbsp;{/section}{$category->name}</option>
												{fun name=categories_select Categories=$category->subcategories level=$level+1}
											{/if}
										{/foreach}
										{/defun}

										</select>
										
									</p></td>
								</tr>
								<tr>
									<td class="model"></td>
									<td class="model"><p>
										<nobr><input type=checkbox name='use_additional_categories' onclick="mselect=document.getElementById('add_cats'); if(this.checked)mselect.style.display='block';else mselect.style.display='none';" {if $Item->additional_categories}checked{/if}> Дополнительные категории</nobr>
										<br>
										<select  id=add_cats {if !$Item->additional_categories}style='display:none;'{/if} name=categories[] class="select3" multiple size=7>
											{include file=cat_option.tpl Categories=$Categories SelectedCategories=$Item->additional_categories level=0}
										</select>
									</p></td>
								</tr>
							</table>
							
<link type="text/css" href="js/jquery/custom/css/sunny/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<script src="js/jquery/custom/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="js/jquery/custom/js/jquery-ui-1.7.2.custom.min.js" type="text/javascript"></script>							
<script type="text/javascript">
{literal}
$(function(){
	$(".sortable").sortable();

	$(".add_variant").click(function(){
		var new_variant_html = "<li style='display:none;'>"+
		  "<div class=variant_move><img src='images/move.gif'></div>"+
		  "<div class=variant_name><input type=text name=variants[name][] value=''><input type=hidden name=variant_id value='0'></div>"+
		  "<div class=variant_sku><input type=text name=variants[sku][] value=''></div>"+
		  "<div class=variant_price><input type=text name=variants[price][] value=''></div>"+
		  "<div class=variant_stock><input type=text name=variants[stock][] value=''></div>"+
		  "<div class=variant_delete><a href='#' id=delete_variant><img src='images/delete_variant.gif'></a></div>"+
    	"</li>";
    	$('ul#product_variants').append(new_variant_html);
    	$('ul#product_variants li').show('fast');
    	$('a#add_multivariants').hide('fast');
    	$('input#first_variant_name').show('fast');
    	$('a#add_variant').show('fast');
    	
		$('a#delete_variant').click(function() {
			$(this).parent().parent().fadeOut(200, function() { $(this).remove(); });
			return false;
		});
    	
		return false;
	});
	
	$('a.delete_variant').click(function() {
		$(this).parent().parent().fadeOut(200, function() { $(this).remove(); });
		return false;
	});
	
});
{/literal}
</script>		

<div class="yellow_block">
  <div class=variants_header>
      <div class=variant_move>&nbsp;</div>
      <div class=variant_name>Название варианта</div>
      <div class=variant_sku>Артикул</div>
      <div class=variant_price>Цена, {$MainCurrency->sign}</div>
      <div class=variant_stock>Склад</div>
  </div>
  <ul id=product_variants class=sortable>
    {if $Item->variants}
    {foreach from=$Item->variants item=variant name=variants}
    <li>
      <div class=variant_move><img {if $Item->variants|@count<=1}style='display:none;'{/if} src='images/move.gif'></div>
      <div class=variant_name><input type=text name=variants[name][{$variant->variant_id}] value='{$variant->name|escape}' {if $Item->variants|@count<=1}id=first_variant_name style='display:none;'{/if}><input type=hidden name=variants[id][{$variant->variant_id}] value='{$variant->variant_id}'> <a href='#' class=add_variant id=add_multivariants {if $Item->variants|@count>1}style='display:none;'{/if}>Добавить вариант</a></div>
      <div class=variant_sku><input type=text name=variants[sku][{$variant->variant_id}] value='{$variant->sku|escape}'></div>
      <div class=variant_price><input type=text name=variants[price][{$variant->variant_id}] value='{$variant->price|escape}'></div>
      <div class=variant_stock><input type=text name=variants[stock][{$variant->variant_id}] value='{$variant->stock|escape}'></div>
      <div class=variant_delete><a {if $Item->variants|@count<=1}style='display:none;'{/if} href='#' class=delete_variant><img src='images/delete_variant.gif'></a></div>
    </li>
    {/foreach}
    {else}
    <li>
      <div class=variant_move><img style='display:none;' src='images/move.gif'></div>
      <div class=variant_name><input type=text name=variants[name][] value='{$variant->name|escape}' {if $Item->variants|@count<=1}id=first_variant_name style='display:none;'{/if}><input type=hidden name=variants[id][{$variant->variant_id}] value='{$variant->variant_id}'> <a href='#' class=add_variant id=add_multivariants {if $Item->variants|@count>1}style='display:none;'{/if}>Добавить вариант</a></div>
      <div class=variant_sku><input type=text name=variants[sku][] value='{$variant->sku|escape}'></div>
      <div class=variant_price><input type=text name=variants[price][] value='{$variant->price|escape}'></div>
      <div class=variant_stock><input type=text name=variants[stock][] value='{$variant->stock|escape}'></div>
      <div class=variant_delete><a  style='display:none;'  href='#'><img src='images/delete_variant.gif'></a></div>
    </li>
    {/if}
  </ul>  
  <a href='#' class=add_variant id=add_variant {if $Item->variants|@count<=1}style='display:none;'{/if}>Добавить вариант</a>
</div>

							
							<div class="gray_block">
								<table>
								<tr>
									<td class="model2">URL</td>
									<td class="m_t"><p><input name="url" type="text" class="input6" value='{$Item->url}'/></p></td>
								</tr>
								<tr>
									<td class="model2">Meta Title</td>
									<td class="m_t"><p><input name="meta_title"  type="text" class="input6" value='{$Item->meta_title|escape}'/></p></td>
								</tr>
								<tr>
									<td class="model2">Meta Keywords</td>
									<td class="m_t"><p><input name="meta_keywords" type="text" class="input6" value='{$Item->meta_keywords|escape}'/></p></td>
								</tr>
								<tr>
									<td class="model2">Meta Description</td>
									<td class="m_t"><p><input name="meta_description" type="text" class="input6" value='{$Item->meta_description|escape}'/></p></td>
								</tr>
								<tr>
									<td class="model2">Связанные товары</td>
									<td class="m_t"><p><input name="related" type="text" class="input6" value='{foreach name=related from=$Item->related item=r}{$r}{if !$smarty.foreach.related.last}, {/if}{/foreach}' title='Артикулы, через запятую'/></p></td>
								</tr>
								</table>
								{if $Properties}
								{foreach from=$Properties item=property}
								<table id=properties[{$property->property_id}] class=property_table style='display:none;'>
								<tr>
									<td class="model2"><a class=link href='index.php?section=Property&item_id={$property->property_id}&token={$Token}'>{$property->name|escape}</a></td>
									<td class="m_t"><p>
									{if $property->options}
									<select class=select3 name='properties[{$property->property_id}]'>
										<option value=''>Неопределено</option>
										{foreach item=option from=$property->options}
										<option value='{$option|escape}' {if $option==$property->value}selected{/if}>{$option|escape}</option>
										{/foreach}
									</select>
									{else}
									<input class=input6 type='text' name='properties[{$property->property_id}]' value='{$property->value|escape}'>
									{/if}
									</p></td>
								</tr>
								</table>
								{/foreach}	
								{/if}
							</div>
							
							<script>
							var properties = new Array();

							{foreach item=p from=$Properties}
							properties[{$p->property_id}] = Array({foreach name=pc item=pc from=$p->categories}'{$pc->category_id}'{if !$smarty.foreach.pc.last},{/if}{/foreach});
							{/foreach}

							{literal}
							function display_properties(category_id)
							{
								for(var i in properties)
								{  
									if(in_array(category_id, properties[i]))
									{
										document.getElementById('properties['+i+']').style.display = 'block';
									}
									else
									{
										document.getElementById('properties['+i+']').style.display = 'none';
									}
									
								}
							}
							
							function in_array(what, where) {
								var a=false;
								for(var i=0; i<where.length; i++) {
									if(what == where[i]) {
										a=true;
										break;
									}
								}
								return a;
							}		
							
							display_properties(document.product.category_id.value);					
							{/literal}
							</script>

							
							<p><input type="submit" value="Сохранить" class="submit"/></p>
					</div>
					
					
					<div id="over_right">
						<div class="gray_block1">
							<span class="model">Основное изображение</span>
																
							<table>
								<tr>
									<td>
									    <input type=hidden value='0' name=delete_large_image>
									    
									    {if $Item->large_image}
										<img id=large_image class="image_preview" src='../files/products/{$Item->large_image}?r={math equation="rand(1,1000000)"}' alt=""/>
										<p><img src="./images/cancel1.jpg" alt=""/><a href="#" class="link" onclick="javascript: window.document.getElementById('large_image').src='images/no_photo.jpg'; window.document.product.delete_large_image.value = 1; return false;">Удалить</a></p>
										{else}
										<img id=large_image class="image_preview" src='images/no_photo.jpg' alt=""/>
										{/if}
									</td>
									<td class="pad_l">
										<p><input type="file" name="large_image" class="input7"/></p>
										<p class="mrg_top"><input name="large_image_url" value="{if $smarty.post.large_image_url}{$smarty.post.large_image_url}{else}http://{/if}" type="text" class="input8"  /></p>
										{if $UseGd}
										  <p class="mrg_top"><input {if (!$Item->small_image && !$smarty.post) || $smarty.post.auto_small == 1}checked{/if} name='auto_small' value='1' type="checkbox"/><span class="sozd">создать маленькое автоматически</span></p>
										{/if}
									</td>
								</tr>
							</table><br/>
							
							
							<span class="model">Маленькое изображение</span>
							<table>
								<tr>
									<td>
									    <input type=hidden value='0' name=delete_small_image>
									    
									    {if $Item->small_image}
										<img id=small_image class="image_preview" src='../files/products/{$Item->small_image}?r={math equation="rand(1,1000000)"}' alt=""/>
										<p><img src="./images/cancel1.jpg" alt=""/><a href="#" class="link" onclick="javascript: window.document.getElementById('small_image').src='images/no_photo.jpg'; window.document.product.delete_small_image.value = 1; return false;">Удалить</a></p>
										{else}
										<img id=small_image class="image_preview" src='images/no_photo.jpg' alt=""/>
										{/if}
									</td>
									<td class="pad_l">
										<p><input type="file" name="small_image" class="input7"/></p>
										<p class="mrg_top"><input name="small_image_url" value="{if $smarty.post.small_image_url}{$smarty.post.small_image_url}{else}http://{/if}" type="text" class="input8" /></p>
									</td>
								</tr>
							</table>		
							<br>					
							<span class="model"><input name='digital_product' type=checkbox {if $Item->download}checked{/if} onclick="if(this.checked)d='block';else d='none';document.getElementById('digital_product').style.display=d;"> Цифровой товар</span> {if $Item->download}({$Item->download}){/if}
							<table id=digital_product {if !$Item->download}style='display:none;'{/if}>
								<tr>
									<td class="pad_l">
										<p><input type="file" name="download_file" class="input7"/></p>
									</td>
								</tr>
							</table>		
							<p><input type="submit" value="Сохранить" class="submit3"/></p>						
						</div>
					</div>
				</div>
				
				
				
				<div class="area">
					<span class="model4">Краткое описание</span>
					<p><textarea id="description" name="description" class="editor_small">{$Item->description}</textarea></p>
				</div>
				
				<div class="area">
					<span class="model4">Полное описание</span>
					<p><textarea name="body" class="editor_big">{$Item->body}</textarea></p>
				</div>
				<p>
				<input type=hidden name='product_id' value='{$Item->product_id}'>
				<input type="submit" value="Сохранить" class="submitx"/></p>
				
				
				
				
				<div>
					<span class="model3">Дополнительные изображения</span>
						<div class="gray_block2">
							
          <input type=hidden value='' name=delete_fotos>
          {section name=foto loop=$FotosNum start=0}
          {assign var="i" value=$smarty.section.foto.index}
          {assign var="fotos" value=$Item->fotos}
          {assign var="foto" value=$fotos[$i]}

							
							
							<div class="additional_image">
							<table>
								<tr>
									<td>			    
									    {if $foto && $Item->product_id}
										<a href='../files/products/{$foto}?r={math equation="rand(1,1000000)"}'><img id=image_{$i} class="image_preview" src='../files/products/{$foto}?r={math equation="rand(1,1000000)"}' alt=""/></a>
										{else}
										<img id=image_{$i} class="image_preview" src='images/no_photo.jpg' alt=""/>
										{/if}
									</td>
									<td class="pad_l">
										<p><input type="file" name="fotos[{$i}]" class="input7"/></p>
										<p class="mrg_top"><input name="fotos_url[{$i}]" type="text" class="input8" value="{if $smarty.post.fotos_url[$i]}{$smarty.post.fotos_url[$i]}{else}http://{/if}" /></p>
										<p><img src="./images/cancel1.jpg" alt=""/><a href="#" class="link" onclick="window.document.getElementById('image_{$i}').src='images/no_photo.jpg?r={math equation="rand(1,1000000)"}'; window.document.product.delete_fotos.value += '{$i},'; return false;">Удалить</a></p>
									</td>
								</tr>
							</table>							
							</div>
          {/section}				
          			
						</div>
						<p><input type="submit" value="Сохранить" class="submitx"/></p>
				</div>
				
				<br/>
			</div>
			</form>
			
	 
    </div>
  </div>	    
</div>
<!-- Content #End /--> 


{include file='tinymce_init.tpl'}

{if $Settings->meta_autofill}
<!-- Autogenerating meta tags -->
{literal}
<script>

// Templates
var meta_title_template = '%category %brand %name';
var meta_keywords_template = '%category, %brand, %name';
var meta_description_template = '%description';

var item_form = document.product;

var meta_title_touched = true;
var meta_keywords_touched = true;
var meta_description_touched = true;
var url_touched = true;
	
// generating meta_title
function generate_title(template, category, brand, name, description)
{
	return template.replace('%category', category).replace('%brand', brand).replace('%name', name).replace('%description', description).replace(/^\s+/g,"");
}	

// generating meta_keywords
function generate_keywords(template, category, brand, name, description)
{	
	return template.replace('%category', category).replace('%brand', brand).replace('%name', name).replace('%description', description).replace(/^(,\s)+|\s+$/g,"");
}	

// generating meta_title
function generate_description(template, category, brand, name, description)
{	
	return template.replace('%category', category).replace('%brand', brand).replace('%name', name).replace('%description', description).replace(/^\s+|\s+$/g,"");
}	

// generating meta_title
function generate_url(category, brand, name)
{
	url = name;
	if(brand != '') url = brand+' '+url;
	if(category != '') url = category+' '+url;
	return translit(url);
}	


// sel all metatags
function set_meta()
{	
	var category_name = item_form.category_id.options[item_form.category_id.selectedIndex].getAttribute('category_name');
	var brand_name = item_form.brand_id.options[item_form.brand_id.selectedIndex].getAttribute('brand_name');
	var product_name = item_form.model.value;
	
	var product_description = tinyMCE.get("description").getContent().replace(/(<([^>]+)>)/ig," ").replace(/(\&nbsp;)/ig," ");

	// Meta Title
	if(!meta_title_touched)
		item_form.meta_title.value = generate_title(meta_title_template, category_name, brand_name, product_name, product_description);		

	// Meta Keywords
	if(!meta_keywords_touched)
		item_form.meta_keywords.value = generate_keywords(meta_keywords_template, category_name, brand_name, product_name, product_description);		

	// Meta Description
	if(!meta_description_touched)
		item_form.meta_description.value = generate_description(meta_description_template, category_name, brand_name, product_name, product_description);		

	// Url
	if(!url_touched)
		item_form.url.value = generate_url(category_name, brand_name, product_name, product_description);		

}

function translit(url){
	url = url.replace(/[\s]+/gi, '_');
	return url.replace(/[^0-9a-zа-я_]+/gi, '');
}

function autometageneration_init()
{
	tinyMCE.get("description").onChange.add(function(ed, e) { set_meta(); });
	tinyMCE.get("description").onKeyUp.add(function(ed, e) { set_meta(); });

	var product_description = tinyMCE.get("description").getContent().replace(/(<([^>]+)>)/ig," ").replace(/(\&nbsp;)/ig," ");

	var category_name = item_form.category_id.options[item_form.category_id.selectedIndex].getAttribute('category_name');
	var brand_name = item_form.brand_id.options[item_form.brand_id.selectedIndex].getAttribute('brand_name');
	var product_name = item_form.model.value;
	var product_description = tinyMCE.get("description").contentDocument.documentElement.textContent;
	if(item_form.meta_title.value == '' || item_form.meta_title.value == generate_title(meta_title_template, category_name, brand_name, product_name, product_description))
		meta_title_touched=false;
	if(item_form.meta_keywords.value == '' || item_form.meta_keywords.value == generate_keywords(meta_keywords_template, category_name, brand_name, product_name, product_description))
		meta_keywords_touched=false;
	if(item_form.meta_description.value == '' || item_form.meta_description.value == generate_description(meta_description_template, category_name, brand_name, product_name, product_description))
		meta_description_touched=false;
	if(item_form.url.value == '' || item_form.url.value == generate_url(category_name, brand_name, product_name, product_description))
		url_touched=false;
}

function myattachevent(target, eventName, func)
{
    if ( target.addEventListener )
        target.addEventListener(eventName, func, false);
    else if ( target.attachEvent )
        target.attachEvent("on" + eventName, func);
    else
        target["on" + eventName] = func;
}

if (window.attachEvent) {
	window.attachEvent("onload", function(){setTimeout("autometageneration_init();", 1000)});
} else if (window.addEventListener) {
	window.addEventListener("DOMContentLoaded", autometageneration_init, false);
} else {
	document.addEventListener("DOMContentLoaded", autometageneration_init, false);
}


myattachevent(item_form.url, 'change', function(){url_touched = true});
myattachevent(item_form.meta_title, 'change', function(){meta_title_touched = true});
myattachevent(item_form.meta_keywords, 'change', function(){meta_keywords_touched = true});
myattachevent(item_form.meta_description, 'change', function(){meta_description_touched = true});

myattachevent(item_form.model, 'keyup',  set_meta);
myattachevent(item_form.model, 'change', set_meta);
myattachevent(item_form.brand_id, 'change',  set_meta);
myattachevent(item_form.category_id, 'change', set_meta);


</script>
{/literal}
<!-- END Autogenerating meta tags -->
{/if}

<!-- Управление товарами /-->
