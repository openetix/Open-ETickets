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
 </td>
    <td width='210px' align='right' valign="top"><br>
	{include file="user_login_block.tpl"} <br>
	{include file="cart_resume.tpl"}<br>
	</td>
  </tr>
</table>
</div>
	<div class="footer">
		<hr width="100%" />
		<!-- To comply with our GPL please keep the following link in the footer of your site -->
    <!-- Failure to abide by these rules may result in the loss of all support and/or site status. -->
    Copyright 2010<br />
    Powered By <a href="http://www.fusionticket.org"> Fusion Ticket</a> - Free Open Source Online Box Office
	</div>
</div>
  {literal}
  <script type="text/javascript">
  	jQuery(document).ready(function(){
      //var msg = ' errors';
      var emsg = '{/literal}{printMsg|escape:'quotes' key='__Warning__' addspan=false}{literal}';
      showErrorMsg(emsg);
      var nmsg = '{/literal}{printMsg|escape:'quotes' key='__Notice__' addspan=false}{literal}';
      showNoticeMsg(nmsg);

    });
    var showErrorMsg = function(msg){
      if(msg) {
        jQuery("#error-text").html(msg);
        jQuery("#error-message").show();
        setTimeout(function(){jQuery("#error-message").hide();}, 10000);
      }
    }
    var showNoticeMsg = function(msg){
      if(msg) {
        jQuery("#notice-text").html(msg);
        jQuery("#notice-message").show();
        setTimeout(function(){jQuery("#notice-message").hide();}, 7000);
      }
    }
  </script>
  {/literal}

</body>
</html>