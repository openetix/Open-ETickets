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
 <!-- user_register.tpl -->
{if $ManualRegister}
  {include file="header.tpl" name=!becomemember! header=!memberinfo!}
  <form action='index.php' method='post'  id="user-register">
{else}
  {include file="header.tpl" name=!pers_info! header=!user_notice!}
  <form action='{$_SHOP_root_secured}checkout.php' method='post' id="user-register" >
{/if}
{*  <table width='100%'> <tr><td valign='top'> *}
    {ShowFormToken name='UserRegister'}
    <input type='hidden' name='action' value='register' />
    <input type='hidden' name='register_user' value='on' />

    <table cellpadding="2" bgcolor='white' width='100%' id='guest'>
      {if !$ManualRegister}
        <tr>
          <td colspan='2' class='TblHeader'>
            {if $user->mode() <= '1'}
              {!becomemember!}
            {elseif $user->mode() eq '2'}
              {!becomememberorguest!}
            {else}
              {!becomeguest!}
            {/if}
          </td>
        </tr>
        <tr>
          <td colspan="2" class='TblHigher'>{!guest_info!}</td>
        </tr>
      {/if}
      {if $user->mode() <= '1' or $ManualRegister}
        <input type='hidden' name='ismember' id='type' value='true'/>
      {elseif $user->mode() eq '2'}
        <tr>
          <td colspan='2' class='TblLower'>
            <input id="ismember-checkbox" type='checkbox' name='ismember' id='type' value='true' {if $smarty.post.ismember} checked {/if} /> {!becomemember!}
          </td>
        </tr>
      {/if}
      {include file="user_form.tpl"}
      <tr id='passwords_tr1' >
        <td class='TblLower'>{!password1!} *</td>
        <td class='TblHigher'>
           <input autocomplete='off' type='password' name='password1' size='10' maxlength='10' id="password" />&nbsp;
           {!pwd_min!}
           {printMsg key='password'}
        </td>
      </tr>
      <tr id='passwords_tr2'>
        <td class='TblLower'> {!confirmpassword!} *</td>
        <td class='TblHigher'><input autocomplete='off' type='password' name='password2' size='10'  maxlength='10' /></td>
      </tr>
      <tr>
        <td class='TblLower' width='30%'>{!user_nospam!}&nbsp;*</td>
        <td class='TblHigher' valign='top'>
          <table cellpadding="0" cellspacing="0" width='400'>
            <tr>
              <td >
                <input type='text' name='user_nospam' id='user_nospam' size='10' maxlength="10" value='' >	<sup> &nbsp;{!nospam_info!} </sup>{printMsg key='user_nospam'}
              </td>
              <td align='center'>
                <img src="nospam.php?name=user_nospam" alt='' border=1>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan='2' class='TblHigher'>
          <a onclick='showDialog(this);return false;'  href='agb.php' style="float:left; display:block;">{eval var=!agrement!}</a><span style="float:left;">&nbsp;*</span>
          <input type='checkbox' class='checkbox' name='check_condition' value='check' />{printMsg key='check_condition'}
        </td>
      </tr>

      <tr>
        <td colspan='2' align='right'><input type='submit' name='submit_info' value='{!continue!}'></td>
      </tr>
    </table>

  </form>
<br />
<br />
<br />
<script  type="text/javascript">
{literal}
$(window).load(function(){
{/literal}
  {if $user->mode() <= '1' or $ManualRegister}
    showPasswords(true);
  {elseif $user->mode() eq '2'}
    showPasswords($('#ismember-checkbox').is(':checked'));
  {else}
    showPasswords(false);
  {/if}
{literal}
});
{/literal}
</script>

{if !$ManualRegister}
  {include file='footer.tpl'}
{/if}{/strip}