<?php
/**
 * Provides a shortcode for the .modal, .modal-dialog, and .modal-content classes
 **/
if ( ! class_exists( 'ModalSC' ) ) {
	class ModalSC extends ATHENA_SC_Shortcode {
		public
			$command = 'modal',
			$name = 'Modal',
			$desc = 'Wraps content in an Athena modal.',
			$content = true,
			$group = 'Athena Framework - Modals';

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
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'Required. ID attribute for the outer .modal div. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'labelledby',
					'name'    => 'Labelled By (ARIA)',
					'desc'    => 'ID of an element that provides label or title text for the modal. In most cases, this will be the ID of an inner [modal-title]. Required for accessibility purposes.',
					'type'    => 'text'
				),
				array(
					'param'   => 'size',
					'name'    => 'Modal Size',
					'desc'    => 'Specify an optional size for the modal.',
					'type'    => 'select',
					'options' => $this->size_options(),
					'default' => ''
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'desc'    => 'Any additional CSS classes for the outer .modal div.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the outer .modal div.',
					'type'    => 'text'
				)
			);
		}

		public function size_options() {
			return array(
				''         => 'Standard size',
				'modal-sm' => 'Small',
				'modal-lg' => 'Large',
			);
		}

		/**
		 * Wraps content inside of modal wrapper divs
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'modal fade' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$styles     = $atts['style'] ?: false;
			$id         = $atts['id'];
			$labelledby = $atts['labelledby'];
			$modal_dialog_classes = 'modal-dialog';
			if ( $atts['size'] && !empty( $atts['size'] ) && array_key_exists( $atts['size'], $this->size_options() ) ) {
				$modal_dialog_classes .= ' ' . $atts['size'];
			}

			ob_start();
		?>
			<div class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $labelledby ) { echo 'aria-labelledby="' . $labelledby . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
			role="dialog"
			tabindex="-1"
			aria-hidden="true">
				<div class="<?php echo $modal_dialog_classes; ?>" role="document">
					<div class="modal-content">
						<?php echo do_shortcode( $content ); ?>
					</div>
				</div>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}

/**
 * Provides a shortcode for the .modal-header class
 **/
if ( ! class_exists( 'ModalHeaderSC' ) ) {
	class ModalHeaderSC extends ATHENA_SC_Shortcode {
		public
			$command = 'modal-header',
			$name = 'Modal Header',
			$desc = 'Wraps content in an Athena modal header.',
			$content = true,
			$group = 'Athena Framework - Modals';

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
					'desc'    => 'ID attribute for the modal header. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the modal header.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of an element with class .modal-header
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'modal-header' );
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
 * Provides a shortcode for the .modal-footer class
 **/
if ( ! class_exists( 'ModalFooterSC' ) ) {
	class ModalFooterSC extends ATHENA_SC_Shortcode {
		public
			$command = 'modal-footer',
			$name = 'Modal Footer',
			$desc = 'Wraps content in an Athena modal footer.',
			$content = true,
			$group = 'Athena Framework - Modals';

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
					'desc'    => 'ID attribute for the modal footer. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the modal footer.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of an element with class .modal-footer
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'modal-footer' );
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
 * Provides a shortcode for the .modal-body class
 **/
if ( ! class_exists( 'ModalBodySC' ) ) {
	class ModalBodySC extends ATHENA_SC_Shortcode {
		public
			$command = 'modal-body',
			$name = 'Modal Body',
			$desc = 'Wraps content in an Athena modal body.',
			$content = true,
			$group = 'Athena Framework - Modals';

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
					'desc'    => 'ID attribute for the modal body. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the modal body.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of an element with class .modal-body
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'modal-body' );
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
 * Provides a shortcode for the .modal-title class
 **/
if ( ! class_exists( 'ModalTitleSC' ) ) {
	class ModalTitleSC extends ATHENA_SC_Shortcode {
		public
			$command = 'modal-title',
			$name = 'Modal Title',
			$desc = 'Wraps content in an Athena modal title.',
			$content = true,
			$group = 'Athena Framework - Modals';

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
					'desc'    => 'Specify the type of element to use for the modal title. Divs are used by default.',
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
					'desc'    => 'ID attribute for the modal title. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the modal title.',
					'type'    => 'text'
				)
			);
		}

		public function element_type_options() {
			return array(
				'div'    => 'div',
				'span'   => 'span',
				'h1'     => 'h1',
				'h2'     => 'h2',
				'h3'     => 'h3',
				'h4'     => 'h4',
				'h5'     => 'h5',
				'h6'     => 'h6',
				'strong' => 'strong'
			);
		}

		/**
		 * Wraps content inside of an element with class .modal-title
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'modal-title' );
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
 * Provides a shortcode that generates an arbitrary toggle for a modal.
 **/
if ( ! class_exists( 'ModalToggleSC' ) ) {
	class ModalToggleSC extends ATHENA_SC_Shortcode {
		public
			$command = 'modal-toggle',
			$name = 'Modal Toggle',
			$desc = 'Creates a new link or button that displays a modal.',
			$content = true,
			$preview = true,
			$group = 'Athena Framework - Modals';

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
					'desc'    => 'Specify the type of element to use for the modal toggle. Standard links are used by default.',
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
					'name'    => 'Target Modal',
					'desc'    => 'ID or class of the modal to open. Include the ID or class prefix, e.g. ".my-class" or "#my-id".',
					'type'    => 'text'
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
		 * Wraps content inside of a modal toggler
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$id         = $atts['id'];
			$styles     = $atts['style'];
			$attributes = array( 'data-toggle="modal"' );
			$classes    = $atts['class'] ?: false;
			$elem       = in_array( $atts['element_type'], $this->element_type_options() ) ? $atts['element_type'] : $this->defaults( 'element_type' );

			// Get applicable attributes
			if ( $atts['target'] ) {
				$attributes[] = 'data-target="' . $atts['target'] . '"';
			}

			// Set the toggle's "role" attribute if it is a link.
			if ( $elem == 'a' ) {
				$attributes[] = 'role="button"';
				$attributes[] = 'tabindex="0"';
			}

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

