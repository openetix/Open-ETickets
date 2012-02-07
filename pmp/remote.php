<?PHP
define('ft_check','shop');
  require_once('../includes/config/init_common.php');
  require_once('../includes/config/init.php');
  //print_r($_REQUEST);
  $pmp_id = (int)is($_REQUEST['pmp_id'],5);
  $pmp = PlaceMapPart::loadfull($pmp_id);
  if (!$pmp) {
    return;
  }
  $stats = $pmp->getstats();
  switch ($_GET['load']) {
     case 'zones' :
        $responce->page = 1;
        $responce->total = count($pmp->zones);
        $responce->records = count($pmp->zones);
        if (!empty($pmp->zones)) {
          $i=0;
          foreach($pmp->zones as $zone_ident => $zone) {
            $responce->rows[$i]['id']=$zone->pmz_ident;
            $responce->rows[$i]['cell']=array("<div style='background-color:{$zone->pmz_color};'>&nbsp;</div>", "{$zone->pmz_name} ({$zone->pmz_short_name})",$stats->zones[$zone_ident],'<a class"link" id="renumber" href="#"><img height=15 src="../images/numbers.png" border="0" alt="' . con('edit') . "' title='" . con('edit') . "'></a>\n");
            $i++;
          }
        }
        echo json_encode($responce);
        break;
     case 'cats' :
        $responce->page = 1;
        $responce->total = count($pmp->categories);
        $responce->records = count($pmp->categories);
        if (!empty($pmp->categories)) {
          $i=0;
          foreach($pmp->categories as $ident => $category) {
            $responce->rows[$i]['id']=$ident;
            $responce->rows[$i]['cell']=array("<div style='background-color:{$category->category_color};'>&nbsp;</div>", "{$category->category_name}","{$category->category_price} {$_SHOP->currency}",$stats->categories[$ident],$category->category_numbering);
            $i++;
          }
        }
        echo json_encode($responce);
        break;
    case 'grid':
        $type = (isset($_GET['type']))? (string)($_GET['type']):'';
        $myid = (isset($_GET['id']))? (int)($_GET['id']):0;
        $imagesize = 20;
        echo '
<style type="text/css">
  .pm_seatmap {
      display: inline-block;
      nowrap;
      position: relative;
      overflow: hidden;
      white-space: nowrap;
      margin:0;
      padding:0;
      vertical-align:middle;
      text-align: center;
      border:1px solid transparent;
      width:'.($imagesize).'px;
      height:'.($imagesize).'px;
      font-size: '.((int)($imagesize)/1.75).'px;
      cursor:pointer;
  }
  .pm_seatmap img {
     border:1px dashed transparent;
  }
  .pm_shiftright {
    margin:0;padding:0;
    vertical-align:middle;
    text-align: center;
     border:0px dashed transparent;
     width:'.((int)($imagesize/2)).'px;
     height:'.($imagesize).'px;
  }
  .pm_table {margin:5px;}
  .pm_info{width:100%;}
  .pm_box{width:600px; background-color:#FFFFFF; padding:10px;}
  .pm_nosale{background-color:#d2d2d2}

  .pm_ruler {background-repeat:no-repeat; text-indent: 0px; margin-left:2px; margin-top:1px;}

  .pm_free     {background-image: url("../images/1.png");background-repeat:no-repeat;background-position:center center}

  .pm_occupied {background-image: url("../images/3.png");background-repeat:no-repeat;background-position:center center}

  .pm_none     {background-image: url("../images/icon5.png");background-repeat:no-repeat; background-position:center center}
  .pm_all      {background-image: url("../images/2.png");background-repeat:no-repeat; background-position:center center}
  .pm_number      {background-image: url("../images/8.png");background-repeat:no-repeat; background-position:center center}

  .pm_check {
    cursor:pointer;
  }

  .pm_first {
     clear:both;
  }

  .pm_checkx:hover {
    background-color:#4F07E2;
    cursor:pointer;
  }
</style><div id="selectable" style="border:1px solid red;vertical-align:top;text-align: left; width:'.(($pmp->pmp_width)*($imagesize+2)).'px; ">';
$y = 0; $x = 0;
        for($j = 0;$j < $pmp->pmp_height;$j++) { //
            $y = 0;// ($j*($imagesize+2));
            $x = 0; // ($k*($imagesize+2)) ;
            for($k = 0;$k < $pmp->pmp_width;$k++) {
                $xm = 0;
                $col = '';
                $chk = '';
                $sty = "left: {$x}px; top: {$y}px;";
                $label = $pmp->data[$j][$k];
                if ($z = $label[PM_ZONE]) {
                    if ($z == 'L') {
                        $sty .= "border: 1px dashed #666666;background-color:#dddddd;";
                        if (($type==$label[PM_LABEL_TYPE])) {
                          $chk = 'checked';
                        }

                       // echo "<SPAN class='pm_seatmap pm_check' style='{$sty}'><input type='hidden' name='seat[$j][$k]' value=1 title=\"{$label[PM_LABEL_TYPE]} {$label[PM_LABEL_SIZE]} {$label[PM_LABEL_TEXT]}\" $chk >a</SPAN>"; //style='border:0px;'
                        if ($label[PM_LABEL_TYPE] == 'RE' ) {
                            $irow = empt($pmp->data[$j][$k + 1][PM_ROW],"<span class='ui-icon pm_ruler ui-icon-triangle-1-e'></span>");
                            echo "<div id='seat[$j][$k]' class='pm_seatmap pm_number'>$irow</div>";
                        } elseif ($label[PM_LABEL_TYPE] == 'RW') {
                            $irow = empt($pmp->data[$j][$k - 1][PM_ROW],"<span class='ui-icon pm_ruler ui-icon-triangle-1-w'></span>");
                            echo "<div id='seat[$j][$k]' class='pm_seatmap pm_number'>$irow</div>";
                        } elseif ($label[PM_LABEL_TYPE] == 'SS') {
                             $iseat = empt($pmp->data[$j + 1][$k][PM_SEAT],"<span class='ui-icon pm_ruler ui-icon-triangle-1-s'></span>");;
                            echo "<div id='seat[$j][$k]' class='pm_seatmap pm_number'>$iseat</div>";
                        } elseif ($label[PM_LABEL_TYPE] == 'SN') {
                            $iseat = empt($pmp->data[$j - 1][$k][PM_SEAT],"<span class='ui-icon pm_ruler ui-icon-triangle-1-n'></span>");
                            echo "<div id='seat[$j][$k]' class='pm_seatmap pm_number'>$iseat</div>";
                        } elseif ($label[PM_LABEL_TYPE] == 'T' ) {
                          if (strlen($label[PM_LABEL_TEXT])>3){
                             echo "<div id='seat[$j][$k]' class='pm_seatmap pm_number' alt='{$label[PM_LABEL_TEXT]}' title='{$label[PM_LABEL_TEXT]}'><span class='ui-icon pm_ruler ui-icon-comment'></span></div>";
                           } else {
                             echo "<div id='seat[$j][$k]' class='pm_seatmap pm_number'>{$label[PM_LABEL_TEXT]}</div>";
                          }
                        } elseif ($label[PM_LABEL_TYPE] == 'E') {
                          echo "<div id='seat[$j][$k]' class='pm_seatmap pm_number' title='exit' alt='exit'><span class='ui-icon pm_ruler ui-icon-extlink' alt='exit'></span></div>";
                        } else {
                           $x += ($imagesize+2) ;
                        }
                        continue;
                    }

                    $zone = $pmp->zones[$z];

             //       $sty .= "background-color:{$zone->pmz_color};border:1px solid {$zone->pmz_color};";

                    $cat_id = $label[PM_CATEGORY];
                    $category = $pmp->categories[$cat_id];

                    if ($cat_id) {
                        if ($pmp->data[$j - 1][$k][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-top:1px solid {$category->category_color};";
                         }
                        if ($pmp->data[$j + 1][$k][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-bottom:1px solid {$category->category_color};";
                        }

                        if ($pmp->data[$j][$k - 1][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-left:1px solid {$category->category_color};";
                        }

                        if ($pmp->data[$j][$k + 1][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-right:1px solid {$category->category_color};";
                        }
                    }
                    $cls = '';
                    if (($type=='C' and $myid == $cat_id) or ($type=='Z'  and $myid == $z)) {
                        $cls = 'ui-selected';
                    }

                    echo "<div id='seat[$j][$k]' class='ui-selectee pm_seatmap pm_free {$cls}' style='{$sty}' title=\"Zone: {$zone->pmz_name}| \nCat: {$category->category_name}| \nSeat: {$pmp->data[$j][$k][PM_ROW]}/{$pmp->data[$j][$k][PM_SEAT]} \" ></div>";

                } else {
                    echo "<div id='seat[$j][$k]' class='ui-selectee pm_seatmap pm_none' style='{$sty}'> </div>";
 //                 $x += ($imagesize+2) ;
                }
            }
            echo "<br>";
        }

        echo "</div>";
        echo '	<style type="text/css">
	#selectable .ui-selecting { background-color: #FECA40; }
	#selectable .ui-selected { background-color: #F39814; }

	</style>
	<script type="text/javascript">
	$(function() {
		$("#selectable").selectable({ autoRefresh: false, filter:"div" });
  //  $( ".pm_check" ).draggable();
	});
	</script>';
        break;
    default: print_r($pmp);

  }
?>