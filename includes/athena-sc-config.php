<?php

/**
 * Basic class for individual plugin options.
 **/
if ( ! class_exists( 'ATHENA_SC_Plugin_Option' ) ) {
	class ATHENA_SC_Plugin_Option {
		public
			$option_name,
			$default         = null,  // default value for the option
			$format_callback = 'sanitize_text_field',  // function that formats the option value
			$field_title     = null,
			$field_desc      = null,
			$field_type      = 'text',
			$field_options   = null,
			$field_options_section = 'athena_sc_settings_general';

		function __construct( $option_name, $args=array() ) {
			$this->option_name     = $option_name;
			$this->default         = isset( $args['default'] ) ? $args['default'] : $this->default;
			$this->format_callback = isset( $args['format_callback'] ) ? $args['format_callback'] : $this->format_callback;
			$this->field_title     = isset( $args['field_title'] ) ? $args['field_title'] : $this->field_title;
			$this->field_desc      = isset( $args['field_desc'] ) ? $args['field_desc'] : $this->field_desc;
			$this->field_type      = isset( $args['field_type'] ) ? $args['field_type'] : $this->field_type;
			$this->field_options   = isset( $args['field_options'] ) ? $args['field_options'] : $this->field_options;
			$this->field_options_section = isset( $args['field_options_section'] ) ? $args['field_options_section'] : $this->field_options_section;
		}

		/**
		 * Returns the formatted value, using the function name passed to
		 * $this->format_callback.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @param mixed $value Option value to apply formatting to
		 * @return mixed Option value with formatting applied
		 **/
		function format( $value ) {
			return call_user_func( $this->format_callback, $value );
		}
	}
}


/**
 * Handles options/settings and displaying them in the WordPress admin.
 **/
if ( ! class_exists( 'ATHENA_SC_Config' ) ) {
	class ATHENA_SC_Config {
		public static
			$option_prefix = 'athena_sc_',
			$settings_page_prefix = 'athena_sc_settings';

		/**
		 * Returns a full list of plugin option objects.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @return array array of ATHENA_SC_Plugin_Option objects
		 **/
		public static function get_options() {
			return array(
				new ATHENA_SC_Plugin_Option( self::$option_prefix . 'enable_tinymce_formatting', array(
					'default'         => false,
					'format_callback' => 'wp_validate_boolean',
					'field_title'     => 'Enable TinyMCE tools and formatting',
					'field_desc'      => "When checked, custom Athena Framework formats will be made available in the TinyMCE editor.
										  <br>Additionally, images added to posts/pages via the Media Library will include
										  Athena-friendly alignment and responsiveness classes (does not affect images in existing
										  posts/pages).",
					'field_type'      => 'checkbox'
				) ),
				new ATHENA_SC_Plugin_Option( self::$option_prefix . 'enable_optin_classes', array(
					'default'         => true,
					'format_callback' => 'wp_validate_boolean',
					'field_title'     => 'Enable automatic insertion of opt-in content classes',
					'field_desc'      => "When checked, CSS classes for opt-in content styles will be added to elements automatically
										  in the TinyMCE editor.  Currently, this includes <code>.table</code> for tables and
										  <code>.blockquote</code> for blockquotes.",
					'field_type'      => 'checkbox'
				) ),
				new ATHENA_SC_Plugin_Option( self::$option_prefix . 'enable_responsive_embeds', array(
					'default'         => false,
					'format_callback' => 'wp_validate_boolean',
					'field_title'     => 'Enable responsive embeds',
					'field_desc'      => "When checked, all videos and embed content (e.g. social media embeds) added to post/page
										  content will be made responsive.  Video embeds will span the full width of their containers
										  without overflowing, and social widgets will be centered within their parent containers.
										  <br>We recommend enabling this setting on new sites only.
										  <br><strong style='display: inline-block; padding-top: .5rem;'>
										  Please read before changing this setting:
										  </strong>
										  <br>&bull; After modifying this setting, you should flush your oEmbed cache</strong>
										  by using either <a href='https://developer.wordpress.org/cli/commands/embed-2/cache/clear/' _target='blank'>wp-cli</a>
										  or a third-party plugin.  Depending on the size of your site, this could take a while to complete.
										  <br>&bull; Enabling on an existing site will break existing embeds that include responsive
										  embed wrappers already.  <strong>Tread carefully!</strong>",
					'field_type'      => 'checkbox'
				) ),
				new ATHENA_SC_Plugin_Option( self::$option_prefix . 'remove_image_dims', array(
					'default'         => true,
					'format_callback' => 'wp_validate_boolean',
					'field_title'     => 'Remove width/height attributes on images',
					'field_desc'      => "When checked, inline width and height attributes will be <em>removed</em> from images in
										  post content.
										  <br>For backward compatibility, this setting is enabled by default, but we recommend
										  disabling this setting when possible for performance reasons.",
					'field_type'      => 'checkbox'
				) )
			);
		}

		/**
		 * Returns an option object or false if it doesn't exist.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @param string $option_name Name of the option object to return
		 * @return mixed ATHENA_SC_Plugin_Option object, or false on failure
		 **/
		public static function get_option( $option_name ) {
			$options  = self::get_options();
			if ( substr( $option_name, 0, strlen( self::$option_prefix ) ) !== self::$option_prefix ) {
				$option_name = self::$option_prefix . $option_name;
			}

			$filtered = array_filter( $options, function( $option ) use ( $option_name ) {
				return $option->option_name === $option_name;
			} );

			// Return the first array item in $filtered.
			// NOTE: Use `reset()` here since the first index
			// of $filtered may not be 0.
			return ( ! empty( $filtered ) ) ? reset( $filtered ) : false;
		}

		/**
		 * Creates options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin activation.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @return void
		 **/
		public static function add_options() {
			foreach ( self::get_options() as $option ) {
				add_option( $option->option_name, $option->default );
			}
		}

		/**
		 * Deletes options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin uninstallation.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @return void
		 **/
		public static function delete_options() {
			foreach ( self::get_options() as $option ) {
				delete_option( $option->option_name );
			}
		}


		/**
		 * Applies formatting to a single option.
		 * Intended to be passed to the 'option_{$option}' hook.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @param mixed $value Value of the option
		 * @param string $option_name Option name
		 * @return mixed Formatted option value, or false on failure
		 **/
		public static function format_option( $value, $option_name ) {
			$option = self::get_option( $option_name );
			if ( $option ) {
				return $option->format( $value );
			}
			return $value;
		}


		/**
		 * Adds filters for plugin options that apply our
		 * formatting rules and defaults.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @return void
		 **/
		public static function add_option_filters() {
			foreach ( self::get_options() as $option ) {
				// Apply formatting to returned option values
				add_filter( "option_{$option->option_name}", array( 'ATHENA_SC_Config', 'format_option' ), 10, 2 );
				add_filter( "default_option_{$option->option_name}", function( $default, $o, $passed_default ) use ( $option ) {
					if ( $passed_default ) {
						return $default;
					}
					return $option->default;
				}, 10, 3 );
			}
		}


		/**
		 * Returns a setting type valid for use in `register_setting()`
		 * for the given option object.
		 *
		 * @see https://developer.wordpress.org/reference/functions/register_setting/#parameters
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @param object $option ATHENA_SC_Plugin_Option object
		 * @return string WP setting "type" value for the given option
		 */
		public static function get_setting_type( $option ) {
			$type = 'string';

			// NOTE: Add more use cases here as needed to
			// cover other future field types.
			switch ( $option->field_type ) {
				case 'checkbox':
					$type = 'boolean';
					break;
				case 'text':
				default:
					break;
			}

			return $type;
		}


		/**
		 * Initializes setting registration with the Settings API.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @return void
		 **/
		public static function settings_init() {
			// Register setting sections
			add_settings_section(
				self::$settings_page_prefix . '_general', // option section slug
				'General Settings', // formatted title
				'', // callback that echoes any content at the top of the section
				self::$settings_page_prefix // settings page slug
			);

			// Register settings and their fields
			foreach ( self::get_options() as $option ) {
				// Register setting
				register_setting(
					self::$settings_page_prefix,
					$option->option_name,
					array(
						'type'              => self::get_setting_type( $option ),
						'description'       => $option->field_desc,
						'sanitize_callback' => $option->format_callback,
						'default'           => $option->default
					)
				);

				// Add individual setting field
				if ( $option->field_title && $option->field_options_section ) {
					add_settings_field(
						$option->option_name,
						$option->field_title,  // formatted field title
						array( 'ATHENA_SC_Config', 'display_settings_field' ),  // display callback
						self::$settings_page_prefix,  // settings page slug
						$option->field_options_section,  // option section slug
						array(  // extra arguments to pass to the callback function
							'label_for'   => $option->option_name,
							'description' => $option->field_desc ?: '',
							'type'        => $option->field_type ?: 'text'
						)
					);
				}
			}
		}

		/**
		 * Displays an individual setting's field markup.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @param array $args Array of field display arguments
		 * @return string Field input HTML
		 **/
		public static function display_settings_field( $args ) {
			$option_name   = $args['label_for'];
			$description   = $args['description'];
			$field_type    = $args['type'];
			$current_value = get_option( $option_name );
			$markup        = '';

			switch ( $field_type ) {
				case 'checkbox':
					ob_start();
				?>
					<input type="checkbox" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" <?php checked( $current_value ); ?>>
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;

				case 'text':
				default:
					ob_start();
				?>
					<input type="text" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="<?php echo $current_value; ?>">
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;
			}
		?>

		<?php
			echo $markup;
		}


		/**
		 * Registers the settings page to display in the WordPress admin.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @return string The resulting page's hook_suffix
		 **/
		public static function add_options_page() {
			$page_title = 'Athena Shortcodes Plugin Settings';
			$menu_title = 'Athena Shortcodes';
			$capability = 'manage_options';
			$menu_slug  = 'athena_sc_settings';
			$callback   = array( 'ATHENA_SC_Config', 'options_page_html' );

			return add_options_page(
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$callback
			);
		}


		/**
		 * Displays the plugin's settings page form.
		 *
		 * @author Jo Dickson
		 * @since 0.4.0
		 * @return string Options page form HTML
		 **/
		public static function options_page_html() {
			ob_start();
		?>

		<div class="wrap">
			<h1><?php echo get_admin_page_title(); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( self::$settings_page_prefix );
				do_settings_sections( self::$settings_page_prefix );
				submit_button();
				?>
			</form>
		</div>

		<?php
			echo ob_get_clean();
		}
	}
}
