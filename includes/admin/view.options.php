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

class OptionsView extends AdminView{

  function option_form (&$data, $err=null){
  	global $_SHOP;
  	$yesno = array('No'=>'confirm_no', 'Yes'=>'confirm_yes');

  	echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='update'>\n";
    $this->form_head(con('option_update_title'));
  //	echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  	//echo "<tr><td class='admin_list_title' colspan='2'>".con('option_update_title')."</td></tr>";
  //  $data['shopconfig_user_activate'] = (int)$data['shopconfig_user_activate'];
  //	$this->print_field('shopconfig_lastrun',$data, $err,10,10);

  	$this->print_input('shopconfig_lastrun_int',$data, $err,5,10);

  //	$this->print_input('shopconfig_restime_remind',$data, $err,25,100);
  	//this will tell the auto scripts to check POS orders or not.

    $this->print_select_assoc('shopconfig_check_pos',$data,$err,$yesno);
    $this->print_select_assoc('shopconfig_delunpaid',$data,$err,$yesno);
    $this->print_select_assoc('shopconfig_delunpaid_pos',$data,$err,$yesno);
    $this->print_input('res_delay' ,$data, $err, 5, 10);
    $this->print_input('cart_delay',$data, $err, 5, 10);

  // 	echo "</table>\n<br>";
  //	echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>\n";
  	echo "<tr><td class='admin_list_title' colspan='2'>".con('option_others_title')."</td></tr>";
      $this->print_select_assoc('shopconfig_user_activate',$data,$err,
       array('-1'=>con('act_restrict_cart'),
             '0'=>con('act_restrict_all'),
             '1'=>con('act_restrict_later'),
             '2'=>con('act_restrict_w_guest'),
             '3'=>con('act_restrict_quest_only')));

  	$this->print_input('shopconfig_posttocollect',$data, $err,5,10);

  	$this->print_input('shopconfig_restime',$data, $err,5,10);
   	$this->print_input('shopconfig_maxres',$data, $err,5,10);
   	$this->print_input('shopconfig_maxorder',$data, $err,5,10);

    $this->form_foot();

  }

	function draw () {
	global $_SHOP;
		if($_POST['action']=='update'){
			if(!$this->options_check($_POST)){
				$this->option_form($_POST);
				return;
			}else{
				$query="UPDATE `ShopConfig` SET
	      		shopconfig_lastrun_int="._esc($_POST['shopconfig_lastrun_int']).",
	      		shopconfig_restime="._esc($_POST['shopconfig_restime']).",
	      		shopconfig_check_pos="._esc($_POST['shopconfig_check_pos']).",
	      		shopconfig_delunpaid="._esc($_POST['shopconfig_delunpaid']).",
	      		shopconfig_delunpaid_pos="._esc($_POST['shopconfig_delunpaid_pos']).",
	      		shopconfig_posttocollect="._esc($_POST['shopconfig_posttocollect']).",
	      		shopconfig_user_activate="._esc((int)$_POST['shopconfig_user_activate']).",
	      		res_delay="._esc((int)$_POST['res_delay']).",
	      		cart_delay="._esc((int)$_POST['cart_delay']).",
	      		shopconfig_maxres="._esc($_POST['shopconfig_maxres']).",
	      		shopconfig_maxorder="._esc($_POST['shopconfig_maxorder'])."
	      		limit 1 ";

				if(!ShopDB::query($query)){
          addWarning('update_error');
				}
        addNotice('Options_saved');
			}

		}
		$query="SELECT * FROM `ShopConfig` limit 1";
		if($row=ShopDB::query_one_row($query)){
		  unset($row['shopconfig_ftpassword']);
  		$this->option_form($row);
		}
		return;
  }

  function options_check (&$data){
  	global $_SHOP;

  	foreach(array('shopconfig_lastrun_int',    'shopconfig_maxres', 'shopconfig_restime', //'shopconfig_restime_remind',
                  'shopconfig_posttocollect') as $check) {
      if((!isset($data[$check]) || $data[$check]=='') ){
         addError($check,'mandatory');
      }elseif(!is_numeric($data[$check])){
    		addError($check,'not_number');
    	}elseif($data[$check]<'0'){
    		addError($check,'too_low') ;
      }
   	}
  	if ($data['res_delay'] < $data['cart_delay']) {
    		addError('res_delay', 'res_delay_less_cart');
     }
  	return !hasErrors();

  }
}
?>