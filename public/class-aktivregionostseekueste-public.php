<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://mainetcare.de
 * @since      1.0.0
 *
 * @package    Aktivregionostseekueste
 * @subpackage Aktivregionostseekueste/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Aktivregionostseekueste
 * @subpackage Aktivregionostseekueste/public
 * @author     MaiNetCare <m.mai@mainetcare.de>
 */
class Aktivregionostseekueste_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aktivregionostseekueste_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aktivregionostseekueste_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/aktivregionostseekueste-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aktivregionostseekueste_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aktivregionostseekueste_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aktivregionostseekueste-public.js', array( 'jquery' ), $this->version, false );

	}

	// Add Shortcode


	public function register_shortcodes() {
		// wraps the given URL of acf field url around content,
		// usage: [mnc_field]my website
		add_shortcode( 'mnc_url', function ( $atts, $content = null ) {
			global $post;
			// Attributes
			$atts = shortcode_atts(
				array(),
				$atts,
				'mnc_url'
			);
			$url  = get_field( 'url', $post );
			if ( ! strlen( $url ) ) {
				return '';
			}
			$o = '<a href="' . $url . '" target="_blank">';
			if ( is_null( $content ) ) {
				return $o . 'website' . '</a>';
			}
			// secure output by executing the_content filter hook on $content
			$o .= apply_filters( 'the_content', $content );
			// run shortcode parser recursively
			$o .= do_shortcode( $content );
			return $o . $content . '</a>';
		} );
		add_shortcode( 'mnc_projektkat_url', function ( $atts ) {
			global $post;
			// Attributes
			$atts = shortcode_atts(
				array(),
				$atts,
				'mnc_url'
			);
			// get Taxonomy:
			$terms = get_terms([
				'taxonomy' => 'projektkategorie',
			]);
			if ($terms instanceof  WP_Error) {
				return '';
			}
			$projektkategorie = $terms[0];
			$url  = get_field( 'url', $projektkategorie->term_id );
			return $url;
		} );
	}


}
