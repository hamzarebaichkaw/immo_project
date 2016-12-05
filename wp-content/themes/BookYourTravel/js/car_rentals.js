(function($){

	$(document).ready(function () {
		car_rentals.init();
	});
	
	var car_rentals = {

		init: function () {

			$("#gallery").lightSlider({
				item:1,
				slideMargin:0,
				auto:true,
				loop:true,
				speed:600,
				keyPress:true,
				gallery:true,
				thumbItem:8,
				galleryMargin:3,
				onSliderLoad: function() {
					$('#gallery').removeClass('cS-hidden');
				}  
			});
		
			$('.book_car_rental').on('click', function(event) {
			
				$('#wait_loading').show();
				var carRentalId = window.carRentalId;
				$('#car_booking_form_car_type').html(window.carRentalCarType).val();
				$('#car_booking_form_car_price').html(window.carRentalPrice).val();
				$('#car_booking_form_car_rental_title').html(window.carRentalTitle).val();
				$('#car_booking_form_pick_up').html(window.carRentalPickUp).val();
				$('#car_booking_form_car_rental_id').val(window.carRentalId);

				window.carRentalBookedOutDays = car_rentals.getCarRentalBookedOutDates(window.carRentalId, window.currentMonth, window.currentYear);
				
				car_rentals.showCarRentalForm();
				
				$('body,html').animate({
					scrollTop: 0
				}, 800);
				
				$('#wait_loading').hide();
				event.preventDefault();
			});

			$('#cancel-car_rental-booking').on('click', function(event) {
				car_rentals.hideCarRentalBookingForm();
				car_rentals.showCarRentalInfo();
				event.preventDefault();
			});	

			$('#car_rental-booking-form').validate({
				onkeyup: false,
				ignore: [],
				errorPlacement: function(error, element) {
					if (element.attr('type') == 'hidden' && (element.attr('id') == 'car_booking_form_date_from' || element.attr('id') == 'car_booking_form_date_to'))
						error.appendTo( $('#car_booking_form_datepicker') );
					else
						error.insertAfter(element);
				},
				rules: {
					car_booking_form_first_name: {
						required: true
					},
					car_booking_form_last_name: "required",
					car_booking_form_email: {
						required: true,
						email: true
					},
					car_booking_form_confirm_email: {
						required: true,
						equalTo: "#car_booking_form_email"
					},
					car_booking_form_phone: "required",
					car_booking_form_address: "required",
					car_booking_form_town: "required",
					car_booking_form_zip: "required",
					car_booking_form_country: "required",
					car_booking_form_date_from: "required",
					car_booking_form_date_to: "required",
					car_booking_form_drop_off: "required"
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
					car_booking_form_first_name: window.bookingFormFirstNameError,
					car_booking_form_last_name: window.bookingFormLastNameError,
					car_booking_form_email: window.bookingFormEmailError,
					car_booking_form_confirm_email: {
						required: window.bookingFormConfirmEmailError1,
						equalTo: window.bookingFormConfirmEmailError2
					},
					car_booking_form_phone: window.bookingFormPhoneError,
					car_booking_form_address: window.bookingFormAddressError,
					car_booking_form_town: window.bookingFormCityError,
					car_booking_form_zip: window.bookingFormZipError,
					car_booking_form_country: window.bookingFormCountryError,
					car_booking_form_date_from: window.bookingFormDateFromError,
					car_booking_form_date_to: window.bookingFormDateToError,
					car_booking_form_drop_off: window.bookingFormDropOffError
				},
				submitHandler: function() { 
					car_rentals.processCarRentalBooking(); 
				}
			});
		}, 
		formatPrice: function( price ) {
			if (window.currencySymbolShowAfter)
				return price + ' ' + window.currencySymbol;
			else
				return window.currencySymbol + ' ' + price;
		},
		showCarRentalInfo : function () {
			$('.three-fourth .lSSlideOuter').show();
			$('.three-fourth .inner-nav').show();
			$('.three-fourth .tab-content').show();
			$(".tab-content").hide();
			$(".tab-content:first").show();
			$(".inner-nav li:first").addClass("active");
		},
		showCarRentalForm :	function () {
		
			$('#car_rental-booking-form').show();
			$('.three-fourth .lSSlideOuter').hide();
			$('.three-fourth .inner-nav').hide();
			$('.three-fourth .tab-content').hide();

			car_rentals.bindCarRentalDatePicker();
		},		
		bindCarRentalDatePicker : function () {
		
			if (typeof $('#car_booking_form_datepicker') !== 'undefined') {

				$('#car_booking_form_datepicker').datepicker({
					dateFormat: window.datepickerDateFormat,
					numberOfMonths: 1,
					minDate: 0,
					beforeShowDay: function(d) {
					
						var dUtc = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate());
					
						var selectedTimeFrom = null;
						var selectedTimeTo = null;
						
						if ($("#car_booking_form_date_from").val()) {
							selectedTimeFrom = parseInt($("#car_booking_form_date_from").val());
						}
						if ($("#car_booking_form_date_to").val()) {
							selectedTimeTo = parseInt($("#car_booking_form_date_to").val());
						}
					
						if (window.carRentalBookedOutDays) {
						
							var dateTextForCompare = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2);
							var dateTextForCompare2 = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2) + " 00:00:00";
							
							if (selectedTimeFrom && dUtc == selectedTimeFrom)
								return [true, 'dp-highlight'];								
							if ($.inArray(dateTextForCompare, window.carRentalBookedOutDays) > -1 || $.inArray(dateTextForCompare2, window.carRentalBookedOutDays) > -1)
								return [false, 'ui-datepicker-unselectable ui-state-disabled'];

						}
						
						return [true, selectedTimeFrom && ((dUtc == selectedTimeFrom) || (selectedTimeTo && dUtc >= selectedTimeFrom && dUtc <= selectedTimeTo)) ? "dp-highlight" : ""];
					},
					onSelect: function(dateText, inst) {
						$(".dates_row").show();
						var dateTextForParse = inst.currentYear + '-' + (inst.currentMonth + 1) + '-' + ("0" + inst.currentDay).slice(-2);
						
						var selectedUtcTime = Date.UTC(inst.currentYear, inst.currentMonth, inst.currentDay);
					
						var selectedUtcDate = car_rentals.convertLocalDateToUTC(new Date(selectedUtcTime));
						
						var date1 = null;
						if ($("#car_booking_form_date_from").val()) {
							date1 = car_rentals.convertLocalDateToUTC(new Date(parseInt($("#car_booking_form_date_from").val())));
						}
						var date2 = null;
						if ($("#car_booking_form_date_to").val()) {
							date2 = car_rentals.convertLocalDateToUTC(new Date(parseInt($("#car_booking_form_date_to").val())));
						}
						
						if (!date1 || date2 || (date1 && date1.toString() == selectedUtcDate.toString())) {
							$("#car_booking_form_date_from").val(selectedUtcTime);
							$("#date_from").html(dateText);
							$("#car_booking_form_date_to").val("");
							$("#date_to").html("");
							$(".dates_row").hide();
						} else {
							var dateCompare = Date.parse(dateTextForParse);
							if (dateCompare < date1)
							{
								$("#car_booking_form_date_from").val(selectedUtcTime);
								$("#date_from").html(dateText);
								$("#car_booking_form_date_to").val("");
								$("#date_to").html("");	
								$(".dates_row").hide();							
							}
							else
							{
								date1 = car_rentals.convertLocalDateToUTC(new Date(parseInt($("#car_booking_form_date_from").val())));
								date2 = car_rentals.convertLocalDateToUTC(new Date(selectedUtcTime));
								
								var allOk = true;
								for (var d = date1; d <= date2; d.setDate(d.getDate() + 1)) {
									var dateTextForCompare = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' +  ("0" + d.getDate()).slice(-2);
									if ($.inArray(dateTextForCompare, window.carRentalBookedOutDays) > -1)
										allOk = false;
								}
								
								if (!allOk) {
									$("#car_booking_form_date_from").val(selectedUtcTime);
									$("#date_from").html(dateText);
									$("#car_booking_form_date_to").val("");									
									$("#date_to").html("");	
									$(".dates_row").hide();
								} else {
									$("#car_booking_form_date_to").val(selectedUtcTime);
									$("#date_to").html(dateText);
								}
							}
						}
					},
					onChangeMonthYear: function (year, month, inst) {
						window.currentMonth = month;
						window.currentYear = year;
						window.carRentalBookedOutDays = car_rentals.getCarRentalBookedOutDates(window.carRentalId, window.currentMonth, window.currentYear);
						car_rentals.bindCarRentalDatePicker();
					}
				});
			}

		},
		hideCarRentalBookingForm : function () {
			$('#car_rental-booking-form').hide();
		},
		processCarRentalBooking: function () {
		
			$('#wait_loading').show();
		
			var selectedDateFrom = car_rentals.convertLocalDateToUTC(new Date(parseInt($("#car_booking_form_date_from").val())));
			var selectedDateTo = car_rentals.convertLocalDateToUTC(new Date(parseInt($("#car_booking_form_date_to").val())));
			
			var dateFrom = selectedDateFrom.getFullYear() + "-" + (selectedDateFrom.getMonth() + 1) + "-" + selectedDateFrom.getDate(); 
			var dateTo = selectedDateTo.getFullYear() + "-" + (selectedDateTo.getMonth() + 1) + "-" + selectedDateTo.getDate(); 
					
			var firstName = $('#car_booking_form_first_name').val();
			var lastName = $('#car_booking_form_last_name').val();
			var email = $('#car_booking_form_email').val();
			var phone = $('#car_booking_form_phone').val();
			var address = $('#car_booking_form_address').val();
			var town = $('#car_booking_form_town').val();
			var zip = $('#car_booking_form_zip').val();
			var country = $('#car_booking_form_country').val();
			var requirements = $('#car_booking_form_requirements').val();
			var carRentalId = $('#car_booking_form_car_rental_id').val();
			var pickUp = $('#car_booking_form_pick_up').html();
			var dropOffText = $('#car_booking_form_drop_off option:selected').text();
			var dropOff = $('#car_booking_form_drop_off option:selected').val();
			var carRentalName = $('#car_booking_form_car_rental_title').html();
			var cValS = $('#c_val_s_cr').val();
			var cVal1 = $('#c_val_1_cr').val();
			var cVal2 = $('#c_val_2_cr').val();
				
			$("#car_confirm_first_name").html(firstName);
			$("#car_confirm_last_name").html(lastName);
			$("#car_confirm_email_address").html(email);
			$("#car_confirm_phone").html(phone);
			$("#car_confirm_street").html(address);
			$("#car_confirm_town").html(town);
			$("#car_confirm_zip").html(zip);
			$("#car_confirm_country").html(country);
			$("#car_confirm_requirements").html(requirements);
			$("#car_confirm_date_from").html(dateFrom);
			$("#car_confirm_date_to").html(dateTo);
			$("#car_confirm_pick_up").html(pickUp);
			$("#car_confirm_drop_off").html(dropOffText);
			$('#car_confirm_car_rental_name').html(carRentalName);

			var d1 =car_rentals.convertLocalDateToUTC(new Date(parseInt($("#car_booking_form_date_from").val())));
			var d2 =car_rentals.convertLocalDateToUTC(new Date(parseInt($("#car_booking_form_date_to").val())));       
			var days = ( Math.abs( ( d2-d1 ) / 86400000 ) ); //days between 2 dates		
			var pricePerDay = window.carRentalPrice;
			var totalPrice = days * pricePerDay;
			
			$('#car_confirm_total_price').html(car_rentals.formatPrice(totalPrice));

			
			$.ajax({
				url: BYTAjax.ajaxurl,
				data: {
					'action':'book_car_rental_ajax_request',
					'first_name' : firstName,
					'last_name' : lastName,
					'email' : email,
					'phone' : phone,
					'address' : address,
					'town' : town,
					'zip' : zip,
					'country' : country,
					'requirements' : requirements,
					'date_to' : dateTo,
					'date_from' : dateFrom,
					'car_rental_id' : carRentalId,
					'drop_off' : dropOff,
					'c_val_s' : cValS,
					'c_val_1' : cVal1,
					'c_val_2' : cVal2,
					'nonce' : BYTAjax.nonce
				},
				success:function(data) {
					// This outputs the result of the ajax request
					
					if (data == 'captcha_error') {
						$("div.error div p").html(window.InvalidCaptchaMessage);
						$("div.error").show();
					} else {
						var returnedId = data;
						$("div.error div p").html('');
						$("div.error").hide();
						
						var isReservationOnly = car_rentals.getCarRentalIsReservationOnly(window.carRentalId);
						
						if (window.useWoocommerceForCheckout && window.wooCartPageUri.length > 0 && !isReservationOnly) {
							car_rentals.addCRProdToCart(returnedId);
						} else {
							car_rentals.hideCarRentalBookingForm();
							car_rentals.showCarRentalConfirmationForm();
						}
					}
					$('#wait_loading').hide();
				},
				error: function(errorThrown){

				}
			}); 
		},
		getCarRentalIsReservationOnly : function (carRentalId) {
			var isReservationOnly = 0;

			var dataObj = {
				'action':'car_rental_is_reservation_only_request',
				'car_rental_id' : carRentalId,
				'nonce' : BYTAjax.nonce
			}		

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					isReservationOnly = parseInt(data);
				},
				error: function(errorThrown){

				}
			});

			return isReservationOnly;
		},		
		addCRProdToCart : function (p_id) {
			$.get(window.siteUrl + '/?post_type=product&add-to-cart=' + p_id, function() {
				car_rentals.crRedirectToCart();
			});
		},
		crRedirectToCart : function () {
			top.location.href = window.wooCartPageUri;
		},
		showCarRentalConfirmationForm : function () {
			$('#car_rental-confirmation-form').show();
		},
		hideCarRentalConfirmationForm : function () {
			$('#car_rental-confirmation-form').hide();
		},
		getCarRentalBookedOutDates : function (carRentalId, month, year) {
			var dateArray = new Array();

			var dataObj = {
				'action':'car_rental_booked_dates_request',
				'car_rental_id' : carRentalId,
				'month' : month,
				'year' : year,
				'nonce' : BYTAjax.nonce
			}		

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				async: false,
				success:function(json) {
					// This outputs the result of the ajax request
					var bookedDates = JSON.parse(json);
					var i = 0;
					for (i = 0; i < bookedDates.length; ++i) {
						dateArray.push(bookedDates[i].booking_date);
					}
				},
				error: function(errorThrown){

				}
			});

			return dateArray;
		},
		convertUTCDateToLocalDate : function(date) {
			var newDate = new Date(date.getTime()+date.getTimezoneOffset()*60*1000);

			var offset = parseInt((date.getTimezoneOffset() / 60), 10);
			var hours = date.getHours();

			newDate.setHours(hours - offset);

			return newDate;   
		},
		convertLocalDateToUTC : function (date) { 
			return new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds()); 
		}
	}

})(jQuery);