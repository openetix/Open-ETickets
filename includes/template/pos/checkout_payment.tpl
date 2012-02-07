{order->paymentForOrder order_id=$order_id}
{if $payment_tpl}
  {include file="$payment_tpl.tpl" no_header=true no_footer=true pos=true}
{else}
  {!error!}
{/if}