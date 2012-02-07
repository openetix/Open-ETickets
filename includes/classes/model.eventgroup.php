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

class Eventgroup Extends Model {
  protected $_idName    = 'event_group_id';
  protected $_tableName = 'Event_group';
  protected $_columns   = array( 'event_group_id','*event_group_name', 'event_group_type', 'event_group_description',
                                 '*event_group_status', 'event_group_start_date', 'event_group_end_date', 'event_group_image');

  function load ($id=0){
    $query="select *
            from Event_group
            where Event_group_id="._esc($id);
    if($res=ShopDB::query_one_row($query)){
      $eg=new Eventgroup;
      $eg->_fill($res);
      return $eg;
    }
  }

  function CheckValues(&$arr){
    $this->fillFilename($arr, 'event_group_image');
    $this->fillDate($arr, 'event_group_start_date');
    $this->fillDate($arr, 'event_group_end_date');
    return parent::checkValues($arr);
  }

  function _fill($arr, $nocheck=true){
    if (!isset($arr['event_group_status'])) {$arr['event_group_status'] ='unpub';}
    return parent::_fill($arr, $nocheck);
  }

  static function setState($eg_id, $state) {
    $state =( $state)?'pub':'unpub';
    $query="UPDATE Event_group SET
              event_group_status='{$state}'
            WHERE event_group_id="._esc((int)$eg_id);
    return ShopDB::query($query);
  }

  function delete() {
    $query = "SELECT count(event_name)
              FROM Event
              Where event_group_id="._esc($this->id);
    if (!($res = ShopDB::query_one_row($query, false)) || (int)$res[0]) {
      return addWarning('in_use');
    }
    return parent::delete();
  }

}
?>