<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */
 define ('TABLE_STATS','userstats');
class plugin_userlogging extends baseplugin {

	public $plugin_info		  = 'User Logging';
	/**
	 * description - A full description of your plugin.
	 */
	public $plugin_description	= 'This plugin Will log user access';
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

  public $plugin_actions  = array ('config','install','uninstall','priority','enable');

	public $totaalVisits;

		function GetTotalVisits (){
			$result=ShopDB::Query_one_row("select count(userstats_id) count from ".TABLE_STATS);
			return $result['count'];
		}

		function GetTotalUniqueVisits (){
			$result=ShopDB::Query_one_row("select count(distinct(userstats_ip)) count from ".TABLE_STATS);
			return $result['count'];
		}

		function GetTotalUniqueBrowsers (){
			$result=ShopDB::Query_one_row("select count(distinct(userstats_browser)) count from ".TABLE_STATS);
			return $result['count'];
		}

		function GetTopVisitors (& $total){
			$Visitors=Array();
      $total = 0;
			$result=ShopDB::Query("select userstats_ip, count(*) as count from ".TABLE_STATS." group by userstats_ip order by userstats_ip");
      while ($rec = shopDB::fetch_assoc($result)) {
				$Visitors[$rec["userstats_ip"]] = $rec["count"];
        $total += $rec["count"];
			}
			array_multisort($Visitors,SORT_NUMERIC,SORT_DESC);
			$VisitorCounts=Array();
			$top= 0;
			foreach ($Visitors as $k => $v) {
				$VisitorCounts[$k]=$v."/".$total;
				$top++;
				if ($top==10) {break;}
			}
			return $VisitorCounts;
		}

		function GetTopBrowsers (& $total){
			$BrowserTypes=Array();
      $total = 0;

			$result=ShopDB::Query("select userstats_browser, count(*) as count from ".TABLE_STATS." group by userstats_browser order by userstats_browser");
      while ($rec = shopDB::fetch_assoc($result)) {
				$Referrers[$rec["userstats_browser"]] = $rec["count"];
        $total += $rec["count"];
			}
			array_multisort($Referrers,SORT_NUMERIC,SORT_DESC);
			$top=0;
			$BrowserCounts=Array();
			foreach ($Referrers as $k => $v) {
				$BrowserCounts[$k]=$v."/".$total;
				$top++ ;
				if ($top==10) {break;}
			}
			return $BrowserCounts;
		}

		function GetTopRequests (& $total){
			$Referrers=Array();
      $total = 0;

			$result=ShopDB::Query("select userstats_request_uri, count(*) as count from ".TABLE_STATS." group by userstats_REQUEST_URI order by userstats_REQUEST_URI");
      while ($rec = shopDB::fetch_assoc($result)) {
				$Referrers[$rec["userstats_request_uri"]] = $rec["count"];
        $total += $rec["count"];
			}
			array_multisort($Referrers,SORT_NUMERIC,SORT_DESC);
			$ReferrerCounts=Array();
			$top=0;
			foreach ($Referrers as $k => $v){
				$ReferrerCounts[$k]=$v."/".$total;
				$top++ ;
				if ($top==10) {break;}
			}
			return $ReferrerCounts;
		}

		function GetTopReferrers () {
			$Referrers=Array();$total = 0;

			$result=ShopDB::Query("select userstats_referrer, count(*) as count from ".TABLE_STATS." group by userstats_referrer order by userstats_referrer");
      while ($rec = shopDB::fetch_assoc($result)) {
				$Referrers[$rec["userstats_referrer"]] = $rec["count"];
        $total += $rec["count"];
			}
			$ReferrerCounts=Array();
			$top=0;
			array_multisort($Referrers,SORT_NUMERIC,SORT_DESC);
			foreach ($Referrers as $k => $v){
				$ReferrerCounts[$k]=$v."/".$total;
				$top++ ;
				if ($top==10) {break;}
			}
  		return $ReferrerCounts;
		}

		function config ($page)
			{
      echo "<tr><td colspan=2><br>";
			/* top visitors display*/
			$this->totaalVisits = $this->GetTotalVisits ();

			$TopVisitors =  $this->GetTopVisitors ($total); $row=true;
		//	$output .= '{gui->StartForm method=none title=!MODA_1!}';
      $page->form_head(con(MODA_1)." ({$this->GetTotalUniqueVisits()} ".con(MODA_2).")",'100%');

	//		$output .= "<tr><td class=\"TableHeader\" colspan=2>".con(MODA_1)." (".$this->GetTotalUniqueVisits()."  ".MODA_2.")</td></tr>";
			foreach ($TopVisitors as $k => $v){
				$class=($row= !$row)?"admin_name":"admin_value";
        echo "
              <tr>
                <td class='{$class}'>".(($k=="")?'{empty}':$k)."</td>
                <td class='{$class}' valign='right' width=50>".$v."</td>
              </tr>\n";
				}
			echo '</table><br>';

			/* top browsers display*/
			$TopBrowsers =  $this->GetTopBrowsers ($total); $row=true;
      $page->form_head(con(MODA_3)." ({$this->GetTotalUniqueBrowsers()} ".con(MODA_4).")",'100%');

//			$output .= "<tr><td class=\"TableHeader\" colspan=2>".con(MODA_3)." (".$this->GetTotalUniqueBrowsers()." ".MODA_4.")</td></tr>";
			foreach ($TopBrowsers as $k => $v){
				$class=($row= !$row)?"admin_name":"admin_value";
        echo "
              <tr>
                <td class='{$class}'>".(($k=="")?'{empty}':$k)."</td>
                <td class='{$class}' valign='right' width=50>".$v."</td>
              </tr>\n";
				}
			echo '</table><br>';

			/* top referrers display */
			$TopReferrers =  $this->GetTopReferrers (); $row=true;
      $page->form_head(con(MODA_5),'100%');
			foreach ($TopReferrers as $k => $v)	{
				$class=($row= !$row)?"admin_name":"admin_value";
        echo "
              <tr>
                <td class='{$class}'>".(($k=="")?'{empty}':$k)."</td>
                <td class='{$class}' valign='right' width=50>".$v."</td>
              </tr>\n";
			}
			echo '</table><br>';

			/* top REQUEST_URI display */
			$TopRequests =  $this->GetTopRequests ($total); $row=true;
      $page->form_head(con(MODA_12),'100%');
			foreach ($TopRequests as $k => $v) {
				$class=($row= !$row)?"admin_name":"admin_value";
        echo "
              <tr>
                <td class='{$class}'>".(($k=="")?'{empty}':$k)."</td>
                <td class='{$class}' valign='right' width=50>".$v."</td>
              </tr>\n";
			}
			echo '</table><br>';

			/* raw logfile display */
      $sql = "select * from ".TABLE_STATS." order by userstatse_timestamp desc";
			$result=shopDB::Query($sql);
			if(!isset($_REQUEST['prevoffset'])){$_REQUEST['prevoffset']=0;}
			if(!isset($_REQUEST['offset'])){$_REQUEST['offset']=0;}

			$sql .=" limit ".$_REQUEST['offset'].",10";
			$result=shopDB::Query($sql);

      $page->form_head(con(MODA_6),'100%');
	//		$output .= "<tr>";
	//		$output .= "<td class=\"TableHeader\" colspan=2>"._esc(MODA_6)."</td>";
//			$output .= $this->uw->UI_OpenForm("",$_SERVER['PHP_SELF']."?cmd=".MODS_USE."&file=".MODULE_NAME."&purge=1","");
			//$output .= "<td class=\"TableHeader\" align=\"right\">".$this->uw->UI_Submit(MODA_7)."</td>";
	//		$output .= $this->uw->UI_CloseForm();
  //    $output .= "</tr>";

			$row=true;
      while ($rec = shopDB::fetch_assoc($result)) {
				$class=($row= !$row)?"admin_name":"admin_value";
				echo "<tr>";
				echo "<td width='10%' class='{$class}'>".$rec["userstats_ip"]."</td>\n";
				echo "<td width='10%' class='{$class}'>".date(DATE_FORMAT,$rec["userstats_datestamp"])."<br />".
                                                 date(TIME_FORMAT,$rec["userstats_datestamp"])."</td>\n";
				echo"<td width='80%' class='{$class}'>".
             "<b>From:</b>&nbsp;<a class=\"Table\" href=\"http://".$rec["userstats_referrer"]."\" target=\"_blank\">".$rec["userstats_referrer"]."</a><br />".
             "<b>To:</b>&nbsp;<a class=\"Table\" href=\"http://".$rec["userstats_request_uri"]."\" target=\"_blank\">".$rec["userstats_request_uri"]."</a></td></tr>\n";
				}
			echo'</table></td></tr>';
//			$PrevPageAction=$_SERVER['PHP_SELF']."?cmd=".MODS_USE."&file=".MODULE_NAME;
//			$PrevPageAction.="&offset=" ;
//                        $output .= $this->uw->UI_PageBar($_REQUEST['offset'], $matches, $PrevPageAction);
      Return '';
		}


		function PurgeStats (){
			$result=$this->db->Query("delete from ".TABLE_STATS);
			$Content = $this->uw->UI_Message(MODA_10);
			$Content .= $this->uw->UI_Navigate($_SERVER['PHP_SELF']."?cmd=".MODS_USE."&file=".MODULE_NAME);
			return $Content;
    }

  function doPageload() {
		$date_logged=date('c');
		$ip=$_SERVER['REMOTE_ADDR'];

		$browser  = $_SERVER['HTTP_USER_AGENT'];
    $REQUEST_URI = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

		$referrer = getenv("HTTP_REFERER");
		if (isset( $_SERVER["HTTP_COOKIE"])){
		    $referrer = str_replace("&".$_SERVER["HTTP_COOKIE"],'',$referrer);
		    $referrer = str_replace("?".$_SERVER["HTTP_COOKIE"],'',$referrer);

		    $REQUEST_URI = str_replace("&".$_SERVER["HTTP_COOKIE"],'',$REQUEST_URI);
		    $REQUEST_URI = str_replace("?".$_SERVER["HTTP_COOKIE"],'',$REQUEST_URI);
		    }

		$sql="insert into userstats (userstatse_timestamp, userstats_ip, userstats_browser, userstats_referrer, userstats_server, userstats_request_uri) values (";
		$sql.=_esc($date_logged).", ";
		$sql.=_esc($ip).",";
		$sql.=_esc($browser).",";
		$sql.=_esc($referrer).",";
		$sql.=_esc(print_r($_SERVER,true)).","; //print_r($_SERVER,true)
		$sql.=_esc($REQUEST_URI);
		$sql.=")";
		ShopDB::Query($sql);
  }


}

?>