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

function smarty_block_country ($params, $content, $smarty, &$repeat) {
	global $_SHOP;

  if ($repeat) {
    $select = "SELECT ";
    $from = '`Ort` ';
    $where = "WHERE 1=1 ";

    if($params['order']){
			$params['order']=_esc($params['order'], false);
      $order_by="order by {$params['order']}";
    }

    if($params['event']){
      $from .= " LEFT JOIN `Event` ON ort_id=event_ort_id ";
      $where .= "AND event_status='pub'";
    }

    if($params['event_id']){
			$where .= " and event_id="._esc($params['event_id']);
    }

    if($params['start_date']){
      $where .= " and event_date>="._esc($params['start_date']);
    }
    if($params['end_date']){
      $where .= " and event_date<="._esc($params['end_date']);
    }

    $limit=($params['limit'])?'limit '._esc($params['limit'],false):'';

    if($params['event_sub']){
      $where.=" and event_rep LIKE '%sub%'";

      if($params['event_main_id']){
        $where.=" and event_main_id="._esc($params['event_main_id']);
      }
    }

    if($params['event_main']){
      $where.=" and event_rep LIKE '%main%'";
    }

    if($params['first']){
			$params['first']=(int)$params['first'];
      $limit='limit '.$params['first'];
      if($params['length']){
				$params['length']=(int)$params['length'];
        $limit.=','.$params['length'];
      }
    }else if($params['length']){
			$params['length']=(int)$params['length'];
      $limit='limit 0,'.$params['length'];
    }

  	if($limit){
  		$cfr=true;
      $select .= ' SQL_CALC_FOUND_ROWS ';
  	}

    if($params['distinct']){
      $select .= " DISTINCT "._esc($params['distinct'],false);
    }else{
      $select .= " * ";
    }

    $query="$select FROM $from $where $order_by $limit";
    $res=ShopDB::query($query);

	  $part_count=ShopDB::num_rows($res);

		if($cfr){
		  $query='SELECT FOUND_ROWS();';
      if($row=ShopDB::query_one_row($query, false)){
			  $tot_count=$row[0];
			}
		}else{
		  $tot_count=$part_count;
		}

    $ort=ShopDB::fetch_assoc($res);

  } else {
    $res_a=$smarty->popBlockData();

		$res=$res_a[0];
		$tot_count=$res_a[1];
		$part_count=$res_a[2];

    $ort=ShopDB::fetch_assoc($res);
  }

  $repeat=!empty($ort);

  if($ort){

		$ort['tot_count']=$tot_count;
    $ort['part_count']=$part_count;

    $smarty->assign("shop_country",$ort);

    $smarty->pushBlockData(array($res,$tot_count,$part_count));
  }

  return $content;
}

?>