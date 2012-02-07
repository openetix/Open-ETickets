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
require_once("admin/class.adminview.php");

class PluginsView extends AdminView{

  function table (){
    global $_SHOP;
    $plugins = plugin::loadAll();
    $alt=0;
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
    echo "<input type='hidden' name='action' value='update'/>\n";
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4' border=0>\n";
    echo "<tr><td class='admin_list_title' colspan='5' align='left'>".con('plugins_title')."</td>";
    echo "</tr>\n";

    $showSave =false;
    foreach($plugins as $row){
      echo "<tr class='admin_list_row_$alt'>";
      echo "<td class='admin_list_item' width='150' >";
      if ($row->plugin_id && in_array('config',$row->plugin_actions)) {
        echo "<a href='{$_SERVER['PHP_SELF']}?action=config&plugin_name={$row->plugin_name}'>{$row->plugin_info} {$row->plugin_myversion}</a>";
      } else {
        echo "{$row->plugin_info} {$row->plugin_myversion}";
      }
      echo "</td>\n";
      echo "<td class='admin_list_item' align='left' nowrap><nowrap>";
      echo "{$row->plugin_description}<br>";
      echo con('plugin_email')." <a href='mailto:{$row->plugin_email}'>{$row->plugin_author}</a> <br>";
      echo con('plugin_web')." <a href='{$row->plugin_url}'>{$row->plugin_url}</a></nowrap> </td>";
      echo "<td class='admin_list_item' width='65' align='center' nowrap><nowrap>";
      if ($row->plugin_id  && in_array('priority',$row->plugin_actions)) {
        $sel = array($row->plugin_priority => 'selected="selected"');
        echo "<select name='priority[{$row->plugin_name}]'>
                 <option value='5' {$sel[5]}>5</option>
                 <option value='4' {$sel[4]}>4</option>
                 <option value='3' {$sel[3]}>3</option>
                 <option value='2' {$sel[2]}>2</option>
                 <option value='1' {$sel[1]}>1</option></select>";
        $showSave = true;
      }
      echo "&nbsp;</td>";
      echo "<td class='admin_list_item' width='65' align='center'>";
      if (!$row->plugin_id && in_array('install',$row->plugin_actions)) {
        echo $this->show_button("{$_SERVER['PHP_SELF']}?action=install&plugin_name={$row->plugin_name}","Install",1);
      } else {
        //echo version_compare($row->plugin_version, $row->plugin_myversion);
        if ($row->plugin_id && version_compare($row->plugin_version, $row->plugin_myversion)<0  && in_array('upgrade',$row->plugin_actions)) {
          echo $this->show_button("{$_SERVER['PHP_SELF']}?action=upgrade&plugin_name={$row->plugin_name}","Upgrade",1);
        }

        if ($row->plugin_id && in_array('uninstall',$row->plugin_actions)) {
          echo $this->show_button("javascript:if(confirm(\"".con('plugin_allow_uninstall')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=uninstall&plugin_name={$row->plugin_name}\";}","Uninstall",1,array('tooltiptext'=>"Unistall {$row->plugin_name}?"));
        }
      }

      echo "</td>\n";
      echo "</tr>\n";
      $alt=($alt+1)%2;
    }
    if ($showSave) {
      $this->form_foot(5);
    } else {
      echo "</table></form>";
    }
  }

  function form ($plugin, $data, $title, $add='add'){
    global $_SHOP;
    if (is_null($data)) $data = (array)$plugin;

    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
    echo "<input type='hidden' name='plugin_name' value='{$data['plugin_name']}'/>\n";
    echo "<input type='hidden' name='action' value='save'/>\n";
    echo "<input type='hidden' name='plugin_title' value='$title'/>\n";
    $this->form_head($title);
		$this->print_field_o( 'plugin_name', $data );
		$this->print_field_o( 'plugin_info', $data );
		$this->print_field_o( 'plugin_myversion', $data );
    if (in_array('priority',$data['plugin_actions'])) {
  		$this->print_select( 'plugin_priority', $data, $err, array('5', '4', '3', '2', '1') );
    }
    if (in_array('enable',$data['plugin_actions'])) {
   		$this->print_checkbox( 'plugin_enabled', $data, $err );
    }
    if (in_array('protect',$data['plugin_actions'])) {
   		$this->print_checkbox( 'plugin_protected', $data, $err );
    }
    $this->extra_form($plugin, $data, $err);
    $this->form_foot(2,$_SERVER['PHP_SELF']);
  }

  function draw () {
    global $_SHOP;

    if ($_POST['action'] == 'update') {
       $plugs = plugin::loadAll(true);
       foreach ($plugs as $key => $plugin) {
         if ( $plugin->plugin_id &&
              in_array('priority',$plugin->plugin_actions) &&
              isset($_POST['priority'][$key])  &&
              $plugin->plugin_priority <> $_POST['priority'][$key]) {
           $plugin->plugin_priority = $_POST['priority'][$key];
           $plugin->save();
         }
       }
       $this->table();
    } elseif ($_GET['action'] == 'install' && $_GET['plugin_name']){
      $adm = Plugin::load($_REQUEST['plugin_name']);
      if ($adm &&
          in_array('install',$adm->plugin_actions) &&
          $adm->install()  &&
          in_array('config',$adm->plugin_actions) ) {
         $this->form($adm, null, con('plugin_install_title'));
      } else $this->table();

    } elseif ($_GET['action'] == 'upgrade' && $_GET['plugin_name']){
      $adm = Plugin::load($_REQUEST['plugin_name']);
      if ($adm &&
          in_array('upgrade',$adm->plugin_actions) &&
          $adm->install()  &&
          in_array('config',$adm->plugin_actions) ) {
         $this->form($adm, null, con('plugin_upgrade_title'));
      } else $this->table();
    } elseif ($_GET['action'] == 'config' && $_GET['plugin_name']){
      $adm = Plugin::load($_REQUEST['plugin_name']);
      if ($adm &&
          in_array('config',$adm->plugin_actions) ) {
         $this->form($adm, null, con('plugin_config_title'));
      } else $this->table();
    } elseif ($_POST['action'] == 'save' && $_POST['plugin_name']) {
      $adm = Plugin::load($_REQUEST['plugin_name']);
      if ($adm &&
          in_array('config',$adm->plugin_actions) &&
          (!$adm->fillPost() || !$adm->saveEx())) {
        $this->form($adm, $_POST, $_POST['plugin_title']);
      } else
          $this->table();
    } elseif($_GET['action']=='uninstall' and $_GET['plugin_name']){
      $adm = Plugin::load($_REQUEST['plugin_name']);
      if ($adm &&
          in_array('uninstall',$adm->plugin_actions)) {
        $adm->uninstall();
      }
      $this->table();
    } else {
      $this->table();
    }
  }

  function extra_form($hand, &$data, &$err){
    Global $_SHOP;

    $extras = $hand->config($this);
    if ( $extras) {
      require_once(LIBS.'smarty3/Smarty.class.php');
      require_once('classes/smarty.gui.php');

      $smarty = new Smarty;
  //    $smarty->plugins_dir = array("plugins", INC . "shop_plugins".DS);
      $smarty->plugins_dir  = array("plugins".DS, $_SHOP->includes_dir . "shop_plugins".DS);

      $smarty->compile_id   = 'Adminplugin_'.$_SHOP->lang;
      $smarty->compile_dir  = substr($_SHOP->tmp_dir,0,-1); // . '/web/templates_c/';
      $smarty->cache_dir    = substr($_SHOP->tmp_dir,0,-1); // . '/web/cache/';
      $smarty->config_dir   = INC . 'lang'.DS;

      $gui   = new Gui_smarty($smarty);
      $gui->guidata   = $data;
      $gui->gui_name  = 'admin_name';
  	  $gui->gui_value = 'admin_value';

      $smarty->my_template_source = $extras;
      $smarty->display('string:'. $extras );

    }
  }

}
?>