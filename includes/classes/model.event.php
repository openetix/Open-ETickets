<?PHP
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2011 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *  phpMyTicket - ticket reservation system
 *   Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
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

class Event Extends Model {
  protected $_idName    = 'event_id';
  protected $_tableName = 'Event';
  protected $_columns   = array('#event_id', 'event_created', '*event_name', 'event_text', 'event_short_text', 'event_url',
                                'event_image', '#event_ort_id', 'event_webshop', '#event_pm_id', 'event_timestamp', 'event_date', 'event_time',
                                'event_open', 'event_end', '*event_status', 'event_order_limit', 'event_template',
                                '#event_group_id', 'event_mp3', '*event_rep', '#event_main_id', 'event_type',
                								'event_custom1', 'event_custom2', 'event_custom3', 'event_custom4',
                                'event_total', 'event_free');

  function load ($id, $only_published=TRUE){
    $pub=($only_published)?"and event_status='pub'":'';
    $query="select Event.*, pm_name, ort_name, ort_country, event_group_name
            from Event LEFT JOIN Ort ON event_ort_id=ort_id
                       LEFT JOIN PlaceMap2 pm ON event_pm_id=pm_id
                       LEFT JOIN Event_group eg ON eg.event_group_id= Event.event_group_id
            where Event.event_id="._esc($id)."
            {$pub} limit 1";

    if($res=ShopDB::query_one_row($query)){
      $event = new Event;
      $event->_fill($res);
      return $event;
    }else{
      return FALSE;
    }
  }

  function loadAllSubs ($event_main_id){
    $query="select * from Event
            where event_rep='sub'
            and event_main_id="._esc($event_main_id);
    if($res=ShopDB::query($query)){
      while($event_d=shopDB::fetch_assoc($res)){
        $event=new Event;
        $event->_fill($event_d, false);
        $events[]=$event;
      }
      return $events;
    }
  }

  function save($id = null, $exclude=null){
    $new = $this->id;
    $new = empty($new);
  //  echo 'order_id ',$this->id,'  ', ($new)?1:0 ;
   // print_r(debug_backtrace());
    if (ShopDB::begin('Save event')) {
      if (isset($this->event_recur_type) && $this->event_recur_type != "nothing") {
         if (!$this->saveRecursion()) { return false;}
      } else {
        if(!$new){
          if($this->event_rep=='main' && !$this->update_subs()) {
            return false;
          }
        }
        $this->event_timestamp = $this->event_date." ".$this->event_time;
        if (!parent::save($id, is($exclude,array('event_created')))){
          return self::_abort('Cant_save_event');
        } elseif($new && $this->event_pm_id){
          $pm=PlaceMap::load($this->event_pm_id);

          if($pm and $new_pm_id=$pm->copy($this->event_id)){
            $query="update Event set
                      event_pm_id={$new_pm_id}
                    where event_id={$this->event_id}";
            ShopDB::query($query);
            $this->event_pm_id = $new_pm_id;
          } else {
            return self::_abort('Cant find selected placemap.');
          }
          if ($this->event_rep=='sub') {
            Discount::Copy($this->event_main_id, $this->event_id, $pm->copylist);
          }
        }
      }
      if (ShopDB::commit('event Saved ')){
        return $this->event_id;
      }
    }
  }

  function saveEx(){
    if($id = parent::saveEx()){
      $this->fillFilename($_POST, 'event_image');
      $this->fillFilename($_POST, 'event_mp3');
    }
    return $id;
  }

  // #######################################################
  function saveRecursion () {
    unset($this->event_recur_type);
    if ($this->event_rep == 'main') {
      if (!$id = $this->save(false)) {
        return self::_abort('Cant_create recursion record');
      }
 			$_POST['event_rep'] = 'sub';

      if (!$this->fillPost()) {
        return self::_abort('not_all_all_values_set');
      }
      unset($this->event_recur_type);
 			$this->event_main_id = $id;
    }
 	  $event_dates = $this->getRecurionDates();
		foreach ($event_dates as $event_date) {
      $this->event_date = $event_date;
      $this->event_timestamp = $this->event_date." ".$this->event_time;
      unset($this->event_id);
      if (!$this->saveEx()) {
        return self::_abort('Cant_create recursion record');
      }
		}
    return true;
  }

  function getRecurionDates($invert= true) {
  	$event_dates	= array();
  	$rep_days     = is($this->recurse_days_selection, array());
  	$start_date 	= $this->event_date;
		$end_date     = $this->event_recur_end;

    if ($invert) {
		  $rep_days     = array_diff(array(0,1,2,3,4,5,6), $rep_days);
    }

		$dt_split     = explode("-",$start_date);
		$weekday      = date("w", mktime(0,0,0,$dt_split[1],$dt_split[2],$dt_split[0]));
		$no_days      = ceil(stringDatediff($start_date, $end_date) / 86400 );

    for($i = 0; $i <= $no_days; $i++) {
      $x = ($weekday + $i) % 7;
      if (in_array($x, $rep_days)) {
				$event_dates[] = addDaysToDate($start_date, $i);
      }
    }
		return $event_dates;
  }

  function CheckValues(&$data) {
      $t1 = $this->fillDate($data,'event_view_begin_date');
      $t2 = $this->fillTime($data,'event_view_begin_time');
      if ($t1 || $t2) {
        $data['event_view_begin'] = is($data['event_view_begin_date'],'0000-00-00').' '.is($data['event_view_begin_time'],'00:00').':00';
      }

      $t1 = $this->fillDate($data,'event_view_end_date');
      $t2 = $this->fillTime($data,'event_view_end_time');
      if ($t1 || $t2) {
        $data['event_view_end']   = is($data['event_view_end_date'],'0000-00-00').' '.is($data['event_view_end_time'],'00:00').':00';
      }

     $t1 = $this->fillDate($data,'event_custom4_date');
      $t2 = $this->fillTime($data,'event_custom4_time');
      if ($t1 || $t2) {
         $data['event_custom4']    = is($data['event_custom4_date'],'0000-00-00').' '.is($data['event_custom4_time'],'00:00').':00';
      }
    if (!$data['event_status'] and !$this->event_status) $data['event_status']='unpub';
		$this->fillTime($data,'event_time');
		$this->fillTime($data,'event_open');
		$this->fillTime($data,'event_end');
    $this->fillDate($data,'event_date');
  	if ( $data['event_rep'] == 'unique' ) {
  		$data['event_rep'] = 'main,sub';
  	}
    if ( strpos($data['event_rep'],'sub')!== false ){
      $this->_columns   = array('#event_id', '*event_name', 'event_text', 'event_short_text', 'event_url',
                                'event_image', 'event_webshop', '*event_ort_id', '#event_pm_id', '*event_date', '*event_time',
                                'event_open', 'event_end', '*event_status', '*event_order_limit', 'event_template',
                                '#event_group_id', 'event_mp3', '*event_rep', '#event_main_id', 'event_type',
                                'event_custom1', 'event_custom2', 'event_custom3', 'event_custom4',
                                'event_total', 'event_free');

    }

		if ( !$data['event_id'] ) { //echo 'new:', $data['event_rep'],strpos($data['event_rep'],'sub'),$data['event_pm_ort_id'] ;
			if ( strpos($data['event_rep'],'sub')!== false and $data['event_pm_ort_id'] == 'no_pm' ) {
				addError('event_pm_ort_id','mandatory');
			}
			if ( $data['event_pm_ort_id'] != 'no_pm' ) {
				list( $event_pm_id, $event_ort_id ) = explode( ',', $data['event_pm_ort_id'] );
				$data['event_pm_id']  = $event_pm_id;
				$data['event_ort_id'] = $event_ort_id;
			}
		}
   //checking the event recurrence date
    if(isset($data['event_recur_type']) && $data['event_recur_type'] != "nothing") {
      $this->fillDate($data,'event_recur_end');
    }
    return parent::CheckValues($data);
  }

  function _fill($arr, $nocheck=true)  {
    if ($arr['event_rep']=='sub') {
      $main=Event::load($arr['event_main_id'], FALSE);
      foreach($this->_columns as $key){
        self::getFieldtype($key);
        if (isset($arr["{$key}_chk"]) ) {
          $arr[$key] = $main->$key;
        }
      }
    }
    return parent::_fill($arr,$nocheck);
  }

  //LA FONCTION DELETE EST PUISSANTE!
  function delete (){
    global $_SHOP;
    if($this->event_status=='pub' ){
        echo '<div class=error>'.con('Status_is_pub').'</div>';
        return FALSE;
    }

    if($this->event_rep=='main'){
      $query="select count(*)
              from Event
              where event_status!='trash'
              and   event_main_id="._esc($this->id);
      if(!$count=ShopDB::query_one_row($query, false) or $count[0]>0){

        return addwarning('delete_subs_first');
      }
    } elseif($this->event_status=='nosal' and $this->event_pm_id){
      addNotice('To_Trash');
      return $this->toTrash();
    }

    if(ShopDB::begin('Delete event: '.$this->id )){

      if($this->event_status!='trash'){
        //check if there are non-free seats
        $query="select count(*)
                from Seat
                where seat_event_id="._esc($id)."
                and seat_status!='free'
                and seat_status!='trash'
                FOR UPDATE";
        if(!$count=ShopDB::query_one_row($query, false) or $count[0]>0){
          return self::_abort('seats_not_free');
        }
      }

      $query="delete from Seat
              where seat_event_id="._esc($this->id);
      if(!ShopDB::query($query)){
        return self::_abort(con('seats_delete_failed'));
      }

      if($this->event_pm_id and $pm=PlaceMap::load($this->event_pm_id)){
        if (!$pm->delete()){
          return self::_abort('Cant_delete_PlaceMap');
        }
      }

      $query="delete from Discount
              where discount_event_id="._esc($this->id);
      if(!ShopDB::query($query)){
        return self::_abort('discount_delete_failed');
      }

      $query="DELETE FROM adminlink
              WHERE adminlink_event_id="._esc($this->id);
      if(!ShopDB::query($query)){
        return self::_abort('adminlink_delete_failed');
      }
      $query="DELETE FROM Event
              WHERE event_id="._esc($this->id);
      if(!ShopDB::query($query)){
        return self::_abort('event_delete_failed');
      }

      if (!Order::toTrash()) {
        return false;
      }
      return ShopDB::commit('Event deleted');
    } else {
      return addWarning('Cant_Start_transaction');
    }

  }

  function publish(&$stats, &$pmps, $dry_run=FALSE){
    global $_SHOP;

    if(!$dry_run && !ShopDB::begin('Publish Event')){
      return false;
    }
    if($this->event_pm_id and ($this->event_rep=='sub' or $this->event_rep=='main,sub')){
      if (!PlaceMap::publish($this->event_pm_id, $this->event_id, $stats, $pmps, $dry_run)) {
        return false;
      }
      $es_total = 0;

      if($stats){
        foreach($stats as $category_ident =>$cs_total){
          $es_total += $cs_total;
        }
      }
      $this->event_free  = $es_total;
      $this->event_total = $es_total;
    }

    if(!$dry_run) {
      $this->event_status='pub';
      if (!plugin::call('!EventPublishCheck', $this, 'pub') || !$this->save()) {
        return self::_abort('publish7');
      }
      if( ShopDB::commit('Event publised')){
        return TRUE;
      }
    }else {
      return true;
    }
  }

  function stop_sales (){
    return $this->_change_state('pub','nosal');
  }

  function restart_sales (){
    return $this->_change_state('nosal','pub');
  }

  function _change_state ($old_s, $new_s){

    if($this->event_status!=$old_s){
       return addWarning('oldstate_not_correct');
    }

    if(ShopDB::begin('change event_state')){
      $this->event_status=$new_s;

      if(!plugin::call('!EventPublishCheck', $this, $new_s) || !$this->save()){
        return $this->_abort('error_event_save_changes');
      }
      return ShopDB::commit('Event_state changed');
    } else {
      return addWarning('cant_Start_transaction');;
    }
  }

  function _change_state_subs ($old_s,$new_s){
    $ok=TRUE;

    if($this->event_rep=='main' and $subs=Event::loadAllSubs($this->event_id)){
      foreach($subs as $sub){
        $ok=($sub->_change_status($old_s,$new_s) and $ok);
      }
    }

    return $ok;
  }

  function update_subs (){
    global $_SHOP;
    if(ShopDB::begin('update subevents')){
      $old=self::load($this->event_id,FALSE);

      $names[]='event_text';
      $names[]='event_short_text';

      $names[]='event_url';
      $names[]='event_image';
      $names[]='event_webshop';
      $names[]='event_name';
      //$names[]='event_ort_id';
      //$names[]='event_categories_nr';
      //$names[]='event_date';
      $names[]='event_time';
      $names[]='event_open';
      $names[]='event_order_limit';
      $names[]='event_payment';
      $names[]='event_template';
      $names[]='event_type';
      $names[]='event_group_id';
      $names[]='event_mp3';
      //$names[]='event_rep';


      foreach($names as $name){
        if($this->$name != $old->$name){
          $query="update Event set
                    {$name}="._esc($this->$name)."
                  where {$name}="._esc($old->$name)."
                  and event_rep='sub'
                  and event_main_id="._esc($this->event_id);
          if (!ShopDB::query($query)) {
            return  self::_abort('cant_update_sub_events');
          }
        }
      }
      return ShopDB::commit('Updated subevents');
    }
  }

  function new_from_main ($event_main_id){
    if(!$sub=self::load($event_main_id,FALSE)){
      echo $event_main_id;return;
    }
    unset($sub->event_id);
    $sub->event_main_id=$event_main_id;
    $sub->event_rep='sub';
    return $sub;
  }

  function toTrash(){
    global $_SHOP;

    if($this->event_status != 'nosal'){
      return FALSE;
    }

    if (ShopDB::begin('Trash Event')) {

      $query="update Event set
                event_status='trash'
              where event_id="._esc($this->event_id);

      if(!ShopDB::query($query)){
        return  self::_abort('cant_trash_event');
      }

      $query="update Seat set
                seat_status='trash'
              where seat_event_id="._esc($this->event_id);
      if(!ShopDB::query($query)){
        return self::_abort('cant_trash_seats');
      }

      if (!Order::toTrash()) {
        return false;
      }

      return ShopDB::commit('Event_trashed');
    };
  }

  function emptyTrash(){
    $query="select seat_event_id, count(order_id) as count
            from Seat LEFT JOIN `Order` ON  order_id=seat_order_id
            where seat_status='trash'
            group by seat_event_id";

    if(!$res=ShopDB::query($query)){
      return FALSE;
    }

    while($data=shopDB::fetch_assoc($res)){
      if(!$data['count'] and $event=Event::load($data['seat_event_id'],FALSE)){
        $event->delete();
      }
    }

    $query="select event_id, count(order_id) as count
            from Event,Seat,`Order`
            where event_id=seat_event_id and
            order_id=seat_order_id and
            event_status='trash'
            group by event_id";


    if(!$res=ShopDB::query($query)){
      return FALSE;
    }

    while($data=shopDB::fetch_assoc($res)){
      $all[$data['event_id']]=$data['count'];
    }

    $query="select event_id,count(order_id) as count
            from Event,Seat,`Order`
            where event_id=seat_event_id and
            order_id=seat_order_id and
            event_status='trash' and
            order_status='trash'
            group by event_id";

    if(!$res=ShopDB::query($query)){
      return FALSE;
    }

    while($data=shopDB::fetch_assoc($res)){
      $part[$data['event_id']]=$data['count'];
    }

    $counter=0;
    if(!empty($all)){
      foreach($all as $event_id=>$count){
        if($part[$event_id]==$count){

          $event=Event::load($event_id,FALSE);
          if($event->delete()){$counter++;}
        }
      }
    }

    return $counter;
  }

  static function dec_stat ($event_id,$count){
  	global $_SHOP;
    $query="UPDATE `Event` SET event_free=event_free-{$count}
            WHERE event_id="._esc($event_id)." LIMIT 1";
    if(!ShopDB::query($query) or shopDB::affected_rows()!=1){
      return FALSE;
    }else{
      return TRUE;
    }
  }

  static function inc_stat ($event_id,$count){
    $query="UPDATE `Event` SET event_free=event_free+{$count}
            WHERE event_id="._esc($event_id)." LIMIT 1";
    if(!ShopDB::query($query) or shopDB::affected_rows()!=1){
      return FALSE;
    }else{
      return TRUE;
    }
  }
}
?>