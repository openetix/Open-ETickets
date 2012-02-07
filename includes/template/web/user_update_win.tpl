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
<!-- user_update.tpl -->
  <div id="error-message-user" class="ui-state-error ui-corner-all" style="padding: 1em; margin-top: .7em; display:none;" >
    <p>
      <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
      <span id='error-text-user'>ffff</span>
    </p>
  </div>
{if $usekasse}
  {if $smarty.post.submit_update}
    {if count($user_errors) eq 0}
   	  <script type="text/javascript">
        window.opener.location.href = window.opener.location.href;
	      window.close();
      </script>
      <script type="text/javascript">
      {literal}
      jQuery(document).ready(function(){
      });
      {/literal}
      </script>
   	{/if}
    {assign var='user_data' value=$smarty.post}
  {else}
    {assign var='user_data' value=$user->asarray()}
 	{/if}
{*
<html>
	<head>
		<title></title>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<meta http-equiv="Content-Language" content="nl" />

			<link rel="shortcut icon" href="images\favicon.ico" />
			<link rel="icon" href="images\animated_favicon1.gif" type="image/gif" />
			<link rel='stylesheet' href='style.php' type='text/css' />
	</head>

	<body topmargin="0" leftmargin="0" bgcolor="#FFE2AE">
	<br />
  *}
<div id="update-user-div" style="width:100%;">
		<form action="{$_SHOP_root_secured}checkout.php" method='post' id="update_user">
			{ShowFormToken name='UserUpdate'}
 			<input type='hidden' name='action' value='useredit' />
{else}
<div id="update-user-div" style="width:100%;">
  <form action="index.php" method='post' id="update_user">
    {ShowFormToken name='UserUpdate'}
    <input type='hidden' name='action' value='update' />
   	<input type='hidden' name='personal_page' value='details' />
{/if}
    <input type='hidden' name='user_id' value='{$user->user_id}' />
    <input type="hidden" name="submit_update" value="yes" />

    <table cellpadding="3" class="main" bgcolor='white'>
		  {include file='user_form.tpl'}

      {if $user->is_member}
        {gui->input autocomplete='off'  type='password' name='old_password' size='10'  maxlength='10'}

        {if !$usekasse}
          <tr id='passwords_tr1' >
            <td class='TblLower'>{!new_password!} (opt.)</td>
            <td class='TblHigher'>
              <input autocomplete='off' type='password' name='password1' size='10' maxlength='10' id="password" />
              {!pwd_min!}{printMsg key='password'}
            </td>
          </tr>
          <tr id='passwords_tr2'>
            <td class='TblLower'> {!password2!}</td>
            <td class='TblHigher'><input autocomplete='off' type='password' name='password2' size='10'  maxlength='10' /></td>
          </tr>
        {/if}

		  {/if}
    </table>
    <br />

	<div style="text-align:center;">
   	<input style="float:none;" type='submit' name='submit_update' value='Update' />
  </div>
  </form>
</div>