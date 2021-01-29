<?php
/**
 * Provides a shortcode for the .card class
 **/
if ( ! class_exists( 'CardSC' ) ) {
	class CardSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card',
			$name = 'Card',
			$desc = 'Wraps content in an Athena card.',
			$content = true,
			$preview = true,
			$group = 'Athena Framework - Cards';

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
					'desc'    => 'Specify the type of element to use for the card. Divs are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'div'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the card. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card.',
					'type'    => 'text'
				)
			);
		}

		public function element_type_options() {
			return array(
				'div' => 'div',
				'figure'  => 'figure (semantic element for self-contained illustration, diagram, snippet, etc.)',
				'aside'   => 'aside (semantic element for tangent content, e.g. sidebars)',
				'section' => 'section (semantic element for standalone content)'
			);
		}

		/**
		 * Wraps content inside of a div with class .card
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'card' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles  = $atts['style'] ?: false;
			$id      = $atts['id'];
			$elem    = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );
			$content_formatted = do_shortcode( $content );

			// Fix formatting issues with inner card img's
			if ( preg_match_all( '/(<p>)?(<a [^>]+>)?<img [^>]+>(<\/a>)?(<\/p>)?/', $content_formatted, $matches ) !== false ) {
				foreach ( $matches[0] as $image ) {
					if (
						strpos( $image, 'card-img' ) !== false
						|| strpos( $image, 'card-img-top' ) !== false
						|| strpos( $image, 'card-img-bottom' ) !== false
						|| strpos( $image, 'media-background' ) !== false
					) {
						// Strip wrapper <p>'s from inner card-img's
						$image_filtered = str_replace( '</p>', '', str_replace( '<p>', '', $image ) );

						// Removed fixed width/height attributes from img tags
						if ( strpos( $image_filtered, 'width=' ) !== false || strpos( $image_filtered, 'height=' ) !== false ) {
							$image_filtered = preg_replace( '/(width|height)="\d*"\s/', '', $image_filtered );
						}

						$content_formatted = str_replace( $image, $image_filtered, $content_formatted );
					}
				}
			}

			ob_start();
		?>
			<<?php echo $elem; ?> class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>>
				<?php echo $content_formatted; ?>
			</<?php echo $elem; ?>>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .card-header class
 **/
if ( ! class_exists( 'CardHeaderSC' ) ) {
	class CardHeaderSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card-header',
			$name = 'Card Header',
			$desc = 'Wraps content in an Athena card header.',
			$content = true,
			$group = 'Athena Framework - Cards';

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
					'desc'    => 'Specify the type of element to use for the card header. Divs are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'div'
				),
				array(
					'param'   => 'accordion_toggle',
					'name'    => 'Is an Accordion Toggle',
					'desc'    => 'If checked, the card header will have role="tab" applied to it for accessibility purposes. Should only be checked when the card header serves as a panel tab within an accordion.',
					'type'    => 'checkbox',
					'default' => false
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the card header. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card header.',
					'type'    => 'text'
				)
			);
		}

		public function element_type_options() {
			return array(
				'div'    => 'div',
				'header' => 'header (semantic header element)',
				'h1'     => 'h1',
				'h2'     => 'h2',
				'h3'     => 'h3',
				'h4'     => 'h4',
				'h5'     => 'h5',
				'h6'     => 'h6'
			);
		}

		/**
		 * Wraps content inside of an element with class .card-header
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'card-header' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles  = $atts['style'] ?: false;
			$id      = $atts['id'];
			$elem    = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );
			$attributes = array();
			if ( $atts['accordion_toggle'] ) {
				$attributes[] = 'role="tab"';
			}

			ob_start();
		?>
			<<?php echo $elem; ?> class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
			<?php if ( $attributes ) { echo implode( ' ', $attributes ); } ?>>
				<?php echo do_shortcode( $content ); ?>
			</<?php echo $elem; ?>>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .card-footer class
 **/
if ( ! class_exists( 'CardFooterSC' ) ) {
	class CardFooterSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card-footer',
			$name = 'Card Footer',
			$desc = 'Wraps content in an Athena card footer.',
			$content = true,
			$group = 'Athena Framework - Cards';

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
					'desc'    => 'Specify the type of element to use for the card header. Divs are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'div'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the card footer. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card footer.',
					'type'    => 'text'
				)
			);
		}

		public function element_type_options() {
			return array(
				'div' => 'div',
				'header' => 'footer (semantic footer element)'
			);
		}

		/**
		 * Wraps content inside of an element with class .card-footer
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'card-footer' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles  = $atts['style'] ?: false;
			$id      = $atts['id'];
			$elem    = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );

			ob_start();
		?>
			<<?php echo $elem; ?> class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>>
				<?php echo do_shortcode( $content ); ?>
			</<?php echo $elem; ?>>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .card-block class
 **/
if ( ! class_exists( 'CardBlockSC' ) ) {
	class CardBlockSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card-block',
			$name = 'Card Block',
			$desc = 'Wraps content in an Athena card block.',
			$content = true,
			$group = 'Athena Framework - Cards';

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
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the card block. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card block.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of an element with class .card-block
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'card-block' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles  = $atts['style'] ?: false;
			$id      = $atts['id'];

			ob_start();
		?>
			<div class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>>
				<?php echo do_shortcode( $content ); ?>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .card-title class
 **/
if ( ! class_exists( 'CardTitleSC' ) ) {
	class CardTitleSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card-title',
			$name = 'Card Title',
			$desc = 'Wraps content in an Athena card title.',
			$content = true,
			$group = 'Athena Framework - Cards';

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
					'desc'    => 'Specify the type of element to use for the card title. Divs are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'div'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the card title. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card title.',
					'type'    => 'text'
				)
			);
		}

		public function element_type_options() {
			return array(
				'div' => 'div',
				'span' => 'span',
				'h1' => 'h1',
				'h2' => 'h2',
				'h3' => 'h3',
				'h4' => 'h4',
				'h5' => 'h5',
				'h6' => 'h6'
			);
		}

		/**
		 * Wraps content inside of an element with class .card-title
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'card-title' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles  = $atts['style'] ?: false;
			$id      = $atts['id'];
			$elem    = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );

			ob_start();
		?>
			<<?php echo $elem; ?> class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>>
				<?php echo do_shortcode( $content ); ?>
			</<?php echo $elem; ?>>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .card-subtitle class
 **/
if ( ! class_exists( 'CardSubtitleSC' ) ) {
	class CardSubtitleSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card-subtitle',
			$name = 'Card Subtitle',
			$desc = 'Wraps content in an Athena card subtitle.',
			$content = true,
			$group = 'Athena Framework - Cards';

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
					'desc'    => 'Specify the type of element to use for the card subtitle. Divs are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'div'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card subtitle.',
					'type'    => 'text'
				)
			);
		}

		public function element_type_options() {
			return array(
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p'
			);
		}

		/**
		 * Wraps content inside of an element with class .card-subtitle
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'card-subtitle' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles  = $atts['style'] ?: false;
			$elem    = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );

			ob_start();
		?>
			<<?php echo $elem; ?> class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>>
				<?php echo do_shortcode( $content ); ?>
			</<?php echo $elem; ?>>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .card-text class
 **/
if ( ! class_exists( 'CardTextSC' ) ) {
	class CardTextSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card-text',
			$name = 'Card Text',
			$desc = 'Wraps content as Athena card text.',
			$content = true,
			$group = 'Athena Framework - Cards';

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
					'desc'    => 'Specify the type of element to use for the card text. Divs are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'div'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card text.',
					'type'    => 'text'
				)
			);
		}

		public function element_type_options() {
			return array(
				'div'   => 'div',
				'span'  => 'span',
				'p'     => 'p'
			);
		}

		/**
		 * Wraps content inside of an element with class .card-text
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'card-text' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles  = $atts['style'] ?: false;
			$elem    = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );

			ob_start();
		?>
			<<?php echo $elem; ?> class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>>
				<?php echo do_shortcode( $content ); ?>
			</<?php echo $elem; ?>>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .card-link class.
 **/
if ( ! class_exists( 'CardLinkSC' ) ) {
	class CardLinkSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card-link',
			$name = 'Card Link',
			$desc = 'Creates a new link styled as an Athena card link.',
			$content = true,
			$preview = true,
			$group = 'Athena Framework - Cards';

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
					'param'   => 'href',
					'name'    => 'Link URL',
					'type'    => 'text'
				),
				array(
					'param'   => 'new_window',
					'name'    => 'Open link in a new window',
					'type'    => 'checkbox',
					'default' => false
				),
				array(
					'param'   => 'rel',
					'name'    => 'Link object relationship (rel)',
					'desc'    => 'The relationship between the link and target object. Separate each link type with a single space.',
					'type'    => 'text'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the card link. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card link.',
					'type'    => 'text'
				),
				array(
					'param'   => 'data_toggle',
					'name'    => 'Data-Toggle',
					'desc'    => 'Type of toggling functionality the card link should have. Use the [modal-toggle], [collapse-toggle], [popover] or [tooltip] shortcodes to create toggles for those components instead of the [card-link] shortcode.',
					'type'    => 'select',
					'options' => $this->data_toggle_options()
				)
			);
		}

		public function data_toggle_options() {
			return array(
				''         => '---',
				'button'   => 'button',
				'dropdown' => 'dropdown'
			);
		}

		/**
		 * Wraps content inside of a link with class .card-link
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$href       = $atts['href'];
			$new_window = filter_var( $atts['new_window'], FILTER_VALIDATE_BOOLEAN );
			$id         = $atts['id'];
			$styles     = $atts['style'];
			$attributes = array();
			$classes    = array( 'card-link' );
			$rel        = $atts['rel'];

			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}

			// Get any state-related attributes, if applicable
			if ( in_array( 'active', $classes ) ) {
				$attributes[] = 'aria-pressed="true"';
			}
			else if ( in_array( 'disabled', $classes ) ) {
				$attributes[] = 'aria-disabled="true"';
				$attributes[] = 'tabindex="-1"';
				$attributes[] = 'onclick="return false;"';
			}

			// Get any data-attributes, if applicable
			if ( $atts['data_toggle'] && array_key_exists( $atts['data_toggle'], $this->data_toggle_options() ) ) {
				$attributes[] = 'data-toggle="' . $atts['data_toggle'] . '"';
			}

			// Set the button's "role" attribute if it has no href value (we
			// assume its functionality is added via javascript somewhere.)
			// Otherwise, we assume the button should assume its default role
			// of being a basic link.
			if ( $href == '' || $href == '#' ) {
				$attributes[] = 'role="button"';
			}

			ob_start();
		?>
			<a class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $href ) { echo 'href="' . $href . '"'; } ?>
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $new_window ) { echo 'target="_blank"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
			<?php if ( $rel ) { echo 'rel="' . $rel . '"'; } ?>
			<?php if ( $attributes ) { echo implode( ' ', $attributes ); } ?>
			><?php echo do_shortcode( $content ); ?></a>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .card-group class
 **/
if ( ! class_exists( 'CardGroupSC' ) ) {
	class CardGroupSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card-group',
			$name = 'Card Group',
			$desc = 'Wraps content in an Athena card group.',
			$content = true,
			$group = 'Athena Framework - Cards';

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
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the card group. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card group.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of a div with class .card-group
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'card-group' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$id        = $atts['id'];
			$styles    = $atts['style'] ?: false;

			ob_start();
		?>
			<div class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>>
				<?php echo do_shortcode( $content ); ?>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .card-deck class
 **/
if ( ! class_exists( 'CardDeckSC' ) ) {
	class CardDeckSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card-deck',
			$name = 'Card Deck',
			$desc = 'Wraps content in an Athena card deck.',
			$content = true,
			$group = 'Athena Framework - Cards';

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
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the card deck. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card deck.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of a div with class .card-deck
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'card-deck' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$id        = $atts['id'];
			$styles    = $atts['style'] ?: false;

			ob_start();
		?>
			<div class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>>
				<?php echo do_shortcode( $content ); ?>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .card-columns class
 **/
if ( ! class_exists( 'CardColumnsSC' ) ) {
	class CardColumnsSC extends ATHENA_SC_Shortcode {
		public
			$command = 'card-columns',
			$name = 'Card Columns',
			$desc = 'Wraps content in Athena card columns.',
			$content = true,
			$group = 'Athena Framework - Cards';

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
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the card column wrapper. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the card column wrapper.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of a div with class .card-columns
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'card-columns' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$id        = $atts['id'];
			$styles    = $atts['style'] ?: false;

			ob_start();
		?>
			<div class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>>
				<?php echo do_shortcode( $content ); ?>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}
