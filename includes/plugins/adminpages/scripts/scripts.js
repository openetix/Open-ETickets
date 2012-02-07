	// Nannette Thacker http://www.shiningstar.net
		function confirmSubmit($msg)
			{
			var agree=confirm($msg);
			if (agree)
				return true ;
			else
				return false ;
			}
		// -->
		
		function checkAll(){
			for (var i=0;i<document.search_results.elements.length;i++)
				{
					var e=search_results.elements[i];
					if ((e.name != 'allbox') && (e.type=='checkbox'))
					{
						e.checked=search_results.allbox.checked;
					}
				}
				}

//Expanding Form//				
/************************************************************************************************************
Show hide content with slide effect
Copyright (C) August 2010  DTHMLGoodies.com, Alf Magne Kalleland

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

Dhtmlgoodies.com., hereby disclaims all copyright interest in this script
written by Alf Magne Kalleland.

Alf Magne Kalleland, 2010
Owner of DHTMLgoodies.com

Note: 'dhtmlgoodies' in the following script has been replaced with 'dg'. MH 2011

************************************************************************************************************/

var dg_slideSpeed = 10;	// Higher value = faster
var dg_timer = 10;	// Lower value = faster

var objectIdToSlideDown = false;
var dg_activeId = false;
var dg_slideInProgress = false;
var dg_slideInProgress = false;
var dg_expandMultiple = true; // true if you want to be able to have multiple items expanded at the same time.

function showHideContent(e,inputId)
{
	if(dg_slideInProgress)return;
	dg_slideInProgress = true;
	if(!inputId)inputId = this.id;
	inputId = inputId + '';
	var numericId = inputId.replace(/[^0-9]/g,'');
	var answerDiv = document.getElementById('dg_a' + numericId);

	objectIdToSlideDown = false;

	if(!answerDiv.style.display || answerDiv.style.display=='none'){
		if(dg_activeId &&  dg_activeId!=numericId && !dg_expandMultiple){
			objectIdToSlideDown = numericId;
			slideContent(dg_activeId,(dg_slideSpeed*-1));
		}else{

			answerDiv.style.display='block';
			answerDiv.style.visibility = 'visible';

			slideContent(numericId,dg_slideSpeed);
		}
	}else{
		slideContent(numericId,(dg_slideSpeed*-1));
		dg_activeId = false;
	}
}

function slideContent(inputId,direction)
{

	var obj =document.getElementById('dg_a' + inputId);
	var contentObj = document.getElementById('dg_ac' + inputId);
	height = obj.clientHeight;
	if(height==0)height = obj.offsetHeight;
	height = height + direction;
	rerunFunction = true;
	if(height>contentObj.offsetHeight){
		height = contentObj.offsetHeight;
		rerunFunction = false;
	}
	if(height<=1){
		height = 1;
		rerunFunction = false;
	}

	obj.style.height = height + 'px';
	var topPos = height - contentObj.offsetHeight;
	if(topPos>0)topPos=0;
	contentObj.style.top = topPos + 'px';
	if(rerunFunction){
		setTimeout('slideContent(' + inputId + ',' + direction + ')',dg_timer);
	}else{
		if(height<=1){
			obj.style.display='none';
			if(objectIdToSlideDown && objectIdToSlideDown!=inputId){
				document.getElementById('dg_a' + objectIdToSlideDown).style.display='block';
				document.getElementById('dg_a' + objectIdToSlideDown).style.visibility='visible';
				slideContent(objectIdToSlideDown,dg_slideSpeed);
			}else{
				dg_slideInProgress = false;
			}
		}else{
			dg_activeId = inputId;
			dg_slideInProgress = false;
		}
	}
}



function initShowHideDivs()
{
	var divs = document.getElementsByTagName('DIV');
	var divCounter = 1;
	for(var no=0;no<divs.length;no++){
		if(divs[no].className=='dg_header'){
			divs[no].onclick = showHideContent;
			divs[no].id = 'dg_q'+divCounter;
			var answer = divs[no].nextSibling;
			while(answer && answer.tagName!='DIV'){
				answer = answer.nextSibling;
			}
			answer.id = 'dg_a'+divCounter;
			contentDiv = answer.getElementsByTagName('DIV')[0];
			contentDiv.style.top = 0 - contentDiv.offsetHeight + 'px';
			contentDiv.className='dg_body_content';
			contentDiv.id = 'dg_ac' + divCounter;
			answer.style.display='none';
			answer.style.height='1px';
			divCounter++;
		}
	}
}
window.onload = initShowHideDivs;	
		