var catData = new Object();
var refreshTimer = null;
var eventData = new Object();
var timerid = 0;
var loadOrder = function(){

  orderDialogs();

  $('#cart_table').jqGrid({
    url:'ajax.php?x=cart',
    datatype: 'json',
    mtype: 'POST',
    postData: {"pos":true,"action":"CartInfo"},
    colNames: ['Expire in','Event','No#','Tickets','Price','Total'],
    colModel :[
        {name:'Expire in',  index:'Expire_in',  width: 85, sortable:false },
        {name:'Event',      index:'Event',      width:240, sortable:false, resizable: false },
        {name:'No#',        index:'Count',      width: 45, sortable:false, resizable: false, align:'right' },
        {name:'Tickets',    index:'Tickets',    width:210, sortable:false, resizable: false                },
        {name:'Price',      index:'Price',      width: 70, sortable:false, resizable: false, align:'right' },
        {name:'Total',      index:'Total',      width:100, sortable:false, resizable: false, align:'right' }],
    altRows: true,
    height: 116,

    hiddengrid : true,
    hoverrows : false,
    footerrow : false,
    gridComplete:  function(){
      $('#cart_table td').addClass('payment_form');
      var data = $('#cart_table').getGridParam("userData");
      $.each(data.handlings,function(index, domElement){
        $(this.index).html(this.value);
      });
      $('#total_price').html(data.total);
      $('#cancel').attr("disabled", !data.can_cancel);
      $('#checkout').attr("disabled", !data.can_order);
      bindCartRemove(); // This listens for cart remove button;
    }
  });

  //Add datepickers
  $('#event-from').datepicker({
    minDate:0, changeMonth: true,
    changeYear: true, dateFormat:'yy-mm-dd',
    showButtonPanel: true,
    onSelect: function(dateText, inst) {
       $('#event-from').change();
    }
  });
  $('#event-to').datepicker({
    minDate:0, changeMonth: true,
    changeYear: true, dateFormat:'yy-mm-dd',
    showButtonPanel: true,
    onSelect: function(dateText, inst) {
       $('#event-to').change();
    }
  });
  //Start the event listeners
  $('#event-from').change(function(){ updateEvents(); });
  $('#event-to').change(function(){ updateEvents(); });
  $("#event-id").change(function(){ eventIdChange(); });
  $("#event-id").keyup(function(event){
    if(event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
      eventIdChange();
    }
  });
  $("#cat-select").change(function(){
    if($("#event-id").val() > 0 && $("#cat-select").val() > 0 ){
      var catId = $("#cat-select").val();
      var eventId = $("#event-id").val();
      $("#ft-cat-free-seats").html(catData.categories[catId].free_seats);
      updateSeatChart();
      ajaxQManager.add({
        type:      "POST",
        url:      "ajax.php?x=Discount",
        dataType:   "json",
        data:      {"pos":true,"action":"discounts","event_id": eventId, "cat_id":catId },
        success:function(data, status){
          if(data.enable_discounts){
            $("#discount-select").html("");
            $.each(data.discounts,function(){
              $("#discount-select").append(this.html);
            });
            $("#discount-name").show();
            $("#discount-select").show();
          }else{
            $("#discount-name").hide();
            $("#discount-select").html("<option value='0'></option>"); //hide().
          }
        }
      });
    }
  });
  $('#no_fee').click(function(){ refreshOrder(); });

  //Make sure all add ticket fields are added to this so when clearing selection
  //All fields are reset.
  $('#clear-button').click(function(){ clearOrder(); });

  //Change to live
  // Refresh (Update Price) on transaction change.
  jQuery("input").live("click change",function(){
    if(jQuery(this).attr('name') == 'handling_id' && jQuery(this).is(':radio')){
      refreshOrder();
    }
  });

  //Creates a auto refreshing function.
  refreshTimer = setInterval(function(){refreshOrder();}, 120000);

  $("#order-form").submit(function(){
    $("#error-message-main").hide();
    $(this).ajaxSubmit({
      data:{pos:"yes",action:"_addToCart"},
      dataType: "json",
      success: function(data, status){
        printMessages(data.messages);
        if(data.status){
          refreshOrder(); //Refresh Cart
          refreshCategories(); //Update ticket info (Free tickets etc)
        }
      }
    });
    return false;
  });

  ////
  // Sends the order information the POS Confirm action in controller/checkout.php
  ////
  $("#checkout").click(function(){
    var userdata = {ajax:"yes",pos:"yes",action:"_PosConfirm"};

    userdata['handling_id'] = $("input[type=radio][name='handling_id']:checked").val();
    if(userdata['handling_id'] === undefined){
      message = new Object();
      message.warning = "Select a payment option.";
      printMessages(message);
      return;
    }

    //If user is being passed check its valid
    if(!$('#user_info_none').is(':checked')){
      if(!$('#pos-user-form').valid()){
        message = new Object();
        message.warning = "Please fill missing fields!";
        printMessages(message);
        return;
      }
    }
    if($("input[type=checkbox][name='no_fee']").is(":checked")){
      userdata['no_fee'] = 1;
    }else{
      userdata['no_fee'] = 0;
    }
    $("#user_data input").each(function() {
      userdata[$(this).attr("name")] = $(this).val();
    });
    $("#user_data select").each(function() {
      userdata[$(this).attr("name")] = $(this).val();
    });
    $("#error-message-main").hide();
    ajaxQManager.add({
      type:      "POST",
      url:      "ajax.php?x=posconfirm",
      dataType:   "json",
      data:      userdata,
      success:function(data, status){
        printMessages(data.messages);
        if(data.status){
        	$("#user_data :input").each(function() {
          	$(this).val('');
        	});
          $('#user_id').val(0);
          $("#order_action").html(data.html);
          $("#order_action").dialog('open');
          bindCheckoutSubmitForm();
        }
      }
    });
    return false;
  });

  $("#cancel").click(function(){
   $("#error-message-main").hide();
   ajaxQManager.add({
      type:      "POST",
      url:      "ajax.php?x=poscancel",
      dataType:   "HTML",
      data:      {pos:"yes", action:"_PosCancel"},
      success:function(html, status){
        refreshOrder();
      	$("#user_data :input").each(function() {
          	$(this).val('');
        	});
      	$('#user_id').val(0);
      }
    });

    return false;
  });

  //Load the events
  updateEvents();
}

//Load Dialog Functions

var orderDialogs = function(){

  //Seat Chart Popup Box, Gets pushed back into Form on open.
  $("#seat-chart").dialog({
    bgiframe: false,autoOpen: false,
    height: 'auto',maxHeight: 400,
    width: 'auto',modal: true,
    buttons: {
      'Close': function() {
        $(this).dialog('close');
      }
    },
    close: function(){
        $("#show-seats input").val( $("#seatsselected").text());
    },
    open: function(){
      jQuery("#seat-chart").parent().appendTo($("#order-form"));
    }
  });

  // Opens a dialog to confirm payment
  $("#order_action").dialog({
    bgiframe: false,autoOpen: false,
    height: 'auto',width: 'auto',
    modal: true,
    close: function(event, ui) {
      updateEvents();
      if (timerid) {
        clearTimeout(timerid);
        timerid = -1;
      }
      refreshOrder();
    }
  });

}

//End of order startup