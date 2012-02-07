<?php
/**
%%%copyright%%%
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
 */

if (!defined('ft_check')) {die('System intrusion ');}

/*/Check page is secure
if($_SERVER['SERVER_PORT'] != 443 || $_SERVER['HTTPS'] !== "on") {
$url = $_SHOP->root_secured.$_SERVER['REQUEST_URI'];
echo "<script>window.location.href='$url';</script>"; exit;
//header("Location: https://"$_SHOP->root_secured.$_SERVER['SCRIPT_NAME']);exit;}
}
//remove the www. to stop certificate errors.
if(("https://".$_SERVER['SERVER_NAME']."/") != ($_SHOP->root_secured)) {
$url = $_SHOP->root_secured.$_SERVER['REQUEST_URI'];
echo "<script>window.location.href='$url';</script>"; exit;
}*/

require_once ("controller.admin.main.php");

class ctrlControlMain extends ctrlAdminMain {
  public $auth_required=TRUE;
  public $auth_status="control";
  public $session_name="ControlSession";
  protected $section    = 'control';
  public $title = 'Ticket Taker';

  public function __construct($context='control', $page, $action) {
    parent::__construct($context, $page, $action);
  }

  function loadMenu(){
      $this->addACLs(array('control_admin|control',
                           'orders_admin|pos',
                           'users_admin|pos'),true);
    if (!$this->isAllowed($this->current_page.'_admin')) {
      return false;
    }
    return true;
  }

  function drawContent(){
    global $_SHOP;
    if (!$this->isAllowed($this->current_page.'_admin')) {
      return;
    }
    if (!file_exists(INC."{$this->section}/view.{$this->current_page}.php")) {
      $this->showForbidden();
      return;
    }
    require_once (INC."{$this->section}/view.{$this->current_page}.php");

    $fond = str_replace('.','' ,$this->current_page );
    $classname = "{$fond}View";

    $body = new $classname($this->body_width);
    $this->width = $body->page_width;
    $this->setbody($body);
    $this->drawChild($body);
  }

  function drawHeader() {
    Global $_SHOP;
    if (!$_SERVER["INTERFACE_LANG"]) {
      $_SERVER["INTERFACE_LANG"] = "de";
    }
    $page = $_SERVER["REQUEST_URI"];
    $page_1 = substr($page, 3);
    foreach($this->key as $val) {
      if (!$content) {
        $content = $val;
      } else {
        $content .= "," . $val;
      }
    }

    echo "<html><head>
    <meta HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=UTF-8\">
    <META HTTP-EQUIV=\"Content-Language\" CONTENT=\"" . $_SERVER["INTERFACE_LANG"] . "\">
    <title>" . $this->getTitle() . "</title>
    <link rel='stylesheet' href='style.css'>
    <link rel='shortcut icon' type='images/png' href='{$_SHOP->images_url}favicon.png' />

    <script><!--
    function init(){if(document.f && document.f.codebar){document.f.codebar.focus();}}
    --></script>
    </head><body onload='init();'>";
    echo "  		<div id='wrap'>\n";
    echo "<div  id='header'>
      		<img src=\"".$_SHOP->images_url."logo.png\" border='0'/>
      		<h2>" . $this->getTitle() . "</h2>
             </div>";
    echo"<div id='navbar'>
				<ul>
					<li><a class='link_head' href='index.php?action=change_event'>". con('change_event')."</a></li>
        <li><a class='link_head' href='index.php?action=search_form'>" . con('search')."</a></li>
        <li><a class='link_head' href='index.php?action=logout'>" . con('logout') . "</a>&nbsp;&nbsp;</td></li>
      </ul>
    </div><br>";
  }

  function drawFooter(){
    echo "<br>";
    echo "<div id='footer'>
				Powered by <a href='http://fusionticket.org'>Fusion Ticket</a> - The Free Open Source Box Office
			</div>
		</div>
	</body>
</html>";
  }

}

?>