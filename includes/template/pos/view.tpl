{*
%%copyright%%
*}

{if $smarty.get.action eq 'cancel_order'}
  {order->cancel order_id=$smarty.get.order_id reason=$smarty.get.place}
  {include file="process_select.tpl"}

{elseif $smarty.get.action eq 'cancel_ticket'}
  {order->delete_ticket order_id=$smarty.get.order_id ticket_id=$smarty.get.ticket_id}
  {include file="process_select.tpl"}

{elseif $smarty.post.action eq 'confirm'}
  {include file="process_select.tpl"}

{elseif $smarty.request.action eq 'reorder'}
  {include file="view_reorder.tpl"}

{elseif $smarty.post.action eq 'order_res'}
  {order->res_to_order order_id=$smarty.post.order_id handling_id=$smarty.post.handling place='pos'}
  {if $order_success}
    {include file='process_select.tpl'}
  {else}
    <div class='error'>Error</div>
    {include file="process_select.tpl"}
  {/if}
{else}
  {if $smarty.request.order_id}
    {if $smarty.post.action eq "setpaid"}
      {$order->set_paid_f($smarty.post.order_id)}
  	{/if}
    {if $smarty.post.action eq 'setsend'}
    	{* $order->set_status_f($smarty.post.order_id,'ord') *}
    	{$order->setStatusSent($smarty.post.order_id)}
    {/if}
  {/if}

  {include file="process_select.tpl"}
{/if}