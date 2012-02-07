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
 * This define is used to store the passwords, pleace do not change this after
 * there are uses registrated to the system.
 * This this will invalided all given passwords in the system.
 */
if (!defined('ft_check')) {die('System intrusion ');}
  header('Content-Type: text/html; charset=utf-8');

  global $_SHOP;
  if(function_exists("mb_internal_encoding")) {
    mb_internal_encoding("UTF-8");
  }
  if(function_exists("date_default_timezone_set")) {
    @date_default_timezone_set($_SHOP->timezone);
  }

  ini_set('memory_limit','64M');
  ini_set('magic_quotes_runtime', 0);
  ini_set('allow_call_time_pass_reference', 0);

// mb_

//check if the site is online
  require_once("classes/basics.php");

  require_once("classes/class.shopdb.php");
  require_once("classes/class.model.php");


  set_error_handler("customError");

  //ini_set('session.save_handler','user');
  //require_once("classes/class.sessions.php");

 // print_r($_SERVER);
  $query="SELECT *, UNIX_TIMESTAMP() as current_db_time FROM ShopConfig LIMIT 1";
  if(!$res=ShopDB::query_one_row($query) or $res['status']==='OFF'){
    if($_SHOP->is_admin){

      $_SHOP->system_status_off=TRUE;

    }else{
      echo "<center>
            <h1>This service is temporarily unavailable</h1>
	          <h3>Please return later</h3></center>";
      exit;
    }
  }
  foreach($res as $key => $value){
    if ($key != 'status') {
      $_SHOP->$key = $value;
    }
  }

//  echo "<pre>";
//  print_r($_SHOP);
//  echo "</pre>";
  //starting a new session

  session_name($_SHOP->session_name);

  session_start();
  If (isset($_SHOP->secure_id) and (!isset($_SESSION['_SHOP_SYS_SECURE_ID']) || ($_SHOP->secure_id <> $_SESSION['_SHOP_SYS_SECURE_ID'] ) )) {
    session_unset();
    $_SESSION = array();
    session_destroy(); //echo 'new session_id';
    session_start();
    $_SESSION['_SHOP_SYS_SECURE_ID'] = $_SHOP->secure_id;
  }

//authentifying (if needed)
  require_once "classes/class.secure.php";
  $accepted = Secure::CheckTokens();
  if (!$accepted) {
     $tokens = print_r($_SESSION['tokens'], true);
     writeLog('% Tokens '.(($tokens)?$tokens:'NOT FOUND !!!'));
     writeLog("% Token {$name}, {$value}, {$testme}");
     writeLog('% used IP: '.getIpAddress());
     writeLog(print_r($_SERVER,true));
     writeLog(print_r($_ENV,true));
     writeLog('     ---------------------------------------------------');

     orphancheck();
     session_unset();
     session_destroy();
     $string = "<h1>Access Denied</h1>";
     $string .= "<p><strong>Why?</strong> :- Please check you submitted a form within the same domain (website address).</p>";
     $string .= "<p><strong>Or</strong> :- Your session does not match your url.</p>";
     $string .= "<p>Please check your cookie settings and turn it on.</p>";

     die($string);
  }

  // check the order system for outdated orders and reservations
  check_system();

  if (!loadLanguage('custom')) {loadLanguage('site');}


 // writeLog($old = setlocale(LC_TIME, NULL));

  $loc = con('setlocale_ALL',' ');
  if(!empty($loc)){
    setlocale(LC_ALL, explode(';',$loc));
  }
  $loc = con('setlocale_TIME',' ');
  if(!empty($loc)){
    setlocale(LC_TIME, explode(';',$loc));
  }


  //ini_set("session.gc_maxlifetime", [timeinsec]);


//loading organizer attributes
  if(empty($_SESSION['_SHOP_ORGANIZER_DATA'])){
     $_SESSION['_SHOP_ORGANIZER_DATA'] = Organizer::load();
	}

  $_SHOP->organizer_data=(object)$_SESSION['_SHOP_ORGANIZER_DATA'];
  $_SHOP->currency  = $_SHOP->organizer_data->organizer_currency;
?>