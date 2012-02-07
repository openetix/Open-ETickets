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



require_once(CLASSES."jsonwrapper.php"); // Call the real php encoder built into 5.2+
require_once (CLASSES.'class.controller.php');

class ctrlWebJson Extends Controller  {
  public    $session_name = "ShopSession";
  public    $json = array();
  public    $ErrorsAsWarning = false;
  public function draw() {
    $this->executed = true;
    parent::draw();
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
  		$this->request    = $_REQUEST;
  		$this->actionName = $this->action;
      $this->action = 'do'.ucfirst($this->action);
      $result = $this->callAction();
  		if(!$result){
  		    $object = array("status" => false, "reason" => 'Missing action request', 'request'=>$_REQUEST);
  		    echo json_encode($object);
  		}

    }else{
    	header("Status: 400");
    	echo "This is for AJAX / AJAJ / AJAH requests only, please go else where.";
    }
	}

  public function callAction(){

    if(is_callable(array($this,$this->action))){
		  $this->json = am($this->json, array("status" =>true, "reason" => 'success'));
      //Instead of falling over in a heap at least return an error.
      try{
        $return = call_user_func(array($this, $this->action));
      }catch(Exception $e){
        addWarning('Error!');
        addWarning($e->getMessage());
        return true;
      }
      if($return){
        $this->loadMessages();
    		echo json_encode($this->json);
			}
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


  public function doDiscountpromo() {
    $discount = Discount::load($this->request['id']);
    if (!empty($discount->discount_promo)) {
      $promo = $this->request[$this->request['name']];
      $this->json = (strtoupper($promo) == strtoupper($discount->discount_promo));
    } else {
      $this->json = false;
    }
    return true;
  }

  public function doPlacemap(){
    global $_SHOP;
    if(!isset($this->request['category_id'])){
      addWarning('bad_category_id');
      return true;
    }else{
      $catId = &$this->request['category_id'];
    }
    if(!is_numeric($catId)){
      addWarning('bad_category_id');
      return true;
    }
    $sql = "SELECT *
    	FROM Category c
    	WHERE 1=1
    	AND c.category_id = "._esc($catId);
    $result = ShopDB::query_one_row($sql);
    require_once("shop_plugins".DS."function.placemap.php");
    $this->json['cat'] =$result;
    $this->json['placemap'] = placeMapDraw($result, true, true, 'www', 16, $this->request['seatlimit']); //return the placemap
    return true;
  }

}
?>