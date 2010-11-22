{foreach item=category from=$Categories}
<tr>
  <td>
    <div class="list_left">
      <a href="index.php?section=Categories&action=move_up&item_id={$category->category_id}&token={$Token}" class="fl"><img src="./images/up.jpg" alt="Поднять" title="Поднять"/></a><a href="index.php?section=Categories&action=move_down&item_id={$category->category_id}&token={$Token}" class="fl"><img src="./images/down.jpg" alt="Опустить" title="Опустить"/></a><a href="index.php?section=Categories&set_enabled={$category->category_id}&token={$Token}" class="fl"><img src="./images/{if $category->enabled}lamp_on.jpg{else}lamp_off.jpg{/if}" alt="Активность" title="Активность"/></a>
      <div class="padding">
        <div style='padding-left:{$level*18}px;'>
          <p><a href="index.php?section=Category&item_id={$category->category_id}&token={$Token}" class="{if $category->enabled}tovar_on{else}tovar_off{/if}">{$category->name|escape}</a></p>
          
          {if $category->enabled}
          <a href="http://{$root_url}/catalog/{$category->url}/" class="tovar_min">http://{$root_url}/catalog/{$category->url}/</a>
          {else}
          <span class="tovar_min">http://{$root_url}/catalog/{$category->url}/</span>          
          {/if}
        </div>
      </div>
      <a href="index.php?section=Categories&act=delete&item_id={$category->category_id}&token={$Token}" class="fl" onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'><img src="./images/delete.jpg" alt="Удалить" title="Удалить"/></a>
    </div>
  </td>
</tr>
{include file=cat.tpl Categories=$category->subcategories level=$level+1}					
{/foreach}