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
require_once("admin/class.adminview.php");

class OrganizerView extends AdminView{

  function form ($data, $err, $title){
    global $_SHOP;

    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>".$title."</td></tr>";
  	echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";

    $this->print_input('organizer_name'   ,$data, $err,25,100);
    $this->print_input('organizer_address',$data, $err,25,100);
    $this->print_input('organizer_plz'    ,$data, $err,25,100);
    $this->print_input('organizer_ort'    ,$data, $err,25,100);
    $this->print_input('organizer_state'  ,$data, $err,25,100);
    $this->print_countrylist('organizer_country', $data, $err);
    $this->print_input('organizer_phone'  ,$data, $err,25,100 );
    $this->print_input('organizer_fax'    ,$data, $err,25,100 );
    $this->print_input('organizer_email'  ,$data, $err,25,100 );
    $this->print_input('organizer_currency',$data, $err,4,3 );

    $this->print_file('organizer_logo'     ,$data, $err);
    $this->form_foot();
  }

  function draw () {
    $org = Organizer::load();
    if(isset($_POST['save'])){
      if($org->fillPost() && $org->saveEx()) {
        $_SESSION['_SHOP_ORGANIZER_DATA'] = $org;
      } else {
        $org = $_POST;
      }
    }
    $this->form((ARRAY)$org, null, con('merchant_update_title'));
  }
}
?>