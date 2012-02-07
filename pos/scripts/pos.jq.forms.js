$(window).load(function(){
$("#pos-user-form").validate({
 	rules: {
			user_firstname	: 	{ required :true },
			user_lastname	:	{ required : true },
			user_address	:	{ required : true },
			user_zip 	:	{ required : true },
			user_city 	: 	{ required : true },
			user_phone 	: 	{ digits : true, minlength : 9, maxlength : 14 },
			user_fax 	: 	{ digits : true, minlength : 9, maxlength : 14 },
			user_email 	: 	{ required : true, email :true }
		},
		messages : {
			user_firstname : { required : lang.required },
			user_lastname : { required : lang.required	},
			user_address : { required : lang.required },
			user_zip : { required : lang.required },
			user_city : { required : lang.required	},
			user_phone : { minlength : lang.phone_short, maxlength : lang.phone_long, digits : lang.not_number },
			user_fax : { minlength : lang.fax_short, maxlength : lang.fax_long, digits : lang.not_number },
			user_email : { required : lang.required, email : lang.email_valid }
		},
		errorClass: "form-error",
		success: "form-valid",
		errorPlacement: function(error, element) {
	 		if (element.attr("name") == "check_condition")
		   		error.insertAfter(element);
		 	else
		   		error.insertAfter(element);
		},
    submitHandler: function(form) {
      //do nothing
    }
	});
  
});