<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */
class plugin_Custom_OrderDescription extends baseplugin {

	public $plugin_info		  = 'OrderDescription plugin';
	/**
	 * description - A full description of your plugin.
	 */
	public $plugin_description	= 'This plugin allows you to change the order description at the payment handler';
	/**
	 * version - Your plugin's version string. Required value.
	 */
	public $plugin_myversion		= '0.0.1';
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

  public $plugin_actions  = array ('install','uninstall');

  function doOrderDiscription($order, $discription) {
    if (!defined('OrderDiscriptionfor')) {
      define('OrderDiscriptionfor','Order payment for order# :');
    }
    return con('OrderDiscriptionfor').$order->order_id;
  }

}

?>