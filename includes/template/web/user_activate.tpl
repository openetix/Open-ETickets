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
{include file='header.tpl' name=!act_name!}
{if !$user->activate()}
   {include file="user_registred.tpl"}
   <br>
   <table border="0" cellpadding="5" cellspacing="5" width="600" class="login_table"  >
      <tr>
        <td colspan=2  class="TblLower">
           <h2>{!act_enter_title!}</h2>
        </td>
      </tr>
      <form action='{!PHP_SELF!}' method='post'>
        {ShowFormToken name='TryActivateUser'}
{*        <input type='hidden' name='action' value='activate'> *}
        <tr><td  colspan='2'>{!act_enter_code!}<br><br></td></tr>
        <tr>
          <td>{!act_code!}</td>
          <td><input type='text' name='uar' value='{$smarty.request.uar}' size='40'> &nbsp; <input type='submit' name='submit' value="{!act_send!}"></td>
        </tr>
        <tr><td colspan='2'><a href='index.php?action=resend_activation'>{!act_notarr!}</a></td></tr>
      </table>
   </form>
{else}
    {!success_activate!}
{/if}