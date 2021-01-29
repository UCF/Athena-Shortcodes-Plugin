<?php
/**
 * Provides a shortcode for the .alert class
 **/
if ( ! class_exists( 'AlertSC' ) ) {
	class AlertSC extends ATHENA_SC_Shortcode {
		public
			$command = 'alert',
			$name = 'Alert',
			$desc = 'Wraps content in an Athena alert.',
			$content = true,
			$preview = true,
			$group = 'Athena Framework - Alerts';

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
					'desc'    => 'ID attribute for the alert. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the alert.',
					'type'    => 'text'
				),
				array(
					'param'   => 'role_alert',
					'name'    => 'Use semantic role="alert"',
					'desc'    => 'If checked, the alert will have role="alert" applied to it for accessibility purposes. Leave this box unchecked unless this alert displays content that requires <em>immediate attention from the user</em>, such as a form submission error.',
					'type'    => 'checkbox',
					'default' => false
				)
			);
		}

		/**
		 * Wraps content inside of a div with class .alert
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'alert' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles     = $atts['style'] ?: false;
			$id         = $atts['id'];
			$attributes = array();
			if ( $atts['role_alert'] ) {
				$attributes[] = 'role="alert"';
			}

			ob_start();
		?>
			<div class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
			<?php if ( $attributes ) { echo implode( ' ', $attributes ); } ?>>
				<?php echo do_shortcode( $content ); ?>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}


/**
 * Provides a shortcode for the .alert-link class.
 **/
if ( ! class_exists( 'AlertLinkSC' ) ) {
	class AlertLinkSC extends ATHENA_SC_Shortcode {
		public
			$command = 'alert-link',
			$name = 'Alert Link',
			$desc = 'Creates a new link styled as an Athena alert link.',
			$content = true,
			$preview = true,
			$group = 'Athena Framework - Alerts';

		/**
		 * Returns the shortcode's fields.
		 *
		 * @author Jim Barnes
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
					'desc'    => 'Separate each class with a single space. Refer to the Athena Framework documentation for available classes.',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the link. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the link.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of a link with class .alert-link
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes      = array( 'alert-link' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$href         = $atts['href'];
			$new_window   = filter_var( $atts['new_window'], FILTER_VALIDATE_BOOLEAN );
			$id           = $atts['id'];
			$styles       = $atts['style'];
			$rel          = $atts['rel'];
			$attributes   = array();

			if ( !$href ) {
				$attributes[] = 'tabindex="0"';
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
			$retval = ob_get_clean();
			return trim( $retval );
		}
	}
}


/**
 * Provides a shortcode for the .alert-heading class
 **/
if ( ! class_exists( 'AlertHeadingSC' ) ) {
	class AlertHeadingSC extends ATHENA_SC_Shortcode {
		public
			$command = 'alert-heading',
			$name = 'Alert Heading',
			$desc = 'Wraps content in an Athena alert heading.',
			$content = true,
			$group = 'Athena Framework - Alerts';

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
					'desc'    => 'Specify the type of element to use for the alert heading. Divs are used by default.',
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
					'desc'    => 'ID attribute for the alert heading. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the alert heading.',
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
		 * Wraps content inside of an element with class .alert-heading
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'alert-heading' );
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
