<?php
/**
 * Handles registering TinyMCE WYSIWYG formatting options.
 **/
if ( ! class_exists( 'ATHENA_SC_TinyMCE_Config' ) ) {
	class ATHENA_SC_TinyMCE_Config {

		public static function enable_formats( $buttons ) {
			array_unshift( $buttons, 'styleselect' );
			return $buttons;
		}

		public static function tinymce_formats_to_array( $formats ) {
			$retval = array();
			$array = array_filter( array_map( 'trim', explode( ';', $formats ) ) );
			foreach ( $array as $keyval ) {
				list( $key, $val ) = explode( '=', $keyval );
				$retval[$key] = $val;
			}
			return $retval;
		}

		public static function array_to_tinymce_formats( $array ) {
			$retval = '';
			$keys = array_keys( $array );
			$last_key = array_pop( $keys );
			foreach ( $array as $key => $val ) {
				$retval .= $key . '=' . $val;
				if ( $key !== $last_key ) {
					$retval .= ';';
				}
			}
			return $retval;
		}

		public static function get_block_formats( $block_formats ) {
			$block_formats = self::tinymce_formats_to_array( $block_formats );
			$new_block_formats = array_merge( $block_formats, array(
				'Div' => 'div',
				'Paragraph' => 'p',
				'Heading 1' => 'h1',
				'Heading 2' => 'h2',
				'Heading 3' => 'h3',
				'Heading 4' => 'h4',
				'Heading 5' => 'h5',
				'Heading 6' => 'h6',
				'Preformatted' => 'p'
			) );
			return self::array_to_tinymce_formats( $new_block_formats );
		}

		public static function get_format_options( $classes ) {
			$retval = array();
			if ( is_array( $classes ) ) {
				foreach ( $classes as $cls ) {
					$option = array(
						'title'      => '',
						'inline'     => '',
						'block'      => '',
						'selector'   => 'span,p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
						'classes'    => '',
						'styles'     => '',
						'attributes' => '',
						'exact'      => '',
						'wrapper'    => ''
					);

					if ( is_string( $cls ) ) {
						$option['title'] = $option['classes'] = $cls;
					}
					elseif ( is_array( $cls ) ) {
						$option = array_replace( $option, $cls );
						if ( !$option['title'] ) { $option['title'] = $option['classes']; }
					}
					else {
						continue;
					}

					$option = array_filter( $option );
					$retval[] = $option;
				}
			}
			return $retval;
		}

		public static function get_format( $title, $options ) {
			return array(
				'title' => $title,
				'items' => self::get_format_options( $options )
			);
		}

		public static function get_style_formats( $style_formats ) {
			$style_formats = $style_formats ? json_decode( $style_formats, true ) : array();

			$new_style_formats = array();

			// Define opt-in element classes if automatic insertion of
			// these classes is disabled:
			$blockquote_optin_class = $table_optin_class = null;
			if ( ! get_option( 'athena_sc_enable_optin_classes' ) ) {
				$blockquote_optin_class = array( 'block' => 'blockquote', 'classes' => 'blockquote', 'selector' => 'blockquote' );
				$table_optin_class      = array( 'classes' => 'table', 'selector' => 'table' );
			}

			// Text, list formatting options
			$new_style_formats = array(
				self::get_format( 'Blockquote Styles', array(
					$blockquote_optin_class,
					array( 'block' => 'blockquote', 'classes' => 'blockquote-reverse', 'selector' => 'blockquote' ),
					array( 'block' => 'blockquote', 'classes' => 'blockquote-quotation', 'selector' => 'blockquote' ),
					array( 'block' => 'blockquote', 'classes' => 'blockquote-quotation-inverse', 'selector' => 'blockquote' ),
					array( 'block' => 'blockquote', 'classes' => 'card-blockquote', 'selector' => 'blockquote' ),
					array( 'block' => 'footer', 'classes' => 'blockquote-footer' )
				) ),
				self::get_format( 'Font Family', array(
					'font-sans-serif',
					'font-serif',
					'font-slab-serif',
					'font-condensed'
				) ),
				self::get_format( 'Font Weight/Style', array(
					'font-weight-light',
					'font-weight-normal',
					'font-italic',
					'font-weight-bold',
					'font-weight-black'
				) ),
				self::get_format( 'Heading Size Styles', array(
					'h1',
					'h2',
					'h3',
					'h4',
					'h5',
					'h6',
					'display-1',
					'display-2',
					'display-3',
					'display-4'
				) ),
				self::get_format( 'Heading Underline Styles', array(
					'heading-underline',
					'heading-underline-inverse'
				) ),
				self::get_format( 'Horizontal Rule (hr) Styles', array(
					array( 'classes' => 'hr-2', 'selector' => 'hr' ),
					array( 'classes' => 'hr-3', 'selector' => 'hr' ),
					array( 'classes' => 'hr-black', 'selector' => 'hr' ),
					array( 'classes' => 'hr-primary', 'selector' => 'hr' ),
					array( 'classes' => 'hr-white', 'selector' => 'hr' ),
				) ),
				self::get_format( 'Inline Text Styles', array(
					'lead',
					'mark',
					'small'
				) ),
				self::get_format( 'Letter Spacing', array(
					'letter-spacing-0',
					'letter-spacing-1',
					'letter-spacing-2',
					'letter-spacing-3',
					'letter-spacing-4',
					'letter-spacing-5'
				) ),
				self::get_format( 'List Styles', array(
					array( 'classes' => 'list-unstyled', 'selector' => 'ul' ),
					array( 'classes' => 'list-inline', 'selector' => 'ul' ),
					array( 'classes' => 'list-inline-item', 'selector' => 'li' )
				) ),
				self::get_format( 'Responsive Display Utilities', array(
					'hidden-xs-down',
					'hidden-sm-down',
					'hidden-md-down',
					'hidden-lg-down',
					'hidden-xl-down',
					'hidden-xs-up',
					'hidden-sm-up',
					'hidden-md-up',
					'hidden-lg-up',
					'hidden-xl-up',
					'visible-print-block',
					'visible-print-inline',
					'visible-print-inline-block',
					'hidden-print'
				) ),
				self::get_format( 'Table Styles', array(
					$table_optin_class,
					array( 'classes' => 'table-responsive', 'selector' => 'table' ),
					array( 'classes' => 'table-striped', 'selector' => 'table' ),
					array( 'classes' => 'table-bordered', 'selector' => 'table' ),
					array( 'classes' => 'table-hover', 'selector' => 'table' ),
					array( 'classes' => 'table-sm', 'selector' => 'table' ),
					array( 'classes' => 'table-inverse', 'selector' => 'table' ),
					array( 'classes' => 'thead-inverse', 'selector' => 'thead' ),
					array( 'classes' => 'thead-default', 'selector' => 'thead' ),
					array( 'classes' => 'table-active', 'selector' => 'tr,th,td' ),
					array( 'classes' => 'table-success', 'selector' => 'tr,th,td' ),
					array( 'classes' => 'table-info', 'selector' => 'tr,th,td' ),
					array( 'classes' => 'table-warning', 'selector' => 'tr,th,td' ),
					array( 'classes' => 'table-danger', 'selector' => 'tr,th,td' ),
				) ),
				self::get_format( 'Text Alignment', array(
					'text-left',
					'text-center',
					'text-right',
					'text-justify',
					'text-truncate',
					'text-nowrap',
					'text-sm-left',
					'text-md-left',
					'text-lg-left',
					'text-xl-left',
					'text-sm-center',
					'text-md-center',
					'text-lg-center',
					'text-xl-center',
					'text-sm-right',
					'text-md-right',
					'text-lg-right',
					'text-xl-right'
				) ),
				self::get_format( 'Text Color', array(
					'text-default',
					'text-primary',
					'text-secondary',
					'text-complementary',
					'text-success',
					'text-info',
					'text-warning',
					'text-danger',
					'text-inverse',
					'text-muted',
					'text-white',
					'text-default-aw',
					'text-primary-aw',
					'text-secondary-aw',
					'text-complementary-aw',
					'text-success-aw',
					'text-info-aw',
					'text-warning-aw',
					'text-danger-aw',
					'text-inverse-aw',
					'text-primary-darkest',
					'text-primary-darker',
					'text-primary-lighter',
					'text-primary-lightest',
					'text-metallic-darkest',
					'text-metallic-darker',
					'text-metallic',
					'text-metallic-lighter',
					'text-metallic-lightest'
				) ),
				self::get_format( 'Text Transform', array(
					'text-uppercase',
					'text-lowercase',
					'text-capitalize',
					'text-transform-unset'
				) ),
				self::get_format( 'Text Visibility', array(
					'invisible',
					'text-hide',
					'sr-only',
					'sr-only-focusable'
				) ),
				self::get_format( 'Vertical Align', array(
					'align-baseline',
					'align-top',
					'align-middle',
					'align-bottom',
					'align-text-top',
					'align-text-bottom'
				) ),
			);

			return json_encode( array_merge( $style_formats, $new_style_formats ) );
		}

		public static function get_init_instance_callback() {
			$callback     = '';
			$has_callback = false;

			if ( get_option( 'athena_sc_enable_optin_classes' ) === true ) {
				$has_callback = true;
			}

			if ( $has_callback ):
				ob_start();
			?>
			function (editor) {
				editor.on('NodeChange', function (e) {
					var elems = e.parents;

					elems.forEach(function(elem) {
						/* Tables */
						if (elem.tagName.toLowerCase() === 'table') {
							elem.classList.add('table');
						}

						/* Blockquotes */
						if (elem.tagName.toLowerCase() === 'blockquote') {
							elem.classList.add('blockquote');
						}
					});
				});
			}
			<?php
				$callback = trim( ob_get_clean() );
			endif;

			return $callback;
		}

		public static function register_settings( $settings ) {
			$settings['block_formats'] = self::get_block_formats( isset( $settings['block_formats'] ) ? $settings['block_formats'] : false );
			$settings['style_formats'] = self::get_style_formats( isset( $settings['style_formats'] ) ? $settings['style_formats'] : false );

			if ( $init_instance_callback = self::get_init_instance_callback() ) {
				$settings['init_instance_callback'] = $init_instance_callback;
			}

			return $settings;
		}

		/**
		 * Modifies WordPress's default markup for images added to the WYSIWYG
		 * editor.
		 **/
		public static function format_image_output( $content ) {
			if ( ! preg_match_all( '/<img [^>]+>/', $content, $matches ) ) {
				return $content;
			}

			foreach ( $matches[0] as $image ) {
				if (
					( strpos( $image, 'width=' ) !== false || strpos( $image, 'height=' ) !== false )
					&& strpos( $image, 'img-fluid' ) !== false
				) {
					$image_filtered = preg_replace( '/(width|height)="\d*"\s/', '', $image );
					$content = str_replace( $image, $image_filtered, $content );
				}
			}

			return $content;
		}

		/**
		 * Given a WordPress alignment class, this function returns the
		 * equivalent Athena class(es).
		 **/
		public static function align_wp_to_athena( $align ) {
			$athena_align = '';
			switch ( $align ) {
				case 'left':
				case 'alignleft':
					$athena_align = 'float-left';
					break;
				case 'center':
				case 'aligncenter':
					$athena_align = 'mx-auto d-block';
					break;
				case 'right':
				case 'alignright':
					$athena_align = 'float-right';
					break;
				case 'none':
				case 'alignnone':
				default:
					break;
			}
			return $athena_align;
		}

		/**
		 * Modifies classes applied to <img> tags generated by WordPress.
		 **/
		public static function format_image_output_classes( $class, $id, $align, $size ) {
			$athena_align = self::align_wp_to_athena( $align );
			$class = $athena_align . ' img-fluid size-' . esc_attr( $size ) . ' wp-image-' . $id;
			return $class;
		}

		/**
		 * Overrides WordPress' default caption shortcode and applies Athena
		 * classes.
		 **/
		public static function format_caption_shortcode( $output, $attr, $content ) {
			$atts = shortcode_atts( array(
				'id'	  => '',
				'align'	  => '',
				'caption' => '',
				'class'   => '',
				'width'   => ''
			), $attr, 'caption' );
			$width = 0;
			$style = '';

			if ( ! empty( $atts['id'] ) ) {
				$atts['id'] = 'id="' . esc_attr( sanitize_html_class( $atts['id'] ) ) . '" ';
			}
			if ( ! empty( $atts['width'] ) ) {
				$width = intval( $atts['width'] );
			}

			// Maintain ability to filter caption width from default [caption] sc
			$width = apply_filters( 'img_caption_shortcode_width', $width, $atts, $content );

			if ( $width && intval( $width ) > 1 ) {
				$style = 'style="max-width: ' . intval( $width ) . 'px;"';
			}

			$athena_align  = self::align_wp_to_athena( $atts['align'] );
			$class         = trim( 'figure ' . $athena_align . ' ' . $atts['class'] );
			$html5         = current_theme_supports( 'html5', 'caption' );

			// Add 'figure-img' class to inner <img> and
			// remove any previously-added alignment classes
			if ( preg_match( '/<img [^>]+>/', $content, $matches ) !== false ) {
				if ( strpos( $matches[0], 'figure-img' ) === false ) {
					$image_filtered = str_replace( "class='", "class='figure-img ", str_replace( 'class="', 'class="figure-img ', $matches[0] ) );
					$image_filtered = str_replace( array( 'float-left', 'float-right', 'mx-auto d-block' ), '', $image_filtered );

					// Required for IE11 compatibility
					if ( $width ) {
						$image_filtered = str_replace( ' img-fluid ', ' img-fluid w-100 ', $image_filtered );
					}

					$content = str_replace( $matches[0], $image_filtered, $content );
				}
			}

			if ( $html5 ) {
				$html = '<figure ' . $atts['id'] . $style . ' class="' . esc_attr( $class ) . '">'
				. do_shortcode( $content ) . '<figcaption class="figure-caption">' . $atts['caption'] . '</figcaption></figure>';
			} else {
				$html = '<div ' . $atts['id'] . $style . ' class="' . esc_attr( $class ) . '">'
				. do_shortcode( $content ) . '<p class="figure-caption">' . $atts['caption'] . '</p></div>';
			}

			return $html;
		}
	}
}
