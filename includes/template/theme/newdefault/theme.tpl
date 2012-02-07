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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"[]>
{function name=menu class='' level=0 data=[]}
  <ul class="{$class} level{$level}">
  {foreach $data as $entry}
    <li><a href={$entry.href}>{$entry.title}</a></li>
    {if is_array($entry.menu)}
       {call name=menu data=$entry.menu level=$level+1}
    {/if}
  {/foreach}
  </ul>
{/function}
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en">
<head>
    <!--
    Created by Artisteer v3.1.0.42580
    Base template (without user's data) checked by http://validator.w3.org : "This page is valid XHTML 1.0 Transitional"
    -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

		<link rel='stylesheet' href='style.php' type='text/css' />

		<!-- Must be included in all templates -->
    <!--[if IE 6]><link rel="stylesheet" href="style.php?T=style.ie6.css" type="text/css" media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" href="style.php?T=style.ie7.css" type="text/css" media="screen" /><![endif]-->
		{include file="required_header.tpl"}
		<link rel='stylesheet' href='style.php?T=style.ext.css' type='text/css' />
    <script type="text/javascript" src="style.php?T=script.js"></script>
   <style type="text/css">
   .art-post .layout-item-0 { border-top-width:1px;border-top-style:solid;border-top-color:#3E81A8;margin-top: 10px;margin-bottom: 10px; }
   .art-post .layout-item-1 { color: #151C23; background:repeat #D6E6F0; }
   .art-post .layout-item-2 {
     border-top-style:solid;border-right-style:dotted;
     border-bottom-style:solid;border-left-style:solid;
     border-top-width:0px;
     border-right-width:1px;
     border-bottom-width:0px;
     border-left-width:0px;
     border-top-color:#3E81A8;
     border-right-color:#3E81A8;
     border-bottom-color:#3E81A8;
     border-left-color:#3E81A8;
     color: #151C23;
     padding-right: 10px;
     padding-left: 10px; }

   .art-post .layout-item-3 { color: #151C23; padding-right: 10px;padding-left: 10px; }
   .art-post .layout-item-4 { padding-right: 10px;padding-left: 10px; }
   .art-post .layout-item-5 { margin-bottom: 10px; }
   .art-post .layout-item-6 { color: #152B38; border-spacing: 10px 0px; border-collapse: separate; }
   .art-post .layout-item-7 { border-top-style:solid;border-right-style:solid;border-bottom-style:solid;border-left-style:solid;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-color:#3E81A8;border-right-color:#3E81A8;border-bottom-color:#3E81A8;border-left-color:#3E81A8; color: #152B38; padding-right: 10px;padding-left: 10px; }

   </style>
  <script type="text/javascript">
  	jQuery(document).ready(function(){
      //var msg = ' errors';
      var emsg = '{printMsg|escape:'quotes' key='__Warning__' addspan=false}';
      showErrorMsg(emsg);
      var nmsg = '{printMsg|escape:'quotes' key='__Notice__' addspan=false}';
      showNoticeMsg(nmsg);
      if (navigator.cookieEnabled == false) { $.modal("<div>{!cookie_disabled!}</div>"); }
      {gui->getJQuery}
      $('label.required').append('&nbsp;<strong>*&nbsp;</strong>');

    });
    var showErrorMsg = function(msg){
      if(msg) {
        jQuery("#error-text").html(msg);
        jQuery("#error-message").show();
        setTimeout(function(){ jQuery("#error-message").hide(); }, 10000);
      }
    }
    var showNoticeMsg = function(msg){
      if(msg) {
        jQuery("#notice-text").html(msg);
        jQuery("#notice-message").show();
        setTimeout(function(){ jQuery("#notice-message").hide(); }, 7000);
      }
    }
var ajaxQManager = $.manageAjax.create('ajaxQMan',{
	queue:true,
	abortOld:true,
	maxRequests: 1,
	cacheResponse: false
});
  </script>

</head>
<body>
<div id="art-page-background-glare-wrapper">
    <div id="art-page-background-glare"></div>
</div>
<div id="art-main">
    <div class="cleared reset-box"></div>
    <div class="art-box art-sheet">
        <div class="art-box-body art-sheet-body">
            <div class="art-header">
                <div class="art-headerobject"></div>
                    <div class="art-logo">
                       {if $_shop->shop_sitename}
                          <h1 class="art-logo-name"><a href="./index.html">{$_shop->shop_sitename}</a></h1>
                       {/if}
                       {if $_shop->shop_slogan}
                          <h2 class="art-logo-text">{$_shop->shop_slogan}</h2>
                       {/if}
                    </div>
                </div>
                {include file="Progressbar.tpl" name=$name}
                <DIV style="MARGIN-TOP: 0.35em;MARGIN-Bottom: 0.35em; DISPLAY: none" id=error-message class="ui-state-error ui-corner-all" title="Order Error Message">
                <P><SPAN style="FLOAT: left; MARGIN-RIGHT: 0.3em" class="ui-icon ui-icon-alert"></SPAN><div id=error-text>ffff<br>tttttcv ttt </div> </P></DIV>
                <DIV style="MARGIN-TOP: 0.35em; MARGIN-Bottom: 0.35em; DISPLAY: none" id=notice-message class="ui-state-highlight ui-corner-all" title="Order Notice Message">
                <P><SPAN style="FLOAT: left; MARGIN-RIGHT: 0.3em" class="ui-icon ui-icon-info"></SPAN><div id=notice-text>fff</div> </P></DIV>

                <div class="cleared reset-box"></div>
                <div class="art-bar art-nav">
                  <div class="art-nav-outer">
                    {if $topmenu}
                       {call name=menu data=$topmenu class="art-hmenu"}
                    {else}
                      <ul class='art-menu'><li>
                        welkom to the world
                      </li></ul>
                    {/if}
                  </div>
                </div>
                <div class="cleared reset-box"></div>
                <div class="art-layout-wrapper">
                  <div class="art-content-layout">
                    <div class="art-content-layout-row">
                      <div class="art-layout-cell art-sidebar1">
                        {include file='user_login_block.tpl'}
                        {if $vermenu}
                          <div class="art-box art-vmenublock">
                            <div class="art-box-body art-vmenublock-body">
                              <div class="art-bar art-vmenublockheader">
                                  <h3 class="t">{!vertical_menu!}</h3>
                              </div>
                              <div class="art-box art-vmenublockcontent">
                                <div class="art-box-body art-vmenublockcontent-body">
                                  {call name=menu data=$vermenu class="art-vmenu"}
                              		<div class="cleared"></div>
                                </div>
                              </div>
                          		<div class="cleared"></div>
                            </div>
                          </div>
                        {/if}
                        {include file='cart_view_block.tpl'}
                        {*
                        <div class="art-box art-block">
                          <div class="art-box-body art-block-body">
                            <div class="art-bar art-blockheader">
                                <h3 class="t">Zoeken</h3>
                            </div>
                            <div class="art-box art-blockcontent">
                              <div class="art-box-body art-blockcontent-body">
                                <div>
                                  <form method="get" name="searchform" action="#">
                                    <input type="text" value="" name="s" style="width: 95%;" />
                                    <span class="art-button-wrapper">
                                      <span class="art-button-l"> </span>
                                      <span class="art-button-r"> </span>
                                      <a class="art-button" href="javascript:void(0)">Search</a>
                                    </span>

                                  </form>
                                </div>
                             		<div class="cleared"></div>
                              </div>
                            </div>
                        		<div class="cleared"></div>
                          </div>
                        </div>
                        <div class="art-box art-block">
                          <div class="art-box-body art-block-body">
                            <div class="art-bar art-blockheader">
                              <h3 class="t">Nieuw blok</h3>
                            </div>
                            <div class="art-box art-blockcontent">
                              <div class="art-box-body art-blockcontent-body">
                                <p>Enter Block content here...</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam pharetra, tellus sit amet congue vulputate, nisi erat iaculis nibh, vitae feugiat sapien ante eget mauris.</p>
                                <div class="cleared"></div>
                              </div>
                            </div>
                        		<div class="cleared"></div>
                          </div>
                        </div>
                        *}

                        <div class="cleared"></div>
                          <div class="art-box art-vmenublock">
                            <div class="art-box-body art-vmenublock-body">
                        <script type="text/javascript">
                          <!--
                          google_ad_client = "pub-7366313208188286";
                          /* 200x200, gemaakt 13-9-10 */
                          google_ad_slot = "5400276806";
                          google_ad_width = 200;
                          google_ad_height = 200;
                          //-->
                        </script>
                        <script type="text/javascript"
                          src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>
                      </div>
                      </div>
                      </div>
{* content start here *}
                      <div class="art-layout-cell art-content">
                        <div class="art-box art-post">
                          <div class="art-box-body art-post-body">
                            <div class="art-post-inner art-article">
                              <h2 class="art-postheader">{$pagetitle}</h2>
                              {if $headerNote}
                                <div class="art-postcontent">
                                  {$headerNote}
                                </div>
                              {/if}
                              <div class="art-postcontent">
                                {$WebContent}
                              </div>
                              {if $footNote}
                                <div class="art-postcontent">
                                  {$footNote}
                                </div>
                              {/if}
                              <div class="cleared"></div>
                            </div>
                            <div class="cleared"></div>
                          </div>
                        </div>
                        <div class="cleared"></div>
                      </div>
                      <div class="art-layout-cell art-sidebar2">
                      <div class="cleared"></div>
                        <div class="art-box art-block">
                          <div class="art-box-body art-block-body">

                        <script type="text/javascript">
                          <!--
                          google_ad_client = "pub-7366313208188286";
                          /* 120x600, gemaakt 13-9-10 */
                          google_ad_slot = "1716207720";
                          google_ad_width = 120;
                          google_ad_height = 600;
                          //-->
                        </script>
                        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>

                        <div class="cleared"></div><div class="cleared"></div>
                      </div>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="cleared"></div>
                <div class="art-footer">
                  <div class="art-footer-body">
                    {* <a href="#" class="art-rss-tag-icon" title="RSS"></a> *}
                    <div class="art-footer-text">
                      <p><a href="#">Link1</a> | <a href="#">Link2</a> | <a href="#">Link3</a></p>
                      <p>
                        Copyright c 2011. All Rights Reserved.<br>
                    		<!-- To comply with our GPL please keep the following link in the footer of your site -->
                        <!-- Failure to abide by these rules may result in the loss of all support and/or site status. -->
                        Powered By <a href="http://www.fusionticket.org"> Fusion Ticket</a> - Free Open Source Online Box Office
                      </p>
                    </div>
                    <div class="cleared"></div>
                  </div>
              </div>
              <div class="cleared"></div>
            </div>
        </div>
        <div class="cleared"></div>
        <p class="art-page-footer">Designed by <a href="http://www.fusionticket.org" target="_blank">Niels v/d Spek</a>.</p>
        <div class="cleared"></div>
    </div>
    <div style="display:none" id='showdialog'></div>
  </body>
</html>