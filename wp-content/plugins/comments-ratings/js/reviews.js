(function($){
	$(document ).ready(function(){
		var $rates = $('#add_comment_rating_wrap'),
			path = $rates.data('assets_path' ),
			default_rating = 4;

		if ( typeof $('#add_post_rating').attr('data-pixrating') !== 'undefined' ) {
			default_rating = $('#add_post_rating').attr('data-pixrating');
		}

		$rates.raty({
			half: false,
			target : '#add_post_rating',
			hints: pixreviews.hints,
			path: path,
			targetKeep : true,
			//targetType : 'score',
			targetType : 'hint',
			//precision  : true,
			score: default_rating,
			scoreName: 'pixrating',
			click: function(rating, evt) {
				$('#add_post_rating' ).val( '' + rating );
				$('#add_post_rating option[value="' + rating + '"]' ).attr( 'selected', 'selected' );
			},
			starType : 'i'
		});

		$('.review_rate' ).raty({
			readOnly: true,
			target : this,
			half: false,
			starType : 'i',
			score: function() {
				return $(this).attr('data-pixrating');
			},
			scoreName: 'pixrating'
		});
	});
})(jQuery);