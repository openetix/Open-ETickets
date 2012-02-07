$(document).ready(function(){
  //bindLinks();
 // console.log(address);

	$(".loading").ajaxSend(function(evt, request, settings){
		$(this).show();
	});

	$(".loading").ajaxStop(function(evt, request, settings){
		$(this).fadeOut("fast");
	});
});

var ajaxQManager = $.manageAjax.create('ajaxQMan',{
	queue:true,
	abortOld:true,
	maxRequests: 1,
	cacheResponse: false
});

var bindForms = function(){
	$("form").submit(function(){
    console.log("fire!!");
		var form = $(this);
		if($(form).attr("id") == "order-form"){
    		return false;
    	}
		$(form).ajaxSubmit({
			data:{ajax:"yes"},
			success: function(html){
				if($(form).hasClass("remove-tickets")){refreshOrder(); refreshCategories(); return false;}
				$("#right").html(html);
				return false;
			}
		});
		return false;
	});
}

var bindLinks = function(){
	//$("a:not([href^='http'])").click(function () { //does not work after rebind in ie8.
	$("a").live('click select', function () {
	   console.log("AjaxLink");
    	var url = $(this).attr('href');
    	if($(this).hasClass("ui-dialog-titlebar-close") || $(this).hasClass('.ui-state-hover')){
    		return false;
    	}
    	$("#seat-chart").each(function(){
    		$(this).remove();
    	});
      //window.location.replace(url);#
      location.hash = url;
   		ajaxQManager.clear();
   		ajaxQManager.add({
		//$.ajax({
			type: "GET",
        	url: url,
        	data: {ajax:'yes'},
        	cache:false,
        	success: function(html){
        		clearInterval(refreshTimer);
        		$("#right").html(html);
        	}
    	});
	   	//$.ajax({});
		return false;
	});
}