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

class User extends Model{
  protected $_idName    = 'user_id';
  protected $_tableName = 'User';
  protected $_prefs = array('user_prefs_print','user_prefs_strict');
  protected $_columns   = array('#user_id', '*user_lastname', 'user_firstname', '*user_address', 'user_address1',
                                '*user_zip', '*user_city', 'user_state', '*user_country', 'user_phone', 'user_fax' ,
                                '*user_email', '*user_status', 'user_prefs', 'user_created', 'user_custom1', 'user_custom2',
                                'user_custom3', 'user_custom4', 'user_owner_id', 'user_lastlogin', 'user_order_total',
                                'user_current_tickets', 'user_total_tickets','user_maillist');

  var $is_member = false;

  static function load ($user_id){
    $user = new User(false);
    if ($arr = self::loadArr($user_id)) {
      $user->_fill($arr);
      return $user;
    }
  }


  static function loadArr ($user_id){
    $query="select User.*, auth.active
            from User left join auth on auth.user_id=User.user_id
            where User.user_id='$user_id'";
    if(!$user=ShopDB::query_one_row($query)){
      return FALSE;
    }
  	$user['is_member'] = ($user['user_status']==2);
    $user['active']    = (empty($user['active'])) && $user['is_member'];
    if (!empty($user['user_prefs'])) {
      $prefs = unserialize( $user['user_prefs']);
      foreach($prefs as $key => $value) {
        $user[$key] = $value;
      }

    }
  	$_SESSION['_SHOP_USER']=$user_id;
    return $user;
  }

  static function login ($username, $password){
    if(!isset($username)|| !isset($password)){
      addWarning('mand_all');
      return false;
    }
  	$sql = "SELECT *
        		FROM auth left join User on auth.user_id=User.user_id
        		WHERE auth.username="._esc($username)."
        		AND auth.password="._esc(md5($password))."
        		AND User.user_status=2
        		LIMIT 1";

  	if(!$res=ShopDB::query_one_row($sql)){
  		addWarning('log_err_wrong_usr_info');
  		return false;
  	}
  	if($res['active']) {
  		addWarning('log_err_not_act_info');
  		return FALSE;
  	}
    if (!empty($user['user_prefs'])) {
      $user['user_prefs'] = unserialize( $user['user_prefs']);

    }
    self::log_user($res['user_id']);
    unset($res['password']);
    unset($res['active']);
  	$res['is_member']=true;
  	$_SESSION['_SHOP_USER']=$res['user_id'];
  	return $res;
  }

  function logout (){
    unset($_SESSION['_SHOP_USER']);
  }


  static function register ($status, $data, $mandatory=array(), $secure=0, $short=0){
    $user = new User();
    $data['user_status']=$status;

    if ($user->CheckValues($data, $status, $mandatory, $secure, $short)){

      $query="SELECT count(*) as count
              from auth
              where username="._esc($data['user_email']);
      if($row = ShopDB::query_one_row($query) and $row['count']>0){
        addError('user_email','useralreadyexist');
        return FALSE;
      }

      if (ShopDB::begin('register user')) {
        $user->_fill($data);
        $user->user_status = $status;

        //Try to save user
        if (!$user->save()) {
          return self::_abort('cant save user');
        }
        //Send activation code?
       // echo 'new status:',$status;

        if (in_array($status, array(2))) {
          if ($short and empty($data['password1'])) {
            $data['password1'] = substr( base_convert($active,15,36),0,8);
          }

          $active = md5(uniqid(rand(), true));
          $query="insert into auth (username, password, user_id, active) VALUES (".
                  _esc($data['user_email']).",".
                  _esc(md5($data['password1'])).",".
                  _esc($user->user_id).",".
                  _esc($active).")";

          if(!ShopDB::query($query)){
            addWarning('cant_save_auth');
            return self::_abort('cant store auth');
          }
          $data['user_id'] = $user->user_id;

          if (!User::sendActivationCode($data, $active)) {
            return self::_abort('cant send activation code');
          }
        }


        //Try to commit changes or fail;
        if(!ShopDB::Commit('Registered user')){
          return self::_abort('cant commit user');
        }
        //AND FINALY return the id
        return $user->user_id; //eer silly <<<
      }
    } else {
      Addwarning('error_while_registering_user');
    }
    unset($user);
    return false;
  }

  function saveEx($id=null, $expl=false){
    $prefs =array();
    foreach ($this as $key =>$value){
      if (in_array($key,$this->_prefs )) {
        $prefs[$key] = $value;
      }
    }
    $this->user_prefs = serialize( $prefs);
    return parent::saveEx($id,$expl);
  }

	function updateEx (&$data, $mandatory=0, $short=0){

    	if(!empty($data['user_id'])) {

			/////////////////////////
			///Check user password///
			/////////////////////////

    		$query="SELECT username, password, user_status
              	FROM User left join auth on auth.user_id=User.user_id
              	WHERE User.user_id="._esc((int)$data['user_id']);

    		if (!$user=ShopDB::query_one_row($query)){
    		    addWarning('failed_too_update_user');
        		die('System error while changing user data.');
    		} elseif($user['user_status']==2) {
      		if (empty($data['old_password'])) {
        		adderror('old_password','mandatory');
      		} elseif ($user['password']!==md5($data['old_password']) ) {
    	  		adderror('old_password',"incorrect_password");
          } elseif ($user ['username']<> $data['user_email'] and !hasErrors('user_email')) {
      			$query="select count(*) as count from auth where username="._esc($data['user_email']);
            if ($row=ShopDB::query_one_row($query) and $row['count']>0){
				      adderror('user_email', 'alreadyexist') ;
      			}
      		}
    		}

        if(is($data['user_status'],false)===false){ $data['user_status'] = $user['user_status']; }
    	  $status = $user['user_status'];
  	    $userup = new user();

    	  if ($userup->CheckValues($data, $status, $mandatory, 0, $short)){
          $userup->_fill($data);
    	    $userup->user_status = $status;
    	    if (ShopDB::Begin()){
            if ($userup->save()){
              addNotice("save_successful");
              $set = array();
              if ($user['username']<> $data['user_email']) {
                $set[] = "username="._esc($data['user_email']);
          		}
            	if (!empty($data['password1'])) {
                $set[] = "password="._esc(md5($data['password1']));
           		}

            	if ($set) {
                $set = implode(',',$set);
            		$query="UPDATE auth SET
                          $set
                       	WHERE user_id="._esc((int)$userup->user_id);
                if(!ShopDB::query($query)){
         	  		  return self::_abort('cant update auth');
           			}
           		}
            }
          }
          return ShopDB::Commit('Updated user');
        }
        addWarning('failed_too_update_user');
    	  return false;
      }else{
        addWarning('bad_user_id');
        die("Missing user id. System halted.");
    	}
  	}

  function activate($userdata){
    if (strpos($userdata,'%')!==false) {
      $userdata = urldecode($userdata);
    }
    if (is_base64_encoded($userdata)) {
    	$userdata2 = base64_decode($userdata);
    	list($x,$z,$y) = explode('|', $userdata2, 3);
    	if (isset($x) && isset($y)) {
      	$x = (int)    $x;
      	$y = (string) $y;

        if ( ($x> 0) && (strlen($y) == 32)) {
          $query = "UPDATE auth SET active=NULL WHERE user_id="._esc($x)." AND active="._esc($y)." LIMIT 1";
          if (ShopDB::query($query) and shopDB::affected_rows() == 1) {
            addNotice('act_sent');
            return true;
          } else {
        		addWarning('act_error') ;
            return false;
          }
        }
      }
    }
    addWarning('act_uselink') ;
    return false;
  }

	function resend_activation($email){
		global $_SHOP;

	    $query="SELECT auth.active, User.*
        			FROM auth LEFT JOIN User ON auth.user_id=User.user_id
        			WHERE auth.username="._esc($email);
	    if (!$row=ShopDB::query_one_row($query)) {
	  		addWarning("log_err_wrong_usr_activation_email");
	  	} elseif ($row['active']==null) {
	  		addWarning("log_err_isactive");
	 	} else {
   		$active = md5(uniqid(rand(), true));
   		$query="UPDATE `auth` SET active='$active' WHERE username="._esc($row['user_email'])." LIMIT 1";
       	unset($row['active']);

   		if(ShopDB::query($query) and ShopDB::affected_rows()==1){
         	User::sendActivationCode($row, $active);
          addNotice('act_email_sent');
         	return true;
   		} else {
   		  addWarning("log_err_wrong_usr");
      }
   	}
	}

  public function sendActivationCode($row, $active){
  	require_once('classes/model.template.php');
    global $_USER_ERROR, $_SHOP;
    // new part
    $email = $data['user_email'] ;
    if (!$tpl = Template::getTemplate('Signup_email')) {
      return false;
    }
    $activation = base64_encode("{$row['user_id']}|".date('c')."|$active");
    $row['link']=$_SHOP->root."activation.php?uar=".urlencode($activation);
    $row['activate_code'] = $activation;
    $row['action']='Signup_email';
    //New Mailer

    if(Template::sendMail($tpl,$row)){
      return true;
    } else {
      addWarning("log_err_mailnotsend");
    }
  }

  function is_logged (){
    return (!isset($_SESSION['_SHOP_USER']))? 0: 1;
  }

  function CheckValues (&$data, $status=1, $mandatory=array(), $secure='', $short=true) {
    if (!isset($data['user_id']) and !(in_array($status, array(1,4)))) {
      $mandatory[]='check_condition';
    }
    if (!(in_array($status, array(1)))) {
      $mandatory[]='user_firstname';
    }
    parent::CheckValues ($data, $mandatory);

    if(!empty($data['user_email'])){
      if(!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $data['user_email'])){
        addError('user_email','not_valid_email');
      }
    }

    User::check_NoSpam($secure, $data);

    if (!$short and $status==2) {

      if(empty($data['password1'])) {
        if (empty($data['user_id'])){
          addError('password','mandatory');
        }
      } elseif (empty($data['password2'])) {
        addError('password','pwd_second_missing');
      } elseif (empty($data['password2'])) {
        addError('password','pwd_second_missing');
      } elseif (strlen($data['password1'])<=4) {
        addError('password','pwd_to_short');
      } elseif ($data['password1']!=$data['password2']) {
        addError('password','pwd_not_thesame');
      }
      if (!empty($data['user_id']) and empty($data['old_password'])){
        addError('old_password','mandatory');
      }
    }

    return !hasErrors();
  }

  function delete() {
    $query = "SELECT count(*)
              FROM `Order`
              Where order_user_id="._esc($this->user_id)."
              or    order_owner_id="._esc($this->user_id);
    //var_dump($res = ShopDB::query_one_row($query, false));
    if (!($res = ShopDB::query_one_row($query, false)) || (int)$res[0]) {
      return addWarning('in_use_order');
    }
    if ($this->user_status == 1) {
      $query = "SELECT count(*)
                FROM `Admin`
                Where admin_user_id="._esc($this->user_id);
      //var_dump($res = ShopDB::query_one_row($query, false));
      if (!($res = ShopDB::query_one_row($query, false)) || (int)$res[0]) {
        return addWarning('in_use_admin');
      }
    }
    $query = "SELECT count(*)
              FROM `adminlink`
              Where adminlink_pos_id="._esc($this->user_id);
    //var_dump($res = ShopDB::query_one_row($query, false));
    if (!($res = ShopDB::query_one_row($query, false)) || (int)$res[0]) {
      return addWarning('in_use_adminlink');
    }
    return parent::delete();
  }

  function _fill(&$arr , $nocheck=true)  {
    if ($this->user_status == 1) {
      $arr["user_lastname"] = $arr["kasse_name"];
    }
    return parent::_fill($arr , $nocheck);
  }

  function cleanup($user_id = 0, $delete=false, $inclTrash=true){
    $where= ($user_id)?"and user_id = ".(int)$user_id:"" ;
    $trash= (!$delete and $inclTrash)?"and order_status<>'trash'":"";
		$query="select user_id
						from `User` left join `Order` on (order_user_id = user_id $trash)
						where user_status= 3
            $where
            group by user_id
            having count(order_id)=0";

    if($result=ShopDB::query($query)) {
  	  $count=shopDB::num_rows($result);
  	  If ($delete) {
        while($data=shopDB::fetch_assoc($result)){
          //  print_r($data);
            ShopDB::query("delete from `User` where user_id =".$data["user_id"] );
        }
      }
      return $count;
    }
    return -1;
  }

  function check_NoSpam($secure, $data) {
    If (!empty($secure)) {
      if (empty($data[$secure])) {
        addError($secure,'mandatory');
      }
      elseif ($_SESSION['_NoSpam'][$secure] <> md5(strtoupper ($data[$secure]))) {
        addError($secure,'invalid');
      }
    }
  }

  function forgot_password($email){
    global $_SHOP;
    require_once('classes/model.template.php');
    if (empty($email)) {
      addError('email', 'mandatory');
      return false;
    }
    $query="SELECT * from auth left join User on auth.user_id=User.user_id where auth.username="._esc($email);
    if(!$row=ShopDB::query_one_row($query)){
      addWarning('username_not_found');
      return FALSE;
    }

    $pwd = substr( base_convert(md5(uniqid(rand())),15,36),0,8);
    $pwd_md5=md5($pwd);

    $query="UPDATE auth SET password="._esc($pwd_md5)." WHERE user_id="._esc($row['user_id'])." limit 1";

    if(ShopDB::query($query) and ShopDB::affected_rows()==1){

      $tpl=Template::getTemplate('forgot_passwd');
//      $row = $this->values;
      $row['new_password']=$pwd;
      $row['action']='forgot_passwd';

      if(Template::sendMail($tpl,$row,"",$_SHOP->lang)){
        addNotice('pwd_is_sent');
        return true;
      } else {
        addWarning('cant_send_email');
      }
    } else {
        addWarning('cant_set_new_password');
    }
    return FALSE;
  }

  public function currentTickets($user_id,$status){
    require_once('classes/model.seat.php');

    $options['seat_user_id'] = $user_id;
    $options['status'] = $status;

    return Seat::getCount($options);
  }

  static function log_user($user_id){ //Added by Lxsparks 05/11
    $query="UPDATE User SET user_lastlogin=now() WHERE user_id="._esc($user_id);
    if(!$res=ShopDB::query($query)){
      user_error(shopDB::error());
      return false;
    }
    return true;
  }
}

function convMandatory($mandatory_l){
	if(!empty($mandatory_l)){
		if(preg_match_all('/\w+/',$mandatory_l,$matches)){
			$mandatory=$matches[0];
		}
	}
return $mandatory;
}


function check_email_mx($email){
    if(preg_match('#.+@(?<host>.+)#',$email,$match) > 0 and getmxrr($match['host'],$mxhosts)){
        // mx records gevonden

        $valid = false;

        // mx records overlopen op zoek naar een geldige
        while($host = next($mxhosts) and !$valid){
            // een IPv4 of IPv6 adres volstaat
            $valid = checkdnsrr($host, 'A') or checkdnsrr($host,'AAAA');
        }

        return $valid;
    }

    // geen geldig mail adres wegens geen
    // correcte hostname of geen mx records
    return false;
}

?>