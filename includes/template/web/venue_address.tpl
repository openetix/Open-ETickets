<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<meta http-equiv="Content-Language" content="nl" >

<link REL='stylesheet' HREF='style.php' TYPE='text/css' >

</head>
<body>
    {event event_id=$smarty.get.event_id ort='on' limit=1}
     <table border="0" cellpadding="0" cellspacing="0" width="600">
      <tr>
        <td valign=top>
    	    {if $shop_event.ort_image}
    		    <img src="files/{$shop_event.ort_image}" width='160' align='left' border="0">
          {else}
    		    <img src="images/na.png" align='left' style="margin:20px"  border="0">
          {/if}
          <br>
        </td>
        <td valign='top' align='left' width='400' >
          <h3>{$shop_event.ort_name} </h3><br>
          <table border=0 cellSpacing=2 cellPadding=3 width='90%' bgcolor='white'>
             <tr>
              <td valign=top width='35%' class="TblLower date ">{!ort_address!}:&nbsp;</td>
              <td  class="TblHigher">
                 {$shop_event.ort_address}
                 {if $shop_event.ort_address1}
                   <br>{$shop_event.ort_address1}
                 {/if}
              </td>
            </tr>
            <tr>
               <td valign=top class="TblLower date ">{!ort_city!}:&nbsp;</td>
              <td valign=top   class="TblHigher" >{$shop_event.ort_zip}  {$shop_event.ort_city}</td>
            </tr>
            {if $shop_event.ort_state}
              <tr>
                <td valign=top class="TblLower date ">{!ort_state!}:&nbsp;</td>
                <td valign=top  class="TblHigher"  >{$shop_event.ort_state}</td>
              </tr>
            {/if}
            {if $shop_event.ort_country and $shop_event.ort_country neq $organizer->organizer_country}
              <tr>
                <td valign=top class="TblLower date ">{!ort_country!}:&nbsp;</td>
                <td valign=top  class="TblHigher" >{$shop_event.ort_country}</td>
              </tr>
            {/if}
            <tr>
              <td valign=top class="TblLower date ">{!ort_phone!}:&nbsp;</td>
              <td valign=top  class="TblHigher"  >{$shop_event.ort_phone}</td>
            </tr>
            <tr>
              <td valign=top class="TblLower date ">{!ort_fax!}:&nbsp;</td>
              <td valign=top  class="TblHigher"  >{$shop_event.ort_fax}</td>
            </tr>
            {if $shop_event.ort_url}
              <tr>
                <td valign=top class="TblLower date ">{!ort_url!}:&nbsp;</td>
                <td valign=top  class="TblHigher"  >
                  <a target='_blank' href='{$shop_event.ort_url}'>{!view!}</a></td>
              </tr>
            {/if}
          </table>
        </td>
      </tr>
      {if $shop_event.ort_pm}
        <tr>
          <td colspan=2 >
            <hr>
            <center>
             {$shop_event.ort_pm}
             </center>
            <hr>
          </td>
        </tr>
     {/if}
    </table>
  {/event}
  <div align='right'>
  <button onclick="jQuery.modal.close();">{!close!}</button>
  </div>

</body>