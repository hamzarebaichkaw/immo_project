(function($){

	$(document).ready(function () {
		cruises.init();
	});
	
	var cruises = {

		init: function () {

			cruises.bindSelectDatesButton();
		
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
			
			$('.radio').bind('click.uniform',
				function (e) {
					if ($(this).find("span").hasClass('checked')) 
						$(this).find("input").attr('checked', true);
					else
						$(this).find("input").attr('checked', false);
				}
			);
						
			$('#cruise-booking-form').validate({
				onkeyup: false,
				ignore: [],
				rules: {
					first_name: {
						required: true
					},
					last_name: "required",
					email: {
						required: true,
						email: true
					},
					confirm_email: {
						required: true,
						equalTo: "#email"
					},
					phone: "required",
					address: "required",
					town: "required",
					zip: "required",
					country: "required",
					start_date: "required"
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
					first_name: window.bookingFormFirstNameError,
					last_name: window.bookingFormLastNameError,
					email: window.bookingFormEmailError,
					confirm_email: {
						required: window.bookingFormConfirmEmailError1,
						equalTo: window.bookingFormConfirmEmailError2
					},
					phone: window.bookingFormPhoneError,
					address: window.bookingFormAddressError,
					town: window.bookingFormCityError,
					zip: window.bookingFormZipError,
					country: window.bookingFormCountryError,
					start_date: window.bookingFormStartDateError
				},
				submitHandler: function() { cruises.processCruiseBooking(); }
			});
						
			$('#cancel-cruise-booking').on('click', function(event) {
				cruises.hideCruiseBookingForm();
				cruises.showCruiseInfo();
				event.preventDefault();
			});	
		},
		formatPrice: function( price ) {
			if (window.currencySymbolShowAfter)
				return price + ' ' + window.currencySymbol;
			else
				return window.currencySymbol + ' ' + price;
		},
		bindSelectDatesButton : function () {
		
			$('.book-cruise-select-dates').unbind('click');
			$('.book-cruise-select-dates').on('click', function(event) {
				
				event.preventDefault();
				$('.book-cruise-select-dates').show();
				$(this).hide();
				
				$('#wait_loading').show();
				
				var prevCabinTypeId = window.cabinTypeId;
				
				$("#start_date_span").html("");
				$("#start_date").val("");
				$(".dates_row").hide();
				$(".price_row").hide();
				$('.booking-commands').hide();
				
				var buttonId = $(this).attr('id');
				window.cabinTypeId = buttonId.replace('book-cruise-', '');
			
				if (prevCabinTypeId > 0) {
					$('.cruise_schedule_datepicker').datepicker('destroy');
					$("#cabin_type_" + prevCabinTypeId + " .step1_controls").html('');
					$("#cabin_type_" + prevCabinTypeId + " .step1_controls").show();
				} 

				$("#cabin_type_" + window.cabinTypeId + " .step1_controls").html($(".step1_controls_holder").html());
				$("#cabin_type_" + window.cabinTypeId + " .step1_controls").show();
				
				$("#cabin_type_" + window.cabinTypeId + " .step1_controls .datepicker_holder").addClass('cruise_schedule_datepicker');
					
				cruises.getCruiseScheduleEntries(window.cruiseId, window.cabinTypeId, window.currentDay, window.currentMonth, window.currentYear, cruises.bindCruiseDatePicker);
					
				cruises.bindNextButton();
				cruises.bindResetButton();
			});
		},
		getCruiseIsReservationOnly : function (cruiseId) {
			var isReservationOnly = 0;

			var dataObj = {
				'action':'cruise_is_reservation_only_request',
				'cruise_id' : cruiseId,
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
		bindResetButton : function() {
			$('.book-cruise-reset').unbind('click');
			$('.book-cruise-reset').on('click', function(event) {

				event.preventDefault();
				$('.book-cruise-select-dates').show();
				window.cabinTypeId = 0;
				$("#start_date_span").html("");
				$("#start_date").val("");
				$(".dates_row").hide();
				$(".price_row").hide();
				$('.booking-commands').hide();
				
			});
		},
		bindNextButton : function () {

			$('.book-cruise-next').unbind('click');
			$('.book-cruise-next').on('click', function(event) {

				$('#wait_loading').show();
				event.preventDefault();
								
				var children = $('#booking_form_children').val();
				if (!children)
					children = 0;
					
				var adults = $('#booking_form_adults').val();
				if (!adults)
					adults = 0;
								
				$('.step_1_adults_holder').html(adults);
				$('.step_1_children_holder').html(children);
				$('.step_1_room_type_holder').html('');
				$('.step_1_cabin_type_holder').html($('#cabin_type').html());
				$('.step_1_cruise_date_holder').html($('#start_date_span').html());
				$('.step_1_total_holder').html($('.total_price').html());
				
				cruises.showCruiseBookingForm();
				
				$('body,html').animate({
					scrollTop: 0
				}, 800);
					
				$('#wait_loading').hide();
			});
			
		},
		processCruiseBooking : function () {

			$('#wait_loading').show();
			
			var firstName = $('#first_name').val();
			var lastName = $('#last_name').val();
			var email = $('#email').val();
			var phone = $('#phone').val();
			var address = $('#address').val();
			var town = $('#town').val();
			var zip = $('#zip').val();
			var country = $('#country').val();
			var requirements = $('#requirements').val();
			
			var cruiseStartDate = cruises.convertLocalDateToUTC(new Date(parseInt($("#start_date").val())));
			cruiseStartDate = cruiseStartDate.getFullYear() + "-" + (cruiseStartDate.getMonth() + 1) + "-" + cruiseStartDate.getDate(); 
			var cruiseStartDateText = $("#start_date_span").html();
			var cruiseScheduleId = cruises.getCruiseScheduleId(window.cruiseId, window.cabinTypeId, cruiseStartDate);
			
			var adults = $("#booking_form_adults").val();
			var children = $("#booking_form_children").val();
			
			var cValS = $('#c_val_s_cru').val();
			var cVal1 = $('#c_val_1_cru').val();
			var cVal2 = $('#c_val_2_cru').val();
			
			$("#confirm_first_name").html(firstName);
			$("#confirm_last_name").html(lastName);
			$("#confirm_email_address").html(email);
			$("#confirm_phone").html(phone);
			$("#confirm_street").html(address);
			$("#confirm_town").html(town);
			$("#confirm_zip").html(zip);
			$("#confirm_country").html(country);
			$("#confirm_requirements").html(requirements);
			$("#confirm_cruise_start_date").html(cruiseStartDateText);
			$("#confirm_cruise_title").html(window.cruiseTitle);
			$("#confirm_cruise_adults").html(adults);
			$("#confirm_cruise_children").html(children);
			$("#confirm_cruise_total").html(cruises.formatPrice(window.rateTableTotalPrice));
			
			$.ajax({
				url: BYTAjax.ajaxurl,
				data: {
					'action':'book_cruise_ajax_request',
					'first_name' : firstName,
					'last_name' : lastName,
					'email' : email,
					'phone' : phone,
					'address' : address,
					'town' : town,
					'zip' : zip,
					'country' : country,
					'requirements' : requirements,
					'cruise_schedule_id' : cruiseScheduleId,
					'cruise_start_date' : cruiseStartDate,
					'adults' : adults,
					'children' : children,				
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
						var returned_id = data;
						$("div.error div p").html('');
						$("div.error").hide();
						
						var isReservationOnly = cruises.getCruiseIsReservationOnly(window.cruiseId);
						
						if (window.useWoocommerceForCheckout && window.wooCartPageUri.length > 0 && !isReservationOnly) {
							cruises.addTrProdToCart(returned_id);
						} else {
							cruises.hideCruiseBookingForm();
							cruises.showCruiseConfirmationForm();
						}
					}
					
					$('#wait_loading').hide();
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			}); 
		},
		addTrProdToCart : function (p_id) {
			$.get(window.siteUrl + '/?post_type=product&add-to-cart=' + p_id, function() {
				cruises.trRedirectToCart();
			});
		},	
		trRedirectToCart : function () {
			top.location.href = window.wooCartPageUri;
		},
		getCruiseScheduleId : function (cruiseId, cabinTypeId, date) {

			var scheduleId = 0;

			var dataObj = {
				'action':'cruise_available_schedule_id_request',
				'cruiseId' : cruiseId,
				'cabinTypeId' : cabinTypeId,
				'dateValue' : date,
				'nonce' : BYTAjax.nonce
			}		

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					scheduleId = data;
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});

			return scheduleId;
		},
		bindCruiseRatesTable : function () {
			
			$(".price_row").show();
			$(".command-bittons").show()

			$('table.breakdown thead').html('');
			$('table.breakdown tfoot').html('');
			$('table.breakdown tbody').html('');

			var adults = $('#booking_form_adults').val();
			if (!adults)
				adults = 1;
				
			var children = $('#booking_form_children').val();
			if (!children)
				children = 0;
				
			var colCount = 2;
			var headerRow = '<tr class="rates_head_row">';
			
			headerRow += '<th>' + window.dateLabel + '</th>';		
			
			if (window.cruiseIsPricePerPerson) {
				headerRow += '<th>' + window.adultCountLabel + '</th>';
				headerRow += '<th>' + window.pricePerAdultLabel + '</th>';
				headerRow += '<th>' + window.childCountLabel + '</th>';
				headerRow += '<th>' + window.pricePerChildLabel + '</th>';
				colCount = 6;
			}
			
			headerRow += '<th>' + window.pricePerDayLabel + '</th>';		
			
			headerRow += '</tr>';

			$('table.breakdown thead').append(headerRow);	
			
			var footerRow = '<tr>';
			footerRow += '<th colspan="' + (colCount - 1) + '">' + window.priceTotalLabel + '</th>';
			footerRow += '<td class="total_price">0</td>';
			footerRow += '</tr>';

			$('table.breakdown tfoot').append(footerRow);
			
			if (window.startDate) {
			
				$('#datepicker_loading').show();
			
				var startTime = window.startDate.valueOf();
				
				window.rateTableTotalPrice = 0;
				
				cruises.buildCruiseRateRow(startTime, adults, children);
			}
			
		},
		buildCruiseRateRow : function (startTime, adults, children) {

			var price = 0;
			
			var d = new Date(startTime);
			var day = d.getDate();
			var month = d.getMonth() + 1;
			var year = d.getFullYear();
			var dateValue = day + "-" + month + "-" + year; 

			var dataObj = {
				'action':'cruise_get_price_request',
				'cruiseId' : window.cruiseId,
				'cabinTypeId' : window.cabinTypeId,
				'dateValue' : dateValue,
				'nonce' : BYTAjax.nonce
			}		

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				dataType: 'json',
				success:function(prices) {
					var tableRow = '';
					// This outputs the result of the ajax request
					window.rateTableRowIndex++;
					var pricePerCruise = parseFloat(prices.price);
					var pricePerChild = 0;
					var totalPrice = 0;
					
					tableRow += '<tr>';
					tableRow += '<td>' + dateValue + '</td>';
					
					if (window.cruiseIsPricePerPerson) {
						pricePerChild = parseFloat(prices.child_price);
						tableRow += '<td>' + adults + '</td>';
						tableRow += '<td>' + cruises.formatPrice( pricePerCruise ) + '</td>';
						tableRow += '<td>' + children + '</td>';
						tableRow += '<td>' + cruises.formatPrice( pricePerChild ) + '</td>';
						totalPrice = (pricePerCruise * adults) + (pricePerChild * children);
					} else {
						totalPrice = pricePerCruise;
					}					
					
					$('.total_price').html(cruises.formatPrice(totalPrice));
					$("#confirm_total").html(cruises.formatPrice(totalPrice))
					
					tableRow += '<td>' + cruises.formatPrice(totalPrice) + '</td>';
					window.rateTableTotalPrice = totalPrice;
					
					tableRow += '</tr>';
					
					$('table.breakdown tbody').append(tableRow);
					
					$("table.responsive").trigger('updated');
					$('#datepicker_loading').hide();
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});

		},
		showCruiseInfo : function () {
			$('.three-fourth .lSSlideOuter').show();
			$('.three-fourth .inner-nav').show();
			$('.three-fourth .tab-content').show();
			$(".tab-content").hide();
			$(".tab-content:first").show();
			$(".inner-nav li:first").addClass("active");
		},
		showCruiseBookingForm : function () {
			$('#cruise-booking-form').show();
			$('.three-fourth .lSSlideOuter').hide();
			$('.three-fourth .inner-nav').hide();
			$('.three-fourth .tab-content').hide();			
		},
		hideCruiseBookingForm : function () {
			$('#cruise-booking-form').hide();
		},
		showCruiseConfirmationForm : function () {
			$('#cruise-confirmation-form').show();
		},
		bindCruiseControls : function (cruiseId, cabinTypeId) {

			if ($('#booking_form_adults option').size() == 0) {

				var	max_count = $('li#cabin_type_' + cabinTypeId + ' .cabin-information .max_count').val();
				var max_child_count = $('li#cabin_type_' + cabinTypeId + ' .cabin-information .max_child_count').val();
				
				for ( var i = 1; i <= max_count; i++ ) {
					$('<option ' + (i == 1 ? 'selected' : '') + '>').val(i).text(i).appendTo('#booking_form_adults');
				}
				
				$('#booking_form_adults').change(function (e) {
					cruises.bindCruiseRatesTable();
				});

				if (max_child_count > 0) {
					$('<option selected>').val(0).text(0).appendTo('#booking_form_children');
					for ( var i = 1; i <= max_child_count; i++ ) {
						$('<option>').val(i).text(i).appendTo('#booking_form_children');
					}
					$('#booking_form_children').change(function (e) {
						cruises.bindCruiseRatesTable();
					});
				} else {
					$('.booking_form_children').hide();
				}
				
				$('#booking_form_adults').uniform();
				$('#booking_form_children').uniform();
			}
			
		},
		bindCruiseDatePicker : function  () {	
		
			cruises.bindCruiseControls(window.cruiseId, window.cabinTypeId);

			if (typeof $('.cruise_schedule_datepicker') !== 'undefined') {

				$('.cruise_schedule_datepicker').datepicker({
					dateFormat: window.datepickerDateFormat,
					numberOfMonths: 1,
					minDate: 0,
					beforeShowDay: function(d) {

						var dUtc = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate());
						
						var selectedTime = null;
					
						if ($("#start_date").val()) {
							selectedTime = parseInt($("#start_date").val());
						}

						if (window.cruiseScheduleEntries) {
						
							var dateTextForCompare = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2);
							var dateTextForCompare2 = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2) + ' 00:00:00';
						
							if (dUtc == selectedTime)
								return [true, 'dp-highlight'];								
							if ($.inArray(dateTextForCompare, window.cruiseScheduleEntries) == -1 && $.inArray(dateTextForCompare2, window.cruiseScheduleEntries) == -1)
								return [false, 'ui-datepicker-unselectable ui-state-disabled'];
						}
						
						return [true, selectedTime && ((dUtc == selectedTime)) ? "dp-highlight" : ""];
					},
					onSelect: function(dateText, inst) {

						$(".price_row").show();
						$('.booking-commands').show();
						
						var selectedUtcTime = Date.UTC(inst.currentYear, inst.currentMonth, inst.currentDay);

						window.startDate = cruises.convertLocalDateToUTC(new Date(selectedUtcTime));
					
						$("#start_date_span").html(dateText);
						$("#start_date").val(selectedUtcTime);

						cruises.bindCruiseRatesTable();
					},
					onChangeMonthYear: function (year, month, inst) {
						window.currentMonth = month;
						window.currentYear = year;
						window.currentDay = 1;
						cruises.getCruiseScheduleEntries(window.cruiseId, window.cabinTypeId, window.currentDay, window.currentMonth, window.currentYear,cruises.refreshDatePicker);
					}
				});
			}

		},
		refreshDatePicker : function() {
		
			if (typeof $('.cruise_schedule_datepicker') !== 'undefined') {
				$('.cruise_schedule_datepicker').datepicker( "refresh" );
			}
			$('#wait_loading').hide();	
		},
		getCruiseScheduleEntries : function (cruiseId, cabinTypeId, day, month, year, callDelegate) {

			var dataObj = {
				'action':'cruise_schedule_dates_request',
				'cruiseId' : cruiseId,
				'cabinTypeId' : cabinTypeId,
				'month' : month,
				'year' : year,
				'day' : day,
				'nonce' : BYTAjax.nonce
			}		

			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				async: true,
				success:function(json) {
					// This outputs the result of the ajax request
					var scheduleDates = JSON.parse(json);
					var dateArray = new Array();
					var i = 0;
					for (i = 0; i < scheduleDates.length; ++i) {
						if (scheduleDates[i].cruise_date != null) {
							dateArray.push(scheduleDates[i].cruise_date);
						}
					}
					
					window.cruiseScheduleEntries = dateArray;
					
					if (typeof (callDelegate) !== 'undefined') {
						callDelegate();
					}
					$('#wait_loading').hide();
				},
				error: function(errorThrown){

				}
			});
		},
		convertLocalDateToUTC : function (date) { 
			return new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds()); 
		}
	}
	

})(jQuery);	