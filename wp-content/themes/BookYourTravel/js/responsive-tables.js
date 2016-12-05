(function($){

    "use strict";
	
	var switched = true;
	
	$(window).load(function() {
		responsiveTables.updateTables();
	});
	
	$(window).on("redraw",function(){
		switched=false;
		responsiveTables.updateTables();
	}); // An event to listen for
	
	$(window).on("resize", function() {
		responsiveTables.updateTables();
	});
	
	$(document).ready(function () {	
		responsiveTables.init();
	});
	
	$('table.responsive').on('updated', function(){
		switched = false;
		responsiveTables.updateTables();
	});
	
	var responsiveTables = {
		setSwitched : function (s) {
			switched = s;
		},
		init: function() {
			responsiveTables.updateTables();	
		},
		updateTables : function() {
			if (($(window).width() < 890) && !switched ){
				switched = true;
				$("table.responsive").each(function(i, element) {
					responsiveTables.splitTable($(element));
				});
				console.log('updated');
				return true;
			}
			else if (switched && ($(window).width() > 890)) {
				switched = false;
				$("table.responsive").each(function(i, element) {
					responsiveTables.unsplitTable($(element));
				});
			}
		},
		splitTable: function(original)
		{
			original.wrap("<div class='table-wrapper' />");
			
			var copy = original.clone();
			copy.find("td:not(:first-child), th:not(:first-child)").css("display", "none");
			copy.removeClass("responsive");
			
			original.closest(".table-wrapper").append(copy);
			copy.wrap("<div class='pinned' />");
			original.wrap("<div class='scrollable' />");

			responsiveTables.setCellHeights(original, copy);
		},
		unsplitTable: function (original) {
			original.closest(".table-wrapper").find(".pinned").remove();
			original.unwrap();
			original.unwrap();
		},
		setCellHeights: function(original, copy) {
			var tr = original.find('tr'),
			tr_copy = copy.find('tr'),
			heights = [];

			tr.each(function (index) {
				var self = $(this),
				tx = self.find('th, td');

				tx.each(function () {
					var height = $(this).outerHeight(true);
					heights[index] = heights[index] || 0;
					if (height > heights[index]) heights[index] = height;
				});

			});

			tr_copy.each(function (index) {
				$(this).height(heights[index]);
			});
		}
	}
})(jQuery);