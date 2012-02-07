{*
 * %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *   phpMyTicket - ticket reservation system
 *    Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 3 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't
 * clear to you.
 *}<link rel="stylesheet" type="text/css" href="../css/formatting.css" media="screen" />
{if !$smarty.request.ajax and !$no_header}
  {strip}
    {include file='order.tpl' nofooter=true}
  {/strip}
{/if}

  <div id="checkout_result" title='
    {if $pm_return.approved}
      {!pay_accept!}
    {else}
      {!pay_refused!}
    {/if}
  '>
  <table class='table_dark' cellpadding='5' bgcolor='white' width='500'>
    {if $pm_return.approved}
      <tr>
        <td colspan='2'>{!pay_reg!}!</td>
      </tr>
    {/if}
    {gui->view name=order_id value=$order_id}
    {if $shop_handling.handling_id eq 1}
      {!reserve_tickets!}
    {else}
      {eval var=$shop_handling.handling_text_payment assign=test}
      {gui->view name=payment value=$test}
      {eval var=$shop_handling.handling_text_shipment  assign=test}
      {gui->view name=shipment value=$test}
    {/if}
    {gui->valuta value=$order_total_price assign=test}
    {gui->view name=total_price value=$test}

    {if $pm_return.transaction_id}
      <tr>
        <td>{!trx_id!}</td>
        <td><b>{$pm_return.transaction_id}</b><br/></td>
      </tr>
    {/if}
    {if $pm_return.response}
      <tr>
        <td colspan='2' {if !$pm_return.approved}class='error'{/if}>
          {eval var=$pm_return.response}
        </td>
      </tr>
    {/if}

    {if $pm_return.approved}
      <tr>
        <td>
          <div>
            <a href='checkout.php?action=print&{$order->EncodeSecureCode($order_id)}&mode=2' target='_blank'>{!printinvoice!}</a>
          </div>
        </td>
        <td align='right'>&nbsp;
          {if $shop_order.handling->handling_shipment eq "sp" or $pos}
            <div id="waiting">
              <p style="float:left; display:inline; margin:0;">Waiting for payment..</p>
              <img style="float:right; display:inline;" src="{$_SHOP_themeimages}LoadingImageSmall.gif" width="16" height="16" alt="Waiting for payment, please wait" />
            </div>
            <div id='printticket' style='display:none;'>
              <a href='checkout.php?action=print&{$order->EncodeSecureCode($order_id)}&mode=1' target='_blank'>{!printtickets!}</a>
            </div>
          {/if}
        </td>
      </tr>
    {/if}
   </table>
  </div>
  <script type="text/javascript">
 // var timerid = 0;
  var orderid = {$shop_order.order_id};
  {if !$smarty.request.ajax and !$no_header and !$no_footer}
    {literal}
      jQuery(document).ready(function(){
        jQuery("#checkout_result").dialog({
          bgiframe: false,
          autoOpen: true,
          height: 'auto',
          width: 'auto',
          modal: true,
          close: function(event, ui) {
            if (timerid) {
              clearTimeout(timerid);
              timerid = -1;
            }
            {/literal}window.location = '{$_SHOP_root}index.php';{literal}
          }
        });
      });
    {/literal}
  {/if}
  {if $shop_order.handling->handling_shipment eq "sp" or $pos}
    {literal}

      //The refresh orderpage, the ajax manager SHOULD ALLWAYS be used where possible.
      var checkpaint = function(){
        if (timerid > -1) {
          ajaxQManager.add({
            type:      "POST",
            url:      "ajax.php?x=canprint",
            dataType:   "json",
            data:      {"pos":true,"action":"Canprint",orderid:orderid},
            success:function(data, status){
              if(data.status){
                if (data.show){
                  jQuery('#printticket').show();
                }
                jQuery('#waiting').hide();
              } else {
                timerid = setTimeout('checkpaint()', 1000);
              }
            }
          });
        }
      }
      checkpaint();
    {/literal}
  {/if}
  </script>
{if !$smarty.request.ajax and !$no_footer}
    {include file="footer.tpl"}
{/if}