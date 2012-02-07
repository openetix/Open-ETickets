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

class OrtView extends AdminView {
  function table () {
    $query = "SELECT * FROM Ort order by ort_country desc";
    $res = ShopDB::query($query);
    $alt = 0;
    echo "<table class='admin_list' width='$this->width' cellpadding='2' cellspacing='1'>\n";
    echo "<tr><td class='admin_list_title' colspan='3' align='left'>" . con('ort_title') . "</td>";
    echo "<td colspan='1' align='right'>".$this->show_button("{$_SERVER['PHP_SELF']}?action=add","add_venue",3)."</td>";
    echo "</tr>\n";
    if ($res) {
      while ($row = ShopDB::fetch_assoc($res)) {
          echo "<tr class='admin_list_row_$alt'>";
          echo "<td class='admin_list_item' width='50%'>{$row['ort_name']}</td>\n";
          echo "<td class='admin_list_item'>".$this->getCountry($row['ort_country'])."</td>\n";
          echo "<td class='admin_list_item'>{$row['ort_city']}</td>\n";
          echo "<td class='admin_list_item'width='65' align='right' nowrap><nowrap>";
          echo $this->show_button("{$_SERVER['PHP_SELF']}?action=edit&ort_id={$row['ort_id']}","edit",2);
          echo $this->show_button("javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&ort_id={$row['ort_id']}\";}","remove",2,array('tooltiptext'=>"Delete {$row['ort_name']}?"));
          echo "</nowrap></td>\n";
          echo "</tr>";
          $alt = ($alt + 1) % 2;
      }
    }
    echo "</table>\n";
    // echo "<br><center><a class='link' href='{$_SERVER['PHP_SELF']}?action=import'>".import."</a></center>";
  }

  function form ($data, $err, $title) {
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='save'/>\n";
    if (isset($data['ort_id'])) {
       echo "<input type='hidden' name='ort_id' value='{$data['ort_id']}'/>\n";
    }
    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>" . $title . "</td></tr>";
    $this->print_field_o('ort_id', $data, $err);
    $this->print_input('ort_name', $data, $err, 25, 100);
    $this->print_input('ort_address', $data, $err, 25, 75);
    $this->print_input('ort_address1', $data, $err, 25, 75);
    $this->print_input('ort_zip', $data, $err, 10, 20);
    $this->print_input('ort_city', $data, $err, 25, 50);
    $this->print_input('ort_state', $data, $err, 25, 50);
    $this->print_countrylist('ort_country', $data, $err);
    $this->print_input('ort_phone', $data, $err, 25, 50);
    $this->print_input('ort_fax', $data, $err, 25, 50);
    // $this->print_input('ort_plan_nr',$data, $err,6,100);
    $this->print_input('ort_url', $data, $err, 50, 100);
    $this->print_area('ort_pm', $data, $err, 4, 49);
    $this->print_file('ort_image', $data, $err);

    if  (($data['ort_id'])) {
      $this->form_foot();
        require_once('admin/view.placemaps.php');
        PlaceMapView::table($data['ort_id']);
    		echo "<br \>".$this->show_button("{$_SERVER['PHP_SELF']}",'admin_list',3);
    } else
      $this->form_foot(2,$_SERVER['PHP_SELF']);
  }

  function draw () {
   // addNotice("Action: {$_REQUEST['action']} pm_id {$_REQUEST['pm_id']}");
    if (preg_match('/_pm$/', $_REQUEST['action']) or preg_match('/_pmz$/', $_REQUEST['action']) or
        preg_match('/_pmp$/', $_REQUEST['action']) or preg_match('/_category$/', $_REQUEST['action'])){
        require_once('admin/view.placemaps.php');
        $pm_view = new PlaceMapView($this->width);
        $pm_view->draw();
        $this->addJQuery($pm_view->getJQuery());

    } elseif ($_GET['action'] == 'add') {
       $this->form($row, $err, con('ort_add_title'));
    } elseif ($_GET['action'] == 'edit' && $_GET['ort_id']){
        if ($ort = ort::load($_REQUEST['ort_id'])) {
           $row = (array)$ort;
           $this->form($row, null, con('ort_update_title'));
        }
    } elseif ($_POST['action'] == 'save') {
      if (!$_POST['ort_id'] || !$ort = ort::load($_POST['ort_id'])) {
         $ort = new ort(true);
      }
      if ($ort->fillPost() && $ort->saveEx()) {
        $this->table();
      } else {
        $this->form($_POST, null, con((isset($_POST['ort_id']))?'ort_update_title':'ort_insert_title'));
      }

    } elseif ($_GET['action'] == 'remove' and $_GET['ort_id'] > 0) {
      if($ort = ort::load($_GET['ort_id']))
        $ort->delete();
      $this->table();
    } elseif ($_GET['action'] == 'import') {
        $this->shared_ort_list();

    } else {
      $this->table();
    }
  }
}

?>