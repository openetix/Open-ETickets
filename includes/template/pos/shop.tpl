{*
%%copyright%%
*}{strip}
{if $smarty.get.event_group_id}
  {include file="event_group.tpl"}

{elseif $smarty.get.action=='show_evgroup'}
  {include file="event_groups.tpl"}

{* AJAX CALLES REMOVED, now proccessed in ajax.posajax.php *}

{else}
  {include file="order.tpl"}
{/if}
{/strip}