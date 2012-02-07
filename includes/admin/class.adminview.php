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
require_once("classes/class.component.php");

class AdminView extends Component {
  static $labelwidth = '40%';
  var $tabitems = array(0=> 'admin_default|control');
  var $page_width = 800;
  var $title = "Administration";
  var $ShowMenu = true;
  var $page_length = 15;
  private $jScript = "";

  function __construct ($width=0, $dummy=null){
    global $_SHOP;
    parent::__construct();
    if ($width) {
      $this->width = $width;
    }
    if (is_array($this->tabitems)) {
      $this->tabitems = plugin::call('_'.get_class($this).'_Items', $this->tabitems);
      $this->tabitems = $_SHOP->controller->addACLs($this->tabitems, true);
    }
  }

  function ActionACLs($ACLs){
    global $_SHOP;
    if (is_array($ACLs)) {
      $ACLs = plugin::call('_'.get_class($this).'_Actions', $ACLs);
      $_SHOP->controller->addACLs($ACLs);
    }
  }

  function isAllowed($task, $isAction=false){
    global $_SHOP;
    return $_SHOP->controller->isAllowed($task);
  }

  protected function addJQuery($script){
    $this->jScript .= "\n".$script;
  }

  function extramenus(&$menu){}
  function execute(){return false;}

  function draw(){ }

  function drawtabs($session=null, $show=true, $get='tab'){
    GLOBAL $_SHOP;
    if (is_null($session)) {
      $session = get_class($this);
    }
    if(isset($_REQUEST[$get])) {
      $_SESSION['tabs'][$session] = (int)$_REQUEST[$get];
    } elseif(!isset($_SESSION['tabs'][$session])) {
      $_SESSION['tabs'][$session] = (int)reset($this->tabitems);
    }
    if (!in_array((int)$_SESSION['tabs'][$session], array_values($this->tabitems))) {
      $_SHOP->controller->showForbidden(get_class($this));
      return false;
    }

    if ($show) {
      $_SHOP->trace_subject .= "[tab:{$_SESSION['tabs'][$session]}]";
      if (count($this->tabitems)>1 ) {
        echo $this->PrintTabMenu($this->tabitems, $_SESSION['tabs'][$session], "left");
      }
    }
    return ((int)$_SESSION['tabs'][$session])+1;
  }

  function list_head ($name, $colspan=2, $width = 0) {
      echo "<table class='admin_list' width='" . ($width?$width:$this->width) . "' cellspacing='1' cellpadding='4'>\n";
      echo "<tr><td class='admin_list_title' colspan='$colspan' align='left'>$name</td></tr>\n";
  }

  function form_head ($name, $width = 0, $colspan = 2) {
      echo "<table class='admin_form' width='" . ($width?$width:$this->width) . "' cellspacing='1' cellpadding='4'>\n";
      if ($name) {
        echo "<thead><tr><td class='admin_list_title' colspan='$colspan' >$name</td></tr><thead>";
      }
  }

  function form_foot($colspan = 2, $backlink='', $submit='save', $showreset=true ) {
      echo "<tr  class='admin_value' ><td>";
      if (!is_array($backlink) and $backlink) {
        echo  $this->show_button($backlink,'admin_list',3);
      }
      $colspan = $colspan-1;
      echo "&nbsp;</td><td align='right' class='admin_value' colspan='{$colspan}'>";
      echo $this->Show_button('submit',$submit,3);
      if (is_array($backlink)) {
        echo  $this->show_button($backlink['href'],$backlink['name'],3,$backlink);
      }
      if ($showreset) {
        echo $this->Show_button('reset','res',3);
      }
      echo "</table></form>\n";
  }


  /**
   * AdminView::print_multiRowField()
   *
   * This function will create a multirow of fields.
   * Prime example is Multiple Email Address and Names
   *
   * @param mixed $name array field name
   * @param mixed $data location of array will use $data[$name][$i]
   * @param mixed $err
   * @param integer $size field size
   * @param integer $max max field size
   * @param bool $multiArr to fields Key / Value or just Text/Field
   * @return void
   */
   function print_multiRowField($name, &$data , &$err, $size = 30, $max = 100, $multiArr=false, $arrayPrefix=''){
    if($arrayPrefix <> ''){
      $prefix = $arrayPrefix."[$name]";
    }else{
      $prefix = "{$name}";
    }

    echo "<tr id='{$name}-tr' ><td class='admin_name' width='".self::$labelwidth."'>" , con($name) , "</td>
              <td class='admin_value' ><button id='{$name}-add' type='button'>".con('add_row')."</button> </td></tr>\n";

    $data[$name] = is($data[$name],array()); $i=0;
    foreach($data[$name] as $key=>$val){
      if(!$multiArr){
        echo "<tr id='{$name}-row-$i' class='{$name}-row'><td class='admin_name' width='".self::$labelwidth."'>".con($name)."</td>
                <td class='admin_value'>
                  <input type='text' name='{$prefix}[$i][value]' value='" . htmlspecialchars($val, ENT_QUOTES) . "'>
                  <a class='{$name}-row-delete link' href='#'><img src=\"../images/trash.png\" border='0' alt='".con('remove')."' title='".con('remove')."'></a>
                  ".printMsg($name, $err)."
                </td></tr>\n";
      }else{
        echo "<tr id='{$name}-row-$i' class='{$name}-row'><td class='admin_value' style='width:100%;' colspan='2'>
                <input type='text' name='{$prefix}[$i][key]' value='" . htmlspecialchars($key, ENT_QUOTES) . "'>
                <input type='text' name='{$prefix}[$i][value]' value='" . htmlspecialchars($val, ENT_QUOTES) . "'>
                <a class='{$name}-row-delete link' href='#'><img src=\"../images/trash.png\" border='0' alt='".con('remove')."' title='".con('remove')."'></a>
                ".printMsg($name, $err)."
              </td></tr>\n";
      }
      $i++;
    }
    if($multiArr){
      $script = "
          var {$name}Count = {$i};
          $('#{$name}-add').click(function(){
            $('#{$name}-tr').after(\"<tr id='{$name}-row-\"+{$name}Count+\"' class='{$name}-row' >\"+
                \"<td class='admin_value' style='width:100%;' colspan='2'>\"+
                  \"<input type='text' name='{$prefix}[\"+{$name}Count+\"][key]' value='' />&nbsp; \"+
                  \"<input type='text' name='{$prefix}[\"+{$name}Count+\"][value]' value='' />\"+
                  \"<a class='{$name}-row-delete link' href=''><img src='../images/trash.png' border='0' alt='".con('remove')."' title='".con('remove')."'></a>\"+
                \"</td>\"+
              \"</tr>\");

            {$name}Count++;
          });";
    }else{
      $script = "
          var {$name}Count = {$i};
          $('#{$name}-add').click(function(){
            $('#{$name}-tr').after(\"<tr id='{$name}-row-\"+{$name}Count+\"' class='{$name}-row' ><td class='admin_name' width='".self::$labelwidth."'>".con($name)."</td>\"+
                \"<td class='admin_value'>\"+
                  \"<input type='text' name='{$prefix}[\"+{$name}Count+\"][value]' value='' />\"+
                  \"<a class='{$name}-row-delete link' href=''><img src='../images/trash.png' border='0' alt='".con('remove')."' title='".con('remove')."'></a>\"+
                \"</td>\"+
              \"</tr>\");

            {$name}Count++;
          });";
    }
    $this->addJQuery($script);

    $script = "$('.{$name}-row-delete').live(\"click\",function(){
          $(this).parent().parent().remove();
          return false;
        });";
    $this->addJQuery($script);

  }

  /**
   * @param array other : additional optional options
   *  add_arr => array('value'=>'name') this is the value for the add row button which wen set can be a drop down box.
   */
   function print_multiRowGroup($name, &$data , &$err, $fields=array(),$arrayPrefix='',$other=array()){
    if(!is_array($fields)){
      return false;
    }elseif(empty($fields)){
      return false;
    }

    if($arrayPrefix <> ''){
      $prefix = $arrayPrefix."[$name]";
    }else{
      $prefix = "{$name}";
    }

    if(is($other['add_arr']) && is_array($other['add_arr']) ){
      $select = "<select name='{$name}_group_add' id='{$name}-group-add-field' >";
      foreach($other['add_arr'] as $oVal=>$oName){
        $select .= "<option value='{$oVal}' >{$oName}</option>";
      }
      $select .= "</select>";
    }else{
      $select = "<input type='text' name='{$name}_group_add' id='{$name}-group-add-field' size='15' maxlength='100'>";
    }

    echo "
      <tr id='{$name}-group-add-tr' >
        <td class='admin_name' width='".self::$labelwidth."'>" , con($name) , "</td>
        <td class='admin_value' >
          <button id='{$name}-group-add-button' type='button'>".con($name)." ".con('add_row')."</button>
          {$select}
          <span id='{$name}-error' style='display:none;'>".con('err_blank_or_allready')."</span>
        </td>
      </tr>\n";

    echo "
      <tr id='{$name}-group-select-tr'>
        <td class='admin_name'  width='".self::$labelwidth."'>".con($name)." ".con('select')."</td>
        <td class='admin_value'>
          <select id='{$name}-group-select' name='{$name}_group_select'>\n</select> ".
          $this->show_button('button','remove_group',2,
                             array('id'=>"{$name}-group-delete",
                                   'image'=>"trash.png")).
        "</td>
      </tr>\n";

    $data[$name] = is($data[$name],array()); $opts = "";
    foreach($data[$name] as $group=>$values){
      //for each group add the option list.
      $opts .= "<option value='{$group}'>".con($group)."</option>";

      //Fill Field type and values else add blanks.
      foreach($fields as $field=>$arr){
        $type = is($arr['type'],'text');
        $value = is($values[$field],'');
        if($type=='text'){
          $size=is($arr['size'],40);
          $max=is($arr['max'],100);
          $input = "<td colspan='1' width='60%' class='admin_value'><input type='text' name='{$prefix}[$group][$field]' value='".htmlspecialchars($value, ENT_QUOTES)."' size='{$size}' maxlength='{$max}'></td>";
          $colspan = 1;
        }elseif($type=='textarea'){
          $rows=is($arr['rows'],10);
          $cols=is($arr['cols'],92);
          $input = "</tr>
                    <tr id='{$name}-{$group}-{$field}-row' class='{$name}-row {$name}-group-row {$name}-{$group}-row' style='display:none;'>
                      <td colspan='2' class='admin_value'><textarea rows='{$rows}' cols='{$cols}' name='{$prefix}[$group][$field]'>".$value."</textarea></td>";
          $colspan = 2;
        }

        echo "<tr id='{$name}-{$group}-{$field}-row' class='{$name}-row {$name}-group-row {$name}-{$group}-row' style='display:none;'>
                <td colspan='{$colspan}' class='admin_name'>".con($field)."</td>"
                .$input."
              </tr>\n";
      }
    }
    //This add the exsisting options to the select
    $addOptsScript = "$('#{$name}-group-select').html(\"{$opts}\");";
    $this->addJQuery($addOptsScript);
    //This will let you delete a group
    $deleteScript = "$('#{$name}-group-delete').click(function(){
      var group = $('#{$name}-group-select').val();
      $('.{$name}-'+group+'-row').each(function(){
        $(this).remove();
      });
      $('#{$name}-group-select option[value=\"'+group+'\"]').remove();
      $('#{$name}-group-select').change();
    });";
    $this->addJQuery($deleteScript);

    //This is the add section, We build the fields first then chuck them into jquery;
    unset($input); $inputs = "";
    foreach($fields as $field=>$arr){
      $type = is($arr['type'],'text');
      $value = is($values[$field],'');
      if($type=='text'){
        $size=is($arr['size'],40);
        $max=is($arr['max'],100);
        $input = "<td colspan='1' class='admin_value' width='60%'><input type='text' name='{$prefix}[\"+newGroup+\"][{$field}]' value='' size='{$size}' maxlength='{$max}' /></td>";
        $colspan = 1;
      }elseif($type=='textarea'){
        $rows=is($arr['rows'],10);
        $cols=is($arr['cols'],92);
        $input = "</tr><tr id='{$name}-\"+newGroup+\"-{$field}-row' class='{$name}-row {$name}-group-row {$name}-\"+newGroup+\"-row' style='display:none;'><td colspan='2' class='admin_value'><textarea rows='{$rows}' cols='{$cols}' name='{$prefix}[\"+newGroup+\"][{$field}]'> </textarea></td>";
        $colspan = 2;
      }
      $inputs .= "\"<tr id='{$name}-\"+newGroup+\"-{$field}-row' class='{$name}-row {$name}-group-row {$name}-\"+newGroup+\"-row' style='display:none;'><td colspan='{$colspan}' class='admin_name' width='".self::$labelwidth."'>".con($field)."</td>{$input}</tr>\"+";
    }

    $addScript = "$('#{$name}-group-add-button').click(function(){
      var newGroup = $('#{$name}-group-add-field').val();
      newGroup = jQuery.trim(newGroup);

      if($('#{$name}-group-select option[value=\"'+newGroup+'\"]').val()!=newGroup && newGroup!=''){
        $('#{$name}-group-select-tr').after({$inputs}\"\");
        $('#{$name}-group-select').append(\"<option value='\"+newGroup+\"'>\"+newGroup+\"</option>\").val(newGroup);
        $('#{$name}-group-select').change();
      }else{
        $('#{$name}-error').show();
      }
    });";
    $this->addJQuery($addScript);

    //Add the change script which will run when the select box value changes.
    $changeScript = "$('#{$name}-group-select').change(function(){
      var group = $(this).val();
      $('.{$name}-group-row').each(function(){
        //$(this).hide();
        $(this).attr('style','display:none;');
      });
      $(\".{$name}-\"+group+\"-row\").each(function(){
        $(this).show();
      });
      $('#{$name}-error').hide();
    }).change();";
    $this->addJQuery($changeScript);

  }

  function print_field ($name, $data, $prefix='',$fieldtype='') {
      echo "<tr id='{$name}-tr'><td class='admin_name' width='".self::$labelwidth."'>$prefix" , con($name) , "</td>
          <td class='admin_value'>";
    $data = (is_array($data))?$data[$name]:$data;
    if ($fieldtype == 'valuta') {
      echo valuta($data);
    } elseif ($fieldtype == 'date') {
       echo formatAdminDate($data);
    } else {
       echo $data;
    }
    echo "</td></tr>\n";
  }

  function print_field_o ($name, $data, $prefix='') {
    if (!empty($data[$name])) {
        $this->print_field($name, $data, $prefix);
    }
  }

  function _check ($name,$main,$data){
    if (is_object($main)) {
      if($main->$name==$data[$name]){$chk='checked';}
      return "<input title=".con('reset_to_main')." type='checkbox' id='$name"."_reset_chk' name='$name"."_chk' value=1 $chk align='middle' style='border:0px;'> ";
    }
    return $main;
  }


  function print_input ($name, &$data, &$err, $size = 30, $max = 100, $suffix = '', $arrPrefix='') {
    $suffix = self::_check($name, $suffix,$data);
    if($arrPrefix <> ''){
      $prefix = $arrPrefix."[$name]";
    }else{
      $prefix = "{$name}";
    }
    echo "<tr id='{$name}-tr' ><td class='admin_name'  width='".self::$labelwidth."'>$suffix" . con($name) . "</td>
          <td class='admin_value'><input id='{$name}-input' type='text' name='$prefix' value='" . htmlspecialchars(is($data[$name],''), ENT_QUOTES) . "' size='$size' maxlength='$max'>
          ".printMsg($name, $err)."
          </td></tr>\n";
  }

  function print_password ($name, &$data, &$err, $size = 30, $max = 100, $suffix = '', $arrPrefix='') {
    $suffix = self::_check($name, $suffix,$data);
    if($arrPrefix <> ''){
      $prefix = $arrPrefix."[$name]";
    }else{
      $prefix = "{$name}";
    }
    echo "<tr id='{$name}-tr' ><td class='admin_name'  width='".self::$labelwidth."'>$suffix" . con($name) . "</td>
          <td class='admin_value'><input id='{$name}-input' type='password' name='$prefix'  size='$size' maxlength='$max'>
          ".printMsg($name, $err)."
          </td></tr>\n";// Removed the value='" . htmlspecialchars(is($data[$name],''), ENT_QUOTES) . "' here for savety resonse.
  }

    function print_checkbox ($name, &$data, &$err, $size = '', $max = '')
    {
      $suffix = self::_check($name, $suffix,$data);
      if (empty($data[$name])) {
        $sel0='checked';
      } else {
        $sel1='checked';
      }
      echo "<tr id='{$name}-tr'><td class='admin_name'>$suffix" . con($name) . "</td>

          <td class='admin_value'>
            <input type='radio'  id='{$name}-no' name='$name' value=0 $sel0><label for='{$name}-no'>".con('no')."</label>&nbsp;
            <input type='radio'  id='{$name}-yes' name='$name' value=1 $sel1><label for='{$name}-yes' >".con('yes')."</label>

          ".printMsg($name, $err)."
          </td></tr>\n";

       //     self::print_select_assoc ($name, $data, $err, array('0'=>'no', '1'=>'yes'));
/*        if ($data[$name]) {
            $chk = 'checked';
        }
        echo "<tr><td class='admin_name'  width='".self::$labelwidth."'>" . con($name) . "</td>
                <td class='admin_value'><input type='checkbox' name='$name' value='1' $chk>
                ".printMsg($name, $err)."
                </td></tr>\n";*/
    }

    function print_area ($name, &$data, &$err, $rows = 6, $cols = 50, $suffix = '') {
      $suffix = self::_check($name, $suffix,$data);
      echo "<tr id='{$name}-tr'><td class='admin_name'>$suffix" . con($name) . "</td>
          <td class='admin_value'><textarea id='{$name}-textarea' style='width:100%;' rows='$rows' cols='$cols' name='$name'>" . htmlspecialchars($data[$name], ENT_QUOTES) . "</textarea>
          ".printMsg($name, $err)."
          </td></tr>\n";
    }

    /**
     * AdminView::print_large_area()
     *
     * @param mixed $name
     * @param mixed $data
     * @param mixed $err
     * @param integer $rows
     * @param integer $cols
     * @param string $suffix
     * @param string|array $options ($class depricated) array of options additional options
     * 'escape'=>false, wont escape the html
     * 'class'=>'class-name' CSS Class name
     * @return void
     */
    function print_large_area ($name, &$data, &$err, $rows = 20, $cols = 80, $suffix = '', $options='') {
      if(is_array($options)){
        $class = is($options['class'],'');
      }else{$class = $options;}
      $escape = is($options['escape'],true);
      $suffix = self::_check($name, $suffix,$data);
      echo "<tr id='{$name}-tr'><td colspan='2' class='admin_name'>$suffix" . con($name) . "&nbsp;&nbsp; ".printMsg($name, $err)."</td></tr>
              <tr><td colspan='2' class='admin_value'><textarea id='{$name}-textarea' style='width:100%;' rows='$rows' cols='$cols' id='$name' name='$name' $class>" . ($escape ? htmlspecialchars($data[$name], ENT_QUOTES):$data[$name]) . "</textarea>
              </td></tr>\n";
    }


    /**
     * AdminView::show_button()
     *
     * NEEDS TO BE ECHO'D ON RETURN!
     *
     * returns <a href=$url> ... </a> depending
     * on the settings.
     *
     * @param string url : the url, can also set button type (submit,reset)
     * @param string name : the method of the button, if not recognised will print text instead.
     * @param int type : 1 = text only, 2 = icon (will try to match against $name) 3 = both
     * @param array options : array of additional options.
     *  'disable' => false,
     *  'image'=>"image url",
     *  'style'=>'extra style info here'
     *  'classes'=>'css-class-1 css-class-2'
     *  'tooltiptext'=>''
     *  'showtooltip'=>false
     * @return string
     */
     function show_button($url, $name, $type=1, $options=array() ){

      if(!empt($name,false)){
          return;
      }
      $button = false;
      $text = false;
      $icon = false;
      $iconArr = array(
        'add'=>array('image'=>'add.png'),
        'edit'=>array('image'=>'edit.gif'),
        'view'=>array('image'=>'view.png'),
        'list'=>array('image'=>'arrow_left.png'),
        'delete'=>array('image'=>'trash.png'),
        'remove'=>array('image'=>'trash.png'));

      //Find what to show
      if($type===1 || $type >= 3){
        $icon = false;
        $text = $name;
      }
      if(is($options['image'],false)){
        $icon = true;
        $image = $options['image'];
      }elseif($type===2 || $type >= 3){
        foreach($iconArr as $icoNm=>$iconDtl){
          $name2 = strtolower($name);
          if(preg_match('/'.$icoNm.'/',$name2)){
            $icon = $icoNm;
            $image = $iconDtl['image'];
            break;
          };
        }
        if(!$icon){
          $text = $name;
        }
      }
      //Is it a button?
      if($url=='submit' || $url=='reset'|| $url=='button'){
        $button = true;
      }
      //Extra options
      $classes = is($options['classes'],'');
      $style   = is($options['style'],'');
      $idname  = is($options['id'], $name);
      $disabled= is($options['disable'],false);
      $target= is($options['target'],'');

      if ($target) {
        $target = "target='{$target}'";
      }

      if(!$icon){
        $classes .= " admin-button-text";
      }
      $disClass = ''; $disAtr = '';
      if($disabled){
        $disAtr = " disabled='disabled' ";
        $disClass = " ui-state-disabled ";
        $url = '';
      }
      //Tooltip stuff
      $toolTipName = $this->hasToolTip($name);
      $hasTTClass = 'has-tooltip';
      $toolTipText = con($toolTipName);
      if(is($options['showtooltip'])===false){
        $hasTTClass = '';
        $title = con($name);

      }elseif(empt($options['tooltiptext'],false)){
        $toolTipText = $options['tooltiptext'];
        $toolTipName = empt($toolTipName,$name."_tooltip");

      }elseif(!empt($toolTipName,false)){
        $hasTTClass = '';
        $title = con($name);
      }

      $alt     = is($options['alt'],is($title,con($name)));
      if ($alt) {
        $alt = "alt='{$alt}'";
      }
      $rtn = "";

      //If image bolt on image css for button
      if($icon && $image && $text){ $css = 'admin-button-icon-left'; }else{ $css = ''; }

      if(!$button){
        $rtn .= "<a id='{$idname}' {$target} class='{$hasTTClass} admin-button ui-state-default {$css} ui-corner-all link {$classes} {$disClass}' style='{$style}' href='".empt($url,'#')."' title='{$title}' {$alt}>";
      }else{
        $rtn .= "<button $disAtr type='{$url}' name='{$name}' id='{$idname}' class='{$hasTTClass} admin-button ui-state-default {$css} ui-corner-all link {$classes} {$disClass}' style='{$style}' {$alt}>";
      }
      if($icon && $image && $text){
        $rtn .= " <span class='ui-icon' style='background-image:url(\"../images/{$image}\"); background-position:center center; margin:-8px 5px 0 0; top:50%; left:0.6em; position:absolute;' title='{$title}' ></span>";
      }elseif($icon && $image){
        $rtn .= " <span class='ui-icon' style='background-image:url(\"../images/{$image}\"); background-position:center center; ' title='{$title}' ></span>";
      }
      if($text){
        $rtn .= con($name);
      }
       //Add on the Tooltip div for the text
      if(!empty($hasTTClass)){
        $rtn .= "<div id='{$toolTipName}' style='display:none;'>{$toolTipText}</div>";
      }
      if(!$button){
        $rtn .= "</a>";
      }else{
        $rtn .= "</button>";
      }


      return $rtn;
    }

    function print_set ($name, &$data, $table_name, $column_name, $key_name, $file_name) {
        $ids = explode(",", $data);
        $set = array();
        if (!empty($ids) and $ids[0] != "") {
            foreach($ids as $id) {
                $query = "select $column_name as id from $table_name where $key_name="._esc($id);
                if (!$row = ShopDB::query_one_row($query)) {
                    // user_error(shopDB::error());
                    return 0;
                }
                $row["id"] = $id;
                array_push($set, $row);
            }
        }
        echo "<tr id='{$name}-tr'><td class='admin_name'>" . con($name) . "</td>
    <td class='admin_value'>";
        if (!empty($set)) {
            foreach ($set as $value) {
                echo "<a id='{$name}-a' class='link' href='$file_name?action=view&$key_name=" . $value["id"] . "'>" . $value[$column_name] . "</a><br>";
            }
        }
        echo "</td></tr>\n";
    }

    function print_time ($name, &$data, &$err, $suffix = '') {
      global $_SHOP;
      $suffix = self::_check($name, $suffix,$data);

      if (isset($data[$name])) {
          $src = $data[$name];
          $data["$name-f"] = 'AM';
          list($h, $m, $s) = explode(":", $src);
          if (($_SHOP->input_time_type == 12) and ($h > 12)) {
            $h -=12;
            $data["$name-f"] = 'PM';
          }
      } else {
          $h = $data["$name-h"];
          $m = $data["$name-m"];
      }
      echo "<tr id='{$name}-tr'><td class='admin_name'>$suffix" . con($name) . "</td>
           <td class='admin_value'>
           <input type='text' name='$name-h' value='$h' size='2' maxlength='2' onKeyDown=\"TabNext(this,'down',2)\" onKeyUp=\"TabNext(this,'up',2,this.form['$name-m'])\"> :
           <input type='text' name='$name-m' value='$m' size='2' maxlength='2'>";
           if ($_SHOP->input_time_type == 12) {
             $time_fs = array("AM","PM");
             echo "<select name='$name-f'>";
      	     foreach ($time_fs as $time_f) {
           	    echo "<option ".(($data["$name-f"] == $time_f) ? "selected" :'') ."  value={$time_f}>". $time_f ."</option>";
             }
             echo "</select>";
           }
      echo printMsg($name, $err)."
           </td></tr>\n";
    }

    function print_date ($name, &$data, $datetime= 'D', $suffix = '') {
      global $_SHOP;
      $suffix = self::_check($name, $suffix,$data);
        if (isset($data[$name]) and !printMsg($name)) {
            $src = $data[$name];
            list($y, $m, $d) = explode("-", $src);
        } else {
            $y = $data["$name-y"];
            $m = $data["$name-m"];
            $d = $data["$name-d"];
        }
        $nm = $name . "-m";
        echo "<tr id='{$name}-tr'><td class='admin_name'>$suffix" . con($name) . "</td>
              <td class='admin_value'>";
        $year  = "<input type='text' name='$name-y' value='$y' size='4' maxlength='4'>";
        $month = "<input type='text' name='$name-m' value='$m' size='2' maxlength='2' onKeyDown=\"TabNext(this,'down',2)\" onKeyUp=\"TabNext(this,'up',2,this.form['$name-y'])\">";
        $day   = "<input type='text' name='$name-d' value='$d' size='2' maxlength='2' onKeyDown=\"TabNext(this,'down',2)\" onKeyUp=\"TabNext(this,'up',2,this.form['$nm'])\" >";
        if ($_SHOP->input_date_type == 'iso') {
           echo "{$year} - {$month} - {$day} (yyyy-mm-dd)";
        } elseif ($_SHOP->input_date_type == 'dmy') {
           echo "{$day} - {$month} - {$year} (dd-mm-yyyy)";
        } else {
           echo "{$month} - {$day} - {$year} (mm-dd-yyyy)";
          }
        echo " ".printMsg($name)."
              </td></tr>\n";
    }

    function print_url ($name, &$data, $prefix = ''){
        echo "<tr id='{$name}-tr'><td class='admin_name' width='".self::$labelwidth."'>$prefix" . con($name) . "</td>
    <td class='admin_value'>
    <a id='{$name}-a' href='{$data[$name]}' target='blank'>{$data[$name]}</a>
    </td></tr>\n";
    }

  function Set_time($name, & $data, & $err) {
    global $_SHOP;
    if ( (isset($data[$name.'-h']) and strlen($data[$name.'-h']) > 0) or
    (isset($data[$name.'-m']) and strlen($data[$name.'-m']) > 0) ) {
      $h = $data[$name.'-h'];
      $m = $data[$name.'-m'];
      if ( !is_numeric($h) or $h < 0 or $h >= $_SHOP->input_time_type ) {
        $err[$name] = invalid;
      } elseif ( !is_numeric($m) or $h < 0 or $m > 59 ) {
        $err[$name] = invalid;
      } else {
        if (isset($data[$name.'-f']) and $data[$name.'-f']==='PM') {
          $h = $h + 12;
        }
        $data[$name] = "$h:$m";
      }
    }
  }

  function set_date($name,&$data, &$err) {
    if ( (isset($data["$name-y"]) and strlen($data["$name-y"]) > 0) or
      (isset($data["$name-m"]) and strlen($data["$name-m"]) > 0) or
    (isset($data["$name-d"]) and strlen($data["$name-d"]) > 0) ) {
      $y = $data["$name-y"];
      $m = $data["$name-m"];
      $d = $data["$name-d"];

      if ( !checkdate($m, $d, $y) ) {
        adderror($name,'invalid');
      } else {
        $data[$name] = "$y-$m-$d";
      }
    }

  }

    function print_select ($name, &$data, &$err, $opt, $actions=''){
        // $val=array('both','rows','none');
        $sel[$data[$name]] = " selected ";

        echo "<tr id='{$name}-tr'><td class='admin_name'  width='".self::$labelwidth."'>" . con($name) . "</td>
              <td class='admin_value'>
               <select id='{$name}-select' name='$name' $actions>\n";

        foreach($opt as $v) {
            echo "<option value='$v'{$sel[$v]}>" . con($name . "_" . $v) . "</option>\n";
        }

        echo "</select>".printMsg($name, $err)."
              </td></tr>\n";
    }

    function print_select_assoc ($name, &$data, &$err, $opt, $actions='', $mult = false) {
        // $val=array('both','rows','none');
        $sel[$data[$name]] = " selected ";
        $mu = '';
        if ($mult) {
            $mu = 'multiple';
        }
        echo "<tr id='{$name}-tr'><td class='admin_name'  width='".self::$labelwidth."' $mu>" . con($name) . "</td>
                <td class='admin_value'> <select id='{$name}-select' name='$name'  $actions>\n";

        foreach($opt as $k => $v) {
            echo "<option value='$k'{$sel[$k]}>".con($v)."</option>\n";
        }

        echo "</select>".printMsg($name, $err)."</td></tr>\n";
    }

    function print_color ($name, &$data, &$err) {
        echo "<tr id='{$name}-tr'><td class='admin_name'  width='".self::$labelwidth."'>" . con($name) . "</td>
        <td class='admin_value'>";
          $act = is($data[$name],'#FFFFFF');
        echo "<input type='hidden' id='{$name}_text' name='$name' value='$act'>\n
      		<div id='colorSelector'><div style='background-color: $act'></div></div>";
       echo "<script>$('#colorSelector').ColorPicker({
								color: '{$act}',
								onShow: function (colpkr) {
									$(colpkr).fadeIn(500);
									return false;
								},
								onHide: function (colpkr) {
									$(colpkr).fadeOut(500);
									return false;
								},
								onChange: function (hsb, hex, rgb) {
									$('#colorSelector div').css('backgroundColor', '#' + hex);
									$('#{$name}_text').val('#' + hex);
                }
							});</script>";
       echo "<div style=''>".printMsg($name, $err)."</div></td></tr>\n";
    }

    function view_file ($name, &$data, &$err, $type = 'img', $prefix = '') {
        global $_SHOP;
        $prefix = self::_check($name, $prefix,$data);

        if ($data[$name]) {
            $src = self::user_url($data[$name]);
            echo "<tr id='{$name}-tr'><td class='admin_name'  width='".self::$labelwidth."'>$prefix" . con($name) . "</td>";
            if ($type == 'img') {
              echo "<td class='admin_value'>";
           		if(file_exists(ROOT.'files'.DS.$data[$name])){
           			list($width, $height, $type, $attr) = getimagesize(ROOT.'files'.DS.$data[$name]);
      					if (($width>$height) and ($width > 300)) {
      						$attr = "width='300'";
      					} elseif ($height > 250) {
      						$attr = "height='250'";
      					}
      					echo "<img $attr src='$src'>";
           		}else{
           			echo "<strong>".con("file_not_exsist")."</strong>";
           		}
            } else {
                echo "<td class='admin_value'><a class=link href='$src'>{$data[$name]}</a>";
            }
            echo "</td></tr>\n";
        }
    }

    function print_file ($name, &$data, &$err, $type = 'img', $suffix = ''){
      global $_SHOP;
      $suffix = self::_check($name, $suffix,$data);

        if (!isset($data[$name]) || empty($data[$name])) {
            echo "\n<tr id='{$name}-tr'><td class='admin_name'  width='".self::$labelwidth."'>$suffix" . con($name) . "</td>
            <td class='admin_value'><input type='file' name='$name'>".printMsg($name, $err)."</td></tr>\n";
        } else {
            $src = self::user_url($data[$name]);

            echo "<tr id='{$name}-tr'><td class='admin_name' rowspan=3 valign='top' width='".self::$labelwidth."'>$suffix" . con($name) . "</td>
            <td class='admin_value'>";

            if ($type == 'img') {
           		if(file_exists($_SHOP->files_dir.DS.$data[$name])){
           			list($width, $height, $type, $attr) = getimagesize($_SHOP->files_dir.DS.$data[$name]);
      					if (($width>$height) and ($width > 300)) {
      						$attr = "width='300'";
      					} elseif ($height > 250) {
      						$attr = "height='250'";
      					}
      					echo "<img $attr src='$src'>";
           		}else{
           			echo "<strong title='$src'>".con("file_not_exsist")."</strong>";
           		}
            } else {
                echo "<a href='$src'>{$data[$name]}</a>";
            }

            echo "</td></tr>
              <tr>
                <td class='admin_value'><input id='{$name}-file' type='file' name='$name'>".printMsg($name, $err)."</td>
              </tr>
              <tr>
                <td class='admin_value'><input id='{$name}-checkbox' type='checkbox' name='remove_$name' value='1'>  " . con('remove_image') . "</td>
              </tr>\n";
        }
    }

  function print_countrylist($name, $selected, &$err){
  global $_SHOP,  $_COUNTRY_LIST;
    if (!isset($_COUNTRY_LIST)) {
      If (file_exists($_SHOP->includes_dir."/lang/countries_". $_SHOP->lang.".inc")){
        include_once("lang/countries_". $_SHOP->lang.".inc");
      }else {
        include_once("lang/countries_en.inc");
      }
    }
    if (is_array($selected)) $selected = $selected[$name];

    if($_SHOP->lang=='de'){
  	  if(empty($selected)){$selected='CH';}
    }else{
   	  if(empty($selected)){$selected='US';}
    }

    echo "<tr id='{$name}-tr'><td class='admin_name'  width='".self::$labelwidth."'>" . con($name) . "</td>
            <td class='admin_value'><select id='{$name}-select' name='$name'>";
    $si[$selected]=' selected';
    echo "<option value=''>".con("please_select")."</option>";
    foreach ($_COUNTRY_LIST as $key=>$value){
      $si[$key] = (isset($si[$key]))?$si[$key]:'';
      echo "<option value='$key' {$si[$key]}>$value</option>";
    }
    echo "</select>". printMsg($name, $err). "</td></tr>\n";;
  }

  function getCountry($val){
    global $_SHOP, $_COUNTRY_LIST;
    $val=strtoupper($val);

    if (!isset($_COUNTRY_LIST)) {
      If (file_exists($_SHOP->includes_dir."/lang/countries_". $_SHOP->lang.".inc")){
        include_once("lang/countries_". $_SHOP->lang.".inc");
      }else {
        include_once("lang/countries_en.inc");
      }
    }

    return $_COUNTRY_LIST[$val];
  }

  function user_url($data) {
      global $_SHOP;
      return $_SHOP->files_url . $data;
  }

  function user_file ($path){
      global $_SHOP;
      return $_SHOP->files_dir . DS . $path;
  }

  function delayedLocation($url){
      echo "<SCRIPT LANGUAGE='JavaScript'>
            <!-- Begin
                 function runLocation() {
                   location.href='{$url}';
                 }
                 window.setTimeout('runLocation()', 1500);
            // End -->\n";
      echo "</SCRIPT>\n";
  }

  public function getJQuery(){
    return $this->jScript;
  }

  protected function hasNewVersion(){
    $result = $this->getLatestVersion();
    if(is_array($result)) {
      if ($result['current'][0]) addNotice('new_version_available');
      return $result['current'][1];
    }else{
      return " - Could not check for new version.";
    }
  }

  protected function getLatestVersion($dlLink=false,$ftu=false,$ftp=false){
    require_once("classes/class.restservice.client.php");
    $rsc = new RestServiceClient('http://cpanel.fusionticket.org/versions/latest.xml');
    $rev = explode(" ",INSTALL_REVISION);
    try{
      if(!empty($ftu) && !empty($ftp)){
        $rsc->josUsername = $ftu;
        $rsc->josPassword = $ftp;
        //print_r(base64_decode($_SHOP->shopconfig_ftpassword));
      }
      $rsc->ftrevision = (int)$rev[1];
      $rsc->ftDownloadNow = ($dlLink)?'true':'false';

      $rsc->excuteRequest();
      if (!$dlLink) {
        $array = $rsc->getArray();
        if(!isset($array['versions']['version']['0_attr'])){
          throw new Exception('Couldnt get version');
        }

        $donor_rev = $array['versions']['version']['1_attr']['order'];
        $donor_ver = $array['versions']['version']['1_attr']['version'];
        $result['donor'] = array($donor_rev,$donor_ver);

        $main_rev  = $array['versions']['version']['0_attr']['order'];
        $main_ver  = $array['versions']['version']['0_attr']['version'];
        $result['main'] = array($main_rev, $main_ver);

        if ( $main_rev > (int)$rev[1]){
          $result['current'] = array(true, "<br> - <span style='color:red;'> There is a new version Available: ".$main_ver."! </span>");
        } elseif ( $donor_rev > (int)$rev[1]){
          $result['current'] = array(false, "<br> - <span style='color:blue;'> There is a new <b>donor</b> verion Available: ".$main_ver."! </span>");
        } elseif ( $main_rev == (int)$rev[1]){
          $result['current'] = array(false, "");
        } elseif ( $donor_rev == (int)$rev[1]){
          $result['current'] = array(false, " <span style='color:blue;'><b>donor</b> version. </span>");
        } else {
          $result['current'] = array(false, " <span style='color:blue; '> SVN Build </span>");
        }
        return $result;
      } else {
        return $rsc->getResponse();
      }
    }catch(Exception $e){
      print_r($e->getMessage());
      return false;// " - Could not check for new version.";
    }
  }

  // make tab menus using html tables
  // vedanta_dot_barooah_at_gmail_dot_com

  function PrintTabMenu($linkArray, &$activeTab, $menuAlign="center", $param='tab') {
    Global $_SHOP;

  	$str= "<table width=\"100%\" cellpadding=0 cellspacing=0 border=0  class=\"UITabMenuNav\">\n";
  	$str.= "<tr>\n";
  	if($menuAlign=="right"){
      $str.= "<td width=\"100%\" align=\"left\">&nbsp;</td>\n";
    }
  	foreach ($linkArray as $k => $v){
      if (!is_integer($activeTab)) $activeTab = $v;
      $menuStyle=($v==$activeTab)?"UITabMenuNavOn":"UITabMenuNavOff";
      $str.= "<td valign=\"top\" class=\"$menuStyle\"><img src=\"".$_SHOP->images_url."left_arc.gif\"></td>\n";
      $str.= "<td nowrap=\"nowrap\" height=\"16\" align=\"center\" valign=\"middle\" class=\"$menuStyle\">\n";
      $str.= "      <a class='$menuStyle' href='?{$param}={$v}'>".con($k)."</a>";
      $str.= "</td>\n";
      $str.= "<td valign=\"top\" class=\"$menuStyle\"><img src=\"".$_SHOP->images_url."right_arc.gif\"></td>\n";
      $str.= "<td width=\"1pt\">&nbsp;</td>\n";
    }
  	if($menuAlign=="left"){
      $str.= "<td width=\"100%\" align=\"right\">&nbsp;</td>";
    }
  	$str.= "</tr>\n";
  	$str.= "</table>\n";
	  return $str;
  }

  function get_nav ($page,$count,$condition=''){
    if(!isset($page)){ $page=1; }
    if (!empty( $condition)) {
      $condition .= '&';
    }
    echo "<table border='0' width='$this->width'><tr><td align='center'>";

    if($page>1){
      echo "<a class='link' href='".$_SERVER["PHP_SELF"]."?{$condition}page=1'>".con('nav_first')."</a>";
      $prev=$page-1;
      echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?{$condition}page={$prev}'>".con('nav_prev')."</a>";
    } else {
      echo con('nav_first');
      echo con('nav_prev');
    }

    $num_pages=ceil($count/$this->page_length);
    for ($i=floor(($page-1)/10)*10+1;$i<=min(ceil($page/10)*10,$num_pages);$i++){
      if($i==$page){
        echo "&nbsp;<b>[$i]</b>";
      }else{
        echo "&nbsp;<a class='link' href='".$_SERVER["PHP_SELF"]."?{$condition}page=$i'>$i</a>";
      }
    }
    echo "&nbsp;";
    if($page<$num_pages){
      $next=$page+1;
      echo "<a class='link' href='".$_SERVER["PHP_SELF"]."?{$condition}page=$next'>".con('nav_next')."</a>";
      echo "<a class='link' href='".$_SERVER["PHP_SELF"]."?{$condition}page=$num_pages'>". con('nav_last')."</a>";
    }  else {
      echo con('nav_next')."\n";
      echo con('nav_last')."\n";
    }
    echo "</td></tr></table>";
  }

  /**
   * AdminView::hasToolTip()
   *
   * @param string constantName : constant name
   *
   * @return mixed : false or the tooltip constant name.
   */
  protected function hasToolTip($constantName){
    if(!defined($constantName."_tooltip")){
      return false;
    }else{
      return $constantName."_tooltip";
    }
  }

  function print_hidden ($name, $data=''){
    echo "<input type='hidden' id='$name' name='$name' value='" . ((is_array($data))?$data[$name]:$data) . "'>\n";
  }
}


?>