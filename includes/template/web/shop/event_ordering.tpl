{*                  %%%copyright%%%
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
 *}
{if $user->mode() eq '-1' and !$user->logged && $shop_event.event_date lt $smarty.now|date_format:"%Y-%m-%d"}
  {redirect url="?event_id={$smarty.request.event_id}"}
{/if}
{event event_id=$smarty.request.event_id ort='on' place_map='on' event_status='pub' limit=1}
  {include file="header.tpl" name=!shop! header=!shop_info! footer=!shop_condition!}
  <style type="text/css">
    .seatmapimage {
       width: 16px;
       height:16px;
    }
  </style>

  {include file="event_header.tpl"}
  <div class="art-content-layout-br layout-item-0"></div>
    {cart->maxSeatsAlowed event=$shop_event}
    <form name='f' id='catselect' action='index.php' method='post'>
      {ShowFormToken name='orderevent'}
      <input type='hidden' name='event_id' value='{$shop_event.event_id}'>
      <input type='hidden' name='action' value='addtocart'>
    <table width="100%" cellpadding='2' cellspacing='2' bgcolor='white' >
      <tbody>
        <tr>
          <td class='user_item'>{!select_category!}:</td>
          <td class='user_value'>
            {category event_id=$shop_event.event_id stats="on"}
              {if !$category_id} {$category_id=$shop_category.category_id} {/if}
              <label for="category_id_{$shop_category.category_id}">
                <span id="catcolor" style="background-color:{$shop_category.category_color}">
                 <input type="radio" id="category_id_{$shop_category.category_id}" name="category_id" value="{$shop_category.category_id}" {if $category_id eq $shop_category.category_id}checked{/if} onClick="setNum({$shop_category.category_id},true)" {if $shop_category.category_free == 0}disabled="true"{/if}>
               </span>
                {$shop_category.category_name}</label> &nbsp;
            {/category}
            ({!free_seat!}: <span id="ft-cat-free-seats" >0</span> ({!approx!}))
          </td>
        </tr>
        <tr id='discount-name' {* style="display:none;" *}>
          <td class='user_item' >{!select_discounts!}:</td>
          <td class='user_value'>
            {discount all='on' event_id=$event_id  category_id=$category_id cat_price=$shop_category.category_price}{/discount}
              <label><input class='checkbox_dark' type='radio' name='discount' value='0' checked>{!no_discount!}</label>
            {section name='d' loop=$shop_discounts}
              <label><input class='checkbox_dark discount_{$shop_discounts[d].discount_id}' type='radio' name='discount' value='{$shop_discounts[d].discount_id}'>{$shop_discounts[d].discount_name}&nbsp;</label>
            {/section}
          </td>
        </tr>
      </tbody>
    </table>


<div id="top5tabs" style='margin:0px; padding:0px;'>
	<ul>
    {if $shop_event.pm_image}
  		<li><a href="#tabs-1">{!event_select_cat!}</a></li>
  	{/if}
		<li><a href="#tabs-2">{!event_select_seats!}</a></li>
	</ul>
  {if $shop_event.pm_image}
  	<div id="tabs-1">
      <img class="chartmap" src="files/{$shop_event.pm_image}"  border='1' width='581' usemap="#ort_map">
      <map name="ort_map">
        {category event_id=$shop_event.event_id stats="on"}
          {if $shop_category.category_free gt 0 && $shop_category.category_data}
            <area href="#" cat="{$shop_category.category_id}" {$shop_category.category_data} />
          {/if}
        {/category}
      </map>
  	</div>
  {/if}
	<div id="tabs-2">
         <span id='order_amound'>
         <center>
            <table border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td class='event_data'>
                  {!number_seats!}
                </td>
                <td class='title'>
                  <select style="float:none;"  name='places' >
                    {section name="myLoop" start=1 loop=$seatlimit+1}
                      <option value='{$smarty.section.myLoop.index}' > {$smarty.section.myLoop.index} </option>
                    {/section}
                  </select>
                  <input type='hidden' name='numbering' value='none' />
                </td>
              </tr>
            </table>
          </center>
          </span>
          <span id='order_placemap'>
          <div style='overflow: auto; height: 350px; width:595px; border: 1px solid #DDDDDD;background-color: #fcfcfc'
               id='placemap' align='center' valign='middle'>
          </div>
          <center><div valign='top'> {!placemap_image_explanation!}</div></center>
          </span>

	</div>
</div>

  <div class="art-content-layout-br layout-item-0"></div>
  <div class="art-content-layout layout-item-1">
    <div class="art-content-layout-row" style='padding:10px;'>
      <div class="art-layout-cell layout-item-3"  style='text-align:right; width: 100%;padding:10px;'>
      {gui->button url="button" onclick="validateSelection();" name="add_to_cart" type=1}
  	  </div>
    </div>
  </div>
</form>
<script type="text/javascript">
	mode = 'both';
	function setNum(cat_id, doSwitch) {

      ajaxQManager.add({
         type:       "POST",
         url:        "jsonrpc.php?x=placemap",
         dataType:   "json",
         data:      { "action":"PlaceMap", "category_id":cat_id, "seatlimit":{$seatlimit} },
         success:function(data, status){
         //   printMessages(data.messages);
            if(data.status){
              $("#placemap").html(data.placemap);
          		if (data.placemap != '') {
          			$("#order_amound").hide();
          			$("#order_placemap").show();
          			mode = 'both';
          		} else {
          			$("#order_amound").show();
          			$("#order_placemap").hide();
          			mode = 'none';
          		}
          		$("#places").val(0);
          		if (doSwitch) {
                $("#top5tabs").tabs( "select" , 1);
              }

            }
         }
      });

	}

  setNum({$category_id}, false);

	function validateSelection() {
	  if (mode == 'none') {
			bool= ($('#places').val() != 0 && $('input[name=category_id]:checked').val() != "");
			if (!bool) {
				showErrorMsg('Please select your category and the number of tickets');
				$("html,body").animate({
       				scrollTop: $("#error-message").offset().top
					}, 1000, function(){
					        //scroll complete function
					});
				return false;
			}
		} else {
			bool= $('input[name=category_id]:checked').val();
			if (!bool) {
				showErrorMsg('Please select your category');
				$("html,body").animate({
       				scrollTop: $("#error-message").offset().top
					}, 1000, function(){
					        //scroll complete function
					});
				return false;
			}
		}
		$('#catselect').submit();
		return true;
	}
  $(function() {
    $('.chartmap').maphilight();
  	$("#top5tabs").tabs({ });
    $('area').click(function() {
        var url = $(this).attr('cat');
        $("#category_id_"+url).attr('checked', true);
        setNum(url, true);
       // alert(url);
      return false;
    });
  });
</script>

{/event}