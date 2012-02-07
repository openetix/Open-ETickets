<?PHP

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */
class plugin extends model {
  protected $isLocked = false;
  protected $_idName    = 'plugin_id';
  protected $_tableName = 'plugins';
  protected $_columns   = array( '#plugin_id', '*plugin_name', '*plugin_version','*plugin_enabled',
                                 '*plugin_protected', 'plugin_settings', '*plugin_priority');
  protected $_plug = null;
  public $plugin_id = 0;

  static function load($plugin_name) {
    $query = "select * from `plugins`
              where plugin_name = "._esc($plugin_name);
    if($plug_d=ShopDB::query_one_row($query)) {
      $plugin = new plugin;
      $plugin->_fill($plug_d, false);
      $plugin->_unser_extra();
      return $plugin;
    } elseif (file_exists(INC.'plugins'.DS.'plugin.'.$plugin_name.'.php')) {
      $plugin = new plugin;
      $plugin->plugin_name = $plugin_name;
      $plugin->plug($plugin_name);
      //print_r($event);
      return $plugin;
    }
    return null;
  }

  static function loadAll($allrecord=true ) {
    $query = "select * from `plugins`
              where ".($allrecord?'1=1':'plugin_enabled=1')."
              order by plugin_priority,plugin_name ";
    $plugins = array();
    if($res=ShopDB::query($query)) {
      while($plug_d=shopDB::fetch_assoc($res)){
        if (file_exists(INC.'plugins'.DS.'plugin.'.$plug_d['plugin_name'].'.php')){
          $plugin = new plugin;
          $plugin->plug($plug_d['plugin_name'],$plug_d['plugin_id'] );
          $plugin->_fill($plug_d, false);
          $plugin->_unser_extra();
          $plugins[$plug_d['plugin_name']] = $plugin;
        }
      }
    }
    if ($allrecord) {
      $dir = INC .'plugins';
  	  if ($handle = opendir($dir)) {
  		  while (false !== ($file = readdir($handle))){
          if (!is_dir($dir.$file) && preg_match("/^plugin.(.*?\w+).php/", $file, $matches)) {
            $content = $matches[1];
            if (!isset($plugins[$content])) {
              $plugin = new plugin(true);
              $plugin->plugin_name = $content;
              $plugin->plugin_id = 0;
              $plugin->plug($content);
              $plugins[$content] = $plugin;
            }
          }
        }
  		  closedir($handle);
    	}
    }
    return $plugins;
  }

	private function plug($plugname='',$id=0) {
	  if (empty($plugname)) return;
    if (!isset($this->_plug)){
  		$file = INC.'plugins'.DS.'plugin.'.$plugname.'.php';
  		if (file_exists($file)){
        require_once ($file);
    		$name = 'plugin_'. $plugname;
        $this->_plug = new $name($this, $id);
        $vars = get_object_vars($this->_plug);
        foreach ($vars as $key => $value) {
          if (strpos($key, 'plugin_') ===0) {
            $this->$key = $value;
          }
        }

    		return $this->_plug;
      }
		} else {
      return $this->_plug;
  	}
  }

  function save($id = null, $exclude= false){
		$this->_ser_extra();
    return parent::save($id, $exclude);
  }

  static function call($eventname) {
    global $_SHOP;
    $pluginname = false;
    if (is_array($eventname)) {
      list($pluginname,$eventname) = $eventname;
    }
    $type= substr($eventname,0,1);
    $args = func_get_args();
  	array_shift($args);// print_r($args);

    if (strpos('_%!?*',$type ) !== false) {
      $eventname = substr($eventname,1);
      switch ($type){
        case '_':
           $return = $args[0];
           break;
        case '%':
           $return = false;
           break;
        case '!':
           $return = true;
           break;
        case '?':
           $return = array();
           break;
        case '*':
           $return = null;
           break;
       default :
          $return = '';
      }

    } else $return = null;

    $plugins = false;
    if ($pluginname) {
      if (($plug = plugin::load($pluginname)) && in_array('named', $plug->plugin_actions)) {
        $plugins[$pluginname] = $plug;
        $eventname = 'action'.ucfirst($eventname);
      }
    } else {
      if (!isset($_SHOP->plugins)) {
        $_SHOP->plugins = & plugin::loadAll(false);
      }
      $plugins   = & $_SHOP->plugins;
      $eventname = 'do'.ucfirst($eventname);
    }
    if (!is_array($plugins)) return $return;

    foreach ($plugins as $key => $plugin) {
      $plugin = $plugin->_plug;
      //print_r(get_class_methods(get_class($plugin)));
      if (method_exists($plugin, $eventname )) {
        $ret = call_user_func_array(array($plugin, $eventname ),$args) ;
      //  echo $ret;
        switch ($type){
          case '_':
             $return  = $ret;
             $args[0] = $ret;
             break;
          case '%':
             $return = $return || $ret;
             break;
          case '!':
             if (!$ret) return false;
             break;
          case '?':
             $return[$key] = $ret;
             break;
          case '*':
        		 if( !is_null( $ret ) ) {
        			 return $ret;
             }
             break;
          default:
            $return .= (string)$ret;
        }
      }
    }
    return $return;
  }

  static function getTables( $tbls= null ){
    if(is_null($tbls)){
      include (INC.'install'.DS.'install_db.php');
    }
    $plugins = self::loadAll(true);
    foreach($plugins as $key => $plugin) {

 //     var_dump($plugin->plugin_id);
  //    var_dump($plugin->plugin_name);
      if (!is_array($plugin->plugin_actions)) {
        continue;
      }

      if (!$plugin->plugin_id && !in_array('install',$plugin->plugin_actions)) {
        $plugin->install(false);
      }
      if ($plugin->plugin_id || !in_array('install',$plugin->plugin_actions)) {
        if (!is_object($plugin->_plug)) { continue; }
        $plugin->_plug->getTables($tbls );
      }
    }
    return $tbls;
  }

  function install($updateDB= true) {
    if (!is_object($this->_plug)) { return false; }

    if ($this->_plug->install()) {
      $this->plugin_version = $this->plugin_myversion;
      $this->plugin_enabled = 1;
      if (!$this->save()) {
        addwarning('cant_save_given_plugin');
        return false;
      } elseif ($updateDB) {
        $tbls = self::getTables();
        ShopDB::DatabaseUpgrade($tbls);
      }
      return true ;

    }
  }

  function upgrade() {
    if ($this->_plug->upgrade()) {
      $this->plugin_version = $this->plugin_myversion;
     return $this->save();
    }
  }

  function uninstall() {
    if ($this->_plug->uninstall()) {
      $this->delete();
    }
  }
  function config($page) {
    return $this->_plug->config($page);
  }

  function _ser_extra(){

    if ($this->_plug) {
      $extra = array();
      foreach($this->_plug->extras as $key) {
        self::getFieldtype($key);
        $extra[$key] = is($this->$key, null);
      }
      $this->plugin_settings=serialize($extra);
    }
  }

  function _unser_extra(){
    if (!$this->_plug) return;
    if(!empty($this->plugin_settings)){
      $extra=unserialize($this->plugin_settings);
    } else {
      $extra= array();
    }
    foreach($this->_plug->extras as $key) {
      self::getFieldtype($key);
      if(isset($extra[$key])){
        $this->$key = $extra[$key];
      }
    }
  }

	function CheckValues(&$data){
    if ($this->_plug) {
 			$this->_plug->CheckValues($data);
    }
		return parent::CheckValues($data);
	}

  function _fill (& $data, $nocheck=true){
    if (!empty($data['plugin_name'])) $this->plug($data['plugin_name']);
    $ok = parent::_fill($data, $nocheck);
    if ($ok && $this->_plug) {
      foreach($this->_plug->extras as $key){
        $this->_plug->extra[$key] = is($data[$key], null);
      }

    }
    if ($this->_plug && !$this->_plug->isInit) $this->_plug->init();
    return $ok;
  }
}

/**
 * Base class that implements basic plugin functionality
 * and integration with MantisBT. See the Mantis wiki for
 * more information.
 * @package MantisBT
 * @subpackage classes
 */
abstract class basePlugin {
  public $plugin;
  public $extras    = array();
  public $extra    = array();

	/**
	 * name - Your plugin's full name. Required value.
	 */
  public $plugin_acl  		= null;
  public $plugin_info		= null;
	/**
	 * description - A full description of your plugin.
	 */
	public $plugin_description	= null;
	/**
	 * version - Your plugin's version string. Required value.
	 */
	public $plugin_myversion		= null;
	/**
	 * requires - An array of key/value pairs of basename/version plugin dependencies.
	 * Prefixing a version with '<' will allow your plugin to specify a maximum version (non-inclusive) for a dependency.
	 */
	public $plugin_requires	= null;
	/**
	 * author - Your name, or an array of names.
	 */
	public $plugin_author		= null;
	/**
	 * contact - An email address where you can be contacted.
	 */
	public $plugin_email		= null;
	/**
	 * url - A web address for your plugin.
	 */
	public $plugin_url			= null;
  //
  public $plugin_actions = array ('config','install','uninstall','priority','enable','protect','named');

  private $isInit = false;

	### Core plugin functionality ###
	final public function __construct( $p_base, $id ) {
    $this->plugin = $p_base;
	  $this->plugin_acl = (int)$id << 8;
//	  var_dump($this);
	}

	/**
	 * this function allows your plugin to set itself up, include any necessary API's, declare or hook events, etc.
	 * Alternatively, your can plugin can hook the EVENT_PLUGIN_INIT event that will be called after all plugins have be initialized.
	 */
	public function init() {}

  /**
	 * return an array of default configuration name/value pairs
	 */
	public function config() {
		return '';
	}

  public function getTables(& $tbls) {}

	public function install() {
		return true;
	}

	/**
	 * This callback is executed after the normal schema upgrade process has executed.
	 * This gives your plugin the chance to convert or normalize data after an upgrade
	 */
	public function upgrade( ) {
		return true;
	}

	/**
	 * This callback is executed after the normal uninstallation process, and should
	 * handle such operations as reverting database schemas, removing unnecessary data,
	 * etc. This callback should be used only if Mantis would break when this plugin
	 * is uninstalled without any other actions taken, as users may not want to lose
	 * data, or be able to re-install the plugin later.
	 */
	public function uninstall() {
    return true;
	}

  function __get($name) {
    if ($this->plugin and ($result = $this->plugin->$name)) {
      return $result;
    } else {
      return false;
    }
  }

	function __set($name, $value) {
		if ($this->plugin) {
	  		return $this->plugin->$name = $value;
		} else {
	  		return false;
		}
	}

  public function CheckValues(&$arr){
    foreach($this->extras as $key){
      if (model::getFieldtype($key) & model::MDL_MANDATORY) {
        if ((!isset($arr[$key]) || $arr[$key]=='') && ( (!isset($this->plugin->$key) || ($this->plugin->$key=='')))) {
          addError($key, 'mandatory');
        }
      }
    }
  }
}

?>