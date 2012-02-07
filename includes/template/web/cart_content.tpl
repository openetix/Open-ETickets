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
 {if $cart->is_empty_f()}
  <table class='table_dark' cellpadding='5' bgcolor='white' width='100%'>
    <tr><td class='TblLower' align='center' >  <br><br>
      <span class='title'>{!cart_empty!}<br><br><br><br> </span>
    </td></tr>
  </table>
{else}
  {counter start="0" assign="count"}
  <table class='table_dark' cellpadding='5' width="100%" bgcolor='white'>
    <tr>
			<td class='TblHeader' valign='top'>
 				<b>{!event!}</b>
			</td>
			<td class='TblHeader' valign='top'>
  			<b>{!tickets!}</b>
			</td>
			<td class='TblHeader' valign='top'>
  			<b>{!total!}</b>
			</td>
			<td class='TblHeader' valign='top'>
  			<b>{!expires_in!}</b>
			</td>
    </tr>
    {cart->items}
      {if $check_out eq "on"}
        {if not $seat_item->is_expired()}
          {include file="cart_subcontent.tpl" check_out="on"}
        {/if}
      {else}
        {include file="cart_subcontent.tpl"}
      {/if}
    {/cart->items}
    <tr class="{cycle name='events' values='TblHigher,TblLower'}">
      <td  colspan='2' align='right'>
        <b>{!total_price!}</b>
      </td>
      <td align='right'>
       <b> {valuta value=$cart->total_price_f()}</b> {* //it seems this is not needed: |string_format:"%.2f" *}
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
{/if}