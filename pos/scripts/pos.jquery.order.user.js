var loadUser = function(mycolNames){
	$('#search_user').hide();

	$('#user_data').hide();

	$('#users_table').jqGrid({
		url:'ajax.php?x=users',
		datatype: 'json',
		mtype: 'POST',
		postData: {"pos":true,"action":"UserSearch"},
		colNames: mycolNames,
		colModel :[
			{name:'user_id',    index:'User_id',    width:52 , sortable:false, hidden: true},
			{name:'user_name',  index:'user_name',  width:152, sortable:false, resizable: false},
			{name:'user_zip',   index:'user_zip',   width:102, sortable:false, resizable: false},
			{name:'user_city',  index:'user_city',  width:235, sortable:false, resizable: false},
			{name:'user_email', index:'user_email', width:202, sortable:false, resizable: false} ],
		altRows: true,
		height: 200,

		hiddengrid : true,
		footerrow : false,
		viewrecords: false
    });

	$('#user_info_none').click(function(){
    	$("#user_data :input").each(function() {
        	$(this).val('');
      	});
    	$('#search_user').hide();
    	$('#user_data').hide();
      $('#user_id').val(-1);
    });

	$('#user_info_search').click(function(){
    	$('#search_user').show();
    	$('#user_data').show();
    	$('form#pos-user-form').unbind('keypress');
    	$('form#pos-user-form').bind('keypress',function(e){
            if(e.which == 13){
                $('#search_user').click();
             }});

	    if ($('#user_id').val() <=0) {
	    	$('#user_id').val(-2);
     	}

    });

	$('#user_info_new').click(function(){
    	$('#search_user').hide();
    	$('#user_data').show();
    	$('form#pos-user-form').unbind('keypress');
    	$('form#pos-user-form').bind('keypress',function(e){
            if(e.which == 13){
                $('#pos-user-form').submit();
             }});
      	if (($('#user_id').val() <=0) || confirm('Are you sure you want to create a new user?')) {
        	$("#user_data :input").each(function() {
           		$(this).val('');
        	});
        	$('#user_id').val(0);
      	} else {
        	$('#user_info_search').change();
        	$('#user_info_search').click();
      	}
    });

	$('form#pos-user-form').unbind('keypress');
	$('form#pos-user-form').bind('keypress',function(e){
        if(e.which == 13){
            $('#pos-user-form').submit();
         }});

	$("#search-dialog").dialog({
		bgiframe: false,
		autoOpen: false,
		height: 'auto',
		width: '775',
		modal: true,
		resizable  : false,
		buttons: {
    		'Cancel': function() {
				$(this).dialog('close');
			 },
  		  'Ok': function() {
         	var selrow = $('#users_table').getGridParam("selrow");
         	if (selrow != null) {
     			 	ajaxQManager.add({
  					type:		"POST",
  					url:		"ajax.php?x=getuser",
  					dataType:	"json",
  					data:		{"pos":true,"action":"UserData",'user_id':selrow},
  					success:function(data, status){
                			$.each(data.user, function(i,item){
                   			$("#"+i).val(item);
                			});
   						$("#search-dialog").dialog('close');
             			}
  			  	});
          } else {
            alert('You need to select a user first.');
          }
		  	}
		}
	});

  $('#search_user').click(function() {
	  if ($("#user_firstname").val() != "" && $("#user_lastname").val() != "" || $("#user_email").val() != "" || $("#user_phone").val() != "") {
    	var i=0;
    	var data = $('#users_table').getGridParam('postData');
    	$('#users_table').clearGridData();
    	$("#user_data :input").each(function() {
      	if ($(this).attr("name") != 'user_id') {
        		data[$(this).attr("name")] = $(this).val();
         		if ($(this).val().length >1 ) {
         			i++;
         		}
     	  	}
      	});
        $('#users_table').setGridParam('postData', data);
        $('#users_table').trigger("reloadGrid");
        $("#search-dialog").dialog('open');
     	}
	  else alert('Please provide either of the following to search:\nFull name\nEmail Address\nPhone Number');
	});
}

function array_length(arr) {
	var length = 0;
	for(val in arr) {
	    length++;
	}
	return length;
}