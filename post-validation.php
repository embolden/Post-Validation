<?php
/**
 * Plugin Name: Post Validation
 * Plugin URI:
 * Description: Add validation to posts
 * Author: Integrity
 * Author URI: http://www.integritystl.com
 * Version: 1.0
 */

class Post_Validation {
	/**
	 * @todo: Document Me!
	 */
	function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'add_options' ) );
	}

	/**
	 * @todo: Document Me!
	 */
	function enqueue_script( $hook ) {
		if( $hook != 'post.php' && $hook != 'edit.php' && $hook != 'post-new.php' ) {
			return;
		}

		wp_enqueue_script( 'post-validation', plugins_url( '/js/post-validation.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'post-validation', 'post_validation_to_validate', get_option( 'post-validation-to-validate' ) );
	}

	/**
	 * @todo: Document me!
	 * @link: http://kovshenin.com/2012/the-wordpress-settings-api/
	 */
	function add_admin_menu() {
		add_options_page(
			'Post Validation Settings',           // Browser title
			'Post Validation',                    // Page title
			'manage_options',                     // User permissions
			'post-validation',                    // Menu slug
			array( $this, 'output_options_page' ) // Callback
		);
	}

	/**
	 * @todo: Document me!
	 * @link: http://kovshenin.com/2012/the-wordpress-settings-api/
	 */
	function output_options_page() {
		?>
		<div class="wrap">
			<h2>Post Validation Settings</h2>
			<form action="options.php" method="POST">
				<?php settings_fields( 'post-validation-options' ); ?>
				<?php do_settings_sections( 'post-validation' ); ?>
				<?php submit_button(); ?>
			</form>
		</div><!-- .wrap -->
		<?php
	}

	/**
	 * @todo: Document me!
	 * @link: http://kovshenin.com/2012/the-wordpress-settings-api/
	 */
	function add_options() {
		register_setting(
			'post-validation-options',    // Options Group
			'post-validation-to-validate' // Option Name
		);

		add_settings_section(
			'post-validation-section',                             // Section ID
			'Fields to Validate',                                  // Section Title
			array( $this, 'fields_to_validate_section_callback' ), // Callback
			'post-validation'                                      // Menu Slug
		);

		add_settings_field(
			'post-validation-to-validate',                        // Field ID
			'Validate',                                           // Field title
			array( $this, 'fields_to_validate_field_callback' ),  // Callback
			'post-validation',                                    // Menu slug
			'post-validation-section'                             // Section ID
		);
	}

	/**
	 * @todo: Document me!
	 * @link: http://kovshenin.com/2012/the-wordpress-settings-api/
	 */
	function fields_to_validate_section_callback() {
		echo 'Select the fields that should be validated.';
	}

	/**
	 * @todo: Document me!
	 * @link: http://kovshenin.com/2012/the-wordpress-settings-api/
	 * @link: http://bit.ly/1r71iqP
	 */
	function fields_to_validate_field_callback() {
		$post_type_supports = get_all_post_type_supports( 'post' );
		// var_dump( $post_type_supports );

		$post_type_taxonomies = get_object_taxonomies( 'post', 'names' );
		// var_dump( $post_type_taxonomies );

		$supports = array_merge( array_keys( $post_type_supports ), $post_type_taxonomies );
		// var_dump( $supports );

		$options = get_option( 'post-validation-to-validate' );
		// var_dump( $options );

		$can_validate = array(
			'title',
			'editor',
			// 'thumbnail',
			// 'excerpt',
			'category',
			// 'post_tag',
		);

		echo '<ul>';

		foreach( $supports as $support ) {
			if( in_array( $support, $can_validate ) ) {
				echo '<li><label for="post-validation-' . $support . '"><input name="post-validation-to-validate[]" id="post-validation-' . $support . '" type="checkbox" value="' . $support . '" ' . (in_array( $support, (array) $options ) ? 'checked="checked"' : '' ) . '>' . ucwords( str_replace('_', ' ', $support ) ) . '</label></li>';
			}
		}

		echo '</ul>';
	}
}
new Post_Validation();