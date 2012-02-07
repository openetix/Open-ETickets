{*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't
 * clear to you.

 *}<table width="150" border="0" cellspacing="0" cellpadding="0" >
{event_group group_status='pub'}
   <tr>
     <td height="24" style="padding-left:10px;padding-bottom:10px; ">
     <a class='shop_link' href='shop.tpl?event_group_id={$shop_event_group.event_group_id}'>
     {$shop_event_group.event_group_name}<img src="{$_SHOP_themeimages}link.png" valign='bottom' border='0'></a></td>
   </tr>
{/event_group}
 </table>

<table width="150" border="0" cellspacing="0" cellpadding="0" class='menu'>
<tr> <td height="24" class="menu_td">
     <a class='shop_link' href='calendar.tpl'>
     {!posmenu_calendar!}
     <img src="{$_SHOP_themeimages}link.png" border='0' valign='bottom'></a>
</td></tr>
<tr> <td height="24" class="menu_td">
     <a class='shop_link' href='event_groups.tpl'>
    {!posmenu_festivals!}
     <img src="{$_SHOP_themeimages}link.png" border='0' valign='bottom'></a>
</td></tr>
</table>
<table width="150" border="0" cellspacing="0" cellpadding="0" class='menu'>

<tr> <td height="24" class="menu_td">
     <a class='shop_link' href='conditions.tpl'>{!posmenu_howto!}
     <img src="{$_SHOP_themeimages}link.png" border='0' valign='bottom'></a>
</td></tr>
<tr> <td height="24" class="menu_td">
     <a class='shop_link' href='about.tpl'>
    {!posmenu_about!}
<img src="{$_SHOP_themeimages}link.png" border='0' valign='bottom'></a>
</td></tr>

<tr> <td height="24" class="menu_td">
     <a class='shop_link' href='contact.tpl'>
    {!posmenu_contact!}
<img src="{$_SHOP_themeimages}link.png" border='0' valign='bottom'></a>
</td></tr>
</table>

{* *****User****** *}

{if $smarty.get.action eq 'login'}
  {user->login username=$smarty.post.username password=$smarty.post.password}
{elseif $smarty.get.action eq 'logout'}
 {user->logout}
{/if}

{if $user->logged}
<table width="150" border="0" cellspacing="0" cellpadding="0" class='menu'>
<tr> <td height="24" class="menu_login">
  {!posmenu_welcome!} {user->user_firstname} {user->user_lastname}!
  <a href='shop.tpl?action=logout'>{!logout!}</a>
</td></tr></table>
{else}
<form method='post' action='shop.tpl?action=login'>
<table width='150' border="0" cellspacing="0" cellpadding="0"  class='menu'>
   <tr>
     <td class="menu_login">{!email!}</td>
     <td align='left'><input type='input' name='username' size=8></td>
   </tr>
   <tr>
     <td  class="menu_login">{!password!}</td>
     <td align='left'><input type='password' name='password' size=8></td>
   </tr>
   <tr><td colspan=2 align='center'><input type='submit' value='{!login!}'></td></tr>
</table>
</form>
{/if}


{* *****Panier****** *}

<table width="100%" border="0" cellspacing="3" cellpadding="0" style='border-top:#45436d 1px solid; padding-top:5px;padding-bottom:5px;'>
  <tr>
    <td class='cart_menu_title' align='left' style='padding-left:10px;'>
    {if $cart->is_empty_f()}
      <img src="{$_SHOP_themeimages}caddie.png">
    {else}
      <a href='shop.tpl?action=view_cart' class='shop_link'>
      <img src="{$_SHOP_themeimages}caddie_full.png" border='0'>
    {/if}
    {!shoppingcart!}</a>
    </td>
  </tr>
  <tr>

   {if $cart->is_empty_f()}
       <td valign="top" class='cart_menu'>{!Cart empty!}</td>
   {else}
      {assign var="cart_overview" value=$cart->overview_f()}


       <td valign="top" class='cart_menu'>
    {if $cart_overview.valid}
       <img src='{$_SHOP_themeimages}ticket-valid.png'> {$cart_overview.valid}
     {/if}
     {if $cart_overview.expired}
     <img src='{$_SHOP_themeimages}ticket-expired.png'> {$cart_overview.expired}
     {/if}
    {if $cart_overview.valid}
       <img src='{$_SHOP_themeimages}clock.gif'> {$cart_overview.minttl}'
     {/if}

       </td>
    {/if}
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="5" class='menu_langs'>
  <tr>
    <td>
      <div align="center">
        <a href='shop.tpl?setlang=de' class='langs_link'>[de]</a>
      	<a href='shop.tpl?setlang=fr' class='langs_link'>[fr]</a>
      	<a href='shop.tpl?setlang=en' class='langs_link'>[en]</a>
      	<a href='shop.tpl?setlang=it' class='langs_link'>[it]</a>
      </div>
    </td>
  </tr>
</table>
<br><br>
