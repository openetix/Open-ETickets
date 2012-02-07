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

class EventGroupView extends AdminView{
  function table (){
    global $_SHOP;
  //  $query="SELECT * FROM Event, Ort WHERE event_ort_id=ort_id";
    $query="select * from Event_group";

    if(!$res=ShopDB::query($query)){
      return;
    }

    $alt=0;
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2'>\n";
    echo "<tr><td class='admin_list_title' colspan='1' align='left'>".con('event_group_title')."</td>\n";
    echo "<td  align='right'>".$this->show_button("{$_SERVER['PHP_SELF']}?action=add","add",3)."</td></tr>";

    while($row=shopDB::fetch_assoc($res)){

       echo "<tr class='admin_list_row_$alt'>";
  //     echo "<td class='admin_list_item'>{$row['event_group_id']}</td>";
       echo "<td  class='admin_list_item' >{$row['event_group_name']}</td>";
       echo "<td class='admin_list_item' width='105' align='right' nowrap>\n";

       if($row['event_group_status']=='pub'){
          echo $this->show_button("javascript:if(confirm(\"".con('unpublish_event_group')."\")){ location.href=\"{$_SERVER['PHP_SELF']}?action=unpublish&event_group_id={$row['event_group_id']}\"; }","unpublish",2,
               array('image'=>'checked.gif'));
       } else {
          echo $this->show_button("javascript:if(confirm(\"".con('publish_event_group')."\")){ location.href=\"{$_SERVER['PHP_SELF']}?action=publish&event_group_id={$row['event_group_id']}\"; }","publish",2,
               array('image'=>'unchecked.gif'));
       }
       echo $this->show_button("{$_SERVER['PHP_SELF']}?action=edit&event_group_id={$row['event_group_id']}","edit",2);
       echo $this->show_button("javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&event_group_id={$row['event_group_id']}\";}","remove",2,array('tooltiptext'=>"Delete {$row['event_group_name']}?"));
       echo "</td></tr>";
       $alt=($alt+1)%2;
    }

    echo "</table>\n";

  }

  function form ($data, $err, $title){
    global $_SHOP;
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='save'/>\n";
    if($data['event_group_id']){
      echo "<input type='hidden' name='event_group_id' value='{$data['event_group_id']}'/>\n";
    }
    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>".$title."</td></tr>";

    $this->print_field_o('event_group_id',$data );
    $this->print_input('event_group_name',$data,$err,30,100);
    $this->print_date('event_group_start_date',$data,$err);
    $this->print_date('event_group_end_date',$data,$err);
    $this->select_types('event_group_type',$data,$err);

    $this->print_area('event_group_description',$data,$err);
   // $this->print_input('event_group_url',$data,$err,30,100);
   // $this->print_input('event_image',$data,$err,30,100);
    $this->print_file('event_group_image',$data,$err);
    $this->form_foot(2,$_SERVER['PHP_SELF']);
  }

  function draw (){
    if($_GET['action']=='add'){
      $eg = new Eventgroup(true);
      $this->form((array)$eg,null,con('event_group_add_title'));
    }elseif($_GET['action']=='edit'){
      $row = Eventgroup::load($_GET['event_group_id']);
      $this->form((array)$row, null, con('event_group_update_title'));

    }elseif($_POST['action']=='save'){
      if (!$eg = Eventgroup::load($_POST['event_group_id'])) {
         $eg = new Eventgroup(true);
      }
      if ($eg->fillPost() && $eg->saveEx()) {
        $this->table();
      } else {
        $this->form($_POST, null, con((isset($_POST['$event_group_id']))?'event_group_update_title':'event_group_add_title'));
      }

    }elseif($_GET['action']=='remove' and $_GET['event_group_id']>0){
      $eg = Eventgroup::load($_GET['event_group_id']);
      $eg->delete($_GET['event_group_id']);
      $this->table();

    }elseif($_GET['action']=='publish'){
      Eventgroup::setState($_GET['event_group_id'], true);
      $this->table();

    }elseif($_GET['action']=='unpublish'){
      Eventgroup::setState($_GET['event_group_id'], false);
      $this->table();

    }else {
      $this->table();
    }
  }

  function select_types ($name,&$data,&$err){
    global $_SHOP;
    $types= & $_SHOP->event_group_type_enum;
  //print_r($types);
    $sel[$data["$name"]]=" selected ";
    echo "
          <tr>
             <td class='admin_name'  width='40%'>".con($name)."</td>
             <td class='admin_value'> <select name='$name'>";
    foreach($types as $k=>$v){
       echo "<option value='".$v."' ".$sel[$v].">".con($v)."</option>\n";
    }
    echo "</select>". printMsg($name, $err). "</td></tr>\n";
  }

  function print_type ($name, &$data){
    echo "<tr><td class='admin_name' width='40%'>".con($name)."</td>
    <td class='admin_value'>
    ".con($data[$name])."
    </td></tr>\n";
  }
}
?>