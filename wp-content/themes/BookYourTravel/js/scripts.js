(function($){

	$(document).ready(function () {
		byt_scripts.init();
	});
	
	$(window).load(function() {
		byt_scripts.load();
	});
	
	// Initiate selectnav function
	selectnav();
	
	var byt_scripts = {

		init: function () {
		
			$("input[type=radio]").uniform();
			$("input[type=checkbox]").uniform();
			$("select").not(".dynamic_control").uniform();
			
			//SCROLL TO TOP BUTTON
			$('.scroll-to-top').click(function () {
				$('body,html').animate({
					scrollTop: 0
				}, 800);
				return false;
			});

			//HEADER RIBBON NAVIGATION
			$('.ribbon .profile-nav li').hide();
			$('.ribbon .profile-nav .active').show();
			//$('.ribbon .lang_sel_click > ul > li').show();
			//$('.ribbon .lang_sel_click > ul > li').addClass('active');
			//$('.ribbon .lang_sel_click ul').css('visibility', 'visible');
			
			$(".ribbon li a").click(byt_scripts.handleRibbonClick);
			
			//LIGHTBOX
			$("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square'});
			
			//TABS
			$(".tab-content").hide();
			$(".tab-content.initial").show();
			var activeIndex = $('.inner-nav li.active').index();
			if (activeIndex == -1)
				$(".inner-nav li:first").addClass("active");

			$(".inner-nav a").click(function(e){
				$(".inner-nav li").removeClass("active");
				$(this).parent().addClass("active");
				var currentTab = $(this).attr("href");
				$(".tab-content").hide();
				$(currentTab).show();
				if (currentTab == "#location") {
					window.InitializeMap();
					$mapIframe = $('.gmap iframe');
					if (typeof ($mapIframe) != 'undefined' && $mapIframe.length > 0) {
						$mapIframe.attr( 'src', $mapIframe.attr( 'src' ));
					}					
				}
				e.preventDefault();
			});
			
			var hash = window.location.hash;
			if (hash.length > 0) {
				var hashbang = hash.replace('#', '');
				if (hashbang.length > 0) {
					var anchor = $('.inner-nav li a[href="#' + hashbang + '"]');
					if (anchor.length > 0) {
						var li = anchor.parent();
						if (li.length > 0) {
							$(".inner-nav li").removeClass('active');		
							li.addClass('active');	
							$(".tab-content").hide();
							$(".tab-content#" + hashbang).show();
						}
					}
				}
			}	
			
			//CSS
			$('.top-right-nav li:last-child,.social li:last-child,.twins .f-item:last-child,.ribbon li:last-child,.room-types li:last-child,.three-col li:nth-child(3n),.reviews li:last-child,.three-fourth .deals .one-fourth:nth-child(3n),.full .one-fourth:nth-of-type(4n),.locations .one-fourth:nth-child(3n),.pager span:last-child,.get_inspired li:nth-child(5n)').addClass('last');
			$('.bottom nav li:first-child,.pager span:first-child').addClass('first');
			
			//ROOM TYPES MORE BUTTON
			$(".more-information").slideUp();
			$(".more-info").click(function(e) {
				var moreinformation = $(this).closest("li").find(".more-information");
				var txt = moreinformation.is(':visible') ? window.moreInfoText : window.lessInfoText;
				$(this).text(txt);
				moreinformation.stop(true, true).slideToggle("slow");
				e.preventDefault();
			});
			
			$(".f-item .radio").click(function(e) {
				$(".f-item").removeClass("active");
				$(this).parent().addClass("active");
			});	
				
			$('.grid-view').click(function(e) {
				var currentClass = $(".three-fourth article").attr("class");
				if (typeof currentClass != 'undefined' && currentClass.length > 0) {
					currentClass = currentClass.replace('last', '');
					currentClass = currentClass.replace('full-width', 'one-fourth');
					$(".three-fourth article").attr("class", currentClass);
					$(".three-fourth article:nth-child(3n)").addClass("last");
					$(".view-type li").removeClass("active");
					$(this).addClass("active");
					
					byt_scripts.resizeFluidItems();
				}
				e.preventDefault();
			});
			
			$('.list-view').click(function(e) {
				var currentClass = $(".three-fourth article").attr("class");
				if (typeof currentClass != 'undefined' && currentClass.length > 0) {
					currentClass = currentClass.replace('last', '');
					currentClass = currentClass.replace('one-fourth', 'full-width');
					$(".three-fourth article").attr("class", currentClass);
					$(".view-type li").removeClass("active");
					$(this).addClass("active");
				}
				e.preventDefault();
			});
			
			// LIST AND GRID VIEW TOGGLE
			if (window.defaultResultsView === 0)
				$('.view-type li.grid-view').trigger('click');
			else
				$('.view-type li.list-view').trigger('click');

			
			// ACCOMMODATION PAGE GALLERY
			$('.gallery img:first-child').css('opacity',1);
			
			var i=0,p=1,q=function(){return document.querySelectorAll(".gallery>img")};

			function s(e){
			for(c=0;c<q().length;c++){q()[c].style.opacity="0";q()[e].style.opacity="1"}
			}

			setInterval(function(){
			if(p){i=(i>q().length-2)?0:i+1;s(i)}
			},5000);
		
		},
		load : function () {		
			byt_scripts.resizeFluidItems();
		},
		resizeFluidItems: function() {
			byt_scripts.resizeFluidItem(".one-half.accommodation_item,.one-third.accommodation_item,.one-fourth.accommodation_item,.one-fifth.accommodation_item");
			byt_scripts.resizeFluidItem(".one-half.location_item,.one-third.location_item,.one-fourth.location_item,.one-fifth.location_item");
			byt_scripts.resizeFluidItem(".one-half.tour_item,.one-third.tour_item,.one-fourth.tour_item,.one-fifth.tour_item");
			byt_scripts.resizeFluidItem(".one-half.car_rental_item,.one-third.car_rental_item,.one-fourth.car_rental_item,.one-fifth.car_rental_item");
			byt_scripts.resizeFluidItem(".one-half.cruise_item,.one-third.cruise_item,.one-fourth.cruise_item,.one-fifth.cruise_item");
		},
		handleRibbonClick : function (e) {
			if ($(this).hasClass('fn')) {
				return true; // allow clicking of links like logout.
			} 
			else if (!$(this).hasClass('lang_sel_sel,.lang_sel_other') && $(this).closest("#lang_sel,#lang_sel_list").length > 0 ) {
				return true; // allow clicking of language links.
			}
			else {
				$(".ribbon .profile-nav li").hide();
				
				if ($(this).parent().parent().hasClass('open'))
					$(this).parent().parent().removeClass('open');
				else {
					$(".ribbon .profile-nav").removeClass('open');
					$(this).parent().parent().addClass('open');
				}
				
				$(this).parent().siblings().each(function() {
					$(this).removeClass('active');
				});
				$(this).parent().attr('class', 'active'); 
				
				$('.ribbon .profile-nav li.active').show();
				
				$('.ribbon .profile-nav.open li').show();
				
				return false;
			}
		}, 
		resizeFluidItem : function (filters) {
		
			var filterArray = filters.split(',');
			
			var arrayLength = filterArray.length;
			for (var i = 0; i < arrayLength; i++) {
				var filter = filterArray[i];
				var maxHeight = 0;            
				$(filter + " .details").each(function(){
					if ($(this).height() > maxHeight) { 
						maxHeight = $(this).height(); 
					}
				});
				$(filter + ":not(.fluid-item) .details").height(maxHeight);   
			}
		}
	}
	
})(jQuery);	

//first, checks if it isn't implemented yet
if (!String.prototype.format) {
  String.prototype.format = function() {
	var args = arguments;
	return this.replace(/{(\d+)}/g, function(match, number) { 
	  return typeof args[number] != 'undefined'
		? args[number]
		: match
	  ;
	});
  };
}

window.RedrawMap = (function() {
	google.maps.event.trigger(MapInstance,'resize')
});

window.InitializeMap = (function() {

	if (typeof window.entityLatitude != 'undefined' &&
		typeof window.entityLongitude != 'undefined' &&
		window.entityLatitude.length > 0 &&
		window.entityLongitude.length > 0) {
		var latLong = new google.maps.LatLng(window.entityLatitude, window.entityLongitude);
		var myMapOptions = {
			 zoom: 15
			,center: latLong
			,mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var theMap = new google.maps.Map(document.getElementById("map_canvas"), myMapOptions);
		google.maps.event.trigger(theMap, 'resize');
		
		var marker = new google.maps.Marker({
			map: theMap,
			draggable: true,
			position: new google.maps.LatLng(window.entityLatitude, window.entityLongitude),
			visible: true
		});
		var boxText = document.createElement("div");
		boxText.innerHTML = window.entityInfoboxText;
		var myOptions = {
			 content: boxText
			,disableAutoPan: false
			,maxWidth: 0
			,pixelOffset: new google.maps.Size(-140, 0)
			,zIndex: null
			,closeBoxURL: ""
			,infoBoxClearance: new google.maps.Size(1, 1)
			,isHidden: false
			,pane: "floatPane"
			,enableEventPropagation: false
		};
		google.maps.event.addListener(marker, "click", function (e) {
			ib.open(theMap, this);
		});
		var ib = new InfoBox(myOptions);
		ib.open(theMap, marker);
	}
});

function toggleLightbox(id) {
	$ = jQuery.noConflict();
	if (id != 'login_lightbox' && $('#login_lightbox').is(":visible"))
		$('#login_lightbox').hide();
	else if (id != 'register_lightbox' && $('#register_lightbox').is(":visible"))
		$('#register_lightbox').hide();
	$('#' + id).toggle(500);
}	