<?php
/*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't
 * clear to you.

 */

session_cache_limiter("must-revalidate");

if (!defined('ft_check')) {die('System intrusion ');}
require_once("admin/class.adminview.php");

class report_archive_event extends AdminView {
  function cp_form (&$data,&$err){
		global $_SHOP;

    $query = "select * from Event
              where event_rep LIKE '%sub%'
              {$_SHOP->admin->getEventRestriction()}
              and event_pm_id IS NOT NULL
              ORDER BY event_date,event_time,event_name";

		if($res=ShopDB::query($query)){
		  while($row=shopDB::fetch_assoc($res)){
			  $event[$row['event_id']]=formatDate($row['event_date']).'-'.formatTime($row['event_time']).' '.$row['event_name'];
			}
		}

		echo "<form action='{$_SERVER["PHP_SELF"]}' method='GET'>";
		$this->form_head(con('export_xml_event_title'));

		$this->print_select_assoc('export_entrant_event',$data,$err,$event);// choose an event
		echo "
		<tr><td align='center' class='admin_value' colspan='2'>
  		  	<input type='hidden' name='run' value='{$_REQUEST['run']}'>

		<input type='submit' name='submit' value='".con('submit')."'></td></tr>
		</table></form>";
  }
  function execute (){
    global $_SHOP;


    if($_GET['export_entrant_event']>0){
      $event_id=(int)$_GET["export_entrant_event"];
      $query="SELECT *
              FROM Event left join Ort on Event.event_ort_id=Ort.ort_id
              WHERE Event.event_id='{$event_id}'";

      if(!$res=ShopDB::query($query) or !$event=ShopDB::fetch_assoc($res)){
         //user_error(mysql_error());
       return 0;
      }
      require_once 'Spreadsheet/Excel/Writer.php';
      // Creating a workbook
      $workbook = new Spreadsheet_Excel_Writer();
      // sending HTTP headers
      $workbook->send($event["event_name"]."-".$event["event_date"].".xls");
      // Creating a worksheet
      $worksheet = $workbook->addWorksheet('Event Data');
      // The actual data
      foreach($event as $k=>$v){
       $worksheet->write(0, $i,$k);
       $worksheet->write(1, $i,$v);
       $i++;
      }

      $worksheet1 = $workbook->addWorksheet('Category Data');

      $query="SELECT *
              FROM Category
              WHERE category_event_id='{$event_id}'";
      if(!$res=ShopDB::query($query)){
        user_error(ShopDB::error());
        return FALSE;
      }
      $j=1;

      while($row=ShopDB::fetch_assoc($res)){
        $i=0;
        foreach($row as $k=>$v){
         if($j==1){
          $worksheet1->write(0,$i,$k);
         }
         $worksheet1->write($j,$i,$v);
         $i++;
       }
       $j++;
      }

      $worksheet3 = $workbook->addWorksheet('Ticket Data');

      $query="select *
              from Seat LEFT JOIN Discount ON seat_discount_id=discount_id
              left join `Order` on seat_order_id=order_id
              left join User on seat_user_id=user_id
             where  seat_event_id='{$event_id}'";
      if(!$res=ShopDB::query($query)){
        user_error(ShopDB::error());
        return 0;
      }
      $j=1;

      while($row=ShopDB::fetch_assoc($res)){
        $i=0;
        foreach($row as $k=>$v){
         if($j==1){
          $worksheet3->write(0,$i,$k);
         }
         $worksheet3->write($j,$i,$v);
         $i++;
       }
       $j++;
      }
      $workbook->close();
      return true;
    }
  }

  function draw (){
    $this->cp_form($_GET,$this->err);
  }

}

?>