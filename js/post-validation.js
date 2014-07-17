jQuery(document).ready(function($) {

	console.log( post_validation_to_validate );

	$('#post').submit(function( event ) {

		// Validate Title
		if( -1 !== $.inArray( 'title', post_validation_to_validate ) ) {
			var title = $('#title').val();

			if( 0 === title.length ) {
				event.preventDefault();
				post_validation_add_error_message( 'post-validation-title', 'Post title is a required field.' );
			}else {
				post_validation_hide_error_message( 'post-validation-title' );
			}
		}

		// Validate Content
		if( -1 !== $.inArray( 'editor', post_validation_to_validate ) ) {
			var content = $('#content').val();

			if( 0 === content.length ) {
				event.preventDefault();
				post_validation_add_error_message( 'post-validation-content', 'Post content is a required fields.' );
			}else {
				post_validation_hide_error_message( 'post-validation-content' );
			}
		}

		// Validate Categories
		if( -1 !== $.inArray( 'category', post_validation_to_validate ) ) {
			var categories = $('#categorychecklist');
			var categories_checked = false;

			$(categories).find('[name="post_category[]"]').each(function(index) {
				if( $(this).prop('checked') ) {
					categories_checked = true;
					return false; // break out of each
				}
			});

			if( false === categories_checked ) {
				event.preventDefault();
				post_validation_add_error_message( 'post-validation-category', 'At least one category is a required.' );
			}else {
				post_validation_hide_error_message( 'post-validation-category' );
			}
		}

		// Validate Featured Image
		if( -1 !== $.inArray( 'thumbnail', post_validation_to_validate ) ) {
			var thumbnail = $('#set-post-thumbnail').find('img');
			console.log( thumbnail );

			if( 0 === thumbnail.length ) {
				event.preventDefault();
				post_validation_add_error_message( 'post-validation-thumbnail', 'Featured image is required.' );
			}else {
				post_validation_hide_error_message( 'post-validation-thumbnail' );
			}
		}

		// Validate Excerpt
		if( -1 !== $.inArray( 'excerpt', post_validation_to_validate ) ) {
			var excerpt = $('#excerpt').val();

			if( 0 === excerpt.length ) {
				event.preventDefault();
				post_validation_add_error_message( 'post-validation-excerpt', 'Excerpt is a required fields.' );
			}else {
				post_validation_hide_error_message( 'post-validation-excerpt' );
			}
		}

		// Validate Tags
		if( -1 !== $.inArray( 'post_tag', post_validation_to_validate ) ) {
			var tags = $('#post_tag').find('.tagchecklist span');

			if( 0 === tags.length ) {
				event.preventDefault();
				post_validation_add_error_message( 'post-validation-tags', 'At least one tag is a required.' );
			}else {
				post_validation_hide_error_message( 'post-validation-tags' );
			}
		}

	});

});

/**
 * @todo: Document me!
 */
function post_validation_add_error_message( selector, message ) {
	if( 0 === jQuery('#' + selector).length ) {
		jQuery('#post').before('<div id="' + selector + '" class="error"><p>' + message + '</p></div>');
	}
}

/**
 * @todo: Document me!
 */
function post_validation_hide_error_message( selector ) {
	if( jQuery('#' + selector).length > 0 ) {
		jQuery('#' + selector).hide();
	}
}