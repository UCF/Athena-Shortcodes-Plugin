<?php
/**
 * Provides a shortcode that generates a close button.
 **/
if ( ! class_exists( 'CloseSC' ) ) {
	class CloseSC extends ATHENA_SC_Shortcode {
		public
			$command = 'close',
			$name = 'Close Button',
			$desc = 'Creates a new button that closes a parent component.',
			$content = false,
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
					'param'   => 'data_dismiss',
					'name'    => 'Data-Dismiss',
					'desc'    => 'Parent component that should be dismissed/closed when the button is clicked.',
					'type'    => 'select',
					'options' => $this->data_dismiss_options()
				)
			);
		}

		public function data_dismiss_options() {
			return array(
				''         => '---',
				'alert'    => 'alert',
				'modal'    => 'modal'
			);
		}

		/**
		 * Creates a new .close button
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array( 'close' );
			if ( $atts['class'] ) {
				$classes = array_unique( array_merge( $classes, explode( ' ', $atts['class'] ) ) );
			}
			$id         = $atts['id'];
			$styles     = $atts['style'];
			$dismiss    = array_key_exists( $atts['data_dismiss'], $this->data_dismiss_options() ) ? $atts['data_dismiss'] : false;

			ob_start();
		?>
			<button class="<?php echo implode( ' ', $classes ); ?>"
			<?php if ( $id ) { echo 'id="' . $id . '"'; } ?>
			<?php if ( $styles ) { echo 'style="' . $styles . '"'; } ?>
			<?php if ( $dismiss ) { echo 'data-dismiss="' . $dismiss . '"'; } ?>
			type="button" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<?php
			return ob_get_clean();
		}
	}
}

