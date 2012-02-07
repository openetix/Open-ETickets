<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */
/**
 *
 *
 */
class config extends model{
  protected $_idName    = 'config_id';
  protected $_tableName = 'configuration';
  protected $_columns   = null;

  /**
   * Constructor
   */
  function __construct($filldefs= false){
    if (!isset($_SESSION['CONFIG_FIELDS'])) {
      $this->loadConfig();
    }
    $this->_columns = $_SESSION['CONFIG_FIELDS']['fields'];
   // var_dump($this);

    parent::__construct(false);
  }

  static function loadConfig () {
    $tmp = mySql::query("select * from configuration order by config_weight");
    $cfg = array('fields'=>array(),'input'=>array());
    while ($row = mySql::fetch_assoc($tmp)) {
      $cfg['fields'][]=$row['config_required'].'$'.$row['config_field'];
      $cfg['input'][$row['config_group']][$row['config_field']]=array(unserialize($row['config_type']),unserialize($row['config_extra']));
    }
    if (!$cfg['fields']) {
      self::fillConfig(null);
    } else
      $_SESSION['CONFIG_FIELDS'] = $cfg;
  }

  static function fillConfig($config=null, $replace=false) {
    global $config;
    if (!$config) {
      include('data.config.php');
    }
    $tmp = mySql::query("select config_id, config_field from configuration");
    $cfg = array();
    while ($row = mySql::fetch_row($tmp)) {
       $cfg[$row[1]]=$row[0];
    }
  //  mysql::freeResult($tmp);
    $query ='';
    $cfg2 = array('fields'=>array(),'input'=>array());
    $weight = 0;
    foreach ($config as $group => $sub ){
      foreach ($sub as $key => $rec){
        $query  = '';
        if (is_string($rec) && isset($cfg[$key])) {
            $rec = explode('|',$rec);
            $query  = "Update configuration set config_field ="._esc($rec[1]).", config_group ="._esc($rec[0])." where config_id="._esc($cfg[$key]).";\n";
        } elseIf (is_array($rec) && count($rec)>=1) {
          $fields= array();

          if (count($rec)==1) $rec[1]='text';
          if (count($rec)==2) $rec[2]='';
          if (count($rec)==3) $rec[3]='';
          $isnew = !isset($cfg[$key]);
          If (!isset($cfg[$key])) {
            $query = "insert into configuration set ";
            $fields[] = "config_group = "._esc($group);
            $fields[] = "config_field = "._esc($key);
          } else{
            $query = "Update configuration set ";
          }
          $fields[] = "config_weight = "._esc( $weight++ );

          $fields[] = "config_type = "._esc(serialize($rec[1]));
          $fields[] = "config_required = "._esc($rec[2]);
          $fields[] = "config_extra = "._esc(serialize($rec[3]));
          if ($replace || $isnew) {
            $fields[] ="config_value = "._esc(serialize($rec[0]));
          }
          $query .= implode(', ', $fields);
          If (isset($cfg[$key])) {
            $query .= " where config_id ="._esc($cfg[$key]);
          }
          if ($query) {
            MySQL::query($query);
          }
        }
        $cfg2['fields'][]=$rec[2].'$'.$key;
        $cfg2['input'][$group][$key]=array($rec[1], $rec[3]);

      }
    }

    $_SESSION['CONFIG_FIELDS'] = $cfg2;
  }



  static function ApplayConfig() {
    global $_CONFIG;
    $tmp = MySQL::query("select config_field, config_value from configuration");
    while ($row = MySQL::fetch_row($tmp)) {
      $val = unserialize($row[1]);
      if (!is_null($val)) {
        $_CONFIG->$row[0] = $val;
      }
    }
  //  MySQL::freeResult($tmp);
  }

  static function LoadAsArray($group) {
    $query = "select config_field, config_value from configuration ";
    if ($group) {
      $query .=  "where config_group = "._esc($group);
    }
    $tmp = MySQL::query($query);
    $cnf = array();
    while ($row = MySQL::fetch_row($tmp)) {$cfg[$row[0]]=unserialize($row[1]);}
    MySQL::freeResult($tmp);
    return $cfg;
  }

  static function GroupExists($group)
  {
    $tmp = "select count(*) from configuration  where config_group = "._esc($group);
    $c   = MySQL::query_one_row($tmp);
    return !empty($c[0]);
  }

  function fillPost($nocheck=false){
    if (!$nocheck) {
      $arr = $_POST;
      $this->fillArrays($arr, false);
      return $this->_fill($arr,$nocheck);
    } else
      parent::fillPost($nocheck);
  }

  function fillArrays(&$arr, $toString= false){

    foreach($this->_columns as $fieldname){
      if (self::getFieldtype($fieldname) & self::MDL_ARRAY) {
        if ($toString && is_array(is($arr[$fieldname],false))) {
          $result = '';
          $values = $arr[$fieldname];
          foreach($values as $key =>$value) {
            if (!is_numeric($key)) {$result .= "{$key}=";}
            $result .= $value.";";
          }
          $arr[$fieldname] = $result;
        } elseif (!$toString && is_string($arr[$fieldname])) {
          $values = str_replace("\n" ,'' ,$arr[$fieldname] );
          $values = explode(';',$values);
          $result = array();
          foreach($values as $value){
            if (!empty($value)) {
              if (strpos($value, '=')===false) {
                $result[] = $value;
              } else {
                list($id,$value) = explode('=',$value);
                $result[$id] = $value;
              }
            }
          }
          $arr[$fieldname] = $result;
        }
      }
    }
  }

  function Save() {
    $tmp1="select config_id, config_required, config_field from configuration";
    $tmp = MySQL::query($tmp1);
    $tmp2 = '';
    MySQL::Begin('Save config');
    while ($row = MySQL::fetch_assoc($tmp)) {
      $key = $row['config_required'].'$'.$row['config_field'];
      if (($val= $this->_set($key, '~~~'))) {
//        $val  = serialize($val);
        if (!MySQL::query("Update configuration set config_value={$val} where config_id="._esc($row['config_id']))) {
          return self::_abort('cant_save_configvalue', $key);
        }
      }
    }
    mySql::commit('Config saved');

    return true;
  }
}

?>