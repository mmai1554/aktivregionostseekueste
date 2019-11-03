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

	const PAGE_SHOW_MAP = 1352;

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
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/aktivregionostseekueste-public.css', array(), $this->version, 'all' );
		if ( is_page( self::PAGE_SHOW_MAP ) ) {
			wp_enqueue_style( 'Leaflet', 'https://unpkg.com/leaflet@1.5.1/dist/leaflet.css' );
		}

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

		if ( is_page( self::PAGE_SHOW_MAP ) ) {
			wp_enqueue_script( 'leafletjs', 'https://unpkg.com/leaflet@1.5.1/dist/leaflet.js', [], null, false );
		}
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aktivregionostseekueste-public.js', [ 'jquery' ], $this->version, true );

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
			$terms = get_terms( [
				'taxonomy' => 'projektkategorie',
			] );
			if ( $terms instanceof WP_Error ) {
				return '';
			}
			$projektkategorie = $terms[0];
			$url              = get_field( 'url', $projektkategorie->term_id );

			return $url;
		} );
		$this->register_shortcode_mnc_openstreetmap();

	}

	protected function renderMarkerAsHTML( WP_Post $post, $lat, $lng ) {
		$aline       = function ( $left, $right ) {
			return $left . ' ' . $right . '<br>';
		};
		$terms       = get_the_terms( $post->ID, Aktivregionostseekueste_Admin::TAX_PROJEKTTRAEGER );
		$bezeichnung = $post->post_title;
		$url         = get_the_permalink( $post );
		$projektnr   = get_field( 'projektnr', $post );
		$quote       = get_field( 'forderquote', $post );
		$summe       = get_field( 'fordersumme', $post );
		$zeitraum    = get_field( 'umsetzungszeitraum', $post );
		$html        = '<div class="marker" data-lat="' . $lat . '" data-lng="' . $lng . '" data-label="' . $bezeichnung . '">';
		$html        .= '<a href="' . $url . '"><p><strong><span>' . $projektnr . '</span> ' . $bezeichnung . '</strong></p></a>';
		$html        .= '<div>';
		if ( is_array( $terms ) && count( $terms ) > 0 ) {
			foreach ( $terms as $term ) {
				$html .= $aline( 'Träger:', $term->name );
			}
		}
		$html        .= $aline( 'Fördersumme:', $summe );
		$html        .= $aline( 'Zeitraum:', $zeitraum );
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	protected function register_shortcode_mnc_openstreetmap() {
		add_shortcode( 'mnc_openstreetmap', function ( $atts ) {
			global $post;
			$args         = [
				'numberposts'    => - 1,
				'post_type'      => Aktivregionostseekueste_Admin::CPT_AKTIVREGION_PROJEKT,
				'tax_query'      => [
					[
						'taxonomy' => Aktivregionostseekueste_Admin::TAX_PROJEKTKATEGORIE,
						'field'    => 'slug',
						'terms'    => 'abgeschlossene-projekte-2015-2023',
					]
				],
				'posts_per_page' => - 1,
			];
			$objQuery     = new WP_Query( $args );
			$draw_element = function ( $inner_html ) {
				return '<div id="markers">' . $inner_html . '</div>';
			};

			if ( $objQuery->have_posts() ) {
				$arrMarker = [];
				while ( $objQuery->have_posts() ) {
					$objQuery->the_post();
//					$id = $objQuery->post->ID;
					$bezeichnung = get_the_title();
					$arrL        = explode( ' ', get_field( 'ansprechpartner' ) );
					$label       = '';
					foreach ( $arrL as $word ) {
						$label .= $word[0];
					}
					$address = get_field( 'geolocation' );
					if ( null !== $address && count( $address ) > 0 ) {
						$arrMarker[] = $this->renderMarkerAsHTML( $post, $address['lat'], $address['lng'] );
					}
				}

				return '<div id="mimap"></div>' . $draw_element( implode( '', $arrMarker ) );
			} else {
				return '';
			}
		} );
	}

	public function initLeaflet() {
		wp_register_style( 'Leaflet', 'https://unpkg.com/leaflet@1.5.1/dist/leaflet.css' );
		if ( is_page( 1352 ) ) {
			wp_enqueue_style( 'leaflet' );
			wp_enqueue_script( 'leafletjs', 'https://unpkg.com/leaflet@1.5.1/dist/leaflet.js', [], null, true );
		}
	}


}
