{*                  %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2011 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of FusionTicket.
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
 * Any links or references to Fusion Ticket must be left in under our licensing agreement.
 *
 * By USING this file you are agreeing to the above terms of use. REMOVING this licence does NOT
 * remove your obligation to the terms of use.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact help@fusionticket.com if any conditions of this licencing isn't
 * clear to you.
 *}
{if $pm_return.approved}
  {if $shop_order.order_payment_status eq 'paid'}
    {include file="header.tpl" name=!pay_accept! noHeader=$no_header}
  {else}
    {include file="header.tpl" name=!pay_ispending! noHeader=$no_header}
  {/if}
{else}
  {include file="header.tpl" name=!pay_refused! noHeader=$no_header}
{/if}
<table class="table_midtone">
  <tr>
    <td>
        {if $shop_order.order_payment_status eq 'paid'}
          {!pay_reg!}!
        {/if}
        <br />
		    {!order_id!}: <b>{$shop_order.order_id}</b><br/>
		    {if $pm_return.transaction_id}
          {!trx_id!}: <b>{$pm_return.transaction_id}</b><br/>
        {/if}
        {if $pm_return.response}
          <p>{eval var=$pm_return.response}</p>
        {/if}
        <br /> <br />
        {if $pm_return.approved}
          <br />
          <table width='100%'>
            <tr>
              <th align='left' width=200 >
                <a href='checkout.php?action=print&{$order->EncodeSecureCode($order->obj)}' target='_blank'>{!printinvoice!}</a>
              </th>
              {if $shop_order.order_payment_status eq 'paid'}
                <th align='left'>
                  <a href='checkout.php?action=print%mode=2&{$order->EncodeSecureCode($order->obj)}' target='_blank'>{!printtickets!}</a>
                </th>
              {/if}
            </tr>
          </table>
          <br />
        {/if}
    </td>
  </tr>
</table>
      <form method='post' action='index.php'>
        <input name="go_home" value="{!order_more_tickets!}" type="submit">
      </form>

{include file="footer.tpl" noFooter=$no_footer}