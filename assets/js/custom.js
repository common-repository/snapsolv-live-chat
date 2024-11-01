jQuery(document).ready(function($){

var chat_script_status = $('#chat-script-status').val();

if(chat_script_status == 0){
	$('.select-save-button .woocommerce-save-changes-button').attr("disabled","disabled");
	$('.select-drop-pages').css("display","none");
}
	
$('.chat_on_off').change(function(){
	if(chat_script_status == 1){
		if($(this).val() =='on'){
			$('.select-drop-pages').css("display","table-row");
			$('.select-save-button .woocommerce-save-changes-button').attr("disabled","disabled");
			var show_chat_on_page_val = $("#show_chat_on_page option:selected").length;  
			if (show_chat_on_page_val < 1) {	
				$('.select-save-button .woocommerce-save-changes-button').attr("disabled","disabled");	
			} else {
				$('.select-save-button .woocommerce-save-changes-button').removeAttr("disabled");	
			} 
			
		 }	
			
		if($(this).val() =='off'){
			$('.select-drop-pages').css("display","none");
			$('.select-save-button .woocommerce-save-changes-button').removeAttr("disabled","");
		 }
	}
	});
	
	$('.show_chat_on_page').change(function(){
		if(chat_script_status == 1){
			if (($("#show_chat_on_page").val() == "" ) || ($("#show_chat_on_page").val() =='null')) {
				$('.select-save-button .woocommerce-save-changes-button').attr("disabled","disabled");	
			} else {
				$('.select-save-button .woocommerce-save-changes-button').removeAttr("disabled");
			}
		}
	});
	
	$( "#mainform input[type='password']" ).focus(function() {
         var password =$('#wc_settings_tab_demo_password').val();
	     var password_re = $('#wc_settings_tab_demo_password_again').val();
			if(password !='' && password_re !='') {
				if(password != password_re) {
					$('.nomatch_password').text('Passwords do not match');
					 return false;	
				} else {
					$('.nomatch_password').text('');
			}
		}
		 
		 if(password.match(/(?=\S{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z])/)){
		  $('.description_password').text('');
		 } else {
			//alert("Error: Password must contain only letters, numbers and underscores!");
			$('.description_password').text('Password has to be at least 6 chars long, one uppercase and one numerical!');
		    return false; 
			 
		}
    });
	$( "#mainform input[type='password']" ).change(function() {
         var password =$('#wc_settings_tab_demo_password').val();
		 var password_re = $('#wc_settings_tab_demo_password_again').val();
			if(password !='' && password_re !='') {
				if(password != password_re) {
					$('.nomatch_password').text('Passwords do not match');
					 return false;	
				} else {
					$('.nomatch_password').text('');
			}
		}
		 if(password.match(/(?=\S{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z])/)){
		  $('.description_password').text('');
		 } else {
			$('.description_password').text('Password has to be at least 6 chars long, one uppercase and one numerical!');
		    return false; 
			 
		}
    });
	
	$('body').on( "click", ".woocommerce-save-button",function() {	
		
		var is_thirdparty_signup =$('.is_thirdparty_signup').val();
		var agent_email = $('#wc_settings_tab_demo_agent_email').val();
		if(agent_email !='') {
			if(agent_email.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)){
			  $('.nomatch_email').text('');
			 } else {
				$('.nomatch_email').text('Email not valid!');
				return false;  
			}
		} else {
			 	$('.nomatch_email').text('Enter Account Admin email');
		}
		var password =$('#wc_settings_tab_demo_password').val();
		var password_re =$('#wc_settings_tab_demo_password_again').val();
		if(password !='' && password_re !='') {
			if(password != password_re) {
				$('.nomatch_password').text('Passwords do not match');
				 return false;	
			} else {
				$('.nomatch_password').text('');
				}
				
			if(password.match(/(?=\S{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z])/)){
			  $('.description_password').text('');
			 } else {
				$('.description_password').text('Password has to be at least 6 chars long, one uppercase and one numerical!');
				return false; 
			}	
		} else {
			//
		}
		if(is_thirdparty_signup == 'true') {
		$('#mainform').validate({
		
		rules: {

		"brand_name": {
		required: true,
		
		},
		"password_again": {
		required: true,
		
		},		
		"password": {
		required: true,
		
		}
		,	
		"agent_lastname": {
		required: true,
		
		}
		,	
		"agent_firstname": {
		required: true,
		
		},
		"agent_phone": {
                minlength:10,
                maxlength:12,
                number: true
            },
		"term_condition_privacy_policy": {
		required: true,	
		}
			
		},
		messages: {
        brand_name: "Enter your business name",
		agent_firstname: "Enter your firstname",
        agent_lastname: "Enter your lastname",
        password: "Choose Password",
		password_again: "Enter Password again",
   
    },
		
		submitHandler: function(form) {
			var formdata = false;
		    $('#formLoad').show();
			if (window.FormData){
				formdata = new FormData(form);
			}
			$('#formLoad').show();
				$.ajax({
				url         : ajaxurl,
				data        : formdata ? formdata : form.serialize(), 
				cache       : false,
				contentType : false,
				processData : false,
				type        : 'POST',
				success: function(response){
					$('#formLoad').hide();
					try {
						var res = jQuery.parseJSON(response);
						if(res.status == 'fail'){
							if(res.message == 'Something went wrong'){
								$('.res_cur_msg').text('To make this form to work please make sure that outgoing connections are allowed on your server.');
							} else {
								$('.res_cur_msg').text('Authentication Failed.');
							}
						}
						if(res.status == 'success'){
							window.location.href = window.location.href;
						}
						if(res.nothing != ''){
							
							$('.res_cur_msg').text(res.nothing);
						}
					} catch(err) {
						$('.res_cur_msg').text('Something went wrong. Please try after sometime.');
					}
					}
				});
			}
		
			
		});

		}
  });


/*login Script*/

$('body').on( "click", ".woocommerce-login-button",function() {	

       
		$('#mainform1').validate({
		
		rules: {
		"username": {
		required: true,
		
		},		
		"password": {
		required: true,
		},
		"term_condition_privacy_policy": {
		required: true,	
		}
		},
		messages: {
		username: "Enter Account Admin email.",
        password: "Choose Password",
		
   
    },
		
		submitHandler: function(form) {
			var formdata = false;
			if (window.FormData){
				formdata = new FormData(form);
			}
			$('#formLoad').show();
				$.ajax({
				url         : ajaxurl,
				data        : formdata ? formdata : form.serialize(), 
				cache       : false,
				contentType : false,
				processData : false,
				type        : 'POST',
				success: function(response){
					try{
						$('#formLoad').hide();
						var res = jQuery.parseJSON(response);
						//alert(res.status);
						if(res.status == 'fail'){
							//window.location.href = window.location.href;
							if(res.message == 'Something went wrong'){
								$('.res_cur_msg').text('To make this form to work please make sure that outgoing connections are allowed on your server.');
							} else {
								$('.res_cur_msg').text('Authentication Failed.');
							}
						}
						if(res.status == 'success'){
							window.location.href = window.location.href;
						}
						if(res.nothing != ''){
							//window.location.href = window.location.href;
							$('.res_cur_msg').text(res.nothing);
						}
					
					} catch(err) {
						$('.res_cur_msg').text('Something went wrong. Please try after sometime.');
					}
					
					}
				});
			}
		
			
		});

  });

/*Reset Script*/

$('body').on( "click", ".woocommerce-reset-button",function() {	
        //alert('rtest');
		var reset_data = $(this).val();
		   if(reset_data){
				var r = confirm("Do you want to reset ?");
				if (r == true) {
					var data = {
			  'action' : 'reset_snap_settings',
			  'reset_data' : reset_data, 
			  }	
			  jQuery.post(ajaxurl, data, function(response){
				var res = jQuery.parseJSON(response);
					if(res.reset_data=='yes') { 
					  window.location.href = window.location.href;
					 }
				  
			 });
				} else {
					
				}
			}
			
		 

  });
	jQuery('.login-form-snap, .registration-form-snap').next('p.submit').hide();
	jQuery('.login-form-snap').hide();
	jQuery('.click_for_login').click( function(){
		jQuery('.login-form-snap').show();
		jQuery('.registration-form-snap').hide();
	});
	jQuery('.click_for_signup').click( function(){
		jQuery('.login-form-snap').hide();
		jQuery('.registration-form-snap').show();
	});	
		
	jQuery('.snap_solv_container_settings').next('p.submit').hide();
});


