{foreach from=$Categories item=category}
	{if $CurrentCategory != $category->category_id}
    	<option value='{$category->category_id}' {if $category->category_id == $SelectedCategories || is_array($SelectedCategories) && in_array($category->category_id, $SelectedCategories)}selected{/if}>{section name=sp loop=$level}&nbsp;&nbsp;&nbsp;&nbsp;{/section}{$category->name}</option>
    	{include file=cat_option.tpl Categories=$category->subcategories  SelectedCategories=$SelectedCategories  CurrentCategory=$CurrentCategory level=$level+1}
    {/if}
{/foreach}