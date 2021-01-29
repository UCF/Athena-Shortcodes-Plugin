<?php
/**
 * Provides a shortcode for the .collapse class
 **/
if ( ! class_exists( 'CollapseSC' ) ) {
	class CollapseSC extends ATHENA_SC_Shortcode {
		public
			$command = 'collapse',
			$name = 'Collapse',
			$desc = 'Wraps content in an Athena collapsible element.',
			$content = true,
			$group = 'Athena Framework - Collapse';

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
					'desc'    => 'Specify the type of element to use for the collapsible. Divs are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'div'
				),
				array(
					'param'   => 'data_parent',
					'name'    => 'Parent Accordion',
					'desc'    => 'If this collapsible is part of an accordion, specify the ID of the parent accordion here. Include the "#", e.g. "#my-id".',
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
					'desc'    => 'ID attribute for the collapsible. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the collapsible.',
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
		 * Wraps content inside of an element with class .collapse
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'collapse' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles     = $atts['style'] ?: false;
			$id         = $atts['id'];
			$elem       = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );
			$parent     = $atts['data_parent'];
			$attributes = array();

			// Add applicable attributes
			if ( $parent ) {
				$attributes[] = 'data-parent="' . $parent . '"';
				$attributes[] = 'role="tabpanel"';
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
 * Provides a shortcode for the .accordion class
 **/
if ( ! class_exists( 'AccordionSC' ) ) {
	class AccordionSC extends ATHENA_SC_Shortcode {
		public
			$command = 'accordion',
			$name = 'Accordion',
			$desc = 'Wraps content in an Athena accordion.',
			$content = true,
			$group = 'Athena Framework - Collapse';

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
					'desc'    => 'Specify the type of element to use for the accordion. Divs are used by default.',
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
					'desc'    => 'Required. ID attribute for the accordion. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the accordion.',
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
		 * Wraps content inside of an element with class .accordion
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'accordion' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles  = $atts['style'] ?: false;
			$id      = $atts['id'];
			$elem    = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );

			ob_start();
		?>
			<<?php echo $elem; ?> class="<?php echo implode( ' ', $classes ); ?>" role="tablist"
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
 * Provides a shortcode that generates an arbitrary toggle for a collapse component.
 **/
if ( ! class_exists( 'CollapseToggleSC' ) ) {
	class CollapseToggleSC extends ATHENA_SC_Shortcode {
		public
			$command = 'collapse-toggle',
			$name = 'Collapse Toggle',
			$desc = 'Creates a new link or button that displays a collapsible component.',
			$content = true,
			$preview = true,
			$group = 'Athena Framework - Collapse';

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
					'desc'    => 'Specify the type of element to use for the collapse toggle. Standard links are used by default.',
					'type'    => 'select',
					'options' => $this->element_type_options(),
					'default' => 'a'
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the toggle. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the toggle.',
					'type'    => 'text'
				),
				array(
					'param'   => 'target',
					'name'    => 'Target Collapse',
					'desc'    => 'ID or class of the collapsible to open. Include the ID or class prefix, e.g. ".my-class" or "#my-id".',
					'type'    => 'text'
				),
				array(
					'param'   => 'is_expanded',
					'name'    => 'Is Expanded by Default',
					'desc'    => 'If checked, the toggler will represent a collapsible component that is already expanded by default. In most cases, this box should remain unchecked.',
					'type'    => 'checkbox',
					'default' => false
				)
			);
		}

		public function element_type_options() {
			return array(
				'a'      => 'Standard link (a)',
				'button' => 'Button'
			);
		}

		/**
		 * Wraps content inside of a collapse toggler
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$id         = $atts['id'];
			$styles     = $atts['style'];
			$attributes = array( 'data-toggle="collapse"' );
			$classes    = $atts['class'] ?: false;
			$elem       = array_key_exists( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );
			$expanded   = $atts['is_expanded'] ?: $this->defaults( 'is_expanded' );

			// Get applicable attributes
			if ( $atts['target'] ) {
				$attributes[] = 'data-target="' . $atts['target'] . '"';

				// If an element ID is provided as the target, set the
				// aria-controls attribute.
				if ( substr( $atts['target'], 0, 1 ) == '#' ) {
					$attributes[] = 'aria-controls="' . substr( $atts['target'], 1, strlen( $atts['target'] ) - 1 ) . '"';
				}
			}

			// Set the toggle's "role" attribute if it is a link.
			if ( $elem == 'a' ) {
				$attributes[] = 'role="button"';
				$attributes[] = 'tabindex="0"';
			}

			// Set aria-expanded
			$attributes[] = 'aria-expanded="' . $expanded . '"';

			ob_start();
		?>
			<<?php echo $elem; ?>
			<?php if ( $classes ) { echo 'class="'. $classes . '"'; } ?>
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
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

