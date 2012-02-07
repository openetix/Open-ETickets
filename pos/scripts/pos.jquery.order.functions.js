
//Actual Order Functions

var clearOrder = function(){
  $("#cat-select").html("<option value='0'></option>");
  $("#discount-select").html("<option value='0'></option>");//.hide();
  $("#discount-name").hide();
  $("#seat-qty").hide();
  $("#seat-chart").html("");
  $("#date-from").val('');
  $("#date-to").val('');
  $("#ft-event-free-seats").html('');
  $("#ft-cat-free-seats").html('');
	$("#user_data :input").each(function() {
  	$(this).val('');
	});
  $('#user_id').val(0);
  unBindSeatChart();
  updateEvents();
  return false;
}

var eventIdChange = function(){
  var event = $("#event-id option:selected");
  var eventId = $("#event-id").val();
  if(eventId > 0){
    ajaxQManager.add({
      type:      "POST",
      url:      "ajax.php?x=cat",
      dataType:   "json",
      data:      {"pos":true,"action":"categories","event_id":eventId, "categories_only":true},
      success:function(data, status){
        printMessages(data.messages);
        catData = data; //set cat var
        //Fill Categories
        $("#cat-select").hide().html("");
        $.each(data.categories,function(){
          $("#cat-select").append(this.html);
        });
        $("#discount-name").hide();
        $("#cat-select").show().change();
        //Check catData for discounts...

      }
    });
    $("#ft-event-free-seats").html(event.data('seats'));
  }else{
    $("#cat-select").html("<option value='0'></option>");
    $("#discount-name").hide();
    $("#discount-select").html("<option value='0'></option>");//hide().
  }
}

//The refresh orderpage, the ajax manager SHOULD ALLWAYS be used where possible.
var refreshOrder = function(){
  var data = $('#cart_table').getGridParam('postData');
  data['handling_id'] = $("input[type=radio][name=handling_id]:checked").val();
  if($("input[type=checkbox][name='no_fee']").is(":checked")){
  	data['no_fee'] = 1;
  }else{
  	data['no_fee'] = 0;
  }

  $('#cart_table').setGridParam('postData', data);
  $('#cart_table').trigger("reloadGrid");
}

//refresh Categores, will update event categories.
var refreshCategories = function(){
   if($("#event-id").val() > 0 ){
      var eventId = $("#event-id").val();

      ajaxQManager.add({
         type:      "POST",
         url:      "ajax.php?x=cat2",
         dataType:   "json",
         data:      {"pos":true,"action":"categories","categories_only":true,"event_id":eventId},
         success:function(data, status){
            printMessages(data.messages);
            if(data.status){
               catData.categories = data.categories; //set cat var
               updateSeatChart();
            }
         }
      });
   }
}

var updateSeatChart = function(){
   var catId = $("#cat-select").val();
   unBindSeatChart();
   if(catData.categories[catId].numbering){
      $("#seat-qty").hide();
      $("#seat-qty input").val('');
      $("#seat-chart").attr("title", catData.categories[catId].title);
      $("#seat-chart").html(catData.categories[catId].placemap);
      bindSeatChart();
   }else{
      unBindSeatChart();
      $("#seat-chart").html("");
      $("#seat-qty").show();
   }
}
// Update events function will take the dates and compile onto the event var
var updateEvents = function(){
   var dateFrom = $('#event-from').val();
   var dateTo = $('#event-to').val();

   ajaxQManager.add({
      type:      "POST",
      url:      "ajax.php?x=event",
      dataType:   "json",
      data:      {pos:true,action:"events",datefrom:dateFrom,dateto:dateTo},
      success:function(data, status){
         printMessages(data.messages);
         if(data.status){
            $("#event-id").hide().empty();
            $.each(data.events,function(){
               $("#event-id").append($(this.html).data('seats', this.free_seats));
            });
            $("#event-id").show().change();
         }
      }
   });
}
var bindCheckoutSubmitForm = function(){
  $("#payment-confirm-form").ajaxForm({
    data:{ajax:true, pos:"yes", action:"_PosSubmit"},
    url:      "ajax.php?x=_possubmit",
    dataType: "json",
    success:  function(data, status){
      if(jQuery.isPlainObject(data)){
        printMessages(data.messages);
        $("#order_action").dialog('close');
        $("#order_action").html(data.html);
        bindCheckoutSubmitForm();
        $("#order_action").dialog('open');
      }
    }
  });
}
var seatCount = 0;
var bindSeatChart = function(){
   $("#show-seats").show();
   $("#show-seats button").click(function(){
      $("#seat-chart").dialog('open');
   });
}
var unBindSeatChart = function(){
   //$("#seat-chart").dialog('destroy');
   $("#show-seats").hide();
   $("#show-seats button").unbind( "click" );
}

var bindCartRemove = function(){
  $(".remove-cart-row").unbind('submit');
  $(".remove-cart-row").submit(function(){
    $(this).ajaxSubmit({
      url: 'ajax.php?x=removeitemcart',
      dataType: 'json',
      data:{pos:"yes",action:"_removeitemcart"},
      success: function(data){
        printMessages(data.messages);
        if(data.status){
          refreshOrder(); //Refresh Cart
          refreshCategories(); //Update ticket info (Free tickets etc)
        }
      }
    });
    return false;
  });
}

var printMessages = function(messages){
  if(messages === undefined){
    return;
  }
  if (messages.warning) {
    $("#error-text-main").html(messages.warning);
    $("#error-message-main").show();
    setTimeout(function(){$("#error-message-main").hide();}, 8000);
  }
  if (messages.notice) {
    $("#notice-text").html(messages.notice);
    $("#notice-message").show();
    setTimeout(function(){$("#error-notice").hide();}, 8000);
  }
}