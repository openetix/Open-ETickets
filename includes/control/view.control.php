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
require_once("classes/class.component.php");


class ControlView extends Component{

  function checkBarcode(){
    echo "
      <center>
        <div class='control_form'><br>{$_SESSION['event_name']}<br><br>
  	      <form method='POST' action='index.php' name='f' onSubmit='this.submit.disabled=true;return true;'>
            <input type=text name='codebar' value='' size='40'>&nbsp;
            <input type='submit' name='submit' value='".con('check')."' >
            <input type='reset' name='reset' value='".con('res')."' onclick='init();'>
          </form><br>
        </div>
      </center><br><br>";


    if(isset($_POST['codebar'])){

      $bar = plugin::call('*OrderDecodeBarcode', ($_POST['codebar']));
      list($seat_id,$ticket_code)= is($bar, sscanf($_POST['codebar'],"%08d%s"));


      $query="select seat_event_id, discount_name, seat_status, seat_nr, seat_row_nr,
                     category_numbering, category_name, category_color,
                     pmz_name, order_payment_status, order_status,order_id
              from Seat LEFT JOIN PlaceMapZone ON seat_zone_id=pmz_id
                        LEFT JOIN Category on seat_category_id=category_id
                        LEFT JOIN `Order` on seat_order_id= order_id
                        LEFT JOIN `Discount` on discount_id= seat_discount_id
              where seat_id="._esc($seat_id)."
  	          AND   seat_code="._esc($ticket_code);

      if(!$ticket=ShopDB::query_one_row($query)){
        return $this->showerror('ticket_not_found',$ticket);
      } elseif($ticket['seat_event_id'] !==$_SESSION['event']){
        return $this->showerror('not_valid_event',$ticket);
      } elseif($ticket['seat_status']=='check'){
        return $this->showerror('ticket_already_checked',$ticket);
      } elseif($ticket['seat_status']=='free'){
        return $this->showerror('place_not_commanded',$ticket);
      } elseif($ticket['seat_status']=='res'){
        return $this->showerror('place_only_reserved',$ticket);
      } elseif(!in_array($ticket['order_status'],array('ord','pros'))){
        return $this->showerror('order_is_not_valid',$ticket);
      } elseif(!in_array($ticket['order_payment_status'],array('paid'))){
        return $this->showerror('order_is_not_paid',$ticket);
      }
      if ($ticket['discount_name']) {
            $discount_txt = " ( ".$ticket['discount_name']." )";
      }

      if($ticket['category_numbering']=='both'){
        $place_nr=con('place_nr')." ".$ticket['seat_row_nr']."-".$ticket['seat_nr'];
      }else if($ticket['category_numbering']=='rows'){
        $place_nr=con('rang_nr')." ".$ticket['seat_row_nr'];
      }else if($ticket['category_numbering']=='seat'){
        $place_nr=con('place_nr')." ".$ticket['seat_nr'];
      }else if($ticket['category_numbering']=='none'){
        $place_nr=con('place_without_nr');
      }

      echo "<center>
          <table class='check' width='700' cellpadding='5'>
            <tr>
              <td align='center' valign='middle' width='150'><img src='../images/bigsmile.gif'></td>
	            <td align='center' class='success'>
                <table border='0' width='350'>
                  <tr><td align='center' class='success'>".con('check_success')."</td></tr>
                  <tr><td class='value' align='center'>{$ticket['category_name']} {$ticket['pmz_name']} {$discount_txt}</td></tr>
                  <tr><td class='value' align='center'> $place_nr </td></tr>
                  <tr><td colspan='2'> &nbsp;</td></tr>";

      if(isset($ticket['category_color'])){
        echo "
                <tr><td  bgcolor='{$ticket['category_color']}' style='border: #999999 1px dashed;'> &nbsp </td></tr>";
      }

      echo  "
                  <tr><td > &nbsp; </td></tr>
                </table>
              </td>
            </tr>
          </table></center><br><br>";
      OrderStatus::statusChange($ticket['order_id'],'TicketTaker',con('seat_checked').$seat_id,'tickettaker::checkticket');
      $query="UPDATE Seat set seat_status='check' where seat_id="._esc($seat_id);

      if(!ShopDB::query($query)){
        echo "<div class='err'>".con('place_status_not_updated')."</div>";
        return false;
      }

    }
    return true;
  }

  function draw (){
    GLOBAL $_SHOP;
    if($_GET['event_id']){
       $_SESSION['event']=$_GET['event_id'];
       $_SESSION['event_name']=$_GET['event_name'];
  	}

    if($_GET['action']=="search_form"){
      $this->search_form($_GET);
      return 1;
    }

    if($_GET['action']=='search'){
      if(!$query_type=$this->search_check($_GET)){
        $this->search_form($_GET);
        return 1;
      }else{
         $this->result_list($_GET,$query_type);
         return 1;
      }
    }else if($_GET['action']=='search_place'){
      if(!$query_type=$this->search_place_check($_GET)){
        $this->search_form($_GET);
        return 1;
      }else{
        $this->result_place($_GET,$query_type);
        return 1;
      }
    }
    if(!isset($_SESSION['event'])){
      $this->event_list();
      return 1;
    }

    if($_GET['action']=="change_event"){
      unset($_SESSION['event']);
      unset($_SESSIOn['event_name']);
      $this->event_list();
      return 1;
    }
    if(!isset($_POST['codebar'])){
      $this->checkBarcode();
    } elseif ($this->checkBarcode()) {
      echo "<embed src='{$_SHOP->images_url}beep_ok.mp3' autostart='true' loop='false' width='0' height='0'></embed>";
    } else {
      echo "<embed src='{$_SHOP->images_url}beep_error.mp3' autostart='true' loop='false' width='0' height='0'></embed>";
    }

  }

  function showerror($message,$ticket){
    GLOBAL $_SHOP;

    OrderStatus::statusChange($ticket['order_id'],'TicketTaker',$Message,'tickettaker::error');
    echo" <center>
              <div width='150' class='err' align='left' valing='middle' style='padding-left:20px; vertical-align:middle;'>
                 <img src='{$_SHOP->images_url}attention.png' height='100' style='float:left;margin-right:20px;'><span class='error' style='line-height:normal; vertical-align:middle;'>".con($message)."</span>
               </div> </center><br>";
  }

  function event_list(){
  global $_SHOP;
    if($_SHOP->event_ids !=''){
      $query="select * from Event left join Ort on event_ort_id=ort_id
              where event_status != 'unpub'
              AND   event_rep LIKE '%sub%'
     	        and FIELD(event_id,{$_SHOP->event_ids})>0
              order by event_date, event_time ";
      if(!$events=ShopDB::query($query)){
          user_error(shopDB::error());
          return 0;
      }

      echo "
        <table width='100%' class='admin_list'  cellpadding='2'>
          <tr><td colspan='6' class='admin_list_title' align='center'>".con('control_events_list')."</td></tr>
          <tr class='admin_list_header'>
            <td width='200' >".con('event')."</td>
            <td width='200' >".con('date')."</td>
            <td width='200' >".con('ort')."</td>
            <td width='50' align='right'>".con('free')."</td>
            <td width='50' align='right'>".con('com')."</td>
            <td width='50' align='right'>".con('checked')."</td>
          </tr>";
      $alt = 0;
      while($event=shopDB::fetch_assoc($events)){
        $query_ev="SELECT seat_status, COUNT(*) as count FROM Seat where
                   seat_event_id='{$event['event_id']}' GROUP BY seat_status";
         if(!$status=ShopDB::query($query_ev)){
           return 0;
         }

        $ev_stat=array();

        while($stat=shopDB::fetch_assoc($status)){
          $ev_stat[$stat["seat_status"]]=$stat["count"];
        }
        $edate=formatDate($event["event_date"]);
        $etime=formatTime($event["event_time"]);

        echo "
          <tr class='admin_list_row_$alt'>
      	    <td width='200' class='event_list_td' valign='top'>
               <a href='{$_SERVER['PHP_SELF']}?event_id={$event['event_id']}&event_name={$event['event_name']}'>{$event['event_name']}</a>
            </td>
            <td width='200' class='event_list_td' valign='top'>$edate - $etime</td>
            <td width='200' class='event_list_td' valign='top'>{$event['ort_name']} - {$event['ort_city']}</td>
            <td width='50' class='event_list_td' align='right' valign='top'>".$ev_stat["free"]."</td>
            <td width='50' class='event_list_td' align='right' valign='top'>".$ev_stat["com"]."</td>
            <td width='50' class='event_list_td' align='right' valign='top'>".$ev_stat["check"]."</td>
          </tr>";
          $alt = ($alt + 1) % 2;
      }
      echo "</table>";
    }else{
     echo "<br><br><br><br><br><br> <h1><center>",con("no_event_sets"),'</center></h1><br><br><br><br><br><br>';
    }
  }

  function search_form (&$data){
    global $_SHOP;
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>
            <table class='admin_form' width='100%' cellspacing='1' cellpadding='2'>
              <tr><td class='admin_list_title' colspan='2'>".con("search_title_user")."</td></tr>\n";
    $this->print_input('user_lastname',$data, $err,25,100);
    $this->print_input('user_firstname',$data, $err,25,100);
    $this->print_input('user_zip',$data, $err,25,100);
    $this->print_input('user_city',$data, $err,25,100);
    $this->print_input('user_phone',$data, $err,25,100);
    $this->print_input('user_email',$data, $err,25,100);
    echo "
             <tr>
               <td class='admin_name'>".con('user_status')."</td>
               <td class='admin_value'>
                  <select name='user_status'>
                    <option value='0'>------</option>
                    <option value='1'>".con('sale_point')."</option>
                    <option value='2'>".con('member')."</option>
                    <option value='3'>".con('guest')."</option>
                  </select>
               </td>
             </tr>
             <tr><td  class='admin_value' colspan='2'>
                <input type='hidden' name='action' value='search'/>\n
                <input type='submit' name='submit' value='".con('search')."'>
                <input type='reset' name='reset' value='".con('res')."'>
              </td></tr>
            </table>
          </form>
          <br>
          <form method='GET' action='{$_SERVER['PHP_SELF']}'>
            <table class='admin_form' width='100%' cellspacing='1' cellpadding='4'>
              <tr><td class='admin_list_title' colspan='2'>".con("search_title_place")."</td></tr>
              <tr>
                 <td class='admin_name'>".con('event_list')."</td>
                 <td class='admin_value'>
                   <select name='event_id'>
                     <option value='' selected>".con('choice_please')."</option>\n";

    if (!empty($_SHOP->event_ids)) {
      $query="select event_id,event_name,event_date,event_time from Event
              where event_status!='unpub' and event_rep LIKE '%sub%'
          	  and FIELD(event_id,{$_SHOP->event_ids})>0
          	  order by event_date, event_time";
      if(!$res=ShopDB::query($query)){
        user_error(shopDB::error());
        return;
      }
      while($event=shopDB::fetch_assoc($res)){
        $date=formatAdminDate($event["event_date"]);
        $time=formatTime($event["event_time"]);
        echo "<option value='{$event['event_id']}'>".$event["event_name"]." - $date - $time </option>\n";
      }
    }

    echo "
                   </select>
                 </td>
               </tr>";

    $this->print_input('seat_row_nr',$data, $err,4,4);
    $this->print_input('seat_nr',$data, $err,4,4);

    echo "
                <tr>
                  <td  class='admin_value' colspan='2'>
                    <input type='hidden' name='action' value='search_place'/>\n
                    <input type='submit' name='submit' value='".con('search')."'>
                    <input type='reset' name='reset' value='".con('res')."'>
                  </td>
                </tr>
              </table>
            </form>
            <br>
            <form method='GET' action='view_order.php'>
              <table class='admin_form' width='100%' cellspacing='1' cellpadding='4'>
                <tr><td class='admin_list_title' colspan='2'>".con("search_title_order")."</td></tr>";
    $this->print_input('order_id',$data, $err,11,11);
    echo "
                <tr>
                  <td  class='admin_value' colspan='2'>
                    <input type='hidden' name='action' value='details'/>\n
                    <input type='submit' name='submit' value='".con('search')."'>
                    <input type='reset' name='reset' value='".con('res')."'>
                  </td>
                </tr>
              </table>
            </form>\n";
  }

  function print_field ($name, &$data){
    echo "
       <tr>
         <td class='admin_name' width='20%'>".con($name)."</td>
         <td class='admin_value'>{$data[$name]}</td>
       </tr>\n";
    }


  function print_input ($name, &$data, &$err){
      echo "
         <tr>
           <td class='admin_name'  width='20%'>".con($name)."</td>
           <td class='admin_value'>
             <input type='text' name='$name' value='".htmlentities($data[$name],ENT_QUOTES)."' size='$size' maxlength='$max'>
             <span class='admin_err'>{$err[$name]}</span>
           </td>
         </tr>\n";
    }

  function result_list (&$data, $query_type){
    $query="select * from User where ";
    $first=1;
    foreach($query_type as $value){
      if(!$first){ $query.=" AND "; }
      $query.=$value;
      $first=0;
    }
    $this->execute_query($query);
  }

  function result_place (&$data, $query_type){
    $query="select * from Seat,Category,PlaceMapZone,Event,User,`Order` where ";
    $first=1;
    foreach($query_type as $value){
      if(!$first){ $query.=" AND "; }
      $query.=$value;
      $first=0;
    }
    $query.=" AND seat_event_id=event_id AND seat_category_id=category_id
              AND seat_zone_id=pmz_id
              AND seat_user_id=user_id AND seat_order_id=order_id";
    $this->execute_place($query);
  }

  function execute_place ($query){
    if(!$res=ShopDB::query($query)){
       user_error(shopDB::error());
       return;
    }
    echo "
        <table class='admin_list' width='100%' cellspacing='1' cellpadding='2'>
          <tr><td colspan='7' class='admin_list_title'>".con('search_result')."</td></tr>
          <tr>
            <td class='admin_list_item'>".con('event')."</td>
            <td class='admin_list_item'>".con('category')."</td>
            <td class='admin_list_item'>".con('zone')."</td>
            <td class='admin_list_item'>".con('place')."</td>
            <td class='admin_list_item'>".con('price')."</td>
        	  <td class='admin_list_item'>".con('user')."</td>
        	  <td class='admin_list_item'>".con('bs')."</td>
            <td class='admin_list_item'>".con('status')."</td>
  	      </tr>\n" ;

     $alt=0;
     while($row=shopDB::fetch_assoc($res)){
      $flag=1;
      if((!$row["category_numbering"]) or $row["category_numbering"]=='both'){
        $place=$row["seat_row_nr"]."-".$row["seat_nr"];
      }else if($row["category_numbering"]=='rows'){
        $place=con('place_row')." ".$row["seat_row_nr"];
      }else if($row["category_numbering"]=='seat'){
        $place=con('place_seat')." ".$row["seat_nr"];
      }else{
        $place='---';
      }

      echo "
          <tr class='admin_list_row_$alt'>
            <td class='admin_list_item'>".$row["event_name"]."</td>
            <td class='admin_list_item'>".$row["category_name"]."</td>
            <td class='admin_list_item'>".$row["pmz_name"]."</td>

            <td class='admin_list_item'>".$place."</td>
            <td class='admin_list_item'>".$row["ticket_price"]."</td>
        	  <td class='admin_list_item'>
              <a class='link' href='view_user.php?user_id=".$row["user_id"]."'>".$row["user_lastname"]." ".$row["user_firstname"]."</a>
            </td>
  	        <td class='admin_list_item'>
              <a class='link' href='view_order.php?action=details&order_id=".$row["order_id"]."'>".$row["order_id"]."</a>
            </td>
            <td class='admin_list_item'>".$this->print_order_status($row["order_status"])."</td>
      	  </tr>\n";
      $alt=($alt+1)%2;
    }

    if(!$flag){
      echo "<tr><td class='admin_list_item' align='center' style='font-size:30px;color:red;'>".con('no_result')."</td></tr>";
    }
    echo "</table>";
  }


  function execute_query ($query){
    if(!$res=ShopDB::query($query)){
       user_error(shopDB::error());
       return;
    }
    echo "
        <table class='admin_list' width='100%' cellspacing='0' cellpadding='2'>
          <tr><td colspan='6' class='admin_list_title'>".con('search_result')."</td></tr>\n";
    $alt=0;
    while($row=shopDB::fetch_assoc($res)){
      $flag=1;
      echo "
          <tr class='admin_list_row_$alt'>
            <td class='admin_list_item'>".$row["user_id"]."</td>
  	        <td class='admin_list_item'>
              <a class='link' href='view_user.php?user_id=".$row["user_id"]."'>".$row["user_lastname"]." ".$row["user_firstname"]."</a>
            </td>
        	  <td class='admin_list_item'>".$row["user_address"]." ".$row["user_address1"]."</td>

        	  <td class='admin_list_item'>".$row["user_zip"]."</td>
        	  <td class='admin_list_item'>".$row["user_city"]."</td>
        	  <td class='admin_list_item'>".$row["user_country"]."</td>

        	  <td class='admin_list_item'>".$this->print_status($row["user_status"])."</td>
          </tr>" ;
      $alt=($alt+1)%2;
    }

    if(!$flag){
      echo "<tr><td class='admin_list_item' align='center'  style='font-size:30px;color:red;'>".con('no_result')."</td></tr>";
    }
    echo "</table>";
  }

  function search_check (&$data){
    if(!($data["user_lastname"] or $data["user_firstname"] or $data["user_zip"]
        or $data["user_city"] or $data["user_phone"] or $data["user_email"] or $data["user_status"])){
      return FALSE;
    }

    if($data["user_lastname"]){
      $query["user_lastname"]= "user_lastname LIKE '".$data['user_lastname']."%' ";
    }
    if($data["user_firstname"]){
      $query["user_firstname"]= "user_firstname LIKE '".$data['user_firstname']."%' ";
    }
    if($data["user_zip"]){
      $query["user_zip"]= "user_zip LIKE '".$data['user_zip']."%' ";
    }
    if($data["user_city"]){
      $query["user_city"]= "user_city LIKE '".$data['user_city']."%' ";
    }
    if($data["user_phone"]){
      $query["user_phone"]= "user_phone LIKE '".$data['user_phone']."%' ";
    }
    if($data["user_email"]){
      $query["user_email"]= "user_email LIKE '".$data['user_email']."%' ";
    }
    if($data["user_status"]){
      $query["user_status"]= "user_status='".$data['user_status']."' ";
    }

    return $query;
  }

  function search_place_check (&$data){
    if(!isset($data["event_id"])){
      return FALSE;
    }

    if(!isset($data["seat_row_nr"])){
      return FALSE;
    }
    if($data["event_id"]){
      $query["event_id"]="event_id='".$data["event_id"]."'";
    }

    if($data["seat_row_nr"]){
      $query["seat_row_nr"]="seat_row_nr='".$data["seat_row_nr"]."'";
    }
    if($data["seat_nr"]){
      $query["seat_nr"]="seat_nr='".$data["seat_nr"]."'";
    }
    return $query;

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
}
?>