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

class EventLinksView extends AdminView {
  function table ($event_id, $live = false){
      global $_SHOP;
      $alt = 0;
      echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2'>\n";
      echo "<tr><td class='admin_list_title' colspan='3' align='left'>" . con('linked_event_managers') . "</td>";
      echo "</tr>\n";
      $query = 'select Admin.*, adminlink_id from `Admin` left join `adminlink` on admin_id = adminlink_admin_id
                where adminlink_event_id = '._esc($event_id)."
                and admin_status='organizer'
                order by admin_login";
      if ($res = ShopDB::query($query)) {
        while ($row = shopDB::fetch_assoc($res)) {
          echo "<tr class='admin_list_row_$alt'>";
          echo "<td class='admin_list_item' width='40%' style='width:40%;'>{$row['admin_login']}</td>\n";
          echo "<td class='admin_list_item'  >{$row['admin_email']}&nbsp;</td>\n";
          echo "<td class='admin_list_item'width='65' align='right' nowrap><nowrap>";
          echo $this->show_button("javascript:if(confirm(\"".con('al_remove_control')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_al&adminlink_id={$row['adminlink_id']}&event_id={$event_id}\";}","remove",2,array('tooltiptext'=>"Remove {$row['admin_login']}?"));
          echo "</nowrap></td>\n";
          echo "</tr>\n";
          $alt=($alt+1)%2;
        }
      }
      echo "<tr class='admin_list_row_$alt'>";
      echo "  <form action='{$_SERVER['PHP_SELF']}' method=post>\n";
      echo "    <td class='admin_list_item' width='550' colspan='2'  >";
      echo "      <input type=hidden name=action value=add_admin_al>\n";
      echo "      <input type=hidden name=event_id value="._esc($event_id),">\n";
      echo "      <select name='admin_id' style='width:100%;'>\n";
      echo "         <option value='0'></option>\n";
      $query = 'select Admin.* from `Admin`
                where (select  count(*) from `adminlink` where admin_id = adminlink_admin_id and adminlink_event_id = '._esc($event_id).") = 0
                and FIELD(admin_status, 'organizer' )";
      if ($res = ShopDB::query($query)) {
        while ($row = shopDB::fetch_assoc($res)) {
          echo "         <option value='{$row['admin_id']}'> {$row['admin_login']} (Email: ".con($row['admin_email']).") </option>\n";
        }
      }
      echo "      </select></td>\n";
      echo "    <td colspan='1' align='right'>".$this->show_button("submit","add",3)."</td>";
      echo "  </form></tr>\n";
      echo '</table><br>';

      /* ----------------------------------------------------- */
      echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2'>\n";
      echo "<tr><td class='admin_list_title' colspan='3' align='left'>" . con('linked_tickettakers') . "</td>";
      echo "</tr>\n";
      $query = 'select Admin.*, adminlink_id from `Admin` left join `adminlink` on admin_id = adminlink_admin_id
                where adminlink_event_id = '._esc($event_id)."
                and admin_status='control'
                order by admin_login";
      if ($res = ShopDB::query($query)) {
        while ($row = shopDB::fetch_assoc($res)) {
          echo "<tr class='admin_list_row_$alt'>";
          echo "<td class='admin_list_item'  style='width:40%;' >{$row['admin_login']}</td>\n";
          echo "<td class='admin_list_item'  >{$row['admin_email']}&nbsp;</td>\n";
          echo "<td class='admin_list_item'width='65' align='right' nowrap><nowrap>";
          echo $this->show_button("javascript:if(confirm(\"".con('al_remove_control')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_al&adminlink_id={$row['adminlink_id']}&event_id={$event_id}\";}","remove",2,array('tooltiptext'=>"Remove {$row['admin_login']}?"));
          echo "</nowrap></td>\n";
          echo "</tr>\n";
          $alt=($alt+1)%2;
        }
      }
      echo "<tr class='admin_list_row_$alt'>";
      echo "  <form action='{$_SERVER['PHP_SELF']}' method=post>\n";
      echo "    <td class='admin_list_item' width='550' colspan='2'  >";
      echo "      <input type=hidden name=action value=add_admin_al>\n";
      echo "      <input type=hidden name=event_id value="._esc($event_id),">\n";
      echo "      <select name='admin_id' style='width:100%;'>\n";
      echo "         <option value='0'></option>\n";
      $query = 'select Admin.* from `Admin`
                where (select  count(*) from `adminlink` where admin_id = adminlink_admin_id and adminlink_event_id = '._esc($event_id).") = 0
                and FIELD(admin_status, 'control')";
      if ($res = ShopDB::query($query)) {
        while ($row = shopDB::fetch_assoc($res)) {
          echo "         <option value='{$row['admin_id']}'> {$row['admin_login']} (Email: ".con($row['admin_email']).") </option>\n";
        }
      }
      echo "</select></td>\n";
      echo "<td colspan='1' align='right'>".$this->show_button("submit","add",3)."</td>";
      echo "</form></tr>\n";
      echo '</table><br>';
      
      /* ----------------------------------------------------- */
      echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2'>\n";
      echo "<tr><td class='admin_list_title' colspan='3' align='left'>" . con('linked_POSoffices') . "</td>";
      echo "</tr>\n";
      $query = 'select User.*,adminlink_id from `User` left join `adminlink` on user_id = adminlink_pos_id
                where adminlink_event_id = '._esc($event_id);
      
      $query = 'select User.*,adminlink_id, if(seat_pos_id,\'y\',\'n\') as inUse from `User` left join `adminlink` on user_id = adminlink_pos_id  left join 
      (select distinct seat_pos_id from Seat where seat_event_id = '._esc($event_id).' ) 
      as sss on user_id = seat_pos_id where adminlink_event_id ='._esc($event_id);
      
      if ($res = ShopDB::query($query)) {
        while ($row = shopDB::fetch_assoc($res)) {
      echo "<tr class='admin_list_row_$alt'>
  	        <td class='admin_list_item' style='width:40%;'>{$row['user_lastname']}</td>
            <td class='admin_list_item'>{$row['user_city']}</td>";
      echo "<td class='admin_list_item'width='65' align='right' nowrap><nowrap>";
      $toolTipText = con("delete_link_error");
      if($row['inUse'] == 'n'){
      	$allowDeletion = true;
	      $toolTipText = "Remove {$row['user_lastname']}?";
      	
      } else $allowDeletion = false;
          echo $this->show_button("javascript:if(confirm(\"".con('al_remove_pos')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_al&adminlink_id={$row['adminlink_id']}&event_id={$event_id}\";}","remove",2,
          array('tooltiptext'=>$toolTipText, 'disable'=>!$allowDeletion));
          echo "</nowrap></td>\n";
          echo "</tr>\n";
          $alt=($alt+1)%2;
        }
      }
      echo "<tr class='admin_list_row_$alt'>";
      echo "  <form action='{$_SERVER['PHP_SELF']}' method=post>\n";
      echo "    <td class='admin_list_item' width='550' colspan='2'  >";
      echo "      <input type=hidden name=action value=add_pos_al>\n";
      echo "      <input type=hidden name=event_id value="._esc($event_id),">\n";
      echo "      <select name='user_id' style='width:100%;'>\n";
      echo "         <option value='0'></option>\n";
      $query = 'select User.* from `User`
                where (select  count(*) from `adminlink` where user_id = adminlink_pos_id and adminlink_event_id = '._esc($event_id).") = 0
                and user_status = '1'";
      if ($res = ShopDB::query($query)) {
        while ($row = shopDB::fetch_assoc($res)) {
          echo "         <option value='{$row['user_id']}'> {$row['user_lastname']}, {$row['user_city']} </option>\n";
        }
      }

      echo "</select></td>\n";
      echo "<td colspan='1' align='right'>".$this->show_button("submit","add",3)."</td>";
      echo "</form></tr>\n";
      echo '</table>';
      echo $this->show_button("{$_SERVER['PHP_SELF']}",'admin_list',3);
  }


  function draw (){
    global $_SHOP;
//    print_r($_REQUEST);
    if ($_REQUEST['action'] == 'add_admin_al' and $_REQUEST['admin_id'] > 0) {
      AdminLink::create($_REQUEST['event_id'],$_REQUEST['admin_id']);

    } elseif ($_REQUEST['action'] == 'add_pos_al' and $_REQUEST['user_id'] > 0) {
      AdminLink::create($_REQUEST['event_id'],null, $_REQUEST['user_id']);

    } elseif ($_REQUEST['action'] == 'remove_al' and $_REQUEST['adminlink_id'] > 0) {
    	//before deleting, check that the link is not in use
    	//we should never get here as used links do not have a delete button, but check anyway
    	// This check is moved to the model AdminLink where it belongs.
    	if($pmp = AdminLink::load($_REQUEST['adminlink_id'])){
	    	$pmp->delete();
    	}
    }
  }
}

?>