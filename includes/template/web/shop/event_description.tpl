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
    <div class="art-content-layout-br layout-item-0"></div>
    <b>{!event_text!}</b><br>
    <div class="art-content-layout">
      <div class="art-content-layout-row">
        <div class="art-layout-cell layout-item-4" style="width: 100%;">
           {$shop_event.event_text}
        </div>
      </div>
    </div>

{if $shop_event.event_rep eq 'main'}
    <div class="art-content-layout-br layout-item-0"></div>
    <b>{!dates_localities!}</b><br>
    <div class="art-content-layout">
      <div class="art-content-layout-row">
        <div class="art-layout-cell layout-item-4" style="width: 100%;">
          {event event_main_id=$shop_event.event_id ort='on' stats='on' sub='on' event_status='pub' place_map='on'  order="event_date,event_time"}
            <li>
              <a href="index.php?event_id={$shop_event.event_id}">
                {$shop_event.event_date|date_format:!date_format!}
              </a>
	            {$shop_event.event_time|date_format:!time_format!} {$shop_event.pm_name}
            </li>
          {/event}
          {if !$shop_event.event_main_id}
            <p>{!no_sub_events!}</p>
          {/if}
        </div>
      </div>
    </div>
    <div class="art-content-layout-br layout-item-0"></div>

{else}
   {include file="event_prices.tpl"}
{/if}