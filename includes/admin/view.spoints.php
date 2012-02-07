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

class SpointsView extends AdminView{

  function table (){
    global $_SHOP;
    $query="SELECT * FROM User where user_status="._esc(1);
    if(!$res=ShopDB::query($query)){
      user_error(shopDB::error());
      return;
    }

    $alt=0;
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2' border=0>\n";
    echo "<tr><td class='admin_list_title' colspan='1' align='left'>".con('pos_user_title')."</td>";
    echo "<td colspan='2' align='right'>".$this->show_button("{$_SERVER['PHP_SELF']}?action=add","add",3)."</td>";
    echo "</tr>\n";


    while($row=shopDB::fetch_assoc($res)){
      echo "<tr class='admin_list_row_$alt'>";
      echo "<td class='admin_list_item'  >{$row['user_lastname']}</td>\n";
      echo "<td class='admin_list_item'  >{$row['user_city']}</td>\n";
      echo "<td class='admin_list_item'width='65' align='right' nowrap><nowrap>";
      echo $this->show_button("{$_SERVER['PHP_SELF']}?action=edit&user_id={$row['user_id']}","edit",2);
      echo $this->show_button("javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&user_id={$row['user_id']}\";}","remove",2,array('tooltiptext'=>"Delete {$row['user_lastname']}?"));
      echo "</nowrap></td>\n";
      echo "</tr>\n";
      $alt=($alt+1)%2;
    }
    echo "</table>\n";
  }

  function tableAdmins ($user_id){
    global $_SHOP;
    $query="SELECT * FROM Admin where admin_user_id ={$user_id} and admin_status like 'pos%'";
    if(!$res=ShopDB::query($query)){
      user_error(shopDB::error());
      return;
    }

    $alt=0;
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2' border=0>\n";
    echo "<tr><td class='admin_list_title' colspan='3' align='left'>".con('admin_posuser_title')."</td>";
    echo "</tr>\n";


    while($row=shopDB::fetch_assoc($res)){
      echo "<tr class='admin_list_row_$alt'>";
      echo "<td class='admin_list_item' width='550' >{$row['admin_login']}</td>\n";
      echo "<td class='admin_list_item' width='550' >".con($row['admin_status'])."</td>\n";
      echo "<td class='admin_list_item' >".con($row['admin_inuse'])."</td>\n";
      echo "</tr>\n";
      $alt=($alt+1)%2;
    }
    echo "</table>\n";
  }

  function form ($data, $err, $title, $add='add'){
    global $_SHOP;
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
    echo "<input type='hidden' name='user_id' value='{$data['user_id']}'/>\n";
    echo "<input type='hidden' name='action' value='save'/>\n";
    $this->form_head($title);
		$this->print_field_o( 'user_id', $data );
    if (!$data["kasse_name"]) $data["kasse_name"] = $data["user_lastname"];
		$this->print_input( 'kasse_name', $data, $err, 30, 50 );

		$this->print_input( 'user_address', $data, $err, 30, 75 );
		$this->print_input( 'user_address1', $data, $err, 30, 75 );
		$this->print_input( 'user_zip', $data, $err, 8, 20 );
		$this->print_input( 'user_city', $data, $err, 30, 50 );
		//$this->print_input( 'user_state', $data, $err, 30, 50 );
		$this->print_countrylist( 'user_country', $data['user_country'], $err );

		$this->print_input( 'user_phone', $data, $err, 30, 50 );
		$this->print_input( 'user_fax', $data, $err, 30, 50 );
		$this->print_input( 'user_email', $data, $err, 30, 50 );

		$this->print_select( 'user_prefs_print', $data, $err, array('pdt', 'pdf') );
    $this->print_checkbox( 'user_prefs_strict', $data, $err );
    $this->print_checkbox( 'user_prefs_store_now', $data, $err );
   // var_dump($_SESSION);
    $this->form_foot(2,$_SERVER['PHP_SELF']);
    if ($data['user_id']) {
      $this->tableAdmins ($data['user_id']);
    }
  }

  function draw () {
    global $_SHOP;
    if ($_GET['action'] == 'add') {
       $adm = new User(true);
       $row = (array)$adm;
       $this->form($row, $err, con('pos_add_title'));
    } elseif ($_GET['action'] == 'edit' && $_GET['user_id']){
      if ($adm = User::load($_REQUEST['user_id'], 1)) {
         $row = (array)$adm;
         $this->form($row, null, con('pos_update_title'));
      } else $this->table();
    } elseif ($_POST['action'] == 'save') {
      if (!$adm = User::load($_POST['user_id'], 1)) {
         $adm = new User(true);
         $adm->user_status = 1;
      }
      if ($adm->fillPost() && $adm->saveEx()) {
        if ($adm->user_prefs_store_now) {
          $myDomain = ereg_replace('^[^\.]*\.([^\.]*)\.(.*)$', '\1.\2', $_SERVER['HTTP_HOST']);
          $setDomain = ($_SERVER['HTTP_HOST']) != "localhost" ? ".$myDomain" : false;
          $done = setcookie ('test'.$adm->id, $adm->user_prefs_strict.$_SHOP->secure_id.$adm->id.$adm->user_lastname , time()+600, '/', "$setDomain", 0 );
          $done = setcookie ('use'.$adm->id, hash('ripemd160',$adm->user_prefs_strict.$_SHOP->secure_id.$adm->id.$adm->user_lastname) , time()+3600*24*(60), '/', "$setDomain", 0 );
          addNotice('POS registration Cookie placed: ',($done?'Yes':'no'));
        }
        $this->table();
      } else {
        $this->form($_POST, null, con(((isset($_POST['user_id']))?'pos_update_title':'pos_add_title')));
      }

    } elseif($_GET['action']=='remove' and $_GET['user_id']){
      if($adm = User::load($_GET['user_id'], 1))
        $adm->delete();
      $this->table();
    } else {
      $this->table();
    }
  }

}
?>