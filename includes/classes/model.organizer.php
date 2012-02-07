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
class Organizer  Extends Model {
  protected $_idName    = '';
  protected $_tableName = 'Organizer';
  protected $_columns   = array( '*organizer_name', '*organizer_address', '*organizer_ort',
                                 '*organizer_plz', 'organizer_country', 'organizer_state',
                                 'organizer_fax', '*organizer_email', 'organizer_place',
                                 'organizer_phone','*organizer_currency', 'organizer_logo');

  public static function load ($dummy = 0){
    $query = "select * from Organizer limit 1";
    if ($row = ShopDB::query_one_row($query)){
      $org = new Organizer();
      $org->_fill($row);
      return $org;
    }
  }

  function save($id = null, $exclude=null){
    return parent::update($exclude);
  }

  function saveEx($id = null, $exclude=null){
    if($id = parent::saveEx($id, $exclude)){
      $this->fillFilename($_POST, 'organizer_logo');
    }
    return $id;
  }

  function CheckValues(&$arr){
   //if(empty($data['user_email'])){$err['user_email']=mandatory;}
    if($email=$arr['organizer_email']){
      $check_mail = preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i',$email );
      if(!$check_mail){
        addError('organizer_email','not_valid_email');
      }
    }
    return parent::checkValues($arr);
  }

  function _fill(&$arr, $nocheck=true){
    return parent::_fill($arr, $nocheck);
  }

  function delete(){
    return addWarning('cant_delete_organizer');
  }
}
?>