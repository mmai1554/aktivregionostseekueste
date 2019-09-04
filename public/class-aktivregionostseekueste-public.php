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
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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

	public function register_shortcode_mi_pdf() {
		// show list of PDF
		add_shortcode( 'mi_pdf', function () {
			$template = '<div class="mi-downloads"><h3>Downloads<span class="uabb-icon"><i class="fi-download"></i></span></h3><div>%s</div></div>';
			$mi_li = '<li><a href="%s" target="_self">%s (%s)</a></li>';
			if(!have_rows( 'pdf-liste' )) {
				return '';
			}
			$html = [];
			$html[] = '<ul>';
			while ( have_rows( 'pdf-liste' ) ) {
				the_row();
				// $titel    = get_sub_field( 'broschuere_titel' );
				$id    = get_sub_field( 'pdf' );
				$url   = wp_get_attachment_url( $id );
				$title = basename( get_attached_file( $id ) );
				if ( ! strlen( $title ) ) {
					$title = $url;
				}
				$filesize = filesize( get_attached_file( $id ) );
				$filesize = size_format($filesize, 2);
				$render     = sprintf(
					$mi_li,
					$url,
					$title,
					$filesize
				);
				$html[] =  $render;
			}
			$html[] = '</ul>';
			$list =  implode("\n", $html);
			return sprintf($template, $list);
		} );
	}

}