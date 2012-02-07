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

{* config_load file="shop_$_SHOP_lang.conf" *}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>{$organizer->organizer_name}{if $name} - {$name}{/if}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="nl" />

<meta name="generator" content="phpMyTicket" />

<link rel="shortcut icon" href="{$_SHOP_themeimages}favicon.ico" />
<link rel="icon" href="{$_SHOP_themeimages}animated_favicon1.gif" type="image/gif" />
<link rel='stylesheet' href='style.php' type='text/css' />

{literal}
<script language="JavaScript">
<!--
browser_version= parseInt(navigator.appVersion);
browser_type = navigator.appName;
if (browser_type == "Microsoft Internet Explorer" && (browser_version >= 4)) {
  document.write("<link REL='stylesheet' HREF='style_ie.css' TYPE='text/css' />");
}else if (browser_type == "Netscape" && (browser_version >= 4)) {
  document.write("<link REL='stylesheet' HREF='style_nn.css' TYPE='text/css' />");
}else{
  document.write("<link REL='stylesheet' HREF='style_nn.css' TYPE='text/css' />");
}
	var ImageObj = 0;
	if (document.images) {
		ImageObj = 1;
		n1 = new Image(0,0);
		n1.src = '{$_SHOP_themeimages}home_a.gif';
		n1a = new Image(0,0);
		n1a.src = '{$_SHOP_themeimages}home_b.gif';
		n2 = new Image(0,0);
		n2.src = '{$_SHOP_themeimages}disclaimer_a.gif';
		n2a = new Image(0,0);
		n2a.src = '{$_SHOP_themeimages}disclaimer_b.gif';
		}

	function changeImage(imgDocID,imgObjName) {
		if (ImageObj) {
			document.images[imgDocID].src = eval(imgObjName + '.src')
		}
	}
// --></script>
<script type="text/javascript" src="scripts/countdownpro.js" defer="defer"></script>
<meta scheme="countdown1" name="d_hidezero" content="1">
<meta scheme="countdown1" name="h_hidezero" content="1">
<meta scheme="countdown1" name="m_hidezero" content="1">
<meta scheme="countdown1" name="s_hidezero" content="1">
<meta scheme="countdown1" name="event_msg" content="0! ">
<meta scheme="countdown1" name="servertime" content="{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} GMT+00:00">

{/literal}
</head>

<body bgcolor="#ffffff" style="margin: 0px;">

  <br />
  <center>
  <table summary="" cellspacing="0" cellpadding="0" width="1000" border="0">
    <tr>
      <td valign="top" width="770">
        <img height="20" alt="" src="{$_SHOP_themeimages}top_left.gif" width="847" border="0"><br>
      </td>
      <td valign="top" width="49">
      <a onmouseover="changeImage('home','n1a');" onfocus="blur();" onmouseout="changeImage('home','n1')" href="index.php">
      <img height="20" alt="" src="{$_SHOP_themeimages}home_a.gif" width="49" border="0" id="home"></a><br>
      </td>
      <td valign="top" width="12">
      <img height="20" alt="" src="{$_SHOP_themeimages}top_right1.gif" width="12" border="0"><br>
      </td>
      <td valign="top" width="73">
      <a target='agb' onmouseover="changeImage('disclaimer','n2a')" onfocus="blur();" onmouseout="changeImage('disclaimer','n2')" href="agb.php">
      <img height="20" alt="" src="{$_SHOP_themeimages}disclaimer_a.gif" width="73" border="0" id="disclaimer"></a><br>
      </td>
      <td valign="top" width="19">
      <img height="20" alt="" src="{$_SHOP_themeimages}top_right3.gif" width="19" border="0"><br>
      </td>

    </tr>
  </table>
  <img height="5" alt="" src="{$_SHOP_themeimages}spacer.gif" width="1" border="0"><br>
  <table summary="" cellspacing="0" cellpadding="0" width="1000" border="0">
    <tr>
      <td valign="top" width="14">
        <img height="100" alt="" src="{$_SHOP_themeimages}top_left2.gif" width="14" border="0"><br>
      </td>
      <td valign="top" valign='top' style="background-image: url({$_SHOP_themeimages}misc2_46.jpg); background-repeat: repeat-x; background-attachment: scroll;">
        <img height="75" alt="" src="{$_SHOP_themeimages}spacer.gif" width="1" border="0">
        <img height="75" alt="" src="{$_SHOP_themeimages}fusion.png" width="370" border="0"> <br />
      </td>
      <td valign="top" width="14">
        <img height="100" alt="" src="{$_SHOP_themeimages}top_right.gif" width="14" border="0"><br>
      </td>
    </tr>
  </table>
  <img height="5" alt="" src="{$_SHOP_themeimages}spacer.gif" width="1" border="0"><br>
  <img height="10" alt="" src="{$_SHOP_themeimages}groenelijn.gif" width="1000" border="0"><br>
  <div id="navbar">
    <ul >
     <li><a href='index.php'>{#home#}</a>
     <li><a href='calendar.php'>{#calendar#}</a>
     <li><a href='programm.php'>{#program#}</a>
    </ul><br />
  </div>
  <table summary="" cellspacing="0" cellpadding="0" width="1000" border="0" style="height: 72%" >
    <tr>
      <td valign="top" width="13" style="background-image: url('{$_SHOP_themeimages}mid_leftback.gif'); background-repeat: repeat-y; ">
        <img height="5" alt="" src="{$_SHOP_themeimages}spacerwit.gif" width="13" border="0"><br>
        <img height="274" alt="" src="{$_SHOP_themeimages}mid_left.gif" width="7" border="0"><br>
      </td>
      <td valign="top" width="981">
        <img height="3" alt="" src="{$_SHOP_themeimages}spacer.gif" width="1" border="0"><br>
        <table summary="" cellspacing="0" cellpadding="0" width="100%" border="0">
          <tr>
            <td height="12" color="#6b9f31">
              <h1>{$name}&nbsp;</h1>
            </td>
            <td valign="top" width="7">
              <img height="1" alt="" src="{$_SHOP_themeimages}spacer.gif" width="1" border="0">
            </td>
            <td width="210"><b><font color="#6b9f31">&nbsp;</font></b>
            </td>
          </tr>
          <tr>
            <td style="BACKGROUND-POSITION: right top; BACKGROUND-IMAGE: url('{$_SHOP_themeimages}mid_leftbackbig.gif'); BACKGROUND-REPEAT: no-repeat" valign="top">
              <img height="11" alt="" src="{$_SHOP_themeimages}spacer.gif" width="1" border="0"><br>
              <center>