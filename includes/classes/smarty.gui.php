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

/**
 * @author Chris Jenkins
 * @copyright 2008
 */

if (!defined('ft_check')) {die('System intrusion ');}
class Gui_smarty {

	/**
	* Base URL
	*
	* @var string
	*/
	var $width      = '95%';
	var $FormDepth  = 0;
	var $_ShowLabel = True;
	var $gui_name   = 'gui_name';
	var $gui_value  = 'gui_value';
	var $gui_name_width  = '30%';
  var $gui_form_id  = '';
  public $guidata = array();
  private $model  = null;

  function __construct  ($smarty){

    $smarty->register_object("gui",$this, null,true, Array('label'));
    $smarty->assign_by_ref("gui",$this);

    $smarty->register_function('ShowFormToken', array($this,'showFormToken'));
    $smarty->register_function('valuta', array($this,'valuta'));
    $smarty->register_function('print_r', array($this,'print_r'));
    $smarty->register_function('printMsg', array($this,'printMsg'));
    $smarty->register_modifier('clean', 'smarty_modifier_clean');
    $smarty->register_function('weeksofyear', 'weeksofyear');

  }
  function validate($tmp, $arr){
    $result = array();
    foreach ($arr  as $key => $value ){
      $result[] = $key.':'.$value;
    }
    return implode($tmp, $result);
  }

  function printMsg($params, $smarty) {
    $addspan     = is($params['addspan'],true);
    return printMsg($params['key'],null, $addspan );
  }

  function url($params, $smarty=false, $skipnames= false){
    GLOBAL $_CONFIG;
    if (isset($params['surl'])) {
      return $_SHOP->root_secured.$params['surl'];
    } elseif (isset($params['url'])) {
      return $_SHOP->root.$params['url'];
    } else {
      If (!is_array($skipnames)) {$skipnames= array();}
    //  print_r($params);
      $urlparams ='';

      if ($params['secure']) {
        unset($params['secure']);
        $secure = true;
      } else $secure = false;
      parse_str($_SERVER['QUERY_STRING'], $queryHash);

      //   merge parameters from the current url
      //   with the new parameters
      //   notice: the same keys will be overwritten
      $paramHash = array_merge($queryHash, $params);
      foreach ($skipnames as $value) {unset($paramHash[$value]);}
      If ($secure) {
        $result = $_SHOP->root_secure;
      } elseif (isset($params['url'])) {
        $result = $_SHOP->root_secure;
      }

      return $result.'?'.http_build_query($paramHash);
    }
  }

  /**
    *   Smarty {currenturl} plugin
    *
    *   Type:      function
    *   Name:      currenturl
    *   Purpose:   returns the url with the new and merged parameters
    *   Parameters:   - a key=value pair for the parameter string
    *
    *   ChangeLog:
    *   - 1.0 initial release
    *
    *   @version 1.0
    *   @author Bastian Friedrich
    *   @param array
    *   @param Smarty
    *   @return string
    */

   function currenturl($params, $smarty=false, $skipnames=false)
   {
    return url($params, $smarty, $skipnames);
   }

  function print_r($params, $smarty) {
    var_dump($params['var'],true);
    return '';
  }

  function fillArr($params, $smarty)
  {
      if (!isset($params['var'])) {
          $compiler->_syntax_error("assign: missing 'var' parameter", E_USER_WARNING);
          return;
      }
      if (!isset($params['count'])) {
          $compiler->_syntax_error("assign: missing 'count' parameter", E_USER_WARNING);
          return;
      }

      if (!isset($params['value'])) {
          $compiler->_syntax_error("assign: missing 'value' parameter", E_USER_WARNING);
          return;
      }
      If (isset($params['clear'])) {
         $data = array();
      }
      else
      If (is_array($smarty->get_template_vars($params['var']))) {
         $data = $smarty->get_template_vars($params['var']);
      }
      $x = $params['count'];
      for ($i = 0; $i < $x  ; $i++) {
         $data[] = $params['value'];
      }

      $smarty->assign($params['var'],$data);
      return;
  }


  function showFormToken ($params, $smarty) {
    global $_SHOP;
    $name  = str_replace('_','', is($params['name'],'FormToken'));
    $token = Secure::getFormToken($name, is($_SHOP->first_Token,false));
    $_SHOP->first_Token = false;
    return "<input type='hidden' name='___{$name}_{$token}' value='".htmlspecialchars(sha1 (md5(mt_rand()).'~'.$token.'~'.getIpAddress()))."'/>";
  }
/**
 * build a href link
 * @param string $title what the link should say
 * @param string url where the link should point (is automatically expanded if relative link)
 * @param array $htmlAttributes array of attributes of the link, for example "class"=>"dontunderline"
 * @param string an optional message that asks if you are really shure if you click on the link and aborts navigation if user clicks cancel, ignored if false
 * @param boolean $escapeTitle if we should run htmlspecialchars over the links title
 */

	/**
     * escape string stuitable for javascript or php output
     * @param string $s string to escape
     * @param bool $singleQuotes if single quotes should be escaped (true) or double-quotes (false)
     */
	private function escape($s,$singleQuotes=true) {
		if ($singleQuotes) {
		  return str_replace(array("'","\n","\r"),array('\\\'',"\\n",""), $s);
		}
    return str_replace(array('"',"\n","\r"),array('\\"',"\\n",""), $s);
	}

   function setData($params, $smarty) //($name, $width = 0, $colspan = 2)
  {
    If( isset($params['data'])) {
      $this->guidata = $params['data'];
    }
    If( isset($params['nameclass'])) {
      $this->gui_name = $params['nameclass'];
    }
    If( isset($params['valueclass'])) {
      $this->gui_value = $params['valueclass'];
    }
    If( isset($params['namewidth'])) {
      $this->gui_name_width = $params['namewidth'] ;
    }
    $model = is($params['model'],'');
    if ($model) {
      $classname = $model;
      FindClass($classname);
      if (substr($classname,0,6)=='model.') {
        $this->model = new $model();
      }
    }
  }
  function StartForm($params, $smarty) //($name, $width = 0, $colspan = 2)
  {
    $name     = is($params['name']);
    $id       = is($params['id']);
    $title    = is($params['title']);
    $style    = is($params['style']);
    $table    = is($params['table'],true);
    $width    = is($params['width'],$this->width);
    $class    = is($params['class'],'gui_form');
    $enctype  = is($params['enctype'],'application/x-www-form-urlencoded');
    $method   = is($params['method'],'post');
    $url      = is($params['action']);//$this->_URL( $params, $smarty, array('name','class','width','method','title','enctype','onsubmit', 'data' ));
    if ($style)  $style = 'style='._esc($style);
    $this->setData($params,$smarty);
    $this->gui_form_id = $id;
    $return ='';
    If ($method <> 'none') {
      $target       = is($params['target']);
      $onsubmit = is($params['onsubmit'],'');
      $props ='';
      if ($target)   $props .= ' target="'.$target.'"';
      if ($onsubmit) $props .= " onsubmit ='$onsubmit'";
      $return .= "<form action='{$url}' id='{$id}' name='{$name}' method='{$method}' class='{$class}' enctype='{$enctype}'{$props}>\n";
      $return .= self::showFormToken( $params, $smarty);
      $this->FormDepth ++;
      $this->_ShowLabel = True;
      if (!is($params['hasTabs'],false)) {
        $this->addJQuery(" $(\"#{$id}\").validate({}); ");

      } else {
      $this->addJQuery("
        $(\"".$params['hasTabs']."\").tabs({});
        $(\"#{$id}\").validate({
          invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
              var invalidPanels = $(validator.invalidElements()).closest(\".ui-tabs-panel\", form);
              if (invalidPanels.size() > 0) {
                $.each($.unique(invalidPanels.get()), function(){
                  $(this).siblings(\".ui-tabs-nav\")
                  .find(\"a[href='#\" + this.id + \"']\").parent().not(\".ui-tabs-selected\")
                  .addClass(\"ui-state-error\")
                  .show(\"pulsate\",{times: 3});
                });
              }
            }
            return !errors;
          },
          unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass(errorClass);
            $(element.form).find(\"label[for=\" + element.id + \"]\").removeClass(errorClass);
            var \$panel = $(element).closest(\".ui-tabs-panel\", element.form);
            if (\$panel.size() > 0) {
              if (\$panel.find(\".\" + errorClass + \":visible\").size() == 0) {
                \$panel.siblings(\".ui-tabs-nav\").find(\"a[href='#\" + \$panel[0].id + \"']\")
                  .parent().removeClass(\"ui-state-error\");
              }
            }
          }
        }); ");
      }
    }
    if ($this->model) {
      $return .= con('input_fields_requered')."\n";

    }
    $return .= "<fieldset>\n";
    if ($title) {
      $return .= "<legend='$class_title'>$title</legend>\n";
    }
    return $return;
  }

  function EndForm($params, $smarty) //($colspan = 2)
  {
    $name     = is($params['name'],'submit');
    $align    = is($params['align'],'right');
    $title    = is($params['title'], con('gui_save','submit'));
    $class    = is($params['class'], 'gui_footer');
    $noreset  = is($params['noreset'], false);
    $backlink = is($params['backlink'], false);
    $show     = is($params['showbuttons'], true);
    $onclick  = is($params['onclick'],'');
    if ($show) {
      $return = "<div class='$class'>\n";
      if (!is_array($backlink) and $backlink) {
          $return .= $this->button(array('url'=>$backlink, 'name'=>'admin_list', 'id'=>"{$id}_back", 'type'=>3), $smarty );
      }

      if (!$noreset) {
        $return .= $this->button(array('url'=>'reset', 'name'=>'reset', 'class'=>'cancel', 'type'=>3, 'style'=>"float:$align;", 'id'=>"{$id}_reset" ), $smarty );
      }
      $return .= $this->button(array('url'=>'submit', 'style'=>"float:$align;",'type'=>3,  'name'=>$name, 'id'=>"{$id}_{$name}", 'title'=>$title), $smarty );
      $return .= "</div>\n";
    }
    $return .=  "</fieldset>\n";
    if ($this->FormDepth) {
      $this->FormDepth --;
      $return .= "</form>\n";
    }
    return $return;
  }


  function setShowLabel($params, $smarty) {
    $this->_ShowLabel =is($params['set'],$this->_ShowLabel);
  }

  function Label($params, $content, $smarty, &$repeat) {
    //$repeat = false;
    $name = is($params['name']);
    $colspan = is($params['colspan'],1);
    $this->checkRequired($params);
    $params['namex'] = 'Label';
    if (!$repeat) {
      $repeat = false;
      return $this->showLabel($name,$content,$params,$colspan, $smarty);
    }
  }

  private function showlabel($name, $value = null, $params=array(), $colspan=1, $smarty=null) {
    $nolabel  = is($params['nolabel'],false);
    $required = (is($params['required'],false))?' required':'';
    if ($this->_ShowLabel and !$nolabel) {
      $caption  = con(is($params['caption'],$name));
      if ($colspan==2) {
         $class= 'full ';
      }
      $return = "
      <p id='{$name}-tr'>
          <label id='{$name}-label' class='{$class}input{$required}' for='{$name}'>{$caption}</label>\n";
      if ($colspan==2) {
        $return .= "<br/>\n";
      }
      $return .= "<span class='input {$class}' id='{$name}-value'>{$value}";
      if (!is($params['noerror'],false)) {
        $return .= printMsg($name);
      }
      return $return."</span>\n</p>\n";
    } else {
      return $value;
    }
  }

  function view($params, $smarty) //$name, &$data, $prefix = ''*/)
  {
    $name = is($params['name'],'nonename');
    $value  = is($params['value'],$this->guidata[$name]);
    $Option = is($params['option'], false);
    If (!$Option or !empty($value)) {
      return $this->showlabel($name, $value, $params);
    }
  }

  function hidden ($params, $smarty) //$name, &$data, $size = 30, $max = 100)
  {
    $name = is($params['name'] );
    $value  = is($params['value'],$this->guidata[$name]);
    $Option = is($params['option'], false);
    If (!$Option or !empty($value)) {
      return "<input type='hidden' id='$name' name='$name' value='" . clean($value) ."'>";
    }
  }

  private function checkRequired(&$params){
    if ($this->model) {
     $required = $this->model->isMandatory($params['name']);
    }
    $params['required'] = is($params['required'], $required);
  }

  function input ($params, $smarty) //$name, &$data, $size = 30, $max = 100)
  {
    $name = is($params['name'] );
    $idname  = is($params['id'], $name);
    $type = is($params['type'], 'text');
    $value  = is($params['value'],$this->guidata[$name]);
    $validate = is($params['validate'] ,array());
    $title    = is($params['title'] ,'');
    $style    = is($params['style'],'');
    $class    = is($params['class'], '');
    $disabled = $params['disabled'] ? "disabled" :"";

    if ($style) {$style    = ' style="'.$style.'"';}
    if ($title) {$style    = ' title="'.$title.'"';}
    if ($class) {$style    = ' class="'.$class.'"';}

    $this->checkRequired($params);
    if ($params['required']) {
      $validate['required'] = 'true';
    }
    if (strtolower($type)=='date') {
      $size = is($params['size'], 11);
      $max  = is($params['maxlength'] ,11);
      $type = 'text';
      if (!empty($value)) {
        $value = date('d-m-o', strtotime($value));
      }
      $validate[strtolower($type)]= 'true';
      $this->addJQuery("
    if ($(\"#{$idname}\")[0].type!=='date') {
      $(\"#{$idname}\").datepicker({
        showOn: 'both',
        buttonText: '',
        buttonImage: '',
        buttonImageOnly: true,
        showButtonPanel: false
      });
    }");


    } elseif (strtolower($type)=='datetime') {
      if ($value && $value != '0000-00-00 00:00:00') {
        $value = date('d-m-o H:i', strtotime($value));
      } else $value = '';
      $type = 'text';
      $size = is($params['size'], 17);
      $max  = is($params['maxlength'] ,17);
   //   $validate[strtolower($type)]= 'true';
      $this->addJQuery("
$(\"#{$idname}\").datetimepicker({
      showOn: 'both',
      buttonText: '',
      buttonImageOnly: true,
      buttonImage: '',
      	hourGrid: 3,
      	stepMinute: 5,
      	minuteGrid: 10
});");


    } elseif (strtolower($type)=='time') {
      if ($value) {
        $value = date('H:i', strtotime($value));
      }
      $type = 'text';
      $size = is($params['size'], 5);
      $max  = is($params['maxlength'] ,5);
 //     $validate[strtolower($type)]= 'true';
      $value = substr($value,0,5);
      $this->addJQuery("
    if ($(\"#{$idname}\")[0].type!=='time') {
      $(\"#{$idname}\").timepicker({
        showOn: 'both',
        buttonText: '',
        buttonImageOnly: true,
        buttonImage: '',
      	hourGrid: 3,
      	stepMinute: 5,
      	minuteGrid: 10
      });
    }");


    } elseif (false and strtolower($type)!=='text') {
      //        $validate[strtolower($type)]= 'true';

    } else {
      $size = is($params['size'], 30);
      $max  = is($params['maxlength'] ,100);
    }
    $validate  = $this->validate(',',$validate );

    return $this->showlabel($name, "<input type='$type' id='$idname' name='$name' value='" . clean($value) .
             "' size='$size' maxlength='$max' validate='{$validate}' {$style} {$disabled}>", $params);
  }

  function inputDate ($params, $smarty) {
    $params['type'] =  is($params['type'],'date' );
    return $this->input($params, &$smarty);
  }

 function inputTime ($params, $smarty) //($name, &$data, &$err,  = '')
  {
    $params['type'] =  is($params['type'],'time' );
    return $this->input($params, &$smarty);
  }


  function checkbox ($params, $smarty) //($name, &$data, &$err, $size = '', $max = '')
  {
    $name   = is($params['name']    );
    $value  = is($params['value'],$this->guidata[$name]);

    if ($value) {
      $chk = 'checked';
    }
    $validate = is($params['validate'] ,array());
    $this->checkRequired($params);
    if ($params['required']) {
      $validate['required'] = 'true';
    }

    $validate  = $this->validate(',',$validate );
    return $this->showlabel($name, "<input type='checkbox' id='$name' name='$name' value='1' $chk validate='{$validate}'>",$params);
  }

  function yesNo ($params, &$smarty) //($name, &$data, &$err, $rows = 6, $cols = 40,  = '')
  {
    $name    = is($params['name']   );
    $class   = is($params['class'],'');
    $options = is($params['options'], array(0=>'no',1=>'yes'));
    $keys    = array_keys ($options);
    $value   = is($params['value'],$this->guidata[$name]);

    $validate = is($params['validate'] ,array());

    $this->checkRequired($params);
    if ($params['required']) {
      $validate['required'] = 'true';
    }

    if (empty($value)|| $value==$keys[0]) {
      $sel0='checked=checked';
    } else {
      $sel1='checked=checked';
    }
    $validate  = $this->validate(',',$validate );
    return $this->showlabel($name,"
          <input type='radio'  id='{$name}-no'  name='$name' value='{$keys[0]}' {$sel0} validate='{$validate}'><label for='{$name}-no'>".con($options[$keys[0]])."</label>&nbsp;
          <input type='radio'  id='{$name}-yes' name='$name' value='{$keys[1]}' {$sel1}><label for='{$name}-yes' >".con($options[$keys[1]])."</label>
        ",$params);
  }


  function area ($params, &$smarty) //($name, &$data, &$err, $rows = 6, $cols = 40,  = '')
  {
    $name = is($params['name']   );
    $rows = is($params['rows'], 6);
    $cols = is($params['cols'],40);
    $class = is($params['class'],'');
    $escape = is($params['escape'],'all');
    $large = is($params['large'],false);
    $validate = is($params['validate'] ,array());
    $value  = is($params['value'],$this->guidata[$name]);
    $this->checkRequired($params);
    if ($params['required']) {
      $validate['required']='true';
    }
    $validate  = $this->validate(',',$validate );
    return $this->showlabel($name, "<textarea rows='$rows' style='width:99%;' cols='$cols' id='$name' name='$name' validate='{$validate}'>" . clean($value, $escape). "</textarea>",$params, ($large?2:1));
  }

  function ckeditor ($params, &$smarty) //($name, &$data, &$err, $rows = 6, $cols = 40,  = '')\
   {
    GLOBAL $_CONFIG;

    $name = is($params['name']   );
    $rows = is($params['rows'], 6);
    $cols = is($params['cols'],40);
    $class = is($params['class'],'');
    $escape = is($params['escape'],'html');
    $large = is($params['large'],true);
    $validate = is($params['validate'] ,array());
    $value  = is($params['value'],$this->guidata[$name]);
    $this->checkRequired($params);
    if ($params['required']) {
      $validate['required'] ='true';
    }
    include("ckeditor_php5.php");

    // Create a class instance.
    $CKEditor = new CKEditor();
      $config['toolbar'] = array(
      array( 'Source', '-','Preview', 'Maximize', 'ShowBlocks','-','About'),
      array( 'Cut','Copy','Paste','PasteText','-','Undo','Redo' ),
      array('Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ),
      '/',
      array( 'Bold','Italic','Underline','Strike','Subscript','Superscript','-', 'TextColor','BGColor', '-','RemoveFormat' ),
  array( 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl'),

      );
/*
   { name: 'document',    items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
   { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
   { name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
   { name: 'forms',       items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
   '/',
   { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
   { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
   { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
   { name: 'insert',      items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
   '/',
   { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
   { name: 'colors',      items : [ 'TextColor','BGColor' ] },
   { name: 'tools',       items : [ 'Maximize', 'ShowBlocks','-','About' ] }

   */






    $config['skin'] = 'office2003';

    // Do not print the code directly to the browser, return it instead.
    $CKEditor->returnOutput = true;

    // Path to the CKEditor directory, ideally use an absolute path instead of a relative dir.
    //   $CKEditor->basePath = '/ckeditor/'
    // If not set, CKEditor will try to detect the correct path.
    $CKEditor->basePath = $_CONFIG->root.'scripts/ckeditor/';

    // Set global configuration (will be used by all instances of CKEditor).
    $CKEditor->config['width'] = '99%';

    // Change default textarea attributes.
    $CKEditor->textareaAttributes = array("cols" => $cols, "rows" => $rows);

    // The initial value to be displayed in the editor.
    $initialValue = clean($value, $escape);

    // Create the first instance.
    $code = $CKEditor->editor($name, $value, $config);
    $validate  = $this->validate(',',$validate );

    return $this->showlabel($name, $code,$params, ($large?2:1));
  }

  function button($params, $smarty ){
    global $_SHOP;
    $name = is($params['name']    );
    $url  = is($params['url']    );
    $type  = is($params['type'], 2);

    $toolbutton = ( ($type==2) || is($params['toolbutton'],false))?'tool':'';

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
    if(($type & 1) == 1){
      $icon = false;
      $text = is($params['value'],$name);
    }
    if(is($params['image'],false)){
      $icon = true;
      $image = $params['image'];
    }elseif(($type & 2) == 2){
      foreach($iconArr as $icoNm=>$iconDtl){
        $name2 = strtolower($name);
        if(preg_match('/'.$icoNm.'/',$name2)){
          $icon = $icoNm;
          $image = $iconDtl['image'];
          break;
        };
      }
      if(!$icon){
        $text = is($params['value'],$name);
      }
    }
    //Is it a button?
    if($url=='submit' || $url=='reset'|| $url=='button'){
      $button = true;
    }
    //Extra options
    $classes = is($params['classes'],' ui-corner-all');
    $style   = is($params['style'],'');
    $idname  = is($params['id'], $name);
    $disabled= is($params['disable'],false);
    $target= is($params['target'],'');

    if ($target) {
      $target = "target='{$target}'";
    }

    $onclick = is($params['onclick'], '');
    if ($onclick) {
      $onclick = 'onclick="'.$onclick.'"';
    }
    if(!$icon){
      $classes .= " admin-button-text";
    }
    $disClass = ''; $disAtr = '';
    if($disabled){
      $disAtr = " disabled='disabled' ";
      $disClass = " ui-state-disabled ";
      $onclick = " onclick='javascript:return false;' ";
      $url = '';
    }
    //Tooltip stuff
    $toolTipName = $this->hasToolTip($name);
    if (defined($toolTipName)) {
      $toolTipText = con($toolTipName);
      $hasTTClass = 'has-tooltip';
    }
    if(is($params['showtooltip'], false)===false){
      $hasTTClass = '';
      $title = con($name);

    }elseif(empt($params['tooltiptext'],false)){
      $toolTipText = $params['tooltiptext'];
      $toolTipName = empt($toolTipName,$name."-tooltip");

    }elseif(!empt($toolTipName,false)){
      $hasTTClass = '';
      $title = con($name);
    }

    $alt     = is($params['alt'],is($title,con($name,'')));
    if ($alt) {
      $alt = "alt='{$alt}'";
    }
    $rtn = "";

    //If image bolt on image css for button
    if($icon && $image && $text){ $css = 'admin-button-icon-left'; }else{ $css = ''; }

    if(!$button){
      $rtn .= "<a id='{$idname}' {$target} class='{$hasTTClass} admin-{$toolbutton}button ui-state-default {$css} ui-corner-all link {$classes} {$disClass}' style='{$style}' href='".empt($url,'#')."' title='{$title}' {$onclick} {$alt}>";
    }else{
      $rtn .= "<button $disAtr type='{$url}' name='{$name}' id='{$idname}' class='{$hasTTClass} admin-{$toolbutton}button ui-state-default {$css} link {$classes} {$disClass}' style='{$style}' {$onclick} {$alt}>";
    }
    if($icon && $image && $text){
      $rtn .= "<span class='ui-icon' style='background-image:url(\"{$_CONFIG->root}img/{$image}\"); background-position:center center; margin:-8px 5px 0 0; top:50%; left:0.6em; position:absolute;' title='{$title}' ></span>";
    }elseif($icon && $image){
      $rtn .= "<span class='ui-icon' style='background-image:url(\"{$_CONFIG->root}img/{$image}\"); background-position:center center; ' title='{$title}' ></span>";
    }
    if($text){
      $rtn .= con($text);
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
    return $rtn;//. $disabled;
  }

  function jbutton($params, $smarty ){
    global $_CONFIG;
    $name = is($params['name']    );
    $url  = is($params['url']    );
    $type  = is($params['type'], 2);
    $classes = is($params['classes'],'');
    $style   = is($params['style'],'');
    $idname  = is($params['id'], $name);
    $disabled= is($params['disable'],false);
    $onclick = is($params['onclick'], '');

    if(!empt($name,false)){
      return;
    }
    $button = false;
    $text = false;
    $icon = false;
    $disClass = '';
    $disAtr = '';

    //Find what to show
    if($type===1 || $type >= 3){
      $text = $name;
    }
    if(is($params['image'],false)){
      $icon = true;
      $image = $params['image'];
    }elseif($type===2 || $type >= 3){
      $iconArr = array(
        'add'=>array('image'=>'ui-icon-plusthick'),
        'edit'=>array('image'=>'ui-icon-pencil'),
        'view'=>array('image'=>'ui-icon-comment'),
        'list'=>array('image'=>'ui-icon-arrowthick-1-w'),
        'delete'=>array('image'=>'ui-icon-trash'),
        'remove'=>array('image'=>'ui-icon-trash'),
        'home'=>array('image'=>'ui-icon-home')
        );
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
    if ($onclick) {
      $onclick = ' onclick="'.$onclick.'"';
    }
    if($disabled){
      $disAtr = " disabled='disabled' ";
      $disClass = " ui-state-disabled ";
      if (!$button) $url = '';
      $onclick = '';
    }
    //Tooltip stuff
    $toolTipName = $this->hasToolTip($name);
    $hasTTClass = 'has-tooltip';
    $toolTipText = con($toolTipName);
    if(is($params['showtooltip'], false)===false){
      $hasTTClass = '';
      $title = is($params['title'],con($name));

    }elseif(empt($params['tooltiptext'],false)){
      $toolTipText = $params['tooltiptext'];
      $toolTipName = empt($toolTipName,$name."-tooltip");

    }elseif(!empt($toolTipName,false)){
      $hasTTClass = '';
      $title = con($name);
    }

    $alt     = is($params['alt'],is($title,con($name)));
    if ($alt) {
      $alt = " alt='{$alt}'";
    }
    $rtn = "";

    //If image bolt on image css for button
    if($icon && $image){
      $css = "{icons: {primary: '{$image}' }";
      if (!$text) $css .= ', text: false';
      $css .= '}';
    }else{ $css = ''; }
    if ($style) $style=" style='{$style}'";
    $class= trim("{$hasTTClass} {$classes} {$disClass}");
    if ($class) $class=" class='{$class}'";

    if(!$button){
      $rtn .= "<a id='{$idname}'{$class}{$style} href='".empt($url,'#')."' title='{$title}'{$onclick}{$alt}>";
    }else{
      $rtn .= "<button $disAtr type='{$url}' name='{$name}' id='{$idname}'{$class}{$style}{$onclick}{$alt}>";
    }
    $this->addJQuery("$(\"#{$idname}\").button({$css});");
/*    if($icon && $image && $text){
   $rtn .= "   <span class='ui-icon' style='background-image:url(\"{$_CONFIG->root}img/{$image}\"); background-position:center center; margin:-8px 5px 0 0; top:50%; left:0.6em; position:absolute;' title='{$title}' ></span>";
   }elseif($icon && $image){
   $rtn .= "   <span class='ui-icon' style='background-image:url(\"{$_CONFIG->root}img/{$image}\"); background-position:center center; ' title='{$title}' ></span>";
   }
*/
    if($text){
      $rtn .= con($text);
    } else {
      $rtn .= $title;
    }
    //Add on the Tooltip div for the text
/*    if(!empty($hasTTClass)){
   $rtn .= "<div id='{$toolTipName}' style='display:none;'>{$toolTipText}</div>";
   } */
    if(!$button){
      $rtn .= "</a>";
    }else{
      $rtn .= "</button>";
    }
    return $rtn;//. $disabled;
  }

  function hasErrors () {
    return hasErrors();
  }

  public function getJQuery(){
    return $this->jScript;
  }

  protected function addJQuery($script){
    $this->jScript .= $script."\n";
  }


  protected function hasToolTip($constantName){
    if( defined($constantName."-tooltip")){
      return false;
    }else{
      return $constantName."-tooltip";
    }
  }


  function viewUrl ($params, $smarty) //($name, &$data,  = '')
  {
    $name = is($params['name']    );
    return $this->showlabel($name, "<a href='{$this->guidata[$name]}' target='blank'>{$this->guidata[$name]}</a>",$params['nolabel'],$params);
  }

  function select ($params, &$smarty) //($name, &$data, &$err, $opt)
  {
    return $this->selection ($params, $smarty); //($name, &$data, &$err, $opt)
  }

  function selection ($params, $smarty) //($name, &$data, &$err, $opt)
  {
    $name = is($params['name']);
    $idname  = is($params['id'], $name);

    $opt  = is($params['options']);
    $prefix = is($params['prefix']);
    $mult =   is($params['multiselect']);
    $size =   is($params['size']);
    $con  =   is($params['con']);
    $class  =   is($params['class']);
    $style  =   is($params['style']);
    $nokey =  is($params['nokey'], false);
    $value = is($params['value'], $this->guidata[$idname]);
    $value = is($value, $params['default']);
    $validate = is($params['validate'] ,array());
    $this->checkRequired($params);
    if ($params['required']) {
      $validate['required']='true';
    }

    if ($style) $style = "style='{$style}' ";
    if ($class) $class = "class='{$class}' ";
    If (!is_array($opt)) {
      $opt  = explode('|',$opt);
    }
//    print_r($opt);
    // $val=array('both','rows','none');
    if (is_array($value)) {
      foreach($value as $v) {
        $sel[$v] = " selected ";
        $mult = true;
      }
    } else {
      $sel[$value] = " selected ";
    }

    $mult = ($mult)?"multiple":'';
    $mult .= ($size)?" size='$size'":'';
    if (count($opt)==1 && !is($params['showalways'])) {
      $key = array_keys($opt);
      $params['value'] = is($value, $key[0]);
      return $this->hidden($params,$smarty );
    }
    $validate  = $this->validate(',',$validate );

    $return = "<select {$class}{$style} id='$idname' name='$name' $mult value='$value' validate='{$validate}'>\n";
    if (is($params['DefaultEmpty'],false)) {
      $return .= "<option value=''>" .  con('select_'.$idname) . "</option>\n";
    }

    foreach($opt as $v => $n) {
        if (is_array($n)) {
          $return .= "<optgroup label='{$v}'>\n";
          foreach($n as $v2 => $n2) {
            if($nokey) {
              $v2 = $n2;
            }
            $cap = ($prefix or $con)?con($prefix.$n2):$n2;
            $return .= "<option value='". htmlspecialchars($v2)."' {$sel[$v2]}>" .  htmlspecialchars($cap) . "</option>\n";
          }
          $return .= "</optgroup>\n";
          continue;
        } elseif (strpos($n, '~')!==false) {
          list($v, $n) = explode('~',$n);
        } elseif($nokey) {
          $v = $n;
        }
        $cap = ($prefix or $con)?con($prefix.$n):$n;
        $return .= "<option value='". htmlspecialchars($v)."' {$sel[$v]}>" .  htmlspecialchars($cap) . "</option>\n";
    }

    return $this->showlabel($name, $return. "</select>", $params);
  }

  protected function loadCountrys() {
    global $_SHOP,  $_COUNTRY_LIST;
    if (!isset($_COUNTRY_LIST)) {
      If (file_exists(INC."lang".DS."countries_". $_SHOP->lang.".inc")){
        include_once(INC."lang".DS."countries_". $_SHOP->lang.".inc");
      }else {
        include_once(INC."lang".DS."countries_en.inc");
      }
    }
  }
  function getCountry($name){
    global $_SHOP, $_COUNTRY_LIST;
    self::Loadcountrys();
    return $_COUNTRY_LIST[$name];
  }


  function getCountryName($params){
    global $_SHOP, $_COUNTRY_LIST;
    $name     = is($params['name']);
    self::Loadcountrys();
    return $_COUNTRY_LIST[$name];
  }
  function viewCountry($params, $smarty){
    global $_SHOP, $_COUNTRY_LIST;
    $this->Loadcountrys();
    if (!isset($params['value'])){
      $name     = is($params['name']);
      $val=strtoupper($this->guidata[$name]);
    } else {
      $val=strtoupper($params['value']);
    }
    $params['value'] = $_COUNTRY_LIST[$val];
    return $this->view($params,$smarty);
  }

  function selectCountry($params, $smarty) { //($sel_name, $selected, &$err){
    global $_SHOP,  $_COUNTRY_LIST;
    $this->Loadcountrys();
    if (isset($params['DefaultEmpty'])) {
      $params['options'] = array_merge(array(''=>'['.con('select_country').']'), $_COUNTRY_LIST);
    }else{
      $params['options'] = $_COUNTRY_LIST;
    }
    return $this->selection($params, $smarty);
  }

  protected function loadStates() {
    global $_SHOP,  $_STATE_LIST;
    if (!isset($_STATE_LIST)) {
      If (file_exists(INC."lang".DS."states_". $_SHOP->lang.".inc")){
        include_once(INC."lang".DS."states_". $_SHOP->lang.".inc");
      }
    }
  }

  function viewState($params, $smarty){
    global $_SHOP, $_STATE_LIST;
    $this->LoadStates();
    $name     = is($params['name']);
    if (isset($_STATE_LIST)) {
      $val=strtoupper($this->guidata[$name]);
      $params['value'] = $_STATE_LIST[$val];
    } else {
      $params['value'] = $this->guidata[$name];
    }

    return $this->view($params, $smarty);
  }


  function selectState($params, $smarty) { //($sel_name, $selected, &$err){
    global $_SHOP,  $_STATE_LIST;
    $this->LoadStates();
    if (isset($_STATE_LIST)) {
      $params['options'] = $_STATE_LIST;
      return $this->selection($params, $smarty);
    } else {
      return $this->input($params, $smarty);
    }
  }

  function selectColor ($params, $smarty) //($name, &$data, &$err)
  {
    $name = is($params['name']);

    $return = "<select name='$name'>\n";

    $act = $this->guidata[$name];

    for($r = 16;$r < 256;$r += 64) {
        for($g = 16;$g < 256;$g += 64) {
            for($b = 16;$b < 256;$b += 64) {
                $color = '#' . dechex($r) . dechex($g) . dechex($b);
                if ($act == $color) {
                    $return .= "<option value='$color'style='color:$color;' selected>$color</option>\n";
                } else {
                    $return .= "<option value='$color'style='color:$color;'>$color</option>\n";
                }
            }
        }
    }

    return $this->showlabel($name, $return."</select>",$params);
  }

  function viewFile ($params, $smarty) //($name, &$data, &$err, $type = 'img',  = '')
  {
    $name = is($params['name']);
    $type = is($params['type'],'img');

    if ($this->guidata[$name]) {
      $src = $this->user_file($this->guidata[$name]);
      if ($type == 'img') {
        // NVDS: there must be some size checking here.
        $return = "<img  src='$src'>";
      } else {
        $return = "<a class=link href='$src'>{$this->guidata[$name]}</a>";
      }
      return $this->showlabel($name, $return, $params);
    }
  }

  function inputFile ($params, $smarty) //($name, &$data, &$err, $type = 'img',  = '')
  {
    $name = is($params['name']);
    $type = is($params['type'],'img');
    $value = is($params['value'], $this->guidata[$name]);
    if (!$value) {
        return $this->showlabel($name, "<input type='file' name='$name'>",$params);
    } else {
      $src = $this->user_url('files/'.$this->guidata[$name]);
      $loc = $this->user_file($this->guidata[$name]);
      if (!file_exists($loc)) {
        $return = "<b>".con("image_not_found")."</b>";
        $remove_text = ($type == 'img')?"remove_image":"remove_link";
      }elseif ($type == 'img') {
         list($width, $height, $type, $attr) = getimagesize($loc);

         if (($width>$height) and ($width > 300)) {
           $attr = "width='300'";
         } elseif ($height > 250) {
           $attr = "height='250'";
         }

         $return = "<img $attr src='$src'>";
         $remove_text = "remove_image";
      } else {
          $return = "<a href='$src'>".con('input_link')."</a>";
          $remove_text = "remove_link";
      }
      return $this->showlabel($name, $return . "<br><input type='checkbox' id='remove_$name'  name='remove_$name' value='1'><label for='remove_$name'>".con($remove_text)."</label><br><input type='file' size=35 name='$name'>" ,$params);
    }
  }

  function Navigation($params, $smarty) { //($offset, $matches, $url, $stepsize=10)
    $name     = is($params['name'],'offset');
    //var_dump($params);
    $offset   = is($params['offset'], $_SESSION[$name]);
    unset($params['offset']);

    $matches  = is($params['count'],0);
    $stepsize = is($params['length'],10);
    $maxpages = is($params['maxpages'],10);
    $params['a'] = is($params['a'],$this->action);
    if (!$params['a']) { unset($params['a']);}

   // If ($matches<=$stepsize ) {return "";}

    //TODO: Should this be using a new pagnation method?
    $url     = $this->url( $params, $smarty, array('name',$name,'maxpages','count','length'));

    $breaker = ( strpos($url,'?')===false)?'?':'&';
    $output = '<div class="gui_pager">';

    if ($offset<0) {$offset=0;}
    if ($offset !=0){
      $output .= $this->button(array("url"=>$url.$breaker.$name."=0", 'name'=>'nav_first', 'toolbutton'=>true), $smarty);
      $output .= $this->button(array("url"=>$url.$breaker.$name."=".max(0,$offset-$stepsize), 'name'=>'nav_prev', 'toolbutton'=>true), $smarty);
    } else {
      $output .= $this->button(array("url"=>$url.$breaker.$name."=0", 'name'=>'nav_first', 'disable'=>true, 'toolbutton'=>true), $smarty);
      $output .= $this->button(array("url"=>$url.$breaker.$name."=".max(0, $offset-$stepsize), 'name'=>'nav_prev', 'disable'=>true, 'toolbutton'=>true), $smarty);
    }

    $offpages=intval($offset/$stepsize);
    if ($offset%$stepsize) {$offpages++;}

    $pages=intval($matches/$stepsize);
    if ($matches%$stepsize) {$pages++;}
    $start = 1;
    if ($offpages >= intval($maxpages/2)){
         $start = $offpages - intval($maxpages/2);
         If ($start < 2) $start =2;
         //if ($start >= $pages-$maxpages) $start = $pages-$maxpages;
         $output .= '&nbsp;...&nbsp;';
         }
    for ($i=$start;$i<=$pages;$i++) {
      if (($i-$start == $maxpages-1) and ($i<$pages)) {
         $output .= '&nbsp;...&nbsp;';
         break;
      }
      if ($offpages+1 == $i){
         $output .= $this->button(array("url"=>$url.$breaker.$name."=".($stepsize*($i-1)), 'name'=>$i, 'disable'=>true, 'toolbutton'=>true),$smarty);
      } else {
         $output .= $this->button(array("url"=>$url.$breaker.$name."=".($stepsize*($i-1)), 'name'=>$i, 'toolbutton'=>true),$smarty);
      }
    }
    if (!($offset+$stepsize >= $matches)) {
      $output .= $this->button(array("url"=>$url.$breaker.$name."=".min($matches-1,$offset+$stepsize), 'name'=>'nav_next', 'toolbutton'=>true), $smarty);
      $output .= $this->button(array("url"=>$url.$breaker.$name."=".($matches-$stepsize), 'name'=>'nav_last', 'toolbutton'=>true), $smarty);
    } else {
      $output .= $this->button(array("url"=>$url.$breaker.$name."=".min($matches-1,$offset+$stepsize), 'name'=>'nav_next', 'disable'=>true, 'toolbutton'=>true), $smarty);
      $output .= $this->button(array("url"=>$url.$breaker.$name."=".($matches-$stepsize), 'name'=>'nav_last', 'disable'=>true, 'toolbutton'=>true), $smarty);
    }
    $output .= '</div>';
    return  $output;
  }


  function tabBar($params , $smarty) {
    $TabBarid  = is($params['TabBarid'],'TabBarid');
    $Tabname   = is($params['name'],'tab');
    $menuAlign = is($params['align'],'left');
    $value     = is($params['value']);
    $menu      = is($params['menu']);

    if (!empty($value)) {
      $_SESSION[$TabBarid] = $value;
    } elseif (isset($_REQUEST[$Tabname])) {
      $_SESSION[$TabBarid] = $_REQUEST[$Tabname];
    } elseif (!isset($_SESSION[$TabBarid])) {
      $_SESSION[$TabBarid] = '';
    }

    If (!is_array($menu)) {
      $opt  = explode('|',$menu);
      $menu = array();
      foreach ($opt as $key => $val) {
        list($k,$a) = explode('=',$menu);
        $menu[$k] = $a;
      }
    }
    $smarty->assign($TabBarid, $_SESSION[$TabBarid]);
    return  PrintTabMenu($menu, $_SESSION[$TabBarid], $menuAlign,$Tabname);
  }

  function captcha($params, $smarty) //($name)
  {
  global $_SHOP;
    //print_r($smarty);
    $name = is($params['name']);
    $validate = is($params['validate'] ,array());
    $validate['required'] = 'true';
//   $validate .= 'remote: {url:\'nospam.php\', type:\'post\', data:{ name:\'user_nospam\', check: function() { return $(\'#'.$name.'\').val(); } } }';

//  $this->addJQuery(" $(\"#{$id}\").validate(); ");
/*    $this->addJQuery("
    $('#{$this->gui_form_id}').validate({
      rules: {
        user_nospam: {
          required: true,
          remote: {
            url: 'nospam.php',
            type: 'post',
            data: {
              name: '{$name}',
              check: function() { return $('input[name$=\"{$name}\"]').val(); }
            }
          }
        }
      }
    });
");*/
    $params['noerror'] = true;
    $validate  = $this->validate(',',$validate );

    return $this->showlabel($name,
//           "<span style='width:100%;margin:0;clear:none; display: inline-block;'>\n".
           "     <img src='{$_CONFIG->root}nospam.php?name={$name}' alt='' style='float:right; margin:1px;padding:0;' border=1>\n".
           "     <input type='hidden' name='_~nospam~_' value='".base64_encode($name)."'>\n".
           "     <input type='text' id='{$name}' name='{$name}' size='10' maxlength='15' value='' validate=\"{$validate}\">". printMsg($name)."<br>\n".
           "     <f6>".con('captcha_info')."</f6>\n".
//           "</span>".
           "\n",$params);
  }

  function delayedLocation($params, $smarty) { //($url){
      $url = $this->view->_URL($params);
      return "<SCRIPT LANGUAGE='JavaScript'>
            <!-- Begin
                 function runLocation() {
                   location.href='{$url}';
                 }
                 window.setTimeout('runLocation()', 1500);
            // End -->\n</SCRIPT>\n";
  }

  function valuta ($params, $smarty){
    global $_SHOP;

    $valuta = valuta($params['value'], $params['code']);

    if(!empty($params['assign'])){
      $smarty->assign($params['assign'],$valuta);
    }else{
      return $valuta;
    }
  }

  function print_set ($params, $smarty) //($name, &$data, $table_name, $column_name, $key_name, $file_name)
  {
      $ids = explode(",", $this->guidata);
      $set = array();
      if (!empty($ids) and $ids[0] != "") {
          foreach($ids as $id) {
              $query = "select {$column_name} as id from {$table_name} where {$key_name}="._esc($id);
              if (!$row = MySQL::query_one_row($query)) {
                  return '';
              }
              $row["id"] = $id;
              array_push($set, $row);
          }
      }
      $result = '';
      if (!empty($set)) {
          foreach ($set as $value) {
              $result .= "<a class='link' href='$file_name?action=view&$key_name={$value['id']}'>" . $value[$column_name] . "</a><br>";
          }
      }
    return $this->showlabel($name, $result,$params);
  }

  function image($params, &$smarty) {
    global $_SHOP;
    $file      = ROOT.'files'.DS.is($params['href'],'None');
    $src       = $_SHOP->files_url .$params['href'];
    $newwidth  = is($params['width'],100);
    $newheight = is($params['height'],100);
    if(!file_exists($file)){
      $src = $file      = $_SHOP->images_url . "theme/".$_SHOP->theme_name.'/'.'na.png';
    }
      list($width, $height, $type, $attr) = getimagesize($file);
      if (($width>$height) and ($width > $newwidth)) {
				$attr = "width='{$newwidth}'";
			} elseif ($height > $newheight) {
				$attr = "height='{$newheight}'";
			}
			echo "<img $attr src='$src'>";
    }

  protected function user_url($data){
      global $_SHOP;
      return $_SHOP->root . $data;
  }

  protected function user_file ($path) {
      return ROOT. 'files'. DS . $path;
  }


}


function smarty_modifier_clean($string, $type='ALL') {
  return clean($string, $type);
}

function PrintTabMenu($linkArray, $activeTab=0, $menuAlign="center", $tabname='tab') {
  Global $_CONFIG;
	$tabCount=0;

	$str= "<table width=\"100%\" cellpadding=0 cellspacing=0 border=0  class=\"UITabMenuNav\">\n";
	$str.= "<tr>\n";
	if($menuAlign=="right"){
    $str.= "<td width=\"100%\" align=\"left\">&nbsp;</td>\n";
  }
	foreach ($linkArray as $k => $v){
    $menuStyle=($k==$activeTab)?"UITabMenuNavOn":"UITabMenuNavOff";
    $str.= "<td valign='top' height='16' width= '20px' class='{$menuStyle}left '>&nbsp;</td>\n";
    $str.= "<td nowrap='nowrap' align='center' valign='middle' class='{$menuStyle}'>\n";
    $str.= "  <a class='$menuStyle' href='?{$tabname}=$k'>". $v . "</a>";
    $str.= "</td>\n";
    $str.= "<td valign=\"top\" class='{$menuStyle}right'>&nbsp;</td>\n";
    $str.= "<td width=\"1pt\">&nbsp;</td>\n";
    $tabCount++;
  }
	if($menuAlign=="left"){
    $str.= "<td width=\"100%\" align=\"right\">&nbsp;</td>";
  }
	$str.= "</tr>\n";
	$str.= "</table>\n";
 return $str;
}

function weeksofyear($year){
  $result = idate("W",mktime(0,0,0,12,28, $year)); //idate('W', strtotime("31 dec ".is($params,$_SESSION['settings']['jaar'])));
  return $result;
}

?>