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
 *}{strip}

{if $user->mode() eq 0 && !$user->active}
	{include file="user_activate.tpl"}
{else}
  {include file="header.tpl" name=!shopping_cart_check_out! header=!Handling_cont_mess!}
   <!-- checkout_preview.tpl -->
  {if  $user->mode() lte 2 && $user->new_member}
    <table class='table_dark' cellpadding='5' bgcolor='white' width='100%'>
      <tr>
        <td class='TblLower'>
          <span class='title'>{!act_name!}<br><br> </span>
          {include file="user_nm_registered.tpl"}
    		</td>
      </tr>
    </table>
 	{/if}

  {include file="cart_content.tpl" check_out="on"}
  {assign var=total value=$cart->total_price_f()}

	<br />

  <table cellpadding="0" cellspacing='0' border='0' width='100%'>
    <tr>
      <td width="50%" valign="top" align="left">
        {include file="user_address.tpl" title="on"}
  		</td>
    	<td valign='top' align="right">
        {if !$update->is_demo()}
          <form method='post' name='handling' id="ft-order-handling"> {*   onsubmit='this.submit.disabled=true;return true;' *}
          {ShowFormToken name='OrderHandling'}
          <input type='hidden' name='action' value='confirm' />
        {/if}
        <table border='0' width='90%' cellpadding="5" bgcolor='white'>
   			  <tr>
            <td colspan='3' class='TblHeader' align='left'>
              <h2>{!handlings!}</h2>
            </td>
     			</tr>
      		{assign var=min_date value=$cart->min_date_f()}
          {update->view event_date=$min_date}

          {handling www='on' event_date=$min_date total=$total}   {* checked="checked" *}
  				  <tr class="{cycle name='payments' values='TblHigher,TblLower'}">
              <td class='payment_form'>
                <input  type='radio' id='{$shop_handling.handling_id}_check' class='checkbox_dark' name='handling_id' value='{$shop_handling.handling_id}' />
  		  		  </td>
            	<td class='payment_form'>
  	  			    <label for='{$shop_handling.handling_id}_check'>
                  {!payment!}: {eval var=$shop_handling.handling_text_payment}<br/>
              		{!shipment!}: {eval var=$shop_handling.handling_text_shipment}
     		  			</label>
          		</td>
              <td class='payment_form' align='right'>
                {if  $shop_handling.fee}
                  + {gui->valuta value=$shop_handling.fee|string_format:"%.2f"}
                {/if}&nbsp;
              </td>
        		</tr>

   	      {/handling}
          {if $order->can_freeTicketCode()}
         		<tr class="{cycle values='TblHigher,TblLower'}">
              <td colspan="3">
                <table style='width:100%;'>
               		<tr>
                    <td>
                      {!freeTicketCode!}
                    </td>
                    <td>
                      <input type='text' name='FreeTicketCode' />
                    </td>
                	</tr>
                </table>
              </td>
          	</tr>
          {/if}

        	{if $update_view.currentres}
         		<tr class="{cycle values='TblHigher,TblLower'}">
              <td colspan="3">
             	  {*$update_view.maxres*}
             	  {!limit!}
              </td>
          	</tr>
          {/if}
          <tr>
            <td colspan="3">
          	  <input type='submit' id='checkout-commit' name='submit' value='{!order_it!}'/>

              {if !$update->is_demo()}
        	  	  </form>
          		{else}
               	<div class='error'><br/> For safety issues we have disabled the order button. </div>
          		{/if}
          		{* update->view event_date=$min_date user=user->user_id *}
              {* TODO: chris check this code, i have no clue how it need to work exactly *}
              {* for now i have an extra option maked that check shopconfig_restime      *}
          		{* if $updateview.can_reserve() *}
          		{if $update_view.can_reserve AND $user->active}
                {if !$update->is_demo()}
                  <form action='' method='post' name='handling'>
              	    <input type='hidden' name='action' value='reserve' />
                		{ShowFormToken name='ReservHandling'}
         			  {/if}
                <input style="float:right; display:inline-block;" type='submit' name='submit_reserve' value='{!reserve!}'/>
   		  		    <span style="float:right; display:inline-block;">{!orclick!}</span>
      					{if !$update->is_demo()}
      					  </form>
          			{/if}
       				{/if}
            </td>
          </tr>
       </table>

    	</td>
   	</tr>
  </table>
  <script language="javascript" type="text/javascript">
  {literal}
  jQuery(document).ready(function(){
    jQuery("form#ft-order-handling").submit(function(){
      handlingRadio = $("input:radio[name='handling_id']:checked").val();

      if(handlingRadio === undefined){
        message = "{/literal}{!Select_handling_option!}{literal}";
        showErrorMsg(message);
        return false;
      }
      jQuery('#checkout-commit').attr('disabled','true');
      return true;
    });
  });
  {/literal}
  </script>
{/if}
{include file="footer.tpl"}{/strip}