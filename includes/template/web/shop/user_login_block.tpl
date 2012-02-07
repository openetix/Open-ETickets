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
{if $smarty.post.action eq 'login'}
	{user->login username=$smarty.post.username password=$smarty.post.password uri=$smarty.post.uri}
{elseif $smarty.get.action eq 'logout'}
	{user->logout}
{/if}

{if $user->logged}
  {$vermenu=[['href'=>'index.php?action=person_user', 'title'=>"{!person_user!}"],
             ['href'=>'index.php?action=person_orders', 'title'=>"{!person_orders!}"],
             ['href'=>'index.php?action=logout', 'title'=>"{!logout!}"]]  scope='root'}
{else}
                        <div class="art-box art-block">
                          <div class="art-box-body art-block-body">
                            <div class="art-bar art-blockheader">
                                <h3 class="t">{!member!}</h3>
                            </div>
                            <div class="art-box art-blockcontent">
                              <div class="art-box-body art-blockcontent-body">
                                <div>
                                  <form method='post' action='index.php' style='margin-top:0px;' id="login-form">
                                      {ShowFormToken name='login'}
                                      <input type="hidden" name="action" value="login">
                                      <input type="hidden" name="type" value="block">
                                      {if $smarty.get.action neq "logout" and $smarty.get.action neq "login"}
                                        <input type="hidden" name="uri" value="{$smarty.server.REQUEST_URI}">
                                      {/if}
                                      <p id="form-login-username">
                                    		<label for=for="modlgn-username" class="login_content">{!email!}</label>
                                        <input type='input' id="modlgn-username" name='username' size='20'> {printMsg key='loginusername'}
                                    	</p>
                                    	<pid="form-login-remember">
                                    		<label for="modlgn-passwd"></label>{!password!}</label>
                                        <input id="modlgn-passwd" type='password' name='password' size='20'>{printMsg key='loginpassword'}

                                      </p>
                                      <span class="art-button-wrapper" >
                                        <span class="art-button-l"> </span>
                                        <span class="art-button-r"> </span>
                                  			<input  class="art-button" type='submit' value='{!login_button!}' style='font-size:10px;'/>
                                      </span>
                                    <ul>
                                      <li><a  href='index.php?action=register'>{!register!}</a></li>
                                  		<li><a onclick='showDialog(this);return false;' href='forgot_password.php'>{!forgot_pwd!}</a></li>
                                  	</ul>
                                  </form>

                                </div>
                             		<div class="cleared"></div>
                              </div>
                            </div>
                        		<div class="cleared"></div>
                          </div>
                        </div>
{/if}