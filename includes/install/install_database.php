<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2011 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *  phpMyTicket - ticket reservation system
 *   Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
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

class install_database {
  static function precheck($Install) {
    return true; //(!$_SESSION['ConfigExist']) or ($_SESSION['DB_Error']) or ($_SESSION['radio'] == 'NORMAL');
  }

  static function postcheck($Install) {
    Install_Request(Array('db_name','db_uname','db_pass', 'db_host', 'db_prefix'),'SHOP');
    if(empty($_SESSION['SHOP']['db_host']))
      {array_push($Install->Errors,'No database hostname specified.');}
    if(empty($_SESSION['SHOP']['db_name']))
      {array_push($Install->Errors,'No database name specified.');}
    if ($Install->Errors) return true;
    $link = OpenDatabase();

    if(@mysqli_errno ($link)==1049 and $_REQUEST['db_create_now']){
      $link->query('CREATE DATABASE ' . $_SESSION['SHOP']['db_name']);
      if(!(@mysqli_connect_error($link) or @mysqli_error($link))){
        $link->select_db($_SESSION['SHOP']['db_name']);
        $_SESSION['radio']    = 'NORMAL';
//        $_SESSION['db_demos'] = $_REQUEST['db_demos'];
      }
    }

    if(@mysqli_connect_error($link) or @mysqli_error($link)){
      array_push($Install->Errors,'A database connection could not be established using the settings you have provided.<br>'.
                                 'Error code: '. @mysqli_connect_error($link) . @mysqli_error($link));
      if(@mysqli_errno ($link)==1049) $_SESSION['DB_Error'] = true;
      return true;
    }

    $row = shopdb::query_one_row("show variables like 'have_inno%'");
    if ($row && ($row['Value'] !== 'YES')) {
      array_push($Install->Errors,'Fusion Ticket uses the MySQL InnoDB engine. This is not installed on your server.');
      return true;
    }

    if ($result = $link->Query("SHOW TABLE STATUS LIKE 'Admin'")) {
      //do nothing here;
    } elseif ($result = $link->Query("SHOW TABLE STATUS LIKE 'admin'")) {
      //do nothing here;
    }
    if (!$result) {
      $_SESSION['DatabaseExist'] = false;
    } elseif ( !$row = $result->fetch_assoc()) {
      $_SESSION['DatabaseExist'] = false;
    } elseif ( $row['rows']>0  ) {
      $_SESSION['DatabaseExist'] = false;
    } else {
      $_SESSION['DatabaseExist'] = true;
      $_SESSION['radio'] = 'UPGRADE';
    }

    $OrgExist = false;
    if (ShopDB::tableExists('Organizer')) {
      $select = 'select * from Organizer LIMIT 0,1';
      if ($row = ShopDB::query_one_row($select)) {
        $OrgExist = true;
        foreach ($row as $key => $value) {
           $_SESSION['ORG'][$key] = stripslashes($value);

        }
      }
    }
    if (!$OrgExist) {
      $_SESSION['ORG']['organizer_name'] ='Demo Owner';
      $_SESSION['ORG']['organizer_address'] = '5678 Demo St';
      $_SESSION['ORG']['organizer_ort'] = '11001';
      $_SESSION['ORG']['organizer_plz'] = 'Demo Town';
      $_SESSION['ORG']['organizer_state'] = 'DT';
      $_SESSION['ORG']['organizer_country'] = 'US';
      $_SESSION['ORG']['organizer_email'] = '';
      $_SESSION['ORG']['organizer_fax'] = '(555) 555-1215';
      $_SESSION['ORG']['organizer_phone'] = '(555) 555-1214';
      $_SESSION['ORG']['organizer_place'] = '';
      $_SESSION['ORG']['organizer_currency'] = 'USD';
      $_SESSION['ORG']['organizer_logo'] = 'organizer_logo_3.png';
    }
    return true;
  }

  static function display($Install) {
    Install_Form_Open ($Install->return_pg,'','Database Connection Settings');
    if (!$_SESSION['SHOP']['db_host']) $_SESSION['SHOP']['db_host'] = 'localhost';
    if (!$_SESSION['SHOP']['db_name']){
      $tmp = strtolower( 'ft_'.INSTALL_VERSION);
      $tmp = str_replace(" ", "", $tmp);
      $tmp = str_replace(".", "_", $tmp);
      $tmp = str_replace("-", "_", $tmp);
      $_SESSION['SHOP']['db_name'] = $tmp;
    }

    echo "<table cellpadding=\"1\" cellspacing=\"2\" width=\"100%\">
            <tr>
              <td colspan=\"2\">
                 Enter the required database connection information below to allow the installation process to create tables in the specified database.<br>
              </td>
            </tr>
            <tr> <td height='6px'></td> </tr>
            <tr>
              <td width='30%'>Hostname</td>
              <td><input type=\"text\" name=\"db_host\" value=\"".$_SESSION['SHOP']['db_host']."\" /></td>
            </tr>
            <tr>
              <td>Database</td>
              <td><input type=\"text\" name=\"db_name\" value=\"".$_SESSION['SHOP']['db_name']."\" /></td>
            </tr>
            <tr>
              <td>Username</td>
              <td><input type=\"text\" name=\"db_uname\" value=\"".$_SESSION['SHOP']['db_uname']."\" /></td>
            </tr>
            <tr>
              <td>Password</td>
              <td><input type=\"text\" name=\"db_pass\" value=\"".$_SESSION['SHOP']['db_pass']."\" /></td>
            </tr>\n";
    if (!empty($_SESSION['DB_Error']) ) {
      echo "
            <tr>
              <td><br>Create Database:</td>
              <td><br><input type=checkbox name='db_create_now' value='1'></td>
            </tr>";
    }
    echo "</table>\n";

    Install_Form_Buttons ();
    Install_Form_Close ();

  }
}
?>