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
{assign var='event_id'    value=$smarty.post.event_id}
{assign var='category_id' value=$smarty.post.category_id}

{if $event_id}
  {category event='on' category_id=$category_id}
    {discount all='on' event_id=$event_id  category_id=$category_id cat_price=$shop_category.category_price}{/discount}
    {if $shop_discounts}
      {include file="header.tpl" name=!discounts!}
      <form action='index.php' method='post' id='discount-select'>
        {ShowFormToken name='Discounts'}
        <table class="table_midtone">
          <tr>
            <td valign='top'>
              <table   width='100%' border='0' cellspacing='0'>
                <tr>
                  <td class='title2' colspan='{$shop_discounts_count+1}' valign='top'>
                    {$shop_category.event_name} - {$shop_category.category_name}
                  </td>
                </tr>
                <tr>
                  <td  valign='top'> <b>{!place_nr!}</b></td>
                  <td colspan='{$shop_discounts_count+1}'></td>
                </tr>
                {assign var='seats' value=$last_item->seats}
                {foreach key=key from=$seats item=seat name=foo}
                  <tr>
                    <td >
                      {if $shop_category.category_numbering eq 'both'}
                        {!seat!} {$seat->seat_row_nr} - {$seat->seat_nr}
                      {elseif $shop_category.category_numbering eq 'rows'}
                        {!row!} {$seat->seat_row_nr}
                      {elseif $shop_category.category_numbering eq 'seat'}
                        {!seat!} {$seat->seat_nr}
                      {elseif $shop_category.category_numbering eq 'none'}
                        {!ticket!} {$smarty.foreach.foo.index+1}
                      {/if}
                    </td>
                    <td style='font-size:11px;font-family:Verdana;'>
                      <label><input class='checkbox_dark' type='radio' name='discount[{$key}]' value='0' checked>{!normal!}</label>
                    </td>
                    {section name='d' loop=$shop_discounts}
                      <td style='font-size:11px;font-family:Verdana;'>
                        <label><input class='checkbox_dark discount_{$shop_discounts[d].discount_id}' type='radio' name='discount[{$key}]' value='{$shop_discounts[d].discount_id}'>{$shop_discounts[d].discount_name}&nbsp;{valuta value=$shop_discounts[d].discount_price|string_format:"%.2f"} </label>
                      </td>
                    {/section}
                  </tr>
                {/foreach}
                <tr>
                  <td colspan='{$shop_discounts_count+2}' align='center'>
                    <table width='100%' border=0>
                    {section name='d' loop=$shop_discounts}
                      {if $shop_discounts[d].discount_promo}
                        <tr id='discount_promo_{$shop_discounts[d].discount_id}_tr' >
                          <td width='40%' class='TblLower' >
                             {!discount_promo_for!}{$shop_discounts[d].discount_name}
                          </td>
                          <td class='TblHigher'>
                            <input name='discount_promo_{$shop_discounts[d].discount_id}'>{printMsg key='discount_promo_$shop_discounts[d].discount_id'}
                          </td>
                        </tr>
                      {/if}
                    {/section}

                    </table>
                    <input type='hidden' name='event_id' value='{$shop_category.event_id}'>
                    <input type='hidden' name='category_id' value='{$shop_category.category_id}'>
                    <input type='hidden' name='item_id' value='{$last_item->id}'>
                    <input type='hidden' name='action' value='adddiscount'><br>
                    <input type='submit' name='submit' value='{!continue!}'>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </form>
      <script  type="text/javascript">
        $("#discount-select").validate();
        {section name='d' loop=$shop_discounts}
          {if $shop_discounts[d].discount_promo}
            $('#discount_promo_{$shop_discounts[d].discount_id}_tr').hide();
          {/if}
        {/section}
        $(":radio").click(function(){literal}{ {/literal}
          var n, promotr, promoinp;
          {section name='d' loop=$shop_discounts}
            {if $shop_discounts[d].discount_promo}
              n = $(".discount_{$shop_discounts[d].discount_id}:checked").length;
              promotr  = $('#discount_promo_{$shop_discounts[d].discount_id}_tr');
              promoinp = $("input[name='discount_promo_{$shop_discounts[d].discount_id}']");
              showPromocode(n >0, promotr, promoinp, {$shop_discounts[d].discount_id});
            {/if}
          {/section}
        {literal} });
        var showPromocode = function(show, promoname, promoinp, promoid){
          promoinp.rules("remove");
          if(show == true){
            promoname.show();
            promoinp.rules("add",{ required : true,
                            			 remote: {
                                      url: "jsonrpc.php",
                                      type: "post",
                                      data: {
                                        name: promoinp.attr('name'),
                                        action: "DiscountPromo",
                                        id : promoid
                                      }
                              		 }
            });
          }else{
            promoname.hide();
          }
        }
        {/literal}

      </script>


    {else}
      {include file='cart_view.tpl'}
    {/if}
  {/category}
{/if}