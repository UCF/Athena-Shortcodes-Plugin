<?php
/**
 * Provides a shortcode for the .container class
 **/
if ( ! class_exists( 'ContainerSC' ) ) {
	class ContainerSC extends ATHENA_SC_Shortcode {
		public
			$command = 'container',
			$name = 'Container',
			$desc = 'Wraps content in an Athena container.',
			$content = true,
			$group = 'Athena Framework - Grid System';

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
					'param'   => 'type',
					'name'    => 'Container Type',
					'desc'    => 'Specify the type of container to use.  Standard, fixed-width .container\'s are used by default.',
					'type'    => 'select',
					'options' => array(
						'container' => 'Fixed-width per breakpoint (.container)',
						'container-fluid' => 'Fluid container (.container-fluid)'
					)
				),
				array(
					'param'   => 'class',
					'name'    => 'CSS Classes',
					'type'    => 'text'
				),
				array(
					'param'   => 'id',
					'name'    => 'ID',
					'desc'    => 'ID attribute for the container. Must be unique.',
					'type'    => 'text'
				),
				array(
					'param'   => 'style',
					'name'    => 'Inline Styles',
					'desc'    => 'Any additional styles for the container.',
					'type'    => 'text'
				)
			);
		}

		/**
		 * Wraps content inside of a div with class .container
		 **/
		public function callback( $atts, $content='' ) {
			$atts = shortcode_atts( $this->defaults(), $atts );

			$classes = array();
			$classes[] = $atts['type'] ?: 'container';
			if ( $atts['class'] ) {
				$classes[] = $atts['class'];
			}
			$styles = $atts['style'] ?: false;
			$id     = $atts['id'] ?: false;

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
