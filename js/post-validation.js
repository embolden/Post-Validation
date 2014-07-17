jQuery(document).ready(function($) {

	console.log( post_validation_to_validate );

	$('#post').submit(function( event ) {

		// Validate Title
		if( $.inArray( 'title', post_validation_to_validate ) !== -1 ) {
			var title = $('#title').val();

			if( title.length === 0 ) {
				event.preventDefault();
				post_validation_add_error_message( 'post-validation-title', 'Post title is a required field.' );
			}else {
				post_validation_hide_error_message( 'post-validation-title' );
			}
		}

		// Validate Content
		if( $.inArray( 'editor', post_validation_to_validate ) !== -1 ) {
			var content = $('#content').val();

			if( 0 === content.length ) {
				event.preventDefault();
				post_validation_add_error_message( 'post-validation-content', 'Post content is a required fields.' );
			}else {
				post_validation_hide_error_message( 'post-validation-content' );
			}
		}

		// Validate Categories
		if( $.inArray( 'category', post_validation_to_validate ) !== -1 ) {
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