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
		wp_localize_script( 'post-validation', 'post_validation_to_validate', get_option( 'post-validation-to-validate-' . get_post_type() ) );
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

		$post_types = array_keys( get_post_types( array( 'public' => true ), 'names', 'and' ) );
		// var_dump( $post_types );

		$will_validate = array(
			'post',
			'page',
		);

		foreach( $post_types as $post_type ) {
			if( in_array( $post_type, $will_validate ) ) {
				register_setting(
					'post-validation-options',                   // Options Group
					'post-validation-to-validate-' . $post_type // Option Name
				);

				add_settings_field(
					'post-validation-to-validate-' . $post_type,                  // Field ID
					'Validate ' . ucwords( str_replace( '_', ' ', $post_type ) ), // Field title
					array( $this, 'fields_to_validate_field_callback' ),          // Callback
					'post-validation',                                            // Menu slug
					'post-validation-section',                                    // Section ID
					$post_type                                                    // Callback args
				);
			}
		}
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
	function fields_to_validate_field_callback( $post_type ) {
		// var_dump( $post_type );

		$post_type_supports = get_all_post_type_supports( $post_type );
		// var_dump( $post_type_supports );

		$post_type_taxonomies = get_object_taxonomies( $post_type, 'names' );
		// var_dump( $post_type_taxonomies );

		$supports = array_merge( array_keys( $post_type_supports ), $post_type_taxonomies );
		// var_dump( $supports );

		$options = get_option( 'post-validation-to-validate-' . $post_type );
		// var_dump( $options );

		$can_validate = array(
			'title',
			'editor',
			// 'thumbnail',
			// 'excerpt',
			'category',
			// 'post_tag',
		);

		ob_start(); ?>

		<ul>
		<?php foreach( $supports as $support ) : ?>
			<?php if( in_array( $support, $can_validate ) ) : ?>
				<li>
					<label for="post-validation-<?php echo $post_type . '-' . $support; ?>">
						<input name="post-validation-to-validate-<?php echo $post_type; ?>[]" id="post-validation-<?php echo $post_type . '-' . $support; ?>" type="checkbox" value="<?php echo $support; ?>" <?php echo ( in_array( $support, (array) $options ) ? 'checked="checked"' : '' ); ?>><?php echo ucwords( str_replace('_', ' ', $support ) ); ?>
					</label>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>

		<?php
		$output = ob_get_clean();
		echo $output;
	}
}
new Post_Validation();