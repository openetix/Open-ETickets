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

require_once("admin/class.adminview.php");

class PlaceMapCategoryView extends AdminView {
  function table ($pm_id, $live = false) {
    $alt = 0;
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2'>\n";
    echo "<tr><td class='admin_list_title' colspan='4' align='left'>" .con('categories'). "</td>\n";
    if (!$live) {//
      echo "<td colspan=5 align='right' valign='middle' >".$this->show_button("{$_SERVER['PHP_SELF']}?action=add_category&pm_id={$pm_id}","add",3)."</td>\n";// ".con('add')."
    }
    echo "</tr>\n";
    if ($cats = PlaceMapCategory::LoadAll($pm_id)){
      foreach($cats as  $category) {
        echo "<tr class='admin_list_row_$alt'>";
        echo "<td class='admin_list_item' width=10 bgcolor='{$category->category_color}'>&nbsp;</td>\n";
        echo "<td class='admin_list_item' width='50%'>{$category->category_name}</td>\n";
        echo "<td class='admin_list_item'>{$category->category_size} ".con('cat_at')." {$category->category_price} </td>\n";
        echo "<td class='admin_list_item'>" . con($category->category_numbering) . " </td>\n";

        echo "<td class='admin_list_item' width=65 align=right>";
        echo $this->show_button("{$_SERVER['PHP_SELF']}?action=edit_category&pm_id={$pm_id}&category_id={$category->category_id}","edit",2);
        echo $this->show_button("javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_category&pm_id={$pm_id}&category_id={$category->category_id}\";}","remove",2,
                                  array('tooltiptext'=>"Delete {$category->category_name}?",
                                        'disable'=>$live ));
        echo'</td></tr>';
        $alt = ($alt + 1) % 2;
      }
    }
    echo '</table>';
  }

  function form ($data, $err) {
    //print_r($data);
    if (!isset($data['event_status'])) {
      $query = "select event_status
                from `PlaceMap2` left join `Event` on pm_event_id = event_id
                where pm_id = "._esc($_REQUEST['pm_id']);
      $row = ShopDB::query_one_row($query);
      $data['event_status'] = $row['event_status'];
    }
    echo "<form action='{$_SERVER['PHP_SELF']}' method='post'>";
    echo "<input type=hidden name=action value=save_category>";
    if ($data['category_id']) {
       echo "<input type=hidden name=category_id value={$data['category_id']}>";
    } else {
      $data['category_pm_id'] =(isset($data['category_pm_id']))?$data['category_pm_id']:$_REQUEST['pm_id'];
      $pm = PlaceMap::load($_REQUEST['pm_id']);//print_r($pm);
      $data['category_event_id'] = $pm->pm_event_id;
    }
    echo "<input type=hidden name=category_pm_id value={$data['category_pm_id']}>";
    echo "<input type=hidden name=pm_id value={$data['category_pm_id']}>";
    echo "<input type=hidden name=category_event_id value={$data['category_event_id']}>";

    $this->form_head(con('categories'));

    $this->print_field_o('category_id', $data);
    $this->print_input('category_name', $data, $err, 30, 100);
    if (!$data['event_status'] or ($data['event_status'] != 'pub')) {
        $this->print_input('category_price', $data, $err, 6, 6);
    } else {
        $this->print_field('category_price', $data);
    }
    $this->print_select_tpl('category_template', $data, $err);
    $this->print_color('category_color', $data, $err);
//    $this->print_field('event_status', $data);
    if (!$data['event_status'] or ($data['event_status'] === 'unpub')) {
      $this->print_select('category_numbering', $data, $err, array('none', 'rows', 'seat', 'both'),'');
      $script = "
      $('#category_numbering-select').change(function(){
        if($(this).val() == 'none'){
          $('#category_size-tr').show();
        }else{
          $('#category_size-tr').hide();
        }
      });
      $('#category_numbering-select').change();";
      $this->addJQuery($script);
      $this->print_input('category_size', $data, $err, 6, 6);
    } elseif (($data['event_status'] === 'nosal') && empty($data['category_id'])) {
      echo "<input type='hidden' name='category_numbering' value='none'>";
      $this->print_input('category_nosale_size', $data, $err, 6, 6);
    } else {
      $this->print_field('category_numbering', $data);
      $this->print_field('category_size', $data);
      $taken = $data['category_size'] - $data['category_free'];
      $this->print_field('number_taken', $taken);
    }

    $this->print_area('category_data', $data, $err, 3, 40);

    if ($data['event_status'] == 'nosal' && $data['category_numbering'] == 'none'&& !empty($data['category_id'])) {
      $this->form_foot();
      echo "<br>";
      echo "<form action='{$_SERVER['PHP_SELF']}' method=post>";
      echo "<input type=hidden name=pm_id value={$data['category_pm_id']}>";
      echo "<input type=hidden name=category_id value={$data['category_id']}>";
      echo "<input type=hidden name=action value=resize_category>";
      $this->form_head(con('category_new_size_title'));
      $this->print_input('category_new_size', $data, $err, 6, 6);
    }
    $this->form_foot(2, "{$_SERVER['PHP_SELF']}?action=edit_pm&pm_id={$data['category_pm_id']}");
  }


  function draw () {
    if ($_GET['action'] == 'add_category' and $_GET['pm_id'] > 0) {
      $pmc = new PlaceMapCategory(true);
      $this->form((Array)$pmc, null);
    } elseif ($_GET['action'] == 'edit_category' and $_GET['category_id'] > 0) {
      $category = PlaceMapCategory::load((int)$_GET['category_id']);
      $data = (array)$category;
      $this->form($data, null);
    } elseif ($_POST['action'] == 'save_category' && $_POST['category_pm_id'] > 0) {
      if (!$pmc = PlaceMapCategory::load((int)$_POST['category_id'])) {
         $pmc = new PlaceMapCategory(true);
      }
      if (!$pmc->fillPost() || !$pmc->saveEx()) {
        $this->form($_POST, null);
      } else {
        $category = PlaceMapCategory::load($pmc->id);
        if (($category->event_status == 'nosal') &&
            (int)$_POST['category_new_size'] &&
            !$category->change_size((int)$_POST['category_nosale_size'])) {
          $data = (array)$category;
          addError('category_nosale_size', 'error');
          $data['category_nosale_size'] = $_POST['category_nosale_size'];
          $this->form($data, null);
          return false;
        }
        return true;
      }


    } elseif ($_GET['action'] == 'remove_category' and $_GET['category_id'] > 0) {
      if($pmc = PlaceMapCategory::load($_GET['category_id']))
        $pmc->delete();
      return true;
    } elseif ($_POST['action'] == 'resize_category' and $_POST['category_id'] > 0) {
      $category = PlaceMapCategory::load((int)$_POST['category_id']);

      if (!$category->change_size((int)$_POST['category_new_size'])) {
        $data = (array)$category;
        addError('category_new_size', 'error');
        $data['category_new_size'] = $_POST['category_new_size'];
        $this->form($data, null);
      } else {
        $data = (array)$category;
        $this->form($data, null);
      }
    }
  }

  // ################# petits fonctions speciales ##################
  function print_select_tpl ($name, &$data, &$err) {
    $query = "SELECT template_name
              FROM Template
              WHERE template_type='pdf2'
              ORDER BY template_name";
    if (!$res = ShopDB::query($query)) {
        user_error(shopDB::error());
        return false;
    }

    $sel[$data[$name]] = " selected ";

    echo "<tr><td class='admin_name'  width='40%'>" . con($name) . "</td>
            <td class='admin_value'>
             <select name='$name'>
             <option value=''></option>\n";

    while ($v = shopDB::fetch_row($res)) {
        $value = htmlentities($v[0], ENT_QUOTES);
        echo "<option value='$value' " . $sel[$v[0]] . ">{$v[0]}</option>\n";
    }

    echo "</select>".printMsg($name, $err)."</td></tr>\n";
  }

}

?>