<?PHP
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

class AdminUsersView extends AdminView{

  function table (){
    global $_SHOP;
    $query="SELECT * FROM Admin";
    if(!$res=ShopDB::query($query)){
      user_error(shopDB::error());
      return;
    }

    $alt=0;
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2' border=0>\n";
    echo "<tr><td class='admin_list_title' colspan='2' align='left'>".con('admin_user_title')."</td>";
    echo "<td colspan='1' align='right'>".$this->show_button("{$_SERVER['PHP_SELF']}?action=add","add",3)."</td>";
    echo "</tr>\n";


    while($row=shopDB::fetch_assoc($res)){
      echo "<tr class='admin_list_row_$alt'>";
      echo "<td class='admin_list_item' width='550' >{$row['admin_login']}</td>\n";
      echo "<td class='admin_list_item' width='550' >".con('admin_status_'.$row['admin_status'])."</td>\n";
      echo "<td class='admin_list_item'width='65' align='right' nowrap><nowrap>";
      echo $this->show_button("{$_SERVER['PHP_SELF']}?action=edit&admin_id={$row['admin_id']}","edit",2);
      echo $this->show_button("javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&admin_id={$row['admin_id']}\";}","remove",2,array('tooltiptext'=>"Delete {$row['admin_login']}?"));
      echo "</nowrap></td>\n";
      echo "</tr>\n";
      $alt=($alt+1)%2;
    }
    echo "</table>\n";
  }

  function form ($data, $err, $title, $add='add'){
    global $_SHOP;
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
    echo "<input type='hidden' name='admin_id' value='{$data['admin_id']}'/>\n";
    echo "<input type='hidden' name='action' value='save'/>\n";
    $this->form_head($title);
		$this->print_field_o( 'admin_id', $data );
    $this->print_input('admin_login',$data,$err,30,100);
    $this->print_select('admin_status',$data, $err, $_SHOP->admin->allowedroles() );
   // var_dump($_SESSION);
    $this->print_posoffices('admin_user_id',$data);
    $this->print_events('control_event_ids', $data);
    $this->print_input('admin_email',$data,$err,30,100);
    $script = "
        $('#admin_status-select').change(function(){
           var data = $(this).val();
           if (data.substr(0, 3) === 'pos') {
              $('#admin_user_id-tr').show();
           } else {
              $('#admin_user_id-tr').hide();
           }
           if (data === 'control') {
              $('#control_event_ids-tr').show();
           } else {
              $('#control_event_ids-tr').hide();
           }
        ";
    $script .=  " });";
    if ($data['admin_status'] !== 'control') {
      $script .= "
        $('#control_event_ids-tr').hide();
          ";
    }
    $this->addJQuery($script);

 //   $this->print_select_assoc('admin_ismaster',$data,$err,array('No'=>'no','Yes'=>'yes'));

    $this->print_password ('password1', $data, $err);
    $this->print_password ('password2', $data, $err);
    if ($_SESSION['_SHOP_AUTH_USER_NAME']<>$data['admin_login']){
      $this->print_select_assoc('admin_inuse',$data,$err,array('No'=>'no','Yes'=>'yes'));
    } else {
      $this->print_field_o('admin_inuse',$data,$err,array('No'=>'no','Yes'=>'yes'));
    }
    $this->form_foot(2,$_SERVER['PHP_SELF']);
  }

  function draw () {
    global $_SHOP;
    $this->admintype = $admintype;
    if ($_GET['action'] == 'add') {
       $adm = new Admins(true, $admintype);
       $row = (array)$adm;
       $this->form($row, $err, con('admin_add_title'));
    } elseif ($_GET['action'] == 'edit' && $_GET['admin_id']){
      if ($adm = Admins::load($_REQUEST['admin_id'])) {
         $row = (array)$adm;
         $this->form($row, null, con('admin_update_title'));
      } else $this->table();
    } elseif ($_POST['action'] == 'save') {
      if (!$adm = Admins::load($_POST['admin_id'])) {
         $adm = new Admins(true);
      }
      if ($adm->fillPost() && $adm->saveEx()) {
        $this->table();
      } else {
        $this->form($_POST, null, con(((isset($_POST['admin_id']))?'admin_update_title':'admin_add_title')));
      }

    } elseif($_GET['action']=='remove' and $_GET['admin_id']){
      if($adm = Admins::load($_GET['admin_id']))
        $adm->delete();
      $this->table();
    } else {
        $this->table();
    }
  }

  function print_posoffices($name, &$data) {
    global $_SHOP;
    $query = "SELECT user_id, user_lastname, user_city FROM `User`
              where user_status = 1
              order by user_city, user_lastname";
    if (!$res = ShopDB::query($query)) {
        return;
    }

    if ($data[$name]) {
      $sel[$data[$name]] = 'selected';
    }
    echo "<tr id='{$name}-tr'><td class='admin_name' >" . con($name) . "</td>
          <td class='admin_value'><select  name='$name'>\n";

     while ($row = shopDB::fetch_assoc($res)) {
       echo "<option value='{$row['user_id']}' {$sel[$row['user_id']]}>{$row['user_city']} - {$row['user_lastname']}</option>\n";
    }

    echo "</select>". printMsg($name, $err). "</td></tr>\n";
    if (strpos($data['admin_status'], 'pos') !==0) {
      $script = "$('#admin_user_id-tr').hide();";
      $this->addJQuery($script);

    }
  }
  function print_events($name, &$data){
    if($data["$name"] and $data["$name"]!=""){
      $event=explode(",",$data["$name"]);
    }
    $query="select event_id,event_name,event_date,event_time
            from Event
            where event_pm_id is not null
		  		and event_rep LIKE '%sub%'
		  		AND event_status <> 'unpub'
		  		AND event_date >= date(now())
            order by event_date,event_time";
    if(!$res=ShopDB::query($query)){
      user_error(shopDB::error());
      return;
    }

    echo "<tr id='{$name}-tr'><td  class='admin_name' width='40%' valign='top'>".con($name)."</td>
          <td class='admin_value'>";
    echo "<select multiple size='10' name='control_event_ids[]' style='width:100%'>";
    while($row=shopDB::fetch_assoc($res)){
      $sel=(in_array($row["event_id"], $event))?"selected":"";
      $date=formatAdminDate($row["event_date"]);
      $time=formatTime($row["event_time"]);
      echo "<option value='".$row["event_id"]."' $sel>$date $time ". $row["event_name"]."</option>";
    }
    echo "</select></td></tr>\n";
  }
}
?>