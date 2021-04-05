<?php
/**
 * Helper class for quickly defining shortcodes.
 **/
if ( ! class_exists( 'ATHENA_SC_Shortcode' ) ) {
	abstract class ATHENA_SC_Shortcode {
		public
			$command = 'Shortcode', // "Pretty" shortcode name
			$name = 'shortcode',    // Actual command name (lowercase, alphanumeric, with dashes and underscores)
			$desc = '',             // A brief description of the shortcode
			$content = false,       // Whether or not the shortcode accepts inner contents (is enclosing)
			$preview = false,       // Whether a preview of this shortcode should be displayed within the WP SCIF interface
			$aliases = array();     // One or more alternate names for this shortcode (accepts an array of strings)

		/**
		 * Returns an array of fields
		 *
		 * @author Jim Barnes
		 * @since 1.0.0
		 *
		 * @return Array | The array of fields.
		 **/
		public function fields() {
			return array();
		}

		/**
		 * Returns an array of fields for the
		 * shortcake plugin.
		 *
		 * @return Array | The array of fields
		 */
		public function shortcake_fields() {
			if ( ! empty( $this->fields() ) ) {
				return athena_sc_wpscif_convert( $this->fields() );
			}

			return array();
		}

		/**
		 * Registers the fields with the `WP-Shortcode-Interface` Plugin
		 *
		 * @author Jim Barnes
		 * @since 1.0.0
		 *
		 * @param $shortcodes Array | The registered shortcodes.
		 * @return Array | The modified registered shortcodes array.
		 **/
		public function register_interface() {
			return array(
				'command' => $this->command,
				'name'    => $this->name,
				'desc'    => $this->desc,
				'content' => $this->content,
				'fields'  => $this->fields(),
				'preview' => $this->preview,
				'group'   => $this->group ?: 'Athena Framework - Uncategorized'
			);
		}

		/**
		 * Returns the default values.
		 *
		 * @author Jim Barnes
		 * @since 1.0.0
		 *
		 * @param $param String | Optional, single shortcode param that a default value should be returned for.
		 * @return Mixed | An array of default values, or a single param's default value (if $param is set).
		 **/
		public function defaults( $param=null ) {
			$retval = array();
			$fields = $this->fields();

			foreach( $fields as $field ) {
				if ( isset( $field['default'] ) ) {
					$retval[$field['param']] = $field['default'];
				}
				else {
					$retval[$field['param']] = '';
				}
			}

			if ( $param && isset( $retval[$param] ) ) {
				$retval = $retval[$param];
			}

			return $retval;
		}

		/**
		 * Registers the shortcode
		 *
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public function register_shortcode() {
			$command_names = $this->command_names();
			foreach ( $command_names as $command ) {
				add_shortcode( $command, array( $this, 'callback' ) );
			}
		}

		/**
		 * The callback for the shortcode
		 *
		 * @author Jim Barnes
		 * @since 1.0.0
		 *
		 * @param $atts Array | The shortcode attributes
		 * @param $content string | The html content within the shortcode
		 *
		 * @return string | The html output of the shortcode.
		 **/
		public function callback( $atts, $content ) {
			return '';
		}

		/**
		 * Determines if the shortcode $content contains
		 * the shortcode itself.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $content string | The content
		 * @return string
		 **/
		public function contains_nested( $content ) {
			$pattern = '/\[[a-zA-Z \=\"]+?\](.*+)?(\[\/\w+\])?/s';

			preg_match( $pattern, $content, $matches );

			if ( $matches ) {
				return true;
			}

			return false;
		}

		/**
		 * Returns a list of all names this shortcode is registered under
		 * (the primary command name and any aliases).
		 *
		 * @author Jo Dickson
		 * @since 0.3.2
		 * @return array Array of shortcode command names
		 */
		public function command_names() {
			return array_merge( array( $this->command ), $this->aliases );
		}
	}
}
