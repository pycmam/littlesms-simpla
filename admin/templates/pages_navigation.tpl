<div class="peid">
{foreach key=index item=page from=$Pages}
      {if $index!=$CurrentPage}
        <a href="{$page}" class="peid_off">{$index+1}</a>
      {else}
        <a href="{$page}" class="peid_on">{$index+1}</a>
      {/if}
{/foreach}

<script type="text/javascript" src="../js/ctrlnavigate.js"></script> 

{if $PrevPageUrl}
<a id="PrevLink" href="{$PrevPageUrl}" class="alink">←&nbsp;назад</a>
{/if}

{if $NextPageUrl}
<a id="NextLink" href="{$NextPageUrl}" class="alink">вперед&nbsp;→</a>
{/if}

</div>