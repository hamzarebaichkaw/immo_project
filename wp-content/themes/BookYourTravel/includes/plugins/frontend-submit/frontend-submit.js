(function($){

	$(document).ready(function () {
		frontend_submit.init();
	});

	var frontend_submit = {

		init: function () {
	
			$.validator.addMethod( "greaterThan", 
				function(value, element, params) {
					if (!/Invalid|NaN/.test(new Date(value))) {
						return new Date(value) > new Date($(params).val());
					}
					return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val())); 
				},
				''
			);
	
			if (typeof($('.fes-upload-form.fes-form-vacancy #fes_start_date')) != 'undefined' && $('.fes-upload-form.fes-form-vacancy #fes_start_date').length > 0) {	
				$('.fes-upload-form.fes-form-vacancy #fes_start_date').datepicker({
					dateFormat: window.datepickerDateFormat,
					numberOfMonths: 1,
					minDate: 0,
					showOn: 'button',
					buttonImage: window.themePath + '/images/ico/calendar.png',
					buttonImageOnly: true,
					onClose: function (selectedDate) {
						var d = $.datepicker.parseDate(window.datepickerDateFormat, selectedDate);
						d = new Date(d.getFullYear(), d.getMonth(), d.getDate()+1);
						$(".fes-upload-form.fes-form-vacancy #fes_end_date").datepicker("option", "minDate", d);
					}
				});

				$('.fes-upload-form.fes-form-vacancy #fes_end_date').datepicker({
					dateFormat: window.datepickerDateFormat,
					numberOfMonths: 1,
					minDate: 0,
					showOn: 'button',
					buttonImage: window.themePath + '/images/ico/calendar.png',
					buttonImageOnly: true,
					onClose: function (selectedDate) {
						var d = $.datepicker.parseDate(window.datepickerDateFormat, selectedDate);
						d = new Date(d.getFullYear(), d.getMonth(), d.getDate()+1);
						$(".fes-upload-form.fes-form-vacancy #fes_start_date").datepicker("option", "maxDate", d);
					}
				});
			}

			if ($( '.fes-upload-form.fes-form-room_type' ).length > 0 ) {
				$( '.fes-upload-form.fes-form-room_type' ).validate({
					submitHandler: function(form) {
						form.submit();
					}
				});
			}
	
			if ($( '.fes-upload-form.fes-form-accommodation' ).length > 0) {
				$( '.fes-upload-form.fes-form-accommodation' ).validate({
					submitHandler: function(form) {
						form.submit();
					}
				});
			}
	
			if ( $( '.fes-upload-form.fes-form-vacancy' ).length > 0 ) {
				$( '.fes-upload-form.fes-form-vacancy' ).validate({
					submitHandler: function(form) {
						form.submit();
					}
				});
				
				$(".fes-upload-form.fes-form-vacancy #fes_end_date").rules( 'add', { greaterThan: ".fes-upload-form.fes-form-vacancy #fes_start_date" } );	
			}
	
			if ($( '.fes-upload-form.fes-form-accommodation #fes_accommodation_is_price_per_person' ).is(":checked")) {
				$('.per_person').show();
			} else {
				$('.per_person').hide();
			}
					
			$( '.fes-upload-form.fes-form-accommodation #fes_accommodation_is_price_per_person' ).on('change', function(e) {
				if(this.checked) {
					$('.per_person').show();
				} else {
					$('.per_person').hide();
				}		
			});
			
			if ($( '.fes-upload-form.fes-form-accommodation #fes_accommodation_is_self_catered' ).is(":checked")) {
				$('.room_types').hide();
			} else {
				$('.room_types').show();
			}
			$( '.fes-upload-form.fes-form-accommodation #fes_accommodation_is_self_catered' ).on('change', function(e) {
				if(this.checked) {
					$('.room_types').hide();
				} else {
					$('.room_types').show();
				}		
			});
			
			$( '.fes-upload-form.fes-form-vacancy select#fes_accommodation_id' ).on('change', function(e) {
				var accommodationId = $(this).val()
				var isSelfCatered = frontend_submit.accommodationIsSelfCatered(accommodationId);
				
				if (isSelfCatered) {
					$('.room_types').hide();
					$('.room_types').removeClass('required');
				} else {
				
					var roomTypes = frontend_submit.listAccommodationRoomTypes(accommodationId);
					
					$('.fes-upload-form.fes-form-vacancy select#fes_room_type_id').find('option:gt(0)').remove();
					
					var roomTypeOptions = "";

					$.each(roomTypes,function(index){
						roomTypeOptions += '<option value="'+ roomTypes[index].id +'">' + roomTypes[index].name + '</option>'; 
					});

					$('.fes-upload-form.fes-form-vacancy select#fes_room_type_id').append(roomTypeOptions);
					
					$('.room_types').addClass('required');
					$('.room_types').show();
				}
				
				var isPricePerPerson = frontend_submit.accommodationIsPricePerPerson(accommodationId);
				if (isPricePerPerson) {
					$('.per_person').show();
					$('.per_person').addClass('required');
				} else {
					$('.per_person').hide();
					$('.per_person').removeClass('required');
				}


			});
		
		},
		accommodationIsSelfCatered : function (accommodationId) {
			var retVal = 0;
			var _wpnonce = $('#fes_nonce').val();
				
			var dataObj = {
					'action':'fs_accommodation_is_self_catered_ajax_request',
					'accommodationId' : accommodationId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					retVal = data;
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			}); 
			
			return parseInt(retVal);
		},
		accommodationIsPricePerPerson : function(accommodationId) {

			var retVal = 0;
			var _wpnonce = $('#fes_nonce').val();
				
			var dataObj = {
					'action':'fs_accommodation_is_price_per_person_ajax_request',
					'accommodationId' : accommodationId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					retVal = data;
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			}); 
			
			return parseInt(retVal);
		},

		listAccommodationRoomTypes : function(accommodationId) {
			
			var retVal = null;
			var _wpnonce = $('#fes_nonce').val();
				
			var dataObj = {
					'action':'fs_accommodation_list_room_types_ajax_request',
					'accommodationId' : accommodationId,
					'nonce' : _wpnonce
				}				  

			$.ajax({
				url: window.adminAjaxUrl,
				data: dataObj,
				async: false,
				success:function(json) {
					// This outputs the result of the ajax request
					retVal = JSON.parse(json);
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			}); 
			
			return retVal;
			
		}
	}
})(jQuery);