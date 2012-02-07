<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */
class plugin_ACL_manager extends baseplugin {

	public $plugin_info		  = 'ACL Adminitration Manager plugin';
	/**
	 * description - A full description of your plugin.
	 */
	public $plugin_description	= 'This plugin is used to handle the Admin ACL.';
	/**
	 * version - Your plugin's version string. Required value.
	 */
	public $plugin_myversion		= '0.0.4';
	/**
	 * requires - An array of key/value pairs of basename/version plugin dependencies.
	 * Prefixing a version with '<' will allow your plugin to specify a maximum version (non-inclusive) for a dependency.
	 */
	public $plugin_requires	= null;
	/**
	 * author - Your name, or an array of names.
	 */
	public $plugin_author		= 'The FusionTicket team';
	/**
	 * contact - An email address where you can be contacted.
	 */
	public $plugin_email		= 'info@fusionticket.com';
	/**
	 * url - A web address for your plugin.
	 */
	public $plugin_url			= 'http://www.fusionticket.org';

  public $plugin_priority = 1;

  public $plugin_actions  = array ('install','uninstall','protected');

  private $acl;
  private $acl_loaded = false;

  function init() {
    require_once(CLASSES.'class.acl.php'); //echo "[ACL_init]";
    $this->acl = new Awf_Acl();
    parent::init();
  }
/*
  function doAdminMenuItems($menu) {
     //print_r($menu);
     $menu['index2.php'] = 'ACL_menager';
     plugin::call('AddACLResource','ACL_menager', 'admin' );
     return $menu;
  }
*/
  function doLoadACL() {
 // echo "[ACL_load]";
    if ($this->acl_loaded) return true;
    $this->acl->add(new Awf_Acl_Role('control'))
              ->add(new Awf_Acl_Role('pos'))
              ->add(new Awf_Acl_Role('posman'))
              ->add(new Awf_Acl_Role('organizer'))
              ->add(new Awf_Acl_Role('admin'))
              ->add(new Awf_Acl_Role('adminz'));

    if (!$this->acl->hasResources()) {
    // Setup the list of roles.
    // 'admin','organizer','control','pos'
      $this->acl->add('control')
                ->add('pos')
                ->add('posman')
                ->add('organizer')
                ->add('admin')->add('adminz');
    }

    // Vertel de Awf_Acl instantie welke role wat mag
    $this->acl->allow('control','control')
              ->extend('pos','control')
                 ->allow('pos','pos')
                 ->allow('pos','shop')
                 ->allow('pos','view')
              ->extend('posman','pos')
                 ->allow('posman','posman')
              ->extend('organizer','control')
                 ->allow('organizer','organizer')
              ->extend('admin', 'organizer')
                 ->allow('admin','admin')
      ->extend('adminz', 'admin')
      ->allow('adminz','adminz')
      ;
    return $this->acl_loaded = true;
  }

  function doisAllowedACL($Role,$Resource) {
    plugin::call('%LoadACL');
    return $this->acl->isAllowed($Role,$Resource);
  }

  function doisACL() {
    return plugin::call('%LoadACL');
  }
  function doGetRolesACL($roles = array()){
    if (!is_array($roles)) $roles = array();
    $return = array_merge($roles, $this->acl->getRolenames());
    return $return;
  }

  function doAddACLResource($name, $role='') {
     plugin::call('%LoadACL');
     $this->acl->add($name);
     If (!empty($role)) {
       $this->acl->allow($role, $name);
     } else {
       $x = $this->acl->getRolenames();
       $this->acl->allow(reset($x), $name);
     }
     return '';
  }

  function doACLShow() {
    print_r($this->acl);
    return true;
  }
}

?>