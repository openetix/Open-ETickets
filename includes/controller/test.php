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
   function findinside( $filestring) {
        $string = file_get_contents($filestring);
        preg_match_all('/define\(["\']([a-zA-Z0-9_]+)["\'],[ ]*(.*?)\);/si',  $string, $m); //.'/i'
        return array_combine( $m[1],$m[2]);
    }

    $en = findinside('includes/lang/site_en.inc');
    $du = findinside('includes/lang/site_nlz.php');
    ksort($en, SORT_LOCALE_STRING);
    echo "<table border=1>";
    foreach ($en as $key =>$value) {
      $keyx=(isset($du[$key]))?$key:"<b>$key</b>";
      echo "<tr><td>$keyx</td><td>".htmlentities($value)."</td><td>";
      echo(isset($du[$key]))?htmlentities($du[$key]):"&nbsp;","</td></tr>\n";
    }
    $diff= array_diff_key($du, $en);
    foreach ($diff as $key =>$value) {
      echo "<tr><td><b>$key</b></td><td>&nbsp;</td><td>".htmlentities($value)."</td></tr>\n";
    }
    echo "</table>";
?>