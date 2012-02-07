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
{if $smarty.post.action eq 'login'}
	{user->login username=$smarty.post.username password=$smarty.post.password uri=$smarty.post.uri}
{elseif $smarty.get.action eq 'logout'}
	{user->logout}
{/if}

{if $user->logged}
<table  width="195px" border="0" cellpadding="0" cellspacing="0" class="cart_table">
	<tr>
		<td class="login_title" >{!member!}</td>
	</tr>
  	<tr>
		<td class="login_content">{!welcome!} <b>{$user->user_firstname|clean} {$user->user_lastname|clean}</b>!
			<br>
			<li><a  href='index.php?personal_page=on'>{!pers_page!}</a></li>
			<li><a  href='index.php?action=logout'>{!logout!}</a></li>
		</td>
	</tr>
</table>
{else}
  <table width="195px"  border="0" cellspacing="0" cellpadding="0"  class="cart_table">
  	<tr>
  		<td class="login_title">{!member!}</td>
  	</tr>
    <form method='post' action='index.php' style='margin-top:0px;' id="user-login">
    <input type="hidden" name="action" value="login">
    <input type="hidden" name="type" value="block">
    {ShowFormToken name='login'}

    {if $smarty.get.action neq "logout" and $smarty.get.action neq "login"}
      <input type="hidden" name="uri" value="{$smarty.server.REQUEST_URI}">
    {/if}
  	<tr>
  		<td class="login_content">{!email!}</td>
  	</tr>
  	<tr>
  		<td class="login_content" style='padding-left:25px;'>
        	<input type='input' name='username' size='20' style='font-size:10px;' > {printMsg key='loginusername'}
      	</td>
  	</tr>
  	<tr>
  		<td  class="login_content">{!password!}</td>
  	</tr>
  	<tr>
		<td class="login_content" style='padding-left:25px;'>
        	<input type='password' name='password' size='20' style='font-size:10px;' />{printMsg key='loginpassword'}<br />
			<input type='submit' value='{!login_button!}' style='font-size:10px;'/>
      	</td>
  	</tr>
  	<tr>
  		<td class="login_content">
  			<li><a  href='index.php?action=register'>{!register!}</a></li>
  		</td>
  	</tr>
  	<tr>
  		<td class="login_content">
  			<li><a onclick='showDialog(this);return false;' href='forgot_password.php'>{!forgot_pwd!}</a></li>
  		</td>
  	</tr>
  </form>
  </table>
{/if}