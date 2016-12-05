(function($){
	$(document).ready(function () {
		tours.init();
	});
	
	var tours = {
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
			
			tours.initializeTourControls();
		
			$('.radio').bind('click.uniform',
				function (e) {
					if ($(this).find("span").hasClass('checked')) 
						$(this).find("input").attr('checked', true);
					else
						$(this).find("input").attr('checked', false);
				}
			);			
			
			$('.booking-commands').hide();
			tours.getTourScheduleEntries(window.tourId, window.currentDay, window.currentMonth, window.currentYear, tours.bindTourDatePicker);
			tours.bindResetButton();
			tours.bindNextButton();
			
			$('#tour-booking-form').validate({
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
				submitHandler: function() { tours.processTourBooking(); }
			});
						
			$('#cancel-tour-booking').on('click', function(event) {
				tours.hideTourBookingForm();
				tours.showTourInfo();
				event.preventDefault();
			});	
		},	
		formatPrice: function( price ) {
			if (window.currencySymbolShowAfter)
				return price + ' ' + window.currencySymbol;
			else
				return window.currencySymbol + ' ' + price;
		},
		bindNextButton : function() {
		
			$('.book-tour-proceed').unbind('click');
			$('.book-tour-proceed').on('click', function(event) {
				$('#wait_loading').show();
				$('.step_1_adults_holder').html($("#booking_form_adults").val());
				$('.step_1_children_holder').html($("#booking_form_children").val());
				$('.step_1_tour_date_holder').html($("#start_date_span").html());
				$('.step_1_total_holder').html(tours.formatPrice(window.rateTableTotalPrice));
			
				$('#tour_name').html(window.tourTitle);
				tours.showTourBookingForm();
				$('#wait_loading').hide();
				
				$('body,html').animate({
					scrollTop: 0
				}, 800);
			
				event.preventDefault();
			});
		},
		bindResetButton : function() {
			$('.book-tour-reset').unbind('click');
			$('.book-tour-reset').on('click', function(event) {
				event.preventDefault();
				
				$("#start_date_span").html('');
				$("#start_date").val('');
				$(".dates_row").hide();
				$(".price_row").hide();
				$('.booking-commands').hide();
				
				tours.getTourScheduleEntries(window.tourId, window.currentDay, window.currentMonth, window.currentYear, tours.refreshDatePicker);
				
			});
		},
		showTourInfo : function () {
			$('.three-fourth .lSSlideOuter').show();
			$('.three-fourth .inner-nav').show();
			$('.three-fourth .tab-content').show();
			$(".tab-content").hide();
			$(".tab-content:first").show();
			$(".inner-nav li:first").addClass("active");
		},
		showTourBookingForm : function () {
			$('#tour-booking-form').show();
			$('.three-fourth .lSSlideOuter').hide();
			$('.three-fourth .inner-nav').hide();
			$('.three-fourth .tab-content').hide();
		},		
		hideTourBookingForm : function () {
			$('#tour-booking-form').hide();
		},		
		showTourConfirmationForm : function () {
			$('#tour-confirmation-form').show();
		},		
		getMaxPeople : function (tourScheduleId) {
		
			var tourStartDate = tours.convertLocalDateToUTC(new Date(parseInt($("#start_date").val())));
			tourStartDate = tourStartDate.getFullYear() + "-" + (tourStartDate.getMonth() + 1) + "-" + tourStartDate.getDate(); 
		
			var max_people = 0;
			var dataObj = {
				'action':'tour_max_people_ajax_request',
				'tourScheduleId' : tourScheduleId,
				'tourId' : window.tourId,
				'dateValue' : tourStartDate,
				'nonce' : BYTAjax.nonce
			}		
			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				async: false,
				success:function(data) {
					// This outputs the result of the ajax request
					max_people = data;
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			}); 
			
			return max_people;
		},
		processTourBooking : function () {
		
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
			var tourStartDate = tours.convertLocalDateToUTC(new Date(parseInt($("#start_date").val())));
			tourStartDate = tourStartDate.getFullYear() + "-" + (tourStartDate.getMonth() + 1) + "-" + tourStartDate.getDate(); 
			var tourScheduleId = tours.getTourScheduleId(window.tourId, tourStartDate);
			var tourStartDateText = $("#start_date_span").html();
			var adults = $("#booking_form_adults").val();
			var children = $("#booking_form_children").val();
			
			var cValS = $('#c_val_s_tour').val();
			var cVal1 = $('#c_val_1_tour').val();
			var cVal2 = $('#c_val_2_tour').val();
			
			$("#confirm_first_name").html(firstName);
			$("#confirm_last_name").html(lastName);
			$("#confirm_email_address").html(email);
			$("#confirm_phone").html(phone);
			$("#confirm_street").html(address);
			$("#confirm_town").html(town);
			$("#confirm_zip").html(zip);
			$("#confirm_country").html(country);
			$("#confirm_requirements").html(requirements);
			$("#confirm_tour_start_date").html(tourStartDateText);
			$("#confirm_tour_title").html(window.tourTitle);
			$("#confirm_tour_adults").html(adults);
			$("#confirm_tour_children").html(children);
			$("#confirm_tour_total").html(tours.formatPrice(window.rateTableTotalPrice));
			
			$.ajax({
				url: BYTAjax.ajaxurl,
				data: {
					'action':'book_tour_ajax_request',
					'first_name' : firstName,
					'last_name' : lastName,
					'email' : email,
					'phone' : phone,
					'address' : address,
					'town' : town,
					'zip' : zip,
					'country' : country,
					'requirements' : requirements,
					'tour_schedule_id' : tourScheduleId,
					'tour_start_date' : tourStartDate,
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
						
						var isReservationOnly = tours.getTourIsReservationOnly(window.tourId);
						
						if (window.useWoocommerceForCheckout && window.wooCartPageUri.length > 0 && !isReservationOnly) {
							tours.addTrProdToCart(returned_id);
						} else {
							tours.hideTourBookingForm();
							tours.showTourConfirmationForm();
						}
					}
					$('#wait_loading').hide();
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			}); 
		},
		getTourIsReservationOnly : function (tourId) {
			var isReservationOnly = 0;
			var dataObj = {
				'action':'tour_is_reservation_only_request',
				'tour_id' : tourId,
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
		addTrProdToCart : function (p_id) {
			$.get(window.siteUrl + '/?post_type=product&add-to-cart=' + p_id, function() {
				tours.trRedirectToCart();
			});
		},
		trRedirectToCart : function () {
			top.location.href = window.wooCartPageUri;
		},		
		bindTourDatePicker : function  () {	
			if (typeof $('.tour_schedule_datepicker') !== 'undefined') {
				$('.tour_schedule_datepicker').datepicker({
					dateFormat: window.datepickerDateFormat,
					numberOfMonths: 1,
					minDate: 0,
					beforeShowDay: function(d) {
						var dUtc = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate());
						
						var selectedTime = null;
					
						if ($("#start_date").val()) {
							selectedTime = parseInt($("#start_date").val());
						}
					
						if (window.tourScheduleEntries) {
							var dateTextForCompare = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2);
							var dateTextForCompare2 = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + ("0" + d.getDate()).slice(-2) + ' 00:00:00';
						
							if (dUtc == selectedTime)
								return [true, 'dp-highlight'];								
							if ($.inArray(dateTextForCompare, window.tourScheduleEntries) == -1 && $.inArray(dateTextForCompare2, window.tourScheduleEntries) == -1)
								return [false, 'ui-datepicker-unselectable ui-state-disabled'];
						}
						
						return [true, selectedTime && ((dUtc == selectedTime)) ? "dp-highlight" : ""];
					},
					onSelect: function(dateText, inst) {

						$(".price_row").show();
						$('.dates_row').show();
						$('.booking-commands').show();
					
						var selectedUtcTime = Date.UTC(inst.currentYear, inst.currentMonth, inst.currentDay);

						window.startDate = tours.convertLocalDateToUTC(new Date(selectedUtcTime));
					
						$("#start_date_span").html(dateText);
						$("#start_date").val(selectedUtcTime);

						tours.bindTourDropDowns();
						tours.bindTourRatesTable();
					},
					onChangeMonthYear: function (year, month, inst) {
						window.currentMonth = month;
						window.currentYear = year;
						window.currentDay = 1;
						
						tours.getTourScheduleEntries(window.tourId, window.currentDay, window.currentMonth, window.currentYear,tours.refreshDatePicker);
					}
				});
			}
		
		},
		refreshDatePicker : function() {
		
			if (typeof $('.tour_schedule_datepicker') !== 'undefined') {
				$('.tour_schedule_datepicker').datepicker( "refresh" );
			}
			$('#wait_loading').hide();	
		},
		getTourScheduleId : function (tourId, date) {
			var scheduleId = 0;
			var dataObj = {
				'action':'tour_available_schedule_id_request',
				'tourId' : tourId,
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
		getTourScheduleEntries : function (tourId, day, month, year, callDelegate) {
			var dateArray = new Array();
			var dataObj = {
				'action':'tour_schedule_dates_request',
				'tourId' : tourId,
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
					var i = 0;
					for (i = 0; i < scheduleDates.length; ++i) {
						if (scheduleDates[i].tour_date != null) {
							dateArray.push(scheduleDates[i].tour_date);
						}
					}
					
					window.tourScheduleEntries = dateArray;
					
					if (typeof(callDelegate) !== 'undefined') {
						callDelegate();
					}
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});
		},
		bindTourRatesTable : function () {
			
			$(".price_row").show();
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
			
			if (!window.tourIsPricePerGroup) {
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
			
				// $('#datepicker_loading').show();
			
				var startTime = window.startDate.valueOf();
				
				window.rateTableTotalPrice = 0;
				tours.buildTourRateRow(startTime, adults, children);
			}
			
		},		
		buildTourRateRow : function (startTime, adults, children) {
		
			var price = 0;
			
			var d = new Date(startTime);
			var day = d.getDate();
			var month = d.getMonth() + 1;
			var year = d.getFullYear();
			var dateValue = day + "-" + month + "-" + year; 
			var dataObj = {
				'action':'tour_get_price_request',
				'tourId' : window.tourId,
				'dateValue' : dateValue,
				'nonce' : BYTAjax.nonce
			}		
			$.ajax({
				url: BYTAjax.ajaxurl,
				data: dataObj,
				dataType: 'json',
				success:function(prices) {
					
					console.log(dateValue);
				
					var tableRow = '';
					// This outputs the result of the ajax request
					window.rateTableRowIndex++;
					var pricePerTour = parseFloat(prices.price);
					var pricePerChild = 0;
					var totalPrice = 0;
					
					tableRow += '<tr>';
					tableRow += '<td>' + dateValue + '</td>';
					
					if (!window.tourIsPricePerGroup) {
						pricePerChild = parseFloat(prices.child_price);
						tableRow += '<td>' + adults + '</td>';
						tableRow += '<td>' + tours.formatPrice(pricePerTour) + '</td>';
						tableRow += '<td>' + children + '</td>';
						tableRow += '<td>' + tours.formatPrice(pricePerChild) + '</td>';
						totalPrice = (pricePerTour * adults) + (pricePerChild * children);
					} else {
						totalPrice = pricePerTour;
					}					
					
					$('.total_price').html(tours.formatPrice(totalPrice));
					$("#confirm_total").html(tours.formatPrice(totalPrice))
					
					tableRow += '<td>' + tours.formatPrice(totalPrice) + '</td>';
					window.rateTableTotalPrice = totalPrice;
					
					tableRow += '</tr>';
					
					$('table.breakdown tbody').append(tableRow);
					
					$('#datepicker_loading').hide();
					 $("table.responsive").trigger('updated');
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});
		
		},
		bindTourDropDowns : function() {

			var tourStartDate = tours.convertLocalDateToUTC(new Date(parseInt($("#start_date").val())));
			tourStartDate = tourStartDate.getFullYear() + "-" + (tourStartDate.getMonth() + 1) + "-" + tourStartDate.getDate(); 
			var scheduleId = tours.getTourScheduleId(window.tourId, tourStartDate);
			var maxCount = tours.getMaxPeople(scheduleId);
			maxCount = maxCount > 0 ? maxCount : 1;
			var maxChildCount = maxCount;
			$('#booking_form_adults').unbind();			
			$('#booking_form_adults').find('option').remove();
			for ( var i = 1; i <= maxCount; i++ ) {
				$('<option ' + (i == 1 ? 'selected' : '') + '>').val(i).text(i).appendTo('#booking_form_adults');
			}
			$.uniform.update("#booking_form_adults");
			$('#booking_form_adults').change(function (e) {
				tours.bindTourRatesTable();
			});
			if (maxChildCount > 0) {
				$('#booking_form_children').unbind();
				$('#booking_form_children').find('option').remove();
				$('<option selected>').val(0).text(0).appendTo('#booking_form_children');
				for ( var i = 1; i <= maxChildCount; i++ ) {
					$('<option>').val(i).text(i).appendTo('#booking_form_children');
				}
				$.uniform.update("#booking_form_children");
				
				$('#booking_form_children').change(function (e) {
					tours.bindTourRatesTable();
				});
			} else {
				$('.booking_form_children').hide();
			}
		},
		initializeTourControls : function () {
		
			$('#booking_form_adults').uniform();
			$('#booking_form_children').uniform();
			
		},
		convertLocalDateToUTC : function (date) { 
			return new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds()); 
		}
	}
	
})(jQuery);