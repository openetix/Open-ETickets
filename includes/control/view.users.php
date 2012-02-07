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
//require_once("classes/AUIComponent.php");
require_once("admin/class.adminview.php");
class UserView extends AdminView{

  function UserView ($id){
    $this->user_id=$id;
  }

  function print_user ($user){
    $user["user_country_name"]=$this->getCountry($user["user_country"]);
    $status=$this->print_status($user["user_status"]);
    $user["user_status"]=$status;
    echo "<table class='admin_form' width='100%' cellspacing='1' cellpadding='2' border='0'>\n";
    echo "<tr><td class='admin_list_title' colspan='3'>{$user["user_lastname"]}
    {$user["user_firstname"]}</td></tr>";
    echo "<tr><td class='admin_value' colspan='3' >{$user["user_address"]}";
    if($user["user_address1"]){
      echo " {$user["user_address1"]} </td></tr>";
    }
    echo "<tr><td class='aadmin_value' colspan='3'> {$user["user_zip"]} ";
    echo " {$user["user_city"]}</td></tr>";
    echo " <tr><td class='admin_value' colspan='3'>{$user["user_country_name"]}</td></tr>";
    echo "<tr><td class='admin_value'><b>".con('user_phone')."</b> {$user["user_phone"]}</td>";
    echo "<td class='admin_value'><b>".con('user_fax')."</b> {$user["user_fax"]}</td>";
    echo "<td class='admin_value'><b>".con('user_email')."</b> {$user["user_email"]}</td></tr>";
    echo "<td class='admin_value' colspan='3'><b>".con('user_status')."</b> {$user["user_status"]}</td></tr>";

    /*$this->print_field('user_lastname',$user );
    $this->print_field('user_firstname',$user );
    $this->print_field('user_address',$user );
    $this->print_field('user_address1',$user );
    $this->print_field('user_zip',$user );
    $this->print_field('user_city',$user );
    $this->print_field('user_country_name',$user );
    $this->print_field('user_phone',$user );
    $this->print_field('user_fax',$user );
    $this->print_field('user_email',$user );
    $this->print_field('user_status',$user );*/

    echo "</table>\n";
   }
  function draw (){
   global $_SHOP;
   $user= User::loadArr($this->user_id);
   $this->print_user($user);
   $query="select * from `Order` where order_user_id ="._esc($this->user_id);
   if(!$res=ShopDB::query($query)){
     user_error(shopDB::error());
     return;
   }
   echo  "<br><table class='admin_list' cellspacing='0' cellpadding='3' width='100%'>
   <tr><td class='admin_list_title' colspan='7'>".con('orders')."</td></tr>";
   while($order=shopDB::fetch_assoc($res)){
     echo "<tr><td class='order_item'>".$order["order_id"]."</td>
               <td class='order_item' colspan='6'>".con('tickets_nr')." ".$order["order_tickets_nr"].
	       " - ".valuta($order["order_total_price"])." - ".con('date')."  ".$order["order_date"].
	       " - ".$order["order_shipment_mode"]." - ".
	       $this->print_order_status($order["order_status"])."
	       <a href='view_order.php?action=details&order_id=".$order["order_id"]."'>
         <img src='".$_SHOP->images_url."view.png' border='0'/></a></td><tr>";
     $query="select * from Seat LEFT JOIN Discount ON seat_discount_id=discount_id,Event,Category where seat_order_id="._esc($order["order_id"])."
               AND seat_event_id=event_id AND seat_category_id= category_id";
     if(!$res1=ShopDB::query($query)){
         user_error(shopDB::error());
         return;
      }
      while($ticket=shopDB::fetch_assoc($res1)){
        if((!$ticket["category_numbering"]) or $ticket["category_numbering"]=='both'){
  	  $place=$ticket["seat_row_nr"]."-".$ticket["seat_nr"];
	}else if($ticket["category_numbering"]=='rows'){
  	  $place=place_row." ".$ticket["seat_row_nr"];
	}else if($ticket["category_numbering"]=='seat'){
  	  $place=place_seat." ".$ticket["seat_nr"];
	}else{
	  $place='---';
	}

       echo "<tr><td class='ticket_item_1'>&nbsp;</td>
	       <td class='ticket_item'>".$ticket["seat_id"]."</td>
	       <td class='ticket_item'>".$ticket["event_name"]."</td>
	       <td class='ticket_item'>".$ticket["category_name"]."</td>
	       <td class='ticket_item'>$place</td>
	       <td class='ticket_item'>".$ticket["discount_name"]."</td>
	       <td class='ticket_item' align='right'>".valuta($ticket["seat_price"])."</td>
	       <td class='ticket_item' align='right'>".$this->print_place_status($ticket["seat_status"])."</td>
	       <tr>";
      }
   }
   echo "</table>";

  }
function print_status ($user_status){
  if($user_status=='1'){
    return sale_point;
  }else if ($user_status=='2'){
    return member;
  }else if($user_status=='3'){
    return guest;
  }
}

function print_order_status ($order_status){
  if($order_status=='ord'){
    return "<font color='blue'>".con('order_status_ordered')."</font>";
  }else if ($order_status=='send'){
    return "<font color='red'>".con('order_status_sended')."</font>";
  }else if($order_status=='paid'){
    return "<font color='green'>".con('order_status_paid')."</font>";
  }else if($order_status=='cancel'){
    return "<font color='#787878'>".con('order_status_cancelled')."</font>";
 }
}

  function print_field ($name, &$data){
    echo "<tr><td class='admin_name' width='20%'>".con($name)."</td>
    <td class='admin_value'>
    {$data[$name]}
    </td></tr>\n";
  }


  function print_input ($name, &$data, &$err){
    echo "<tr><td class='admin_name'  width='20%'>".con($name)."</td>
    <td class='admin_value'><input type='text' name='$name' value='".htmlentities($data[$name],ENT_QUOTES)."' size='$size' maxlength='$max'>
    <span class='admin_err'>{$err[$name]}</span>
    </td></tr>\n";
  }
   function con($name){
    if(defined($name)){
      return constant($name);
    }else{
      return $name;
    }
  }
function print_place_status ($place_status){
  switch($place_status){
    case 'free':  return "<font color='green'>".con('free')."</font>";
    case 'res':  return "<font color='orange'>".con('reserved')."</font>";
    case 'com': return "<font color='red'>".con('com')."</font>";
    case 'check':return "<font color='blue'>".con('checked')."</font>";
   }
}

}
?>