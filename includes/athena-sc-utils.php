<?php
/**
 * Various utility classes that help with things.
 */
if ( ! function_exists( 'athena_sc_wpscif_convert' ) ) {
	function athena_sc_wpscif_convert( $fields ) {
		$shortcake_fields = array();

		foreach( $fields as $field ) {
			if ( ! isset( $field['param'] ) ) {
				// There's no param field. Break out of the loop.
				continue;
			}

			// Our converted field
			$shortcake_field = array(
				'attr' => $field['param']
			);

			$shortcake_field['label'] = isset( $field['name'] ) ?
				$field['name'] :
				$field['param'];

			$shortcake_field['type'] = isset( $field['type'] ) ?
				$field['type'] :
				'text';

			if ( isset( $field['desc'] ) ) {
				$shortcake_field['description'] = $field['desc'];
			}

			if ( isset( $field['default'] ) ) {
				$shortcake_field['default'] = $field['default'];
			}

			if (
				in_array( $field['type'], ['select', 'radio', 'checkbox-list'] ) &&
				isset( $field['options'] )
			) {
				$shortcake_field['options'] = $field['options'];
			}

			$shortcake_fields[] = $shortcake_field;
		}

		return $shortcake_fields;
	}
}
