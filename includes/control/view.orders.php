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

require_once("classes/AUIComponent.php");
require_once("classes/smarty.gui.php");
require_once("admin/class.adminview.php");

class OrderView extends AdminView{

  var $page_length=15;

  function order_details($order_id){
    global $_SHOP;
    $query="select * from `Order`,User where order_id="._esc($order_id)." and order_user_id=user_id";

    if(!$order=ShopDB::query_one_row($query)){
      echo "<div class='error'>".con('order_not_found')." $order_id</div>";
      return;
    }

    $status=$this->print_order_status($order);
    $order["order_status"]=$status;

    echo "<table class='admin_form' width='100%' cellspacing='0' cellpadding='2'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>".con('order_nr')."  ".$order_id."</td></tr>";

    $this->print_field('order_tickets_nr',$order);
    $this->print_field('order_total_price',$order);
    $this->print_field('order_date',$order);

    $order['order_shipment_status']=con($order['order_shipment_status']);
    $order['order_payment_status']=con($order['order_payment_status']);

    $this->print_field('order_shipment_status',$order);
    $this->print_field('order_payment_status',$order);
    $this->print_field('order_fee',$order);
    $this->print_field('order_status',$order);
    echo "</table><br>\n";

    if(!$seats=Order::loadTickets($order_id)){
       return;
    }
    echo "<table class='admin_form' width='100%' cellspacing='0' cellpadding='2'>\n";
    echo "<tr><td class='admin_list_title' colspan='7'>".con('tickets')."</td></tr>";
    $alt=0;
    foreach($seats as $ticket){
      if((!$ticket["category_numbering"]) or $ticket["category_numbering"]=='both'){
        $place=$ticket["seat_row_nr"]."-".$ticket["seat_nr"];
      }else if($ticket["category_numbering"]=='rows'){
        $place=con('place_row')." ".$ticket["seat_row_nr"];
      }else if($ticket["category_numbering"]=='seat'){
        $place=con('place_seat')." ".$ticket["seat_nr"];
      }else{
        $place='---';
      }


     echo "<tr class='admin_list_row_$alt'>
      	   <td class='admin_list_item'>".$ticket["seat_id"]."</td>
      	   <td class='admin_list_item'>".$ticket["event_name"]."</td>
      	   <td class='admin_list_item'>".$ticket["category_name"]."</td>
      	   <td class='admin_list_item'>".$ticket["pmz_name"]."</td>

      	   <td class='admin_list_item'>$place</td>
  	   <td class='admin_list_item'>".$ticket["discount_name"]."</td>

      	   <td class='admin_list_item' align='right'>".$ticket["seat_price"]."</td>
      	   <td class='admin_list_item' align='right'>".
  	   $this->print_place_status($ticket["seat_status"])."</td>

  	   <tr>\n";
      $alt=($alt+1)%2;

    }
    echo "</table><br>\n";
    $order["user_country_name"]=gui_smarty::getCountry($order["user_country"]);
    $status=$this->print_status($order["user_status"]);
    $order["user_status"]=$status;
    echo "<table class='admin_form' width='100%' cellspacing='0' cellpadding='2' border='0'>\n";
    echo "<tr><td class='admin_list_title' colspan='2'>".con('user_id')." ".$order["user_id"]."</td></tr>";

    $this->print_field('user_lastname',$order);
    $this->print_field('user_firstname',$order);
    $this->print_field('user_address',$order);
    $this->print_field('user_address1',$order);
    $this->print_field('user_zip',$order);
    $this->print_field('user_city',$order);
    $this->print_field('user_country_name',$order);
    $this->print_field('user_phone',$order);
    $this->print_field('user_fax',$order);
    $this->print_field('user_email',$order);
    $this->print_field('user_status',$order);

    echo "</table>\n";


  }

  function link ($action,$order_id,$img,$confirm=FALSE,$con_msg='',$param=null){
    if($confirm){
      $param['action1']=$action;
      $param['order_id1']=$order_id;

      foreach($param as $key=>$val){
        $par.=$psep."$key=$val";
        $psep="&";
      }

      return "<a href='javascript:if(confirm(\"".con($con_msg)."\")){location.href=\"".$_SERVER['PHP_SELF']."?$par\";}'>".
           "<img border='0' src='images/$img'></a>";
    }
    return "<a href='".$_SERVER['PHP_SELF']."?action=$action&order_id=$order_id'>".
           "<img border='0' src='images/$img'></a>";
  }

  function get_limit ($page,$count){
    if(!$page){ $page=1; }
    $limit["start"]=($page-1)*$this->page_length;
    $limit["end"]=$this->page_length;
    return $limit;

  }

  function get_nav ($page,$count,$condition){
    if(!isset($page)){ $page=1; }

    echo "<table border='0' width='500'><tr><td align='center'>";
    echo "<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=1'>".con('nav_first')."</a>";

    if($page>1){
      $prev=$page-1;
      echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$prev'>".con('nav_prev')."</a>";
    }
    $num_pages=ceil($count/$this->page_length);
    echo "&nbsp;[";
    for ($i=floor(($page-1)/10)*10+1;$i<=min(ceil($page/10)*10,$num_pages);$i++){
      if($i==$page){
        echo "&nbsp;<b>$i</b>";
      }else{
        echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$i'>$i</a>";
      }
    }
    echo "&nbsp;]&nbsp;";
    $next=$page+1;
      if($next*$this->page_length<$count){
        echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$next'>".con('nav_next')."</a>";
      }
    echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?$condition&page=$num_pages'>".con('nav_last')."</a>";

    echo "</td></tr></table>";
  }




  function draw (){
   if($_GET['action']=='details'){
      $this->order_details($_GET["order_id"]);
    }
  }

  function print_status ($user_status){
    if($user_status=='1'){
      return con('sale_point');
    }else if ($user_status=='2'){
      return con('member');
    }else if($user_status=='3'){
      return con('guest');
    }
  }

  function print_order_status ($order){
    switch($order['order_status']){
      case 'ord':    return "<font color='blue'>".con('order_status_ordered')."</font>";
      case 'send':   return "<font color='red'>".con('order_status_sended')."</font>";
      case 'paid':  return "<font color='green'>".con('order_status_paid')."</font>";
      case 'cancel': return "<font color='#787878'>".con('order_status_cancelled')."</font>";
      case 'reissue':return "<font color='#787878'>".con('order_status_reissued')."</font> (
      <a href='{$_SERVER['PHP_SELF']}?action=details&order_id={$order['order_reissued_id']}'>
      {$order['order_reissued_id']}</a> )";
    }
  }

  function print_place_status ($place_status){
    switch($place_status){
      case 'free': return "<font color='green'>".con('free')."</font>";
      case 'res':  return "<font color='orange'>".con('reserved')."</font>";
      case 'com':  return "<font color='red'>".con('com')."</font>";
      case 'check':return "<font color='blue'>".con('checked')."</font>";
    }
  }
}
?>