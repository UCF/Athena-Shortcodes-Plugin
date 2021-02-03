<?php
/*
Plugin Name: Athena Shortcodes
Version: 0.5.3
Author: UCF Web Communications
Description: Provides shortcodes for use with the Athena-Framework.
GitHub Plugin URI: https://github.com/UCF/Athena-Shortcodes-Plugin/
Tags: athena-framework,shortcodes
*/
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ATHENA_SC__PLUGIN_FILE', __FILE__ );
define( 'ATHENA_SC__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! function_exists( 'athena_sc_get_cache_bust' ) ) {
	function athena_sc_get_cache_bust() {
		$plugin_cache_bust = '';
		$plugin_data = function_exists( 'get_plugin_data' ) ? get_plugin_data( ATHENA_SC__PLUGIN_FILE ) : array();

		if ( WP_DEBUG === true ) {
			$plugin_cache_bust = date( 'YmdHis' );
		}
		else {
			if ( ! empty( $plugin_data ) && isset( $plugin['Version'] ) ) {
				$plugin_cache_bust = $plugin_data['Version'];
			}
			else {
				$plugin_cache_bust = date( 'YmdH' );
			}
		}

		return 'v=' . $plugin_cache_bust;
	}
}

define( 'ATHENA_SC__CACHE_BUST', athena_sc_get_cache_bust() );


// Shortcode files
include_once ATHENA_SC__PLUGIN_DIR . 'includes/class-shortcode.php';
include_once ATHENA_SC__PLUGIN_DIR . 'shortcodes/shortcodes.php';

include_once ATHENA_SC__PLUGIN_DIR . 'includes/athena-sc-config.php';
include_once ATHENA_SC__PLUGIN_DIR . 'includes/athena-sc-shortcode-config.php';
include_once ATHENA_SC__PLUGIN_DIR . 'includes/athena-sc-tinymce-config.php';
include_once ATHENA_SC__PLUGIN_DIR . 'includes/athena-sc-embeds.php';


if ( ! function_exists( 'athena_sc_plugin_activated' ) ) {
	function athena_sc_plugin_activated() {
		return ATHENA_SC_Config::add_options();
	}

	register_activation_hook( ATHENA_SC__PLUGIN_FILE, 'athena_sc_plugin_activated' );
}

if ( ! function_exists( 'athena_sc_plugin_deactivated' ) ) {
	function athena_sc_plugin_deactivated() {
		return ATHENA_SC_Config::delete_options();
	}

	register_deactivation_hook( ATHENA_SC__PLUGIN_FILE, 'athena_sc_plugin_deactivated' );
}

if ( ! function_exists( 'athena_sc_init' ) ) {
	function athena_sc_init() {
		// Register settings and options.
		add_action( 'admin_init', array( 'ATHENA_SC_Config', 'settings_init' ) );
		add_action( 'admin_menu', array( 'ATHENA_SC_Config', 'add_options_page' ) );

		// Apply custom formatting to shortcode attributes and options.
		ATHENA_SC_Config::add_option_filters();

		// Add our preconfigured shortcodes.
		add_action( 'athena_sc_add_shortcode', array( 'ATHENA_SC_Shortcode_Config', 'athena_sc_add_shortcode' ), 10, 1 );
		// Update the_content to strip excess <p></p> and <br> insertion around
		// Athena shortcodes.
		add_filter( 'the_content', array( 'ATHENA_SC_Shortcode_Config', 'format_shortcode_output' ), 10, 1 );
		// Hook into ACF's custom field filtering hooks to strip excess
		// <p></p> and <br> insertion around shortcodes in WYSIWYG fields.
		if ( class_exists( 'acf' ) ) {
			add_filter( 'acf/format_value/type=wysiwyg', array( 'ATHENA_SC_Shortcode_Config', 'format_acf_wysiwyg_output' ), 99, 3 );
		}
		// Register our shortcodes.
		add_action( 'init', array( 'ATHENA_SC_Shortcode_Config', 'register_shortcodes' ) );

		// If the `WP-Shortcode-Interface` plugin is installed, add the definitions.
		if ( class_exists( 'WP_SCIF_Config' ) ) {
			add_action( 'wp_scif_add_shortcode', array( 'ATHENA_SC_Shortcode_Config', 'register_shortcodes_interface' ), 10, 1 );
			add_action( 'wp_scif_get_preview_stylesheets', array( 'ATHENA_SC_Shortcode_Config', 'register_shortcodes_preview_styles' ), 10, 1 );
		}

		// Allow shortcodes within text widgets.
		add_filter( 'widget_text', 'do_shortcode' );
	}

	add_action( 'plugins_loaded', 'athena_sc_init' );
}

if ( ! function_exists( 'athena_sc_tinymce_init' ) ) {
	function athena_sc_tinymce_init() {
		// Add backward compatibility for the existing
		// `athena_sc_enable_tinymce_formatting` hook
		// for enabling TinyMCE-specific settings.
		if ( has_filter( 'athena_sc_enable_tinymce_formatting' ) ) {
			add_filter( 'pre_option_athena_sc_enable_tinymce_formatting', function( $value ) {
				return apply_filters( 'athena_sc_enable_tinymce_formatting', false );
			} );
		}

		// Register necessary actions/filters if TinyMCE options are enabled.
		$options_enabled = get_option( 'athena_sc_enable_tinymce_formatting' );
		if ( $options_enabled ) {
			// Enqueue TinyMCE styles.
			add_editor_style( plugins_url( 'static/css/athena-editor-styles.min.css?' . ATHENA_SC__CACHE_BUST, ATHENA_SC__PLUGIN_FILE ) );
			// Enable custom TinyMCE formats.
			add_filter( 'mce_buttons_2', array( 'ATHENA_SC_TinyMCE_Config', 'enable_formats' ) );
			// Register custom formatting options with TinyMCE.
			add_action( 'tiny_mce_before_init', array( 'ATHENA_SC_TinyMCE_Config', 'register_settings' ) );

			// Override classes added to <img> tags generated by WordPress.
			add_filter( 'get_image_tag_class', array( 'ATHENA_SC_TinyMCE_Config', 'format_image_output_classes' ), 10, 4 );
			// Override the default caption shortcode to apply Athena classes.
			add_filter( 'img_caption_shortcode', array( 'ATHENA_SC_TinyMCE_Config', 'format_caption_shortcode' ), 10, 3 );
		}
	}

	add_action( 'init', 'athena_sc_tinymce_init' );
}

if ( ! function_exists( 'athena_sc_responsive_videos_init' ) ) {
	function athena_sc_responsive_videos_init() {
		// Register actions/filters for responsive videos if enabled.
		$responsive_embeds_enabled = get_option( 'athena_sc_enable_responsive_embeds' );
		if ( $responsive_embeds_enabled ) {
			// Enable responsive embed wrappers around oEmbed content.
			add_filter( 'oembed_dataparse', array( 'ATHENA_SC_Embed_Config', 'enable_responsive_oembeds' ), 10, 3 );
			// Enable responsive videos added using the [video] shortcode.
			add_filter( 'wp_video_shortcode', array( 'ATHENA_SC_Embed_Config', 'enable_responsive_videos' ), 10, 5 );
		}
	}

	add_action( 'init', 'athena_sc_responsive_videos_init' );
}

if ( ! function_exists( 'athena_sc_image_dims_init' ) ) {
	function athena_sc_image_dims_init() {
		// Register actions/filters that remove width/height attributes
		// from <img> tags, if enabled.
		$attr_removal_enabled = get_option( 'athena_sc_remove_image_dims' );
		if ( $attr_removal_enabled ) {
			// Modify generated markup for images in post content.
			add_filter( 'the_content', array( 'ATHENA_SC_TinyMCE_Config', 'format_image_output' ) );
		}
	}

	add_action( 'init', 'athena_sc_image_dims_init' );
}

?>
