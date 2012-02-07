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

/**
 * AJAJ will return JSON only!
 *
 * The class will follow strict rules and load the settings to see if a session is present
 * if not then will return false with a bad request status
 *
 * JSON Requests should allways use json_encode(mixed, JSON_FORCE_OBJECT)
 * Its allways good practice to turn the var into an object as
 * JSON is 'Object Notifaction'
 *
 */
//error_reporting(0);
if (!defined('ft_check')) {die('System intrusion ');}
$fond = 0;

require_once(CLASSES."jsonwrapper.php"); // Call the real php encoder built into 5.2+

  //include_once('CONFIG'.DS.'init_config.php');
//  var_dump($_SHOP->timezone);
  if(function_exists("date_default_timezone_set")) {
    @date_default_timezone_set($_SHOP->timezone);
  }
error_reporting(0);
require_once ("controller.pos.checkout.php");

class ctrlPosAjax extends ctrlPosCheckout {
	private $request = array();
	private $json    = array();
  private $actionName = "";
  private $ErrorsAsWarning = false;


  public function drawContent() {
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
  		$this->request    = $_REQUEST;
  		$this->actionName = $this->action;
      $other = substr($this->action,0,1);
 //     echo $this->action;
      if(strtolower($other) == '_'){
        $this->action = 'do'.ucfirst(substr($this->action,1));
      }else{
        $this->action = "get".ucfirst(strtolower($this->action));
      }
      $result = $this->callAction();
  		if(!$result){
  		    $object = array("status" => false, "reason" => 'Missing action request');
  		    echo json_encode($object);
  		}
    }else{
    	header("Status: 400");
    	echo "This is for AJAX / AJAJ / AJAH requests only, please go else where.";
    }
    $this->executed = true;
	}

  public function callAction(){
    if(is_callable(array($this,$this->action))){
		  $this->json = am($this->json,array("status" =>true, "reason" => 'success'));
      //Instead of falling over in a heap at least return an error.
      try{
        $return = call_user_func(array($this,$this->action));
      }catch(Exception $e){
        addWarning('Error!');
        addWarning($e->getMessage());
        $return = false;
      }
      if(!$return){
				$this->json = array("status" => false, "reason" => 'reason unknown !!!');
			}
      $this->loadMessages();
  		echo json_encode($this->json);
			return true;
		}
		return false;
	}

  private function loadMessages() {
    global $_SHOP;
    $this->json['messages']['Error']   = array();
    if (isset($_SHOP->Messages['__Errors__'])) {
      $err = $_SHOP->Messages['__Errors__'];
      foreach ($err as $key => $value) {
        $output = '';
        foreach($value as $val){
          $output .= $val. "</br>";
        }
        if ($this->ErrorsAsWarning ) {
          addWarning('', '* error <b>'.con($key) .'</b>: '.$output);
        }
        $this->json['messages']['Error'][$key] = $output;
      }
    }
    $this->json['messages']['warning'] = printMsg('__Warning__', null, false);
    $this->json['messages']['Notice']  = printMsg('__Notice__', null, false);
  }

	/**
	 * PosAjax::getEvents()
	 *
	 * @param datefrom ('yyyy-mm-dd') optional
	 * @param dateto ('yyyy-mm-dd') optional
	 * @param return_dates_only (true|false) If set to true, event_dates will only be returned.
	 *
	 * Will Return:
	 * 	- events
	 * 		| - id (event_id)
	 *			| - html (option html)
	 * 		  	- free_seats (tot free seats)
	 * 		| - id ....
	 * 	- event_dates
	 * 		| - date ('yyyy-mm-dd')
	 * 		| - date ('yyyy-mm-dd')
	 * 		  - date ...
	 *
	 *
	 * @return boolean : if function returned anything sensisble.
	 */
	private function getEvents(){
	  global $_SHOP;
		//Check for date filters
		if($this->request['datefrom']){
			$fromDate = $this->request['datefrom'];
		}else{
			$fromDate = date('Y-m-d');
		}
		if($this->request['dateto']){
			$toDate = _esc($this->request['dateto']);
		}else{
			$toDate = 'event_date';
		}
	  $eventlinks = $_SHOP->admin->getEventLinks();
	  $eventlinks = ($eventlinks)?$eventlinks:'-1';
    $this->json['events'] = array(); //assign a blank array.
		$sql = "SELECT  event_id, event_name, ort_name, event_date, event_time, event_free es_free
				FROM Event left join 	Ort on ort_id = event_ort_id
				where 1=1
				AND event_date >= "._esc($fromDate)."
				AND event_date <= ".$toDate."
        AND (event_view_begin = '00-00-0000 00:00:00' OR (event_view_begin <= now()))
        and (event_view_end   = '00-00-0000 00:00:00' OR (event_view_end >= now()))
				and event_rep LIKE '%sub%'
				AND event_status = 'pub'
				AND event_free > 0
				ORDER BY event_name, event_date,event_time, event_id
				LIMIT 0,50";
		if($query = ShopDB::query($sql)){
		//Load html and javascript in the json var.

  		//Break down cats and array up with additional details.
  		while($evt = ShopDB::fetch_assoc($query)){
        		$date = formatDate($evt['event_date'],con('shortdate_format'));
        		$time = formatTime($evt['event_time']);

  			$option = "<option value='{$evt['event_id']}'>{$evt['event_name']} - {$evt['ort_name']} - {$date} - {$time}</option>";

  			$this->json['events'][] = array ('html'=>$option,'free_seats'=>$evt['es_free']);
  		}
    }
		if (count($this->json['events'])==0) {
		  $option = "<option value='{$evt['event_id']}'>".con('no_event_sets')."</option>";
		  $this->json['events'][] = array ('html'=>$option,'free_seats'=>0);
        }
		return true;
	}

	/**
	 * PosAjax::getCategories()
	 *
	 * @param categories_only (true|false) will only return the categories if set true else grabs discounts too.
	 *
	 * Will return:
	 *  - categories
	 * 		|- id (number)
	 * 			|- html (category option)
	 * 			|- numbering (true|false)
	 * 			|- placemap (placemap html)
	 * 			|- price (number)
	 *       - free_seats (int)
	 * 		|- id.. (number)
	 * |- enable_discounts (true|false)
	 * |- discounts
	 * 		|- id (number)
	 * 			|- html (discount option)
	 * 			|- type (fixed|percent)
	 * 			 - price (number)
	 * 		|- id.. (number)
	 *
	 * @return boolean as to whether the JSON should be compiled or not.
	 */
	private function getCategories(){
		if(!isset($this->request['event_id'])){
			return false;
		}else{
			$eventId = &$this->request['event_id'];
		}
		if(!is_numeric($eventId)){
	 		return false;
		}

		$sql = "SELECT *
			FROM Category c
			WHERE 1=1
			AND c.category_event_id = "._esc($eventId);
		$query = ShopDB::query($sql);

		//Load html and javascript in the json var.
		$this->json['categories'] = array(); //assign a blank array.

		//Break down cats and array up with additional details.
		while($cat = ShopDB::fetch_assoc($query)){
			$option = "<option value='".$cat['category_id']."'>".$cat['category_name']." -  ".$cat['category_price']."</option>";
			$numbering = false; //default numbering to none
			$placemap = ""; //leave placemap empty shouldnt be filled unless told to colect it.
			if(strtolower($cat['category_numbering']) != 'none'){
				$numbering = true; // If there should be a placemap set to true otherwise leave as false to show qty box.
				//Load Place Map
      $placemap = "<div style='overflow: auto; height: 500px; width:800px;' align='center' valign='center'>";
			$placemap .= $this->loadPlaceMap($cat);
      $placemap .= "</div>";
			}

			$this->json['categories'][strval($cat['category_id'])] = array('html'=>$option,'numbering'=>$numbering,'placemap'=>$placemap,'price'=>$cat['category_price'],'free_seats'=>$cat['category_free']);
		}
		//Finish loading categories and there details lets grab the discounts to...
		//If we only need the categories updating then just stop here.
		if($this->request['categories_only']){
			return true;
		}

		//Select Events Discounts
		$sql = "select discount_id, discount_name, discount_value, discount_type
			FROM Discount d
			WHERE d.discount_event_id = "._esc($eventId);
		$query = ShopDB::query($sql);

		//We count the number of rows to see if we should bother running through discounts.
		$numRows = ShopDB::num_rows($query);

//		if($numRows > 0){
			//Define json array for discounts
			$this->json['enable_discounts'] = false; //enable discounts.
			$this->json['discounts'] = array(); //assign a blank array.
			//Add the  "None Discount"
			$this->json['discounts'][] = array('html'=>"<option value='0' selected='selected'> ".con('normal')." </option>",'type'=>'fixed','price'=>0);
			while($disc = ShopDB::fetch_assoc($query)){
				//Check to see if percent or fixed
  			$this->json['enable_discounts'] = true; //enable discounts.
				if(strtolower($disc['discount_type']) == 'percent' ){
					$option = "<option value='".$disc['discount_id']."'>".$disc['discount_name']." - ".$disc['discount_value']."%</option>";
					$type = "percent";
				}else{
					$option = "<option value='".$disc['discount_id']."'>".$disc['discount_name']." - ".$disc['discount_value']."</option>";
					$type = "fixed";
				}
				//Load up each row
				$this->json['discounts'][] = array('html'=>$option,'type'=>$type,'price'=>$disc['discount_value']);
			}
//		}else{
//			$this->json['enable_discounts'] = false; //disable discounts.
//		}
		return true;
	}

  /**
   * PosAjax::getDiscounts()
   *
   * @param categories_only (true|false) will only return the categories if set true else grabs discounts too.
   *
   * Will return:
   * |- enable_discounts (true|false)
   * |- discounts
   * 		|- id (number)
   * 			|- html (discount option)
   * 			|- type (fixed|percent)
   * 			 - price (number)
   * 		|- id.. (number)
   *
   * @return boolean as to whether the JSON should be compiled or not.
   */
  private function getDiscounts(){
    if(!isset($this->request['event_id'])){
      return false;
    }else{
      $eventId = &$this->request['event_id'];
      $catId   = &$this->request['cat_id'];
    }
    if(!is_numeric($eventId)){
      return false;
    }

    //Select Events Discounts
    $sql = "select discount_id, discount_name, discount_value, discount_type
    	FROM Discount d
    	WHERE  (FIND_IN_SET('yes', discount_active)>0 or FIND_IN_SET('pos', discount_active)>0)
    	and discount_event_id = "._esc($eventId);
    if ($catId) {
       $sql .=  " AND (discount_category_id="._esc($catId)." OR discount_category_id is null)";
    }
    $query = ShopDB::query($sql);

    //We count the number of rows to see if we should bother running through discounts.
    $numRows = ShopDB::num_rows($query);

    //		if($numRows > 0){
    //Define json array for discounts
    $this->json['enable_discounts'] = false; //enable discounts.
    $this->json['discounts'] = array(); //assign a blank array.
    //Add the  "None Discount"
    $this->json['discounts'][] = array('html'=>"<option value='0' selected='selected'> ".con('normal')." </option>",'type'=>'fixed','price'=>0);
    while($disc = ShopDB::fetch_assoc($query)){
      //Check to see if percent or fixed
      $this->json['enable_discounts'] = true; //enable discounts.
      if(strtolower($disc['discount_type']) == 'percent' ){
        $option = "<option value='".$disc['discount_id']."'>".$disc['discount_name']." - ".$disc['discount_value']."%</option>";
        $type = "percent";
      }else{
        $option = "<option value='".$disc['discount_id']."'>".$disc['discount_name']." - ".$disc['discount_value']."</option>";
        $type = "fixed";
      }
      //Load up each row
      $this->json['discounts'][] = array('html'=>$option,'type'=>$type,'price'=>$disc['discount_value']);
    }
    //		}else{
    //			$this->json['enable_discounts'] = false; //disable discounts.
    //		}
    return true;
  }



	/**
	 * PosAjax::_pre_items()
	 *
	 * This is part of the cartlist
	 * @return n one.
	 */
  function _pre_items (&$event_item,&$cat_item,&$place_item,&$data){
    $data[]=array($event_item,$cat_item,$place_item);
  }

	/**
	 * PosAjax::getCartInfo()
	 *
	 * @param categories_only (true|false) will only return the categories if set true else grabs discounts too.
	 *
	 * @return boolean as to whether the JSON should be compiled or not.
	 */
	private function getCartInfo(){
    $this->json['page'] = 1;
    $this->json['total'] = 1;
    $this->json['records'] = 0;
    $this->json['userdata'] = array();
    $mycart=$_SESSION['_SMART_cart'];
    $this->json['userdata']['can_cancel'] = !$this->__MyCart->is_empty_f() or isset($_SESSION['_SHOP_order']);
    $cart_list  =array();
    if($mycart and !$this->__MyCart->is_empty_f()){
      $mycart->iterate(array(&$this,'_pre_items'),$cart_list);
    }


    $counter  = 0;
    $subprice = 0.0;
    foreach ($cart_list as $cart_row) {
      $event_item    = $cart_row[0];
      $category_item = $cart_row[1];
      $seat_item     = $cart_row[2];
      $seat_item_id  = $seat_item->id;
      $seats         = $seat_item->seats;
      $disc          = $seat_item->discount(reset($seats)->discount_id);
      $seatinfo = '';

      if($category_item->cat_numbering=='rows'){
        $rcount=array();
        foreach($seats as $seat){
          $rcount[$seat->seat_row_nr]++;
        }
        foreach($rcount as $row => $count){
          $seatinfo .= ", {$count} x ".con('row')." {$row}";
        }
      } elseif (!$category_item->cat_numbering or $category_item->cat_numbering == 'both'){
        foreach($seats as $places_nr){
 					$seatinfo .= ", {$places_nr->seat_row_nr} - {$places_nr->seat_nr}";
        }
      }
      $seatinfo = substr($seatinfo,2);
      if ($seat_item->ordered) {
            $col = "<font color='red'>".con('Ordered').'</font>';
      } else {
        if ($seat_item->is_expired()) {
            $col = "<font color='red'>".con('expired').'</font>';
      	} else {
      	    $col = $seat_item->ttl()." min.";          //"<img src='../images/clock.gif' valign='middle' align='middle'> ".
        }
        $col ="<form id='remove' class='remove-cart-row' name='remove{$seat_item_id}' action='ajax.php?x=removeitemcart' method='POST' >".
     		 		 "<input type='hidden' value='{$event_item->event_id}' name='event_id' />".
      		 	 "<input type='hidden' value='{$category_item->cat_id}' name='category_id' />".
      		 	 "<input type='hidden' value='{$seat_item_id}' name='item' />".
             "<button type='submit' class='ui-widget-content jqgrow remove-cart-row-button'
                      style='display: inline; cursor: pointer; padding:0; margin: 0; border: 0px'> ".
             "<img src='../images/trash.png' style='display: inline; cursor: pointer;padding:0; margin: 0; border: 0px' width=16></button> ".
             $col.
  			     "</form>";
  //  			 "<input type='hidden' value='remove" name="action" />
      }
      $row = array($col);
      $row[] = "<b>{$event_item->event_name}</b> - {$event_item->event_ort_name}<br>".
               formatdate($event_item->event_date,con('shortdate_format'))."  ".formatdate($event_item->event_time,con('time_format'));
      $row[] = count($seats);
      $col = "{$category_item->cat_name}";
      if ($seatinfo) {
        $col = "<acronym title='{$seatinfo}'>$col</acronym>";
      }
  		if ($disc) {
   	    $col .= "<br><i>".con('Discount_for')." ".$disc->discount_name.'</i>';
      }
      $row[] = $col;
  		if ($disc) {
     	 	$row[] = valuta($disc->apply_to($category_item->cat_price));
  		} else {
     		$row[] = valuta($category_item->cat_price);
  		}
  		$subprice += $seat_item->total_price($category_item->cat_price);
  		$row[] = valuta($seat_item->total_price($category_item->cat_price));

  		$this->json['rows'][] = array('id'=> "{$event_item->event_id}|{$category_item->cat_id}|{$seat_item_id}", 'cell'=> $row);
  		$counter++ ;
		}
    include_once('shop_plugins'.DS.'block.handling.php');
    $sql = 'SELECT `handling_id`, `handling_fee_fix`, `handling_fee_percent`, `handling_fee_type`
            FROM `Handling`
            WHERE handling_sale_mode LIKE "%sp%"';

 		if(check_event($this->__MyCart->min_date_f())){
			$sql .= " and handling_alt <= 3";
		} else {
   		$sql .= " and handling_alt_only='No'";
		}

    $res=ShopDB::query($sql);
    $totalprice = $subprice;
    $handlings = array();
    while ($pay=shopDB::fetch_assoc($res)){
      $fee = calculate_fee($pay, $subprice);
      if (($_POST['handling_id']== $pay['handling_id'] and $counter and $_POST['no_fee']!=='1')) { // and !$counter and $_POST['no_fee']!==1
        $totalprice += $fee;
      }
 			$fee = ($fee == 0.00)? '': '+ '.valuta($fee);
      $handlings[] = array('index'=>"#price_{$pay['handling_id']}", 'value'=>$fee);
    }
    $this->json['userdata']['handlings'] = $handlings;
    $this->json['userdata']['total']     = valuta($totalprice);
    $this->json['userdata']['can_order'] = $counter !== 0;
		return true;
	}


	private function getPlaceMap(){
		if(!isset($this->request['category_id'])){
		  addWarning('bad_category_id');
			return false;
		}else{
			$catId = &$this->request['category_id'];
		}
		if(!is_numeric($catId)){
		  addWarning('bad_category_id');
	 		return false;
		}

		$sql = "SELECT *
			FROM Category c
			WHERE 1=1
			AND c.category_id = "._esc($catId);
		$result = ShopDB::query_one_row($sql);

		if(strtolower($cat['category_numbering']) != 'none'){
      $placemap = "<div style='overflow: auto; height: 450px; width:800px;' align='center' valign='center'>";
			$placemap .= $this->loadPlaceMap($result);
      $placemap .= "</div>";
			$this->json['placemap'] = $placemap;
			return true;
		}
    addWarning('not_placemap');
		return false;
	}

	private function getUserSearch(){
   		$fields = ShopDB::fieldlist('User');
    	$where = '';
   		foreach($_POST as $field => $data) {
      		if (in_array($field,$fields) and strlen(clean($data))>1) {
     			if ($where){ $where.='and ';}
        		$where.= "({$field} like "._esc('%'.clean($data).'%').") \n";
  			}
   		}
   		if (!$where) $where = '1=2';

	   	$this->json['POST'] = $where;

		$sql = "SELECT user_id, CONCAT_WS(', ',user_lastname, user_firstname) AS user_data,
               	user_zip, user_phone, user_city, user_email
				FROM `User`
        		WHERE {$where}";// and user_owner_id =". $_SESSION['_SHOP_AUTH_USER_DATA'][;
		$query = ShopDB::query($sql);
		$numRows = ShopDB::num_rows($query);
	    $this->json['page'] = 1;
	    $this->json['total'] = 1;
	    $this->json['records'] = 0;
	    $this->json['userdata'] = array();

		while($user = ShopDB::fetch_row($query)){
			$this->json['rows'][] = array('id'=>$user[0], 'cell'=> $user);
		}
	return true;
	}

	private function getCanprint(){
		if($this->request['orderid']){
			$orderid = $this->request['orderid'];
		}else{
		  addWarning('bad_order_id');
			return false;
		}

		$sql = "SELECT order_payment_status
            FROM `Order`
            WHERE order_id="._esc($orderid);
    	$q = ShopDB::query_one_row($sql);
 	  	$this->json['status'] = $q['order_payment_status']=='paid';
      $this->json['show'] = true;
		return true;
	}

	private function getUserData(){
		$sql = "SELECT *
            FROM `User`
            WHERE user_id="._esc($_POST['user_id']);
		$query = ShopDB::query($sql);
		$numRows = ShopDB::num_rows($query);
		if($numRows > 0){
  	  $this->json['user'] = ShopDB::query_one_row($sql);
 			return true;
    }
    addWarning('user_not_exsist');
		return false;
	}

	/**
	 * PosAjax::loadPlaceMap()
	 *
	 * @param mixed $category
	 * @return placemap html
	 */
	private function loadPlaceMap($category){
    global $_SHOP;
    require_once("shop_plugins".DS."function.placemap.php");
		return placeMapDraw($category, true, true, 'pos', 16, -1); //return the placemap
	}

  /**
	* @name add to cart function
	*
	* Used to add seats to the cart. Will check if the selected seats are free.
	*
	* @param event_id : required
	* @param category_id : required
	* @param seats : int[] (array) or int : required
	* @param mode : where the order is being made options('mode_web'|'mode_kasse')
	* @param reserved : set to true if you want to reserve only.
	* @param discount_id
	* @return boolean : will return true if that many seats are avalible.
	*/
  private function doAddToCart() {
    $event_id = is($this->request['event_id'],0);
    $category_id = is($this->request['category_id'],0);
    $discount_id = is($this->request['discount_id'],0);
    if($event_id <= 0){
      addWarning('wrong_event_id');
      return false;
    }
    $res = $this->__MyCart->CartCheck($event_id, $category_id, $this->request['place'], $discount_id, 'mode_pos', false, false);
    if($res){
      $this->json['reason']=$res;
      $this->json['status']=true;
    	return true;
    }else{
    	return false;
    }
  }

  private function doRemoveItemCart (){
    $event_id = is($this->request['event_id'],0);
    $cat_id = is($this->request['category_id'],0);
    $item = is($this->request['item'],0);

    if($event_id < 1 || $cat_id < 1 || !is_numeric($item)){
      addWarning('wrong_input_ids');
      return false;
    }
    $this->__MyCart->remove_item_f($event_id,$cat_id,$item);
    return true;
  }


  private function doPosConfirm(){
    $fond=null;
//    require ("controller/pos_template.php");
    try{
      $checkoutRes = $this->_posConfirm();
    }catch(Exception $e){
      addWarning('unknown_error',$e->getMessage());
      return false;
    }

    if(is_string($checkoutRes)){
      $this->json['html'] = $this->smarty->fetch($checkoutRes . '.tpl');
      return true;
    }elseif(is_bool($checkoutRes) and !$checkoutRes){
      return false;
    }
  }

  private function doPosSubmit(){
    $checkoutRes = $this->_submit();
    if(is_string($checkoutRes)){
      $this->json['html'] = $this->smarty->fetch($checkoutRes . '.tpl');
      return true;
    }else {
      return false;
    }
  }
  private function doPosCancel () {
    $this->__MyCart->destroy_f(true);
    return true;
  }


  private function _posConfirm () {

    if ((int)$_POST['handling_id']==0) { // Checks handling is selected
        addWarning('no_handling_selected');//.print_r($_POST,true);
        return false;

    } elseif ((int)$_POST['user_id']==-2) { //Checks that a user type is selected.
        addWarning('no_useraddress_selected');
        return false;

    } elseif ((int)$_POST['user_id']==-1) { //if "No User" use the POS user
      // THis is the POS user that the admin account is linked too.
      $user_id = $_SESSION['_SHOP_AUTH_USER_DATA']['admin_user_id'];
      if(!$user_id){
        addWarning('admin_user_id_blank');
        return false;
      }
      $this->__User->load_f($user_id);

    } elseif ((int)$_POST['user_id']==0) {

      $query="SELECT count(*) as count
              from User
              where user_email="._esc($_POST['user_email']);
      if($row = ShopDB::query_one_row($query) and $row['count']>0){
        addWarning('useralreadyexist');
        return false;
      }
      //if new user selected put the pos user as the owner of the order
      $this->json['newuser_id'] = 'new user';
      $_POST['user_owner_id'] = $_SESSION['_SHOP_AUTH_USER_DATA']['admin_id'];
      $user_id = $this->__User->register_f( 4, $_POST, 0, '', true);
     // addwarning( "new id: $user_id");
      $this->ErrorsAsWarning = true;
      if (!$user_id || hasErrors() ) {
      	$this->json['newuser_id'] = $user_id ;
        return false;
      } else {
        $this->assign('newuser_id', $user_id);
      }
    } else {
      $user_id = $_POST['user_id'];
    }
    $no_fee  = is($_POST['no_fee'], 0);
    $no_cost = is($_POST['no_cost'], 0);
    unset($_SESSION['_SHOP_order']) ;
    if((int)$_POST['handling_id'] === 1){
      $return = $this->_reserve('pos',$user_id);
    }else{
      $return = $this->_confirm('pos', $user_id, $no_fee, $no_cost);
    }
    if ($return == 'checkout_preview' ) {
      return false;
    }else {
      return $return;
    }
  }
}
?>