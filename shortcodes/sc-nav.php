<?php
/**
 * Provides a shortcode for the .nav class
 **/
if ( ! class_exists( 'NavSC' ) ) {
	class NavSC extends ATHENA_SC_Shortcode {
		public
			$command = 'nav',
			$name = 'Nav',
			$desc = 'Wraps content in an Athena nav.',
			$content = true,
			$group = 'Athena Framework - Navs';

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
					'desc'    => 'Specify the type of element to use for the nav. Unordered lists (ul) are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'ul'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the nav.',
					'type'    => 'text'
				),
				array(
					'param'   => 'tablist',
					'name'    => 'Controls Tab Panes',
					'desc'    => 'Check this checkbox if the nav contains links that toggle tab panes when clicked. Applies role="tablist" to the generated nav element.',
					'type'    => 'checkbox'
				)
			);
		}

		public function element_type_options() {
			return array(
				'ul'  => 'ul (unordered list)',
				'nav' => 'nav (semantic navigation element)',
				'div' => 'div'
			);
		}

		/**
		 * Wraps content inside of an element with class .nav
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$styles     = $atts['style'];
			$classes    = array_unique( array_merge( array( 'nav' ), explode( ' ', $atts['class'] ) ) );
			$elem       = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );
			$tablist    = filter_var( $atts['tablist'], FILTER_VALIDATE_BOOLEAN );
			$attributes = array();

			if ( $tablist ) {
				// Set the "role" attribute, if applicable
				$attributes[] = 'role="tablist"';

				// If this is a dynamic, vertical tablist, add `aria-orientation` attr
				if ( in_array( 'flex-column', $classes ) ) {
					$attributes[] = 'aria-orientation="vertical"';
				}
			}

			ob_start();
		?>
			<<?php echo $elem; ?> class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
			<?php if ( $attributes ) { echo implode( ' ', $attributes ); } ?>
			>
				<?php echo do_shortcode( $content ); ?>
			</<?php echo $elem; ?>>
		<?php
			return ob_get_clean();
		}
	}
}


/**
 * Provides a shortcode for the .nav-item class
 **/
if ( ! class_exists( 'NavItemSC' ) ) {
	class NavItemSC extends ATHENA_SC_Shortcode {
		public
			$command = 'nav-item',
			$name = 'Nav Item',
			$desc = 'Wraps content in an Athena nav-item.',
			$content = true,
			$group = 'Athena Framework - Navs';

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
					'desc'    => 'Specify the type of element to use for the nav item. List items (li) are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'li'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the nav item.',
					'type'    => 'text'
				)
			);
		}

		public function element_type_options() {
			return array(
				'li'  => 'li (list item)',
				'div' => 'div'
			);
		}

		/**
		 * Wraps content inside of an element with class .nav-item
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$styles  = $atts['style'];
			$classes = array_unique( array_merge( array( 'nav-item' ), explode( ' ', $atts['class'] ) ) );
			$elem    = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );

			ob_start();
		?>
			<<?php echo $elem; ?> class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
			>
				<?php echo do_shortcode( $content ); ?>
			</<?php echo $elem; ?>>
		<?php
			return ob_get_clean();
		}
	}
}


/**
 * Provides a shortcode for the .nav-link class
 **/
if ( ! class_exists( 'NavLinkSC' ) ) {
	class NavLinkSC extends ATHENA_SC_Shortcode {
		public
			$command = 'nav-link',
			$name = 'Nav Link',
			$desc = 'Wraps content in an Athena nav-link.',
			$content = true,
			$group = 'Athena Framework - Navs';

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
					'name'    => 'Nav Link URL',
					'type'    => 'text'
				),
				array(
					'param'   => 'new_window',
					'name'    => 'If checked, opens link in a new window.',
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
					'desc'    => 'ID attribute for the link. Must be unique. This value is required if this nav link opens a tab pane.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the nav link.',
					'type'    => 'text'
				),
				array(
					'param'   => 'data_toggle',
					'name'    => 'Data-Toggle',
					'desc'    => 'Type of toggling functionality the nav link should have.',
					'type'    => 'select',
					'options' => $this->data_toggle_options()
				)
			);
		}

		public function data_toggle_options() {
			return array(
				''         => '---',
				'pill'     => 'pill',
				'tab'      => 'tab',
				'dropdown' => 'dropdown'
			);
		}

		/**
		 * Wraps content inside of a link with class .nav-link
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$href       = $atts['href'];
			$new_window = filter_var( $atts['new_window'], FILTER_VALIDATE_BOOLEAN );
			$id         = $atts['id'];
			$styles     = $atts['style'];
			$rel        = $atts['rel'];
			$attributes = array();
			$classes    = array_unique( array_merge( array( 'nav-link' ), explode( ' ', $atts['class'] ) ) );

			// Get any data-attributes, if applicable
			if ( $atts['data_toggle'] && array_key_exists( $atts['data_toggle'], $this->data_toggle_options() ) ) {
				$attributes[] = 'data-toggle="' . $atts['data_toggle'] . '"';
			}

			// Set the link's "role" attribute
			if ( $href == '' || $href == '#' || $atts['data_toggle'] == 'dropdown' ) {
				$attributes[] = 'role="button"';
			}
			else if ( $atts['data_toggle'] == 'tab' || $atts['data_toggle'] == 'pill' ) {
				$attributes[] = 'role="tab"';
			}

			// Set aria attributes as necessary
			if ( $atts['data_toggle'] == 'dropdown' ) {
				$attributes[] = 'aria-haspopup="true"';
				$attributes[] = 'aria-expanded="false"';
			}
			else if (
				( $atts['data_toggle'] == 'tab' || $atts['data_toggle'] == 'pill' )
				&& ( strlen( $href ) > 1 && substr( $href, 0, 1 ) == '#' )
			) {
				$attributes[] = 'aria-controls="' . substr( $href, 1 ) . '"';

				if ( in_array( 'active', $classes ) ) {
					$attributes[] = 'aria-selected="true"';
				}
				else {
					$attributes[] = 'aria-selected="false"';
				}
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
			>
				<?php echo do_shortcode( $content ); ?>
			</a>
		<?php
			return ob_get_clean();
		}
	}
}


/**
 * Provides a shortcode for the .tab-content class
 **/
if ( ! class_exists( 'TabContentSC' ) ) {
	class TabContentSC extends ATHENA_SC_Shortcode {
		public
			$command = 'tab-content',
			$name = 'Nav Tab Content',
			$desc = 'Wraps content in an Athena tab-content wrapper.',
			$content = true,
			$group = 'Athena Framework - Navs';

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
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the tab-content element.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of a div with class .tab-content
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$styles  = $atts['style'];
			$classes = array_unique( array_merge( array( 'tab-content' ), explode( ' ', $atts['class'] ) ) );

			ob_start();
		?>
			<div class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
			>
				<?php echo do_shortcode( $content ); ?>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}


/**
 * Provides a shortcode for the .tab-pane class
 **/
if ( ! class_exists( 'TabPaneSC' ) ) {
	class TabPaneSC extends ATHENA_SC_Shortcode {
		public
			$command = 'tab-pane',
			$name = 'Nav Tab Pane',
			$desc = 'Wraps content in an Athena tab-pane.',
			$content = true,
			$group = 'Athena Framework - Navs';

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
					'desc'    => 'A unique ID for the tab pane. Required for the pane to be properly activated when toggled.',
					'type'    => 'text'
				),
				array(
					'param'   => 'labelledby',
					'name'    => 'Labelled By (ARIA)',
					'desc'    => 'ID of an element that provides label or title text for the tab pane. In most cases, this value should be the ID of the pane\'s corresponding [nav-link]. Required for accessibility purposes.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the tab-pane element.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of a div with class .tab-pane
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$id         = $atts['id'];
			$labelledby = $atts['labelledby'];
			$styles     = $atts['style'];
			$classes    = array_unique( array_merge( array( 'tab-pane' ), explode( ' ', $atts['class'] ) ) );

			ob_start();
		?>
			<div class="<?php echo implode( ' ', $classes ); ?>" role="tabpanel" id="<?php echo $id; ?>"
			<?php if ( $labelledby ) { echo 'aria-labelledby="' . $labelledby . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
			>
				<?php echo do_shortcode( $content ); ?>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}
