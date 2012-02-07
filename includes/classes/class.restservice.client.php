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


class RestServiceClient {

	private $url; //the URL we are pointing at

	private $data = array(); //data we are going to send
	private $response; //where we are going to save the response

	public function __construct($url) {
		$this->url = $url;
	}

	//get the URL we were made with
	public function getUrl() {
		return $this->url;
	}

	//add a variable to send
	public function __set($var, $val) {
		$this->data[$var] = $val;
	}

	//get a previously added variable
	public function __get($var) {
		return $this->data[$var];
	}

	public function excuteRequest() {
    global $_SHOP;

    $this->siteUrl = $_SHOP->root;
    $this->siteVersion = CURRENT_VERSION;

		//work ok the URI we are calling
		$uri = $this->url;
    $postData = $this->getPostData();

    //set timeout so that you wont be waiting forever if our server is under heavy load.
    $ctxOpt = array(
      'http' => array(
        'method' => 'POST',
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n".
          "Content-Length: " . strlen($postData) . "\r\n",
        'content' => $postData,
        'timeout' => 2
        )
      );
    if(!empty($_SHOP->shopconfig_proxyaddress) && !empty($_SHOP->shopconfig_proxyport)){
      $ctxOpt['http']['proxy'] = "tcp://".$_SHOP->shopconfig_proxyaddress.":".$_SHOP->shopconfig_proxyport;
    }
    $ctx = stream_context_create($ctxOpt);

		//get the URI trapping errors
		$result = @file_get_contents($uri,false,$ctx);

		// Retrieve HTTP status code
		list($httpVersion, $httpStatusCode, $httpMessage) = explode(' ', $http_response_header[0], 3);

		//if we didn't get a '200 OK' then thow an Exception
		if ($httpStatusCode != 200) {
			throw new Exception('HTTP/REST error: ' . $httpMessage. " ({$httpStatusCode})");
		} else {
		 return	$this->response = $result;
		}
	}

	public function getResponse() {
		return $this->response;
	}

  public function getArray(){
    require_once("class.xml2php.php");
    return Xml2php::xml2array($this->getResponse());
  }

  public static function example(){

    $rws = new RestServiceClient('http://localhost/ft/cpanel/versions/latest.xml');
    //$rws->query = 'Donnie Darko';
    //$rws->results = 8;
    //$rws->appid = 'YahooDemo';
    $rws->excuteRequest();
    $rws->getResponse();
    return $rws->getArray();

  }

	//turn our array of variables to send into a query string
	protected function getQueryString() {
		$queryArray = array();

		foreach ($this->data as $var => $val) {
			$queryArray[] = $var . '=' . urlencode($val);
		}

		$queryString = implode('&', $queryArray);

		return '?' . $queryString;
	}

  /**
   * @author Christopher Jenkins
   * used for posting data
   */
  protected function getPostData(){
    $queryArray = array();

    foreach ($this->data as $var => $val) {
      $queryArray["{$var}"] = $val;
		}

    return http_build_query($queryArray,null,"&");
  }

  static function encrypt($string, $key) {
    srand((double) microtime() * 1000000); //for sake of MCRYPT_RAND
    $key = md5($key); //to improve variance
    /* Open module, and create IV */
    $td = mcrypt_module_open('rijndael-256', '','cfb', '');
    $key = substr($key, 0, mcrypt_enc_get_key_size($td));
    $iv_size = mcrypt_enc_get_iv_size($td);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    /* Initialize encryption handle */
    if (mcrypt_generic_init($td, $key, $iv) != -1) {

      /* Encrypt data */
      $length = strlen($string);
      $string = $length.'|'.$string;

      $c_t = mcrypt_generic($td, $string);
      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);
      $c_t = $iv.$c_t;
      return $c_t;
    } //end if
  }

  static function decrypt($string, $key) {
    $key = md5($key); //to improve variance
    /* Open module, and create IV */
    $td = mcrypt_module_open('rijndael-256', '','cfb', '');
    $key = substr($key, 0, mcrypt_enc_get_key_size($td));
    $iv_size = mcrypt_enc_get_iv_size($td);
    $iv = substr($string,0,$iv_size);
    $string = substr($string,$iv_size);
    /* Initialize encryption handle */
    if (mcrypt_generic_init($td, $key, $iv) != -1) {

      /* Encrypt data */
      $c_t = mdecrypt_generic($td, $string);
      list($length, $padded_data) = explode('|', $c_t, 2);
      $c_t = substr($padded_data, 0, $length);

      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);
      return $c_t;
    } //end if
  }

  static function deCryptJSON($json, $checksom, $token){
  global $localData;
  if (empty($json)) {
    header('HTTP/1.1 400 datablock not filled correctly');
    die;
  } elseif (!RestServiceClient::isBase64($json)) {
    header('HTTP/1.1 400 result not a valid value');
    die;
  } else {
    $json = base64_decode($json);
    if (sha1($json) !== $checksom) {
      header('HTTP/1.1 400 Checksom failed');
      die;

    } else {
      try {
        $json = RestServiceClient::decrypt($json, $token);
      }catch(Exception $e){
        header('HTTP/1.1 400 Decryption faild');
        die (print_r($e));
      }
      $json = json_decode($json,true);
      if ($err = RestServiceClient::json_error() ) {
        header('HTTP/1.1 400 json: '.$err);
        die;
      }
      return $json;
    }
  }
  return false;
}
  static function json_error(){
    switch(json_last_error()) {
      case JSON_ERROR_DEPTH:
        return 'Maximum stack depth exceeded';
        break;
      case JSON_ERROR_CTRL_CHAR:
        return 'Unexpected control character found';
        break;
      case JSON_ERROR_SYNTAX:
        return 'Syntax error, malformed JSON';
        break;
      case JSON_ERROR_NONE:
        return false;//  ' - No errors';
        break;
      default:
        return 'Unknown error: '.json_last_error();
    }
  }

  static function isBase64($data)
  {
    if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
}
?>