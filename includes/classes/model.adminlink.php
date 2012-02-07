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

class Adminlink Extends Model {
  protected $_idName    = 'adminlink_id';
  protected $_tableName = 'adminlink';
  protected $_columns   = array( 'adminlink_id','#adminlink_event_id', '#adminlink_admin_id', '#adminlink_pos_id',
                                 '#adminlink_admgroup_id');

  function create($event_id, $admin_id=null, $pos_id =null, $admgroup_id=null){
      $eg=new Adminlink();
      $eg->adminlink_event_id = $event_id;
      $eg->adminlink_admin_id = $admin_id;
      $eg->adminlink_pos_id = $pos_id;
      $eg->adminlink_admgroup_id = $admgroup_id;
      $eg->save();
      return $eg;
  }

  function load ($id=0){
    $query="select *
            from adminlink
            where adminlink_id="._esc($id);
    if($res=ShopDB::query_one_row($query)){
      $eg=new Adminlink;
      $eg->_fill($res);
      return $eg;
    }
  }
  function delete(){
    /* This query need to be checked !!!
     $query = "select 1 as inUse
                  from `User`
                  left join   `adminlink` on user_id = adminlink_pos_id
                  left join
	    	(select distinct seat_pos_id, seat_event_id
         from Seat
         where seat_status = 'free'
         and seat_event_id = "._esc($_REQUEST['event_id']).') as sss on user_id = seat_pos_id
         where seat_pos_id is not null and adminLink_id = "._esc((int)$_REQUEST['adminlink_id'])."
	    	and adminlink_event_id = seat_event_id";
	    	if ($row = ShopDB::query_one_row($query)){
	    		if($row != null && $row['inUse'] == 1){
     //this is in use, return
     return addWarning(con("delete_link_error"));
    }
  */
    return parent::delete();
  }

}
?>