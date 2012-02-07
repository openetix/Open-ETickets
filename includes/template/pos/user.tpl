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

 *}

<script type="text/javascript">
  {literal}
    $(document).ready(function(){
  {/literal}
      loadUser(['{!user_id!}','{!user_name!}','{!user_zip!}','{!user_city!}','{!user_email!}']);
  {literal}
    });
  {/literal}
</script>


  <table width='99%' border='0' bgcolor='white' align='left' cellspacing='2' cellpadding='2'>
    <thead>
    <tr>
      <td colspan="2" class="title">
        {!pers_info!}
      </td>
    </tr>
    <tr>
      <td class='user_item' colspan='2'>
        <table width='100%' border='0' cellspacing='0' cellpadding='0' >
          <tr>
            <td class='user_item' style='height:22;' > &nbsp;
              <input checked="checked" type='radio' id='user_info_none' class='checkbox_dark' name='user_info' value='0'>
              <label for='user_info_none'> {!none!} </label>
            </td>
            <td class='user_item'  >
               <input type='radio' id='user_info_new' class='checkbox_dark' name='user_info' value='2'>
               <label for='user_info_new'> {!new_partron!} </label>
            </td>
            <td  class='user_item' >
              <input type='radio' id='user_info_search' class='checkbox_dark' name='user_info' value='1'>
              <label for='user_info_search'> {!exist_user!} </label>
          </td>
            <td class='user_item'  align  ='right' width='100'>
              <button type="button" id="search_user" name='action' value='search_user'>{!search!}</button>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    </thead>
    <tbody id='user_data' style="display:none;"> <tr><td class='gui_form'>
      {gui->setdata data=$user_data errors=$user_errors nameclass='user_item' valueclass='user_value' namewidth='120'}
      {gui->input name='user_firstname' mandatory=true size='30' maxlength='50'}
      {gui->input name='user_lastname' mandatory=true size='30' maxlength='50'}
      {gui->input name='user_address' mandatory=true size='30' maxlength='75'}
      {gui->input name='user_address1' size='30' maxlength='75'}
      {gui->input name='user_zip' mandatory=true size='8' maxlength='20'}
      {gui->input name='user_city' mandatory=true size='30' maxlength='50'}
      {gui->selectstate name='user_state'}
      {gui->selectcountry name='user_country' mandatory=true DefaultEmpty=true}
      {gui->input name='user_phone' size='15' maxlength='50'}
      {gui->input name='user_fax' size='15' maxlength='50'}
      {gui->input name='user_email' mandatory=true size='30' maxlength='50'}
      <input type='hidden' id='user_id' name='user_id' value='-1' />
</td></tr>
    </tbody>
  </table>
  <div id="search-dialog" title="{!personal_search_dialog!}">
     <table id="users_table" class="scroll" cellpadding="0" cellspacing="0"></table>
  </div>