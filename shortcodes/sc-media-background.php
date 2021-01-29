<?php
/**
 * Provides a shortcode for media background containers.
 **/
if ( ! class_exists( 'MediaBackgroundContainerSC' ) ) {
	class MediaBackgroundContainerSC extends ATHENA_SC_Shortcode {
		public
			$command = 'media-background-container',
			$name = 'Media Background Container',
			$desc = 'Creates a media background container.',
			$content = true,
			$preview = false,
			$group = 'Athena Framework - Utilities';

		/**
		 * Returns the shortcode's fields.
		 *
		 * @author Jo Dickson
		 * @since 1.0.0
		 *
		 * @return Array | The shortcode's fields.
		 **/
		public function fields() {
			return array(
				array(
					'param'   => 'element_type',
					'name'    => 'Element Type',
					'desc'    => 'Specify the type of element to use. Divs are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'div'
				),
				array(
					'param'   => 'href',
					'name'    => 'Link (for link elements)',
					'type'    => 'text'
				),
				array(
					'param'   => 'new_window',
					'name'    => 'Open link in a new window (for link elements)',
					'type'    => 'checkbox',
					'default' => false
				),
				array(
					'param'   => 'rel',
					'name'    => 'Link object relationship (rel)',
					'desc'    => 'The relationship between the link and target object (for link elements). Separate each link type with a single space.',
					'type'    => 'text'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'desc'    => 'Separate each class with a single space. Refer to the Athena Framework documentation for available classes.',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the element. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the element.',
					'type'    => 'text'
				)
			);
		}

		public function element_type_options() {
			return array(
				'div'     => 'div',
				'section' => 'section',
				'aside'   => 'aside',
				'a'       => 'a'
			);
		}

		/**
		 * Wraps content inside of a .media-background-container
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$href         = $atts['element_type'] === 'a' ? $atts['href'] : '';
			$new_window   = $atts['element_type'] === 'a' ? filter_var( $atts['new_window'], FILTER_VALIDATE_BOOLEAN ) : false;
			$id           = $atts['id'];
			$element_type = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );
			$styles       = $atts['style'];
			$rel          = $atts['element_type'] === 'a' ? $atts['rel'] : '';
			$attributes   = array();
			$classes      = array( 'media-background-container' );
			$content_formatted = do_shortcode( $content );

			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}

			if ( $element_type === 'a' && !$href ) {
				$attributes[] = 'tabindex="0"';
			}

			ob_start();
		?>
			<<?php echo $element_type; ?> class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $href ) { echo 'href="' . $href . '"'; } ?>
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $new_window ) { echo 'target="_blank"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
			<?php if ( $rel ) { echo 'rel="' . $rel . '"'; } ?>
			<?php if ( $attributes ) { echo implode( ' ', $attributes ); } ?>
			>
				<?php echo $content_formatted; ?>
			</<?php echo $element_type; ?>>
		<?php
			return ob_get_clean();
		}
	}
}


/**
 * Provides a shortcode for media backgrounds.
 **/
 if ( ! class_exists( 'MediaBackgroundSC' ) ) {
	class MediaBackgroundSC extends ATHENA_SC_Shortcode {
		public
			$command = 'media-background',
			$name = 'Media Background',
			$desc = 'Creates a media background. Immediate child images and video are auto-detected and used as media background(s).',
			$content = true,
			$preview = false,
			$group = 'Athena Framework - Utilities';

		/**
		 * Returns the shortcode's fields.
		 *
		 * @author Jo Dickson
		 * @since 1.0.0
		 *
		 * @return Array | The shortcode's fields.
		 **/
		public function fields() {
			return array(
				array(
					'param'   => 'object_fit',
					'name'    => 'Object Fit',
					'desc'    => 'How the inner media background should be resized within the media background container.',
					'type'    => 'select',
					'options' => $this->object_fit_options(),
					'default' => 'object-fit-cover'
				),
				array(
					'param'   => 'object_position',
					'name'    => 'Object Position',
					'desc'    => 'How the inner media background should be positioned within the media background container. See <a href="https://css-tricks.com/almanac/properties/o/object-position/">object-position documentation</a> for usage/more information.',
					'type'    => 'text'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'desc'    => 'Separate each class with a single space. Refer to the Athena Framework documentation for available classes.',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the inner element. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the inner element.',
					'type'    => 'text'
				)
			);
		}

		public function object_fit_options() {
			return array(
				'object-fit-contain'    => 'contain',
				'object-fit-cover'      => 'cover',
				'object-fit-fill'       => 'fill',
				'object-fit-none'       => 'none (unset an existing object-fit rule)',
				'object-fit-scale-down' => 'scale-down'
			);
		}

		/**
		 * Wraps content inside of a .media-background and applies
		 * necessary classes to inner img/picture/video. Note that only one
		 * valid media background element within this shortcode is supported.
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$id         = $atts['id'];
			$object_fit = array_key_exists( $atts['object_fit'], $this->object_fit_options() ) ? $atts['object_fit'] : $this->defaults( 'object_fit' );
			$styles     = trim( $atts['style'] );
			$classes    = array( 'media-background', $object_fit );
			$object_pos = $atts['object_position'];

			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			if ( $styles && substr( $styles, -1 ) !== ';' ) {
				$styles .= ';';
			}
			if ( $object_pos ) {
				$styles .= ' object-position: ' . $object_pos . ';';
			}

			$content_formatted = do_shortcode( $content );
			$has_valid_media_bg = false;

			// If there is some inner shortcode content, attempt to parse
			// through it.  Don't pass empty strings to DomDocument->loadHTML
			if ( trim( $content_formatted ) ) {
				$dom = new DomDocument();

				// DomDocument->loadHTML complains about HTML5 elements, so we
				// have to suppress errors. https://stackoverflow.com/a/41845049
				libxml_clear_errors();
				$error_settings_cached = libxml_use_internal_errors( true );

				// Parse the formatted HTML string
				$dom->loadHTML( $content_formatted );
				$node = null;

				// Find a valid child element to use as a media background
				// TODO <picture> support
				$img_nodes = $dom->getElementsByTagName( 'img' );
				if ( $img_nodes->length > 0 ) {
					$node = $img_nodes->item(0);
				}
				$video_nodes = $dom->getElementsByTagName( 'video' );
				if ( $video_nodes->length > 0 ) {
					$node = $video_nodes->item(0);
				}

				// If a valid element was found, modify it as needed
				if ( $node ) {
					// Apply extra classes. Strip out .wp-video-shortcode class
					// for videos.
					$elem_classes = array_merge( explode( ' ', $node->getAttribute( 'class' ) ), $classes );
					if ( $node->nodeName === 'video' && ( $key = array_search( 'wp-video-shortcode', $elem_classes ) ) !== false ) {
						unset( $elem_classes[$key] );
					}
					$node->setAttribute( 'class', implode( ' ', $elem_classes ) );

					// Apply ID
					// TODO <picture> support: don't apply ID to <img> elements
					// that have a parent <picture> elem
					if ( $id ) {
						$node->setAttribute( 'id', $id );
					}

					// Apply extra styles
					if ( $styles ) {
						$node->setAttribute( 'style', $styles );
					}

					// Apply object-position data attribute if necessary
					if ( $object_pos ) {
						$node->setAttribute( 'data-object-position', $object_pos );
					}

					// Video-specific attribute modification
					if ( $node->nodeName === 'video' ) {
						// Remove unnecessary/breaking attributes for videos
						$node->removeAttribute( 'width' );
						$node->removeAttribute( 'height' );
						$node->removeAttribute( 'controls' );

						// Force 'muted' attribute
						$node->setAttribute( 'muted', 'muted' );
					}

					$content_formatted = $dom->saveHTML( $node );
					$has_valid_media_bg = true;
				}

				// Clean up libxml error handling
				libxml_clear_errors();
				libxml_use_internal_errors( $error_settings_cached );
			}

			// Return the media background.  If no valid inner contents are
			// detected, an empty div will be returned.
			if ( !$has_valid_media_bg ) {
				ob_start();
			?>
				<div class="<?php echo implode( ' ', $classes ); ?>"
				<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
				<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
				<?php if ( $object_pos ) { echo 'data-object-position="' . $object_pos . '"'; } ?>
				></div>
			<?php
				$content_formatted = ob_get_clean();
			}
			return $content_formatted;
		}
	}
}
