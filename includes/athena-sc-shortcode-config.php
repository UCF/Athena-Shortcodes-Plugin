<?php
/**
 * Handles registering shortcodes.
 **/
if ( ! class_exists( 'ATHENA_SC_Shortcode_Config' ) ) {
	class ATHENA_SC_Shortcode_Config {
		/**
		 * Registers the shortcodes
		 **/
		public static function register_shortcodes() {
			$shortcodes = self::installed_shortcodes();

			foreach( $shortcodes as $shortcode ) {
				$shortcode->register_shortcode();
			}
		}

		/**
		 * Registers the shortcode fields
		 */
		public static function register_shortcake_uis() {
			$shortcodes = self::installed_shortcodes();

			foreach( $shortcodes as $shortcode ) {
				if ( ! empty( $shortcode->shortcake_fields() ) ) {
					$args = array(
						'label'         => $shortcode->name,
						'listItemImage' => 'dashicons-editor-code',
						'attrs'         => $shortcode->shortcake_fields()
					);

					if ( $shortcode->content ) {
						$args['inner_content'] = array(
							'label' => 'Inner Content'
						);
					}

					shortcode_ui_register_for_shortcode(
						$shortcode->command,
						$args
					);
				}
			}
		}

		public static function register_shortcodes_interface( $shortcodes ) {
			$installed = self::installed_shortcodes();

			foreach( $installed as $shortcode ) {
				$shortcodes[] = $shortcode->register_interface();
			}

			return $shortcodes;
		}

		public static function register_shortcodes_preview_styles( $stylesheets ) {
			$stylesheets[] = plugins_url( 'static/css/athena-editor-styles.min.css', ATHENA_SC__PLUGIN_FILE );
			return $stylesheets;
		}

		public static function installed_shortcodes() {
			// Get shortcodes via the `athena_sc_add_shortcode` hook.
			$installed = self::athena_sc_add_shortcode();
			$installed = apply_filters( 'athena_sc_add_shortcode', $installed );

			return array_map( function( $class ) {
				return new $class;
			}, $installed );
		}

		public static function athena_sc_add_shortcode() {
			return array(
				'ContainerSC',
				'RowSC',
				'ColSC',
				'AlertSC',
				'AlertLinkSC',
				'AlertHeadingSC',
				'AccordionSC',
				'BadgeSC',
				'ButtonSC',
				'CardSC',
				'CardHeaderSC',
				'CardFooterSC',
				'CardBlockSC',
				'CardTitleSC',
				'CardSubtitleSC',
				'CardTextSC',
				'CardLinkSC',
				'CardGroupSC',
				'CardDeckSC',
				'CardColumnsSC',
				'CloseSC',
				'CollapseSC',
				'CollapseToggleSC',
				'GenericLinkSC',
				'IconSC',
				'JumbotronSC',
				'MediaBackgroundContainerSC',
				'MediaBackgroundSC',
				'ModalSC',
				'ModalHeaderSC',
				'ModalFooterSC',
				'ModalBodySC',
				'ModalTitleSC',
				'ModalToggleSC',
				'NavSC',
				'NavItemSC',
				'NavLinkSC',
				'TabContentSC',
				'TabPaneSC'
			);
		}

		public static function athena_sc_get_formatted_shortcodes() {
			$installed = self::installed_shortcodes();
			$shortcodes = array();

			foreach( $installed as $shortcode ) {
				foreach ( $shortcode->command_names() as $command ) {
					$shortcodes[] = $command;
				}
			}

			return $shortcodes;
		}

		/**
		 * Strip out <p></p> and <br> from inner shortcode contents. Applied
		 * only to shortcodes returned by the
		 * athena_sc_get_formatted_shortcodes hook.
		 *
		 * https://wordpress.stackexchange.com/a/130185
		 **/
		public static function format_shortcode_output( $content ) {
			// Get shortcodes via the `athena_sc_get_formatted_shortcodes` hook.
			$shortcodes = self::athena_sc_get_formatted_shortcodes();
			$shortcodes = apply_filters( 'athena_sc_get_formatted_shortcodes', $shortcodes );

			$block = join( '|', $shortcodes );
			$rep = preg_replace( "/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content );
			$rep = preg_replace( "/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep );
			return $rep;
		}

		/**
		 * Strip out <p></p> and <br> from inner shortcode contents within ACF
		 * WYSIWYG field contents.
		 *
		 * There is probably a less janky way of doing this that doesn't
		 * involve re-fetching the field value, but this works at least
		 **/
		public static function format_acf_wysiwyg_output( $content, $post_id, $field ) {
			$value_raw = acf_get_value( $post_id, $field );
			return apply_filters( 'the_content', $value_raw );
		}
	}
}
