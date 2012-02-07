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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- link rel="stylesheet" type="text/css" href="css/formatting.css" media="screen"  -->

		<link rel='stylesheet' href='style.php' type='text/css' />

		<!-- Must be included in all templates -->
		{include file="required_header.tpl"}
		<!-- End Required Headers -->
	</head>

  {*
            $("#error-message").hide();
          $("#notice-message").hide();
*}

	<body class='main_side'>   <center>
		<div class="mainbody" align='left'>
			<img class="spacer" src='{$_SHOP_themeimages}dot.gif' height="1px" />
			<br />
			<img src="{$_SHOP_images}logo.png" align="bottom" />
			<br />

		<div id="navbar">
    		<ul>
     			<li>
 					<a href='index.php'>{!home!}</a>
				</li>
				<li>
					<a href='calendar.php'>{!calendar!}</a>
				</li>
				<li>
					<a href='programm.php'>{!program!}</a>
				</li>
			</ul>     <br>
  		<div align="right" style="vertical-align: top; width:100%; " >
  			<a href="?setlang=en">[en]</a>
  		</div>
		</div>
  <DIV style="MARGIN-TOP: 0.35em;MARGIN-Bottom: 0.35em; DISPLAY: none" id=error-message class="ui-state-error ui-corner-all" title="Order Error Message">
  <P><SPAN style="FLOAT: left; MARGIN-RIGHT: 0.3em" class="ui-icon ui-icon-alert"></SPAN><div id=error-text>ffff<br>tttttcv ttt </div> </P></DIV>
  <DIV style="MARGIN-TOP: 0.35em; MARGIN-Bottom: 0.35em; DISPLAY: none" id=notice-message class="ui-state-highlight ui-corner-all" title="Order Notice Message">
  <P><SPAN style="FLOAT: left; MARGIN-RIGHT: 0.3em" class="ui-icon ui-icon-info"></SPAN><div id=notice-text>fff</div> </P></DIV>
{*
    <div id="error-message" title="{!order_error_message!}" class="ui-state-error ui-corner-all" style="padding: 1em; margin-top: .7em; display:none;" >
       <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
          <span id='error-text'>ffff</span>
       </p>
    </div>
    <div id="notice-message" title="{!order_notice_message!}" class="ui-state-highlight ui-corner-all" style=" padding: 1em; margin-top: .7em; display:none;" >
       <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
          <span id='notice-text'>fff</span>
       </p>
    </div>
*}
		<div class="maincontent">
			<table width='100%' border='0' cellpadding='0' cellspacing='0'>
  				<tr>
					<td valign='top' align='left'><br>
            {include file="Progressbar.tpl" name=$name}
						<br />
  						{if $name}
    						<h1>{$name}</h1>
  						{/if}
  						{if $header}
    						<div>{$header}</div>
  						{/if}