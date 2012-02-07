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
<tr>
  <td class='TblLower' width="120px"> {!user_firstname!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_firstname' size='30' maxlength='50' value='{$user_data.user_firstname|clean}'>{printMsg key='user_firstname'}</td>
</tr>
<tr>
  <td class='TblLower'> {!user_lastname!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_lastname' size='30'  maxlength='50' value='{$user_data.user_lastname|clean}'>{printMsg key='user_lastname'}</td>
</tr>
<tr>
  <td class='TblLower'> {!user_address!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_address' size='30'  maxlength='75' value='{$user_data.user_address|clean}'>{printMsg key='user_address'}</td>
</tr>
<tr>
  <td class='TblLower'> {!user_address2!}  </td>
  <td class='TblHigher'><input type='text' name='user_address1' size='30'  maxlength='75' value='{$user_data.user_address1|clean}'>{printMsg key='user_address1'}</td>
</tr>
<tr>
  <td class='TblLower'> {!user_zip!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_zip' size='8'  maxlength='20' value='{$user_data.user_zip|clean}'>{printMsg key='user_zip'}</td>
</tr>
<tr>
  <td class='TblLower'> {!user_city!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_city' size='30'  maxlength='50' value='{$user_data.user_city|clean}'>{printMsg key='user_city'}</td>
</tr>
<tr>
  <td class='TblLower'> {!user_state!}&nbsp;</td>
  <td class='TblHigher'><input type='text' name='user_state' size='30' maxlength="50" value='{$user_data.user_state|clean}'>{printMsg key='user_state'}</td>
</tr>
{gui->selectcountry name='user_country' value=$user_data.user_country}
<tr>
  <td class='TblLower'  > {!user_phone!} </td>
  <td class='TblHigher'><input type='text' name='user_phone' size='30'  maxlength='50' value='{$user_data.user_phone|clean}'>{printMsg key='user_phone'}</td>
</tr>
<tr>
  <td class='TblLower'  > {!user_fax!} </td>
  <td class='TblHigher'><input type='text' name='user_fax' size='30'  maxlength='50' value='{$user_data.user_fax|clean}'>{printMsg key='user_fax'}</td>
</tr>
<tr>
	<td class='TblLower' > {!user_email!}&nbsp;* </td>
  	<td class='TblHigher'>
	  	<input {if $user_data.user_id}readonly="readonly"{/if} type='text' name='user_email' size='30'  maxlength='50' value='{$user_data.user_email|clean}' id="email" />
	  	{printMsg key='user_email'}
 	</td>

</tr>
{if !$user_data.user_id}
 	<tr>
		<td class='TblLower' > {!confirmemail!}&nbsp;* </td>
		<td class='TblHigher'>
  		<input autocomplete='off' type='text' name='user_email2' size='30'  maxlength='50' value='{$user_data.user_email2|clean}'/>
  	</td>
	</tr>
{/if}