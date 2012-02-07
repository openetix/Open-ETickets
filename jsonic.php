<?php
//Load JSON handler
define('ft_check','remote');
require_once (dirname(__FILE__).'/includes/config/defines.php' );
require_once("classes/class.restservice.client.php");

error_reporting(E_ALL);

set_error_handler("customError");

if (file_exists('jsonic.dat')) {
  $localData = json_decode(file_get_contents('jsonic.dat'),true);
} else {
  $localData['servertoken'] = 'totamPaal';
  $localData['Events'] = array();
}
//if (!isset($localData['Products'] )) {
  $localData['Products'] = array();
  $localData['Products'][] = array('type'=>'d', 'number'=>365, 'price'=>0.0, 'percentage'=>0.0,
                                    'seats'=>600, 'amount'=>20000.00 ,'EventsinSlot'=>1, 'EventsAllowed'=>4);
  $localData['Products'][] = array('type'=>'d', 'number'=>180, 'price'=>35.5, 'percentage'=>0.0,
                                    'seats'=>150, 'amount'=>10000.00 ,'EventsinSlot'=>-1, 'EventsAllowed'=>0);
  $localData['Products'][] = array('type'=>'e', 'number'=>5, 'price'=>5.5, 'percentage'=>0.0,
                                    'seats'=>150, 'amount'=>0 ,'EventsinSlot'=>0, 'EventsAllowed'=>0);
  $localData['Products'][] = array('type'=>'e', 'number'=>5, 'price'=>5.5, 'percentage'=>0.0,
                                    'seats'=>150, 'amount'=>10000.00 ,'EventsinSlot'=>0, 'EventsAllowed'=>0);
  $localData['Products'][] = array('type'=>'d', 'number'=>180, 'price'=>35.5, 'percentage'=>0.0,
                                    'seats'=>150, 'amount'=>10000.00 ,'EventsinSlot'=>-1, 'EventsAllowed'=>0);
  $localData['Products'][] = array('type'=>'e', 'number'=>5, 'price'=>5.5, 'percentage'=>0.0,
                                    'seats'=>150, 'amount'=>0 ,'EventsinSlot'=>0, 'EventsAllowed'=>0);
  $localData['Products'][] = array('type'=>'e', 'number'=>5, 'price'=>5.5, 'percentage'=>0.0,
                                    'seats'=>150, 'amount'=>0 ,'EventsinSlot'=>0, 'EventsAllowed'=>0);
  $localData['Products'][] = array('type'=>'e', 'number'=>5, 'price'=>5.5, 'percentage'=>0.0,
                                    'seats'=>150, 'amount'=>10000.00 ,'EventsinSlot'=>0, 'EventsAllowed'=>0);
  $localData['Products'][] = array('type'=>'d', 'number'=>180, 'price'=>35.5, 'percentage'=>0.0,
                                    'seats'=>150, 'amount'=>10000.00 ,'EventsinSlot'=>-1, 'EventsAllowed'=>0);
  $localData['Products'][] = array('type'=>'e', 'number'=>5, 'price'=>5.5, 'percentage'=>0.0,
                                    'seats'=>150, 'amount'=>0 ,'EventsinSlot'=>0, 'EventsAllowed'=>0);

  if (!$localData['Orders']) {
    $localData['Orders'][0] = array('ordered'=>1, 'orddate'=>'2011-05-08', 'used'=>4, 'product'=>0,'orderid'=> 0 , 'status'=>'paid');
    $localData['Orders'][1] = array('ordered'=>1, 'orddate'=>'2011-05-08', 'used'=>0, 'product'=>1,'orderid'=> 0 , 'status'=>'paid');
  }

//}
if (!isset($localData['Requested'])) {
  $localData['Requested'] = array('seats'=>0,'amount'=>0 ,'EventsinSlot'=>0, 'EventsAllowed'=>0);


}
$localData['CurrentAllwod'] = array('seats'=>0,'amount'=>0 ,'EventsinSlot'=>0, 'EventsAllowed'=>0);
$localData['CurrentUsed']   = array('EventsinSlot'=>0, 'eventsused'=>0);

  foreach($localData['Orders'] as $order) {
    if ($order['ordered']>0 ) {//&& $order['ordered']=='paid'
      $product = $localData['Products'][$order['product']];
      $use = false;
      if ($product['type']=='d' && time() < addDaysToDate($order['orddate'],$product['number']) ) {
        $use = true;
      } elseif ($product['type']=='e' && $order['used'] < $product['number'] ) {
        $use = true;
      }

      if ($use) {
        $localData['CurrentAllwod']['seats']         = calcValue($localData['CurrentAllwod']['seats']        , $order['ordered']* $product['seats']);
        $localData['CurrentAllwod']['amount']        = calcValue($localData['CurrentAllwod']['amount']       , $order['ordered']* $product['amount']);
        $localData['CurrentAllwod']['EventsinSlot']  = calcValue($localData['CurrentAllwod']['EventsinSlot'] , $order['ordered']* $product['EventsinSlot']);
        if ($product['type']=='e') {
          $localData['CurrentAllwod']['EventsAllowed'] = calcValue($localData['CurrentAllwod']['EventsAllowed'],
                                                                   $order['ordered']* $product['number']);
        } else {
          $localData['CurrentAllwod']['EventsAllowed'] = calcValue($localData['CurrentAllwod']['EventsAllowed'],
                                                                   $order['ordered']* $product['EventsAllowed']);
        }
        $localData['CurrentUsed']['eventsused']     += $order['used'];
      }
    }
  }
  foreach($localData['Events'] as $event) {
    if ($event['state'] == 'pub') {
      $localData['CurrentUsed']['EventsinSlot']     += 1;
    }
  }
  $localData['MaxValues'] =  array('seats'=> $localData['CurrentAllwod']['seats'] ,
                       'amount'=>$localData['CurrentAllwod']['amount'] ,
                       'EventsinSlot'=>($localData['CurrentAllwod']['EventsinSlot']==-1)?-1:
                                        max(0,$localData['CurrentAllwod']['EventsinSlot']-$localData['CurrentUsed']['EventsinSlot']),
                       'EventsAllowed'=>($localData['CurrentAllwod']['EventsAllowed']==-1)?-1:
                                        max(0,$localData['CurrentAllwod']['EventsAllowed']-$localData['CurrentUsed']['eventsused']));

if (!isset($_POST['action'])) {
  echo "
    <table width='100%' border=1>
      <tr bgcolor='blue'>
        <th colspan=7><h2>Producten</h2></th>
      </tr>
      <tr>
        <th>ID</th><th>Valid for</th><th>seats</th><th>amount</th><th>EventsinSlot</th><th>EventsAllowed</th>
      </tr>
    ";
  foreach($localData['Products'] as $key => $value) {

    echo "<tr>
        <td>$key</td><td>{$value['number']} {".($value['type']=='d'?'Days':'Events')."}</td><td>{$value['seats']}</td><td>{$value['amount']}</td><td>{$value['EventsinSlot']}</td><td>{$value['EventsAllowed']}</td>
        </tr>";
    }
    echo "</table>
   ";
//------------------------------------------------------------------------------//
  echo "<br>
    <table width='100%' border=1>
      <tr bgcolor='blue'>
        <th colspan=7><h2>Orders</h2></th>
      </tr>
      <tr>
        <th>ID</th><th>type</th><th>ordered</th><th>OrderDate</th><th>valid until</th><th>used</th>
      </tr>
    ";
  foreach($localData['Orders'] as $key => $value) {
    $product = $localData['Products'][$value['product']];
    if ($value['type']='d') {
      $enddate = date('Y-m-d',addDaysToDate($value['orddate'],$product['number']));
    }
    echo "<tr>
        <td>$key</td><td>{$value['product']}</td><td>{$value['ordered']}</td><td>{$value['orddate']}</td><td>{$enddate}</td><td>{$value['used']}</td>
        </tr>";
  }
  echo "</table>
   ";
  //------------------------------------------------------------------------------//
  echo "<br>
      <table width='100%' border=1>
        <tr bgcolor='blue'>
          <th colspan=7><h2>Events</h2></th>
        </tr>
        <tr>
          <th>ID</th><th>Status</th><th>Published</th><th>Nosale</th><th>seats</th><th>amount</th>
        </tr>
      ";
  foreach($localData['Events'] as $key => $value) {
    echo "<tr>
        <td>$key</td><td>{$value['state']}</td><td>{$value['pub']}</td><td>{$value['nosal']}</td><td>{$value['seats']}</td><td>{$value['amount']}</td>
        </tr>";
  }
  echo "</table>
     ";
  echo "<br>
      <table width='100%' border=1>
        <tr bgcolor='blue'>
          <th colspan=7><h2>Current settings</h2></th>
        </tr>
        <tr>
          <th></th><th>Allowed</th><th>Used</th><th>MaxValues</th><th>Last requisted</th>
        </tr>
        <tr>
          <th>seats</th>        <td>{$localData['CurrentAllwod']['seats']}</td><td>&nbsp;</td><td>{$localData['MaxValues']['seats']}</td><td>{$localData['Requested']['seats']}</td>
        </tr>
        <tr>
          <th>amount</th>       <td>{$localData['CurrentAllwod']['amount']}</td><td>&nbsp;</td><td>{$localData['MaxValues']['amount']}</td><td>{$localData['Requested']['amount']}</td>
        </tr>
        <tr>
          <th>EventsinSlot</th> <td>{$localData['CurrentAllwod']['EventsinSlot']}</td><td>{$localData['CurrentUsed']['EventsinSlot']}</td><td>{$localData['MaxValues']['EventsinSlot']}</td><td>{$localData['Requested']['EventsinSlot']}</td>
        </tr>
        <tr>
          <th>EventsAllowed</th><td>{$localData['CurrentAllwod']['EventsAllowed']}</td><td>{$localData['CurrentUsed']['eventsused']}</td><td>{$localData['MaxValues']['EventsAllowed']}</td><td>{$localData['Requested']['EventsAllowed']}</td>
        </tr>
      </table>";
  echo '<hr>';
  echo (nl2br( @ file_get_contents('jsonic.log')));
  file_put_contents('jsonic.log', '' ,FILE_TEXT);
  die;

}
  $_POST['jsonic'] = RestServiceClient::deCryptJSON($_POST['json'],$_POST['checksom'],$localData['servertoken']);
//  file_put_contents('jsonic.log', 'in :'.var_export($_POST,true).chr(13) ,FILE_APPEND);
  file_put_contents('jsonic.log', 'action :'.$_POST['action'].' '.json_encode($_POST['jsonic']).chr(13) ,FILE_APPEND);
  try {
    switch ($_POST['action']) {
      case 'MaxValues':
        $value = $localData['MaxValues'];
        break;

      case 'PublishCheck':
        if (($_POST['jsonic']['state'] == 'nosal') ||
            ((($localData['MaxValues']['seats']<0) or ($_POST['jsonic']['seats']  < $localData['MaxValues']['seats'])) &&
             (($localData['MaxValues']['amount']<0) or ($_POST['jsonic']['amount'] < $localData['MaxValues']['amount'])) &&
             (($localData['MaxValues']['EventsinSlot']<0) or ($localData['MaxValues']['EventsinSlot']>0)) &&
             (($localData['MaxValues']['EventsAllowed']<0) or ($localData['MaxValues']['EventsAllowed']>0)))) {

          if (!isset($localData['Events'][$_POST['jsonic']['event']])) {
            $localData['Events'][$_POST['jsonic']['event']] = array();
            foreach($localData['Orders'] as & $order) {
              if ($order['ordered']>0) {
                $product = $localData['Products'][$order['product']];
                $use = false;
                if ($product['type']=='d' && time() < addDaysToDate($order['orddate'],$product['number']) ) {
                  $use = true;
                } elseif ($product['type']=='e' && $order['used'] < $product['number'] ) {
                  $use = true;
                }
                if ($use and ($product['type']=='e' || ($product['EventsAllowed']>0 && $product['EventsAllowed']> $order['used'] ))) {
                  $order['used'] = $order['used'] + 1;
                  break;
                } elseif ($product['EventsAllowed'] == -1){
                  break;
                }
              }
            }
          }
          $localData['Requested'] = array('seats'=>$_POST['jsonic']['seats'] ,'amount'=>$_POST['jsonic']['amount']  ,
                                          'EventsinSlot'=>1, 'EventsAllowed'=>1);
          $localData['Events'][$_POST['jsonic']['event']] = array_merge($localData['Events'][$_POST['jsonic']['event']],  $_POST['jsonic']);
          if (!isset($localData['Events'][$_POST['jsonic']['event']][$_POST['jsonic']['state']]) ||
             ($_POST['jsonic']['state'] =='nosal' )) {
            $localData['Events'][$_POST['jsonic']['event']][$_POST['jsonic']['state']] = time();

          }
          if (($_POST['jsonic']['state'] =='pub' )) {
            unset($localData['Events'][$_POST['jsonic']['event']]['nosal']);

          }
          $value = true;
        } else
          $value = false;
        break;

      case 'PublishMessage':
        $localData['Requested'] = array('seats'=>0, 'amount'=>0,
                                        'EventsinSlot'=>$_POST['jsonic']['count'] ,
                                        'EventsAllowed'=>0 );
        foreach($_POST['jsonic']['event'] as $key => $event) {
          if ($localData['Requested']['seats'] < $event['seats']) {
            $localData['Requested']['seats'] = $event['seats'];
          }
          if ($localData['Requested']['amount'] < $event['amount']) {
            $localData['Requested']['amount'] = $event['amount'];
          }
          if (!isset($localData['Events'][$key])) {
            $localData['Requested']['EventsAllowed'] += 1;
          }
        }
        $value = false;
        if ($_POST['jsonic']['state'] == 'pub') {
          if (isset($_POST['jsonic']['product'])) {
            $localData['Ordered'] = array('seats'=>0,'amount'=>0 ,'EventsinSlot'=>0, 'EventsAllowed'=>0);
            $localData['neworders'] = array();
            foreach($_POST['jsonic']['product'] as $productid => $ordered) {
              $ordered = (empty($ordered))?'':(int)$ordered;
              $_POST['jsonic']['product'][$productid] =$ordered;
              $productid = (int)substr($productid,1);
              if (!empty($ordered) and isset($localData['Products'][$productid]) ) {
                $product = $localData['Products'][$productid];
                $product['ordered'] = $ordered;
                $product['product'] = $productid;
                $localData['neworders'][] = $product;

                $localData['Ordered']['seats']         = calcValue($localData['Ordered']['seats'] , $ordered* $product['seats']);
                $localData['Ordered']['amount']        = calcValue($localData['Ordered']['amount']       , $ordered* $product['amount']);
                $localData['Ordered']['EventsinSlot']  = calcValue($localData['Ordered']['EventsinSlot'] , $ordered* $product['EventsinSlot']);
                if ($product['type']=='e') {
                  $localData['Ordered']['EventsAllowed'] = calcValue($localData['Ordered']['EventsAllowed'],
                                                                           $order['ordered']* $product['number']);
                } else {
                  $localData['Ordered']['EventsAllowed'] = calcValue($localData['Ordered']['EventsAllowed'],
                                                                           $order['ordered']* $product['EventsAllowed']);
                }
              }
            }
            $localData['MaxValues']['seats']         = calcValue($localData['MaxValues']['seats']        , $localData['Ordered']['seats']);
            $localData['MaxValues']['amount']        = calcValue($localData['MaxValues']['amount']       , $localData['Ordered']['amount']);
            $localData['MaxValues']['EventsinSlot']  = calcValue($localData['MaxValues']['EventsinSlot'] , $localData['Ordered']['EventsinSlot']);
            $localData['MaxValues']['EventsAllowed'] = calcValue($localData['MaxValues']['EventsAllowed'], $localData['Ordered']['EventsAllowed']);
          }
          if (!(($localData['MaxValues']['seats']<0) or ($localData['Requested']['seats']  <= $localData['MaxValues']['seats']))) {
            $error[1] ='bgcolor="red"';
          }
          if (!(($localData['MaxValues']['amount']<0) or ($localData['Requested']['amount'] <= $localData['MaxValues']['amount']))) {
            $error[2] ='bgcolor="red"';
          }

          if (!(($localData['MaxValues']['EventsinSlot']<0)  or ($localData['MaxValues']['EventsinSlot']>=$localData['Requested']['EventsinSlot'] ))) {
            $error[3] ='bgcolor="red"';
          }
          if (!(($localData['MaxValues']['EventsAllowed']<0) or ($localData['MaxValues']['EventsAllowed']>=$localData['Requested']['EventsAllowed'] ))) {
            $error[4] ='bgcolor="red"';
          }

        }
        if ($error || ($_POST['jsonic']['product'] && !is($_POST['jsonic']['ordernow'],false)))  {
          $value = array('publish_not_enough_credits');
          $value[1] = //var_export($localData['MaxValues'],true).
           " There User,<br> You do not have the anough credits to publish this event (again). See the table below.<br>
             We like to offer you the option to order the right products right away by selecting the products you want and pressing the ".
           "[Yes] button below.<br><br>
          <div style='width:99%; border: 1px solid #DDDDDD;background-color: #fcfcfc' align='center' valign='middle'>

              <table width='100%' border=0>
              <tr bgcolor='#9999ff'>
                <th colspan=5 align='left'><h3 color='white'>Current credits</h3></th>
              </tr>

                <tr class='admin_list_header'>
                  <th>&nbsp;</th><th>Allowed</th><th>Available</th><th>Requested</th>
                </tr>
                <tr class='admin_list_row_0'>
                  <th align='left'>Maximume seats</th>
                  <td align='right'>{$localData['CurrentAllwod']['seats']}</td>
                  <td align='right'>{$localData['MaxValues']['seats']}</td>
                  <td align='right' {$error[1]}>{$localData['Requested']['seats']}</td>
                </tr>
                <tr class='admin_list_row_0'>
                  <th align='left'>Maximume amount</th>
                  <td align='right'>".number_format($localData['CurrentAllwod']['amount'],2)."</td>
                  <td align='right'>".number_format($localData['MaxValues']['amount'],2)."</td>
                  <td align='right' {$error[2]}>".number_format($localData['Requested']['amount'],2)."</td>
                </tr>
                <tr class='admin_list_row_0'>
                  <th align='left'>Simultan Events</th>
                  <td align='right'>{$localData['CurrentAllwod']['EventsinSlot']}</td>
                  <td align='right'>{$localData['MaxValues']['EventsinSlot']}</td>
                  <td align='right' {$error[3]}>{$localData['Requested']['EventsinSlot']}</td>
                </tr>
                <tr class='admin_list_row_0'>
                  <th align='left'>Total Events Allowed</th>
                  <td align='right'>{$localData['CurrentAllwod']['EventsAllowed']}</td>
                  <td align='right'>{$localData['MaxValues']['EventsAllowed']}</td>
                  <td align='right' {$error[4]}>{$localData['Requested']['EventsAllowed']}</td>
                </tr>
              </table></div><br>";
          $value[1] .= "
          <div style='width:99%; border: 1px solid #DDDDDD;background-color: #fcfcfc' border-bottom: 0px;align='center' valign='middle'>

            <table width='100%' border=0>
              <tr bgcolor='#9999ff'>
                <th colspan=8 align='left'><h3 color='white'>Select new producten</h3></th>
              </tr>
              <tr  class='admin_list_header'>
                <th width=70>Count</th>
                <th width=50>ID</th>
                <th width=100>Valid for</th>
                <th width=70>Price</th>
                <th width=70>Seats</th>
                <th width=90>Amount</th>
                <th width=70>Simultan</th>
                <th>Events</th>
              </tr>
            ";
          $value[1] .=  "</table></div>";
          $value[1] .= "          <div style='overflow: auto; height: 100px; width:99%;  border-top: 0px; border: 1px solid #DDDDDD;background-color: #fcfcfc' align='center' valign='middle'>

                  <table width='100%' border=0>";
  foreach($localData['Products'] as $key => $row) {

            $value[1] .=  "<tr class='admin_list_row_0'>
                <td width=70 align='center'><input type='number' name='product[_$key]' value='".is($_POST['jsonic']['product']["_$key"],'')."' size=4> </td>
                <td width=50 align='center'><b>$key</b></td>
                <td width=100 align='right'><b>{$row['number']} ".($row['type']=='d'?'Days':'Events')."</b></td>
                <td width=70 align='right'><b>".number_format($row['price'],2)."</b></td>
                <td width=70 align='right'>{$row['seats']}</td>
                <td width=90 align='right'>".number_format($row['amount'],2)."</td>
                <td width=70 align='right'>{$row['EventsinSlot']}</td>
                <td align='right'>{$row['EventsAllowed']}</td>
               </tr>";

          }
          $value[1] .=  "</table></div>
                       <input type='checkbox' name='ordernow' checked=checked><label for='ordernow'>Goto checkout page.</label>";

          $value[] = '
            $("#PublishEvents").submit();
';
          $value[] = 500;
        } elseif (isset($_POST['jsonic']['product'])) {
            if (is($_POST['jsonic']['ordernow'],false)=='pay') {
              $localData['lastOrder'] = is($localData['lastOrder'],0)+1;
              foreach($localData['neworders'] as $key => $row) {
                $localData['Orders'][] = array('ordered'=>$row['ordered'], 'orddate'=>date('Y-m-d',time()),
                                               'used'=>0, 'product'=>$row['product'],
                                               'orderid'=> $localData['lastOrder'] , 'status'=>'new');
              }
              $value = false;
              break;
            }
            $value = array('publish_order_products_now');
            $value[1] = //var_export($localData['MaxValues'],true).
            "</form>".
            " You are ready to pay the selected products. By pressing the [Yes] button below you will redirected to our paypal portal. After you have paid you will comeback. ".
            "<br><br>
            <div style='width:99%; border: 1px solid #DDDDDD;background-color: #fcfcfc' align='center' valign='middle'>

            <table width='100%' border=0>
              <tr bgcolor='#9999ff'>
                <th colspan=8 align='left'><h3 color='white'>List of ordered products</h3></th>
              </tr>
              <tr  class='admin_list_header'>
                <th width=50>Count</th>
                <th width=70>Price</th>
                <th width=80>Total</th>
                <th width=100>Valid for</th>
                <th width=70>Seats</th>
                <th width=90>Amount</th>
                <th width=70>Simultan</th>
                <th>Events</th>
              </tr>
            ";

          foreach($localData['neworders'] as $key => $row) {

            $value[1] .=  "<tr class='admin_list_row_0'>
                <input type='hidden' name='product[_$key]' value='".is($_POST['jsonic']['product']["_$key"],'')."' size=4>
                <td width=50 align='center'>{$row['ordered']}</td>
                <td width=70 align='right'><b>".number_format($row['price'],2)."</b></td>
                <td width=80 align='right'><b>".number_format($row['ordered']*$row['price'],2)."</b></td>
                <td width=100 align='right'>{$row['number']} ".($row['type']=='d'?'Days':'Events')."</td>
                <td width=70 align='right'>".calcValue($row['ordered']*$row['seats'])."</td>
                <td width=90 align='right'>".number_format(calcValue($row['ordered']*$row['amount']),2)."</td>
                <td width=70 align='right'>".calcValue($row['ordered']*$row['EventsinSlot'])."</td>
                <td align='right'>".calcValue($row['ordered']*$row['EventsAllowed'])."</td>
               </tr>";
          }
          $value[1] .=  "</table></div>
                        <input type='checkbox' name='ordernow' value='pay' checked=checked><label for='ordernow'>Pay the order now.</label>";

          $value[] = '
            $("#PublishEvents").submit();
';
          $value[] = 500;

        }




           break;
      default:
        header('HTTP/1.1 501 Function not defined: '.$_POST['action']);
        file_put_contents('jsonic.log', 'Message: 501 Function not defined: '.$_POST['action'].chr(13) ,FILE_APPEND);

        die();
    }
  } catch (Exception $e){
    file_put_contents('jsonic.log', 'err:'.var_export($e,true).chr(13) ,FILE_APPEND);
  }
  file_put_contents('jsonic.log', 'out :'.var_export($value,true).chr(13) ,FILE_APPEND);
  file_put_contents('jsonic.dat', json_encode($localData) ,FILE_TEXT);
  die (base64_encode(RestServiceClient::encrypt(json_encode($value),$localData['servertoken'])));

function customError($errno, $errstr, $error_file, $error_line, $error_context) {
  GLOBAL $_SHOP;
  $errortype = array(
    E_ERROR           => 'Error',
    E_WARNING         => 'Warning',
    E_PARSE           => 'Parsing error',
    E_NOTICE          => 'Notice',
    E_CORE_ERROR      => 'Core error',
    E_CORE_WARNING    => 'Core warning',
    E_COMPILE_ERROR   => 'Compile error',
    E_COMPILE_WARNING => 'Compile warning',
    E_USER_ERROR      => 'User error',
    E_USER_WARNING    => 'User warning',
    E_USER_NOTICE     => 'User notice',
    E_RECOVERABLE_ERROR => 'Recoverable error');
  if(defined('E_STRICT'))
    $errortype[E_STRICT] = 'runtime notice';

  $user_errors = E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE | E_ERROR | E_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING;

  //...blah...
  $error = isset($errortype[$errno])?$errortype[$errno]:$errno;
  if ($errno & $user_errors)  {
    file_put_contents('jsonic.log', "{$error}: $errstr, $error_file @ $error_line".chr(13) ,FILE_APPEND);
  }
}

function addDaysToDate($date,$no_days) {
  $time1  = strtotime($date);
  $res = strtotime((date('Y-m-d', $time1)." +$no_days"."days"));

  return $res;
}

function calcValue($localData, $product=0){
  if ($localData < 0 || $product < 0) {
    return  -1;
  } else {
    return $localData + $product;
  }
}
function is(&$arg, $default = null)
{
  if (isset($arg)) {
    return $arg;
  }
  return $default;
}

?>