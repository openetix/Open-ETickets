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
/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

require_once("classes/class.component.php");

/**
 *
 *
 */
class Controller extends Component{
  protected $smarty ;
  protected $HelperList = array();
  protected $context = '';
  protected $current_page = '';
  protected $title      = '';
  protected $executed   = false;
  protected $menu = null;
  protected $view = NULL;
  protected $action = null;
  protected $useSSL = False;

  public $auth_required=false;
  public $auth_status="";
  public $session_name = "ShopSession";

  function __construct($context='web', $page= '',$action=''){
    global $_SHOP;
    parent::__construct();

    if ($this->useSSL) {
      $this->checkSSL();
    }
    $_SHOP->session_name  = $this->session_name;
    $_SHOP->auth_status   = $this->auth_status;
    $_SHOP->auth_required = $this->auth_required;
    $this->context = $context;
    $this->current_page = $page;
    $this->action = $action;
  }

  public function init() {
    $this->loadMenu();
    plugin::call('*Pageload', $this);
  }

  public function draw() {
    ob_start();
    if ($this->drawAuth()) {
      $this->init();
      if (!$this->executed) {
        $this->drawContent();
      }
    }
    $content = ob_get_contents();
    ob_end_clean();
    if (!$this->executed) {
      $this->drawHeader();
      echo $content;
      $this->drawFooter();
    } else {
      echo $content;

    }
  }

  function drawHeader() {
  }

  function drawFooter(){
  }

  function drawContent(){
  }

  function drawAuth(){
    global $_SHOP;
    if ($this->auth_required) {
      require_once "Auth/Auth.php";
      require_once "classes/model.admin.php";

      //authentication starts here
      $params = array("advancedsecurity"=>false,
                      'sessionName'=> $_SHOP->session_name,
                      );

      $auth_container = new CustomAuthContainer($this->auth_status);
      $_auth = new Auth($auth_container,$params);//,'loginFunction'
      $_auth ->setLoginCallback(array($this,'loginCallback'));
      if ($this->action == 'logout') {
        $_auth->logout();
        session_unset();
        $_SESSION = array();
        session_destroy();
      }
      $_auth->start();

      if (!$_auth->checkAuth()) {
        orphancheck();
        return false;
      }

      if(isset($_auth->admin)){
        $_SHOP->admin = $_auth->admin;
        unset($res->admin_password);
      } elseif($res = Admins::load($_SESSION['_SHOP_AUTH_ADMIN_ID'])) {
        $_SHOP->admin = $res;
        unset($res->admin_password);
      } else {
        session_unset();
        $_SESSION = array();
        session_destroy();
        header("location:{$_REQUEST['href']}");
        die;
      }
      $_SHOP->event_ids = $_SHOP->admin->getEventLinks();
      // print_r($_SESSION);
    }
    return true;
  }
  function logincallback ($username, $auth){
    global $_SHOP;
    if($res = $auth->admin){
      $_SESSION['_SHOP_AUTH_USER_NAME']=$username;
      $_SESSION['_SHOP_AUTH_ADMIN_ID']=$res->admin_id;
      //  $res = empt($res->user,$res);
      $_SHOP->admin = $res;
      unset($res->admin_password);
      //  unset($res->_columns);
      $_SESSION['_SHOP_AUTH_USER_DATA']= (array)$res;
    }	else {
      session_destroy();
      orphancheck();
      exit;
    }

    $_SESSION['_SHOP_AUTH_USER_NAME']=$username;
    // echo ini_get("session.gc_maxlifetime");
  }

  function loadMenu(){
    return true;
  }

  function addACLs($menu_items, $tabs=false){
    global $_SHOP;

    $results = array();
    foreach($menu_items as $link => $text){
      list($txt,$role) = explode('|',$text );
      plugin::call('AddACLResource',$txt, $role );
      if ($_SHOP->admin->isAllowed($txt)) {
        if ($tabs) {
          $results[$txt] = $link;
        } else {
          $results[$link] = $txt;
        }
      }
    }
    return $results;
  }

  function setTitle($title){
    $this->title = $title;
  }

  function getTitle(){
    return $this->title;
  }

  public function setJQuery($script){
    $this->set('jquery',$script);
  }

  public function Loadplugins($pluginList) {
    foreach ($pluginList as $plugin) {
      $filename = 'smarty.'.strtolower($plugin).'.php';
      require_once (CLASSES.$filename);
      $this->HelperList[]=$plugin;
    }
  }

  protected function initPlugins() {
    foreach ($this->HelperList as $plugin) {
      $classname = $plugin.'_smarty';
      $plugin = "__{$plugin}";
      $this->$plugin  = new $classname($this->smarty);
    }
  }
  protected function checkSSL(){
    global $_SHOP;
    //    print_r($_SERVER);
    if ($_SHOP->secure_site) {
      $url = $_SHOP->root_secured.basename($_SERVER['SCRIPT_NAME']);
      if($_SERVER['SERVER_PORT'] != 443 || $_SERVER['HTTPS'] !== "on") {
        header("Location: $url");
        exit;
      }
    } elseif($_SERVER['SERVER_PORT'] != 443 || $_SERVER['HTTPS'] !== "on") {
      addWarning('this_page_is_not_secure');
    }
    /* */
  }

  function setMenu($menu) {
    $this->set("menu",$menu);
    if (is_object($menu)) {$menu->setWidth($this->menu_width-10);}
  }

  function setBody($body) {
    $this->set("body", $body);
  }

    function isAllowed($task, $isAction=false){
      global $_SHOP;
      $okay = $_SHOP->admin->isallowed($task);
      if (!$okay) {
        $this->showForbidden();
      }
      return $okay;
    }

  function showForbidden()
  {
    header('HTTP/1.1 403 Forbidden');

    echo("<html>
    <head>
    <title>403 Forbidden</title>
    </head>
    <body>
    <p>".con('you_do_not_have_access')."</p>
    </body>
    </html>");
    $this->executed = true;
  }
}
?>