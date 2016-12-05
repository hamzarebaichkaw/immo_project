(function($){

	$(document).ready(function () {
		inquiry.init();
	});
	
	var inquiry = {

		init: function () {
	
			$('.contact-' + window.postType).on('click', function(event) {
				inquiry.showInquiryForm();
				event.preventDefault();
			});	

			$('.cancel-' + window.postType + '-inquiry').on('click', function(event) {
				inquiry.hideInquiryForm();
				event.preventDefault();
			});	
			
			$('.' + window.postType + '-inquiry-form').validate({
				onkeyup: false,
				rules: {
					your_name: "required",
					your_email: { required:true, email:true },
					your_phone: "required",
					your_message: "required"
				},
				invalidHandler: function(e, validator) {
					var errors = validator.numberOfInvalids();
					if (errors) {
						var message = errors == 1
							? window.formSingleError
							: window.formMultipleError.format(errors);
						$("div.error div p").html(message);
						$("div.error").show();
					} else {
						$("div.error").hide();
					}
				},
				messages: {
					your_message: window.inquiryFormMessageError,
					your_name: window.inquiryFormNameError,
					your_email: window.inquiryFormEmailError,
					your_phone: window.inquiryFormPhoneError
				},
				submitHandler: function() { inquiry.processInquiry(); }
			});
		},		
		showInquiryForm : function () {
			$('.three-fourth').hide();
			$('.right-sidebar').hide();
			$('.full-width.' + window.postType + '-inquiry-section').show();
		},
		hideInquiryForm : function () {
			$('.three-fourth').show();
			$('.right-sidebar').show();
			$('.full-width.' + window.postType + '-inquiry-section').hide();
		},
		processInquiry : function () {
			var your_name = $('#your_name').val();
			var your_email = $('#your_email').val();
			var your_phone = $('#your_phone').val();
			var your_message = $('#your_message').val();
			var cValS = $('#c_val_s_inq').val();
			var cVal1 = $('#c_val_1_inq').val();
			var cVal2 = $('#c_val_2_inq').val();
			
			var dataObj = {
					'action':'inquiry_ajax_request',
					'your_name' : your_name,
					'your_email' : your_email,
					'your_phone' : your_phone,
					'your_message' : your_message,
					'userId' : window.currentUserId,
					'postId' : window.postId,
					'c_val_s' : cValS,
					'c_val_1' : cVal1,
					'c_val_2' : cVal2,
					'nonce' : BYTAjax.nonce
				}		
			
			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				success:function(data) {
					if (data == 'captcha_error') {
						$("div.error div p").html(window.InvalidCaptchaMessage);
						$("div.error").show();
					} else {
						// This outputs the result of the ajax request
						$('.contact-' + window.postType).hide(); // hide the button
						inquiry.hideInquiryForm();
						$('.inquiry-form-thank-you').show();
					}
				},
				error: function(errorThrown){

				}
			}); 
		}
	}

})(jQuery);