<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mainetcare.de
 * @since      1.0.0
 *
 * @package    Aktivregionostseekueste
 * @subpackage Aktivregionostseekueste/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Aktivregionostseekueste
 * @subpackage Aktivregionostseekueste/admin
 * @author     MaiNetCare <m.mai@mainetcare.de>
 */
class Aktivregionostseekueste_Admin {

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

	const TEXT_DOMAIN = 'aktivregion';

	const CPT_AKTIVREGION_PROJEKT = 'aroprojekte';
	const TAX_PROJEKTKATEGORIE = 'projektkategorien';
	const TAX_ARBEITSKREISE = 'arbeitskreise';
	const TAX_PROJEKTTRAEGER = 'projekttraeger';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/aktivregionostseekueste-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aktivregionostseekueste-admin.js', array( 'jquery' ), $this->version, false );

	}

	function register_cpts() {
		$this->register_aroprojekte();
		$this->register_cpt_termine();
	}


	function register_cpt_termine() {

		$labels = array(
			'name'                  => _x( 'Termine', 'Post Type General Name', self::TEXT_DOMAIN ),
			'singular_name'         => _x( 'Termin', 'Post Type Singular Name', self::TEXT_DOMAIN ),
			'menu_name'             => __( 'Termine', self::TEXT_DOMAIN ),
			'name_admin_bar'        => __( 'Termine', self::TEXT_DOMAIN ),
			'archives'              => __( 'Terminarchiv', self::TEXT_DOMAIN ),
			'parent_item_colon'     => __( 'Parent Item:', self::TEXT_DOMAIN ),
			'all_items'             => __( 'Alle Termine', self::TEXT_DOMAIN ),
			'add_new_item'          => __( 'Neuer Termin', self::TEXT_DOMAIN ),
			'add_new'               => __( 'Neuen Termin hinzufügen', self::TEXT_DOMAIN ),
			'new_item'              => __( 'Neuer Termin', self::TEXT_DOMAIN ),
			'edit_item'             => __( 'Termin  bearbeiten', self::TEXT_DOMAIN ),
			'update_item'           => __( 'Termin aktualisieren', self::TEXT_DOMAIN ),
			'view_item'             => __( 'Termin anschauen', self::TEXT_DOMAIN ),
			'search_items'          => __( 'Termin durchsuchen', self::TEXT_DOMAIN ),
			'not_found'             => __( 'Termin nicht gefunden', self::TEXT_DOMAIN ),
			'not_found_in_trash'    => __( 'Termin nicht im Papierkorb gefunden', self::TEXT_DOMAIN ),
			'featured_image'        => __( 'Featured Image', self::TEXT_DOMAIN ),
			'set_featured_image'    => __( 'Set featured image', self::TEXT_DOMAIN ),
			'remove_featured_image' => __( 'Remove featured image', self::TEXT_DOMAIN ),
			'use_featured_image'    => __( 'Use as featured image', self::TEXT_DOMAIN ),
			'insert_into_item'      => __( 'Zu Termin anhängen', self::TEXT_DOMAIN ),
			'uploaded_to_this_item' => __( 'Zu Termin hochgeladen', self::TEXT_DOMAIN ),
			'items_list'            => __( 'Terlinliste', self::TEXT_DOMAIN ),
			'items_list_navigation' => __( 'Termine list navigation', self::TEXT_DOMAIN ),
			'filter_items_list'     => __( 'Filter Terminliste', self::TEXT_DOMAIN ),
		);
		// dashicons-calendar-alt
		$args = array(
			'label'               => __( 'Termine', self::TEXT_DOMAIN ),
			'description'         => __( 'Termine als Liste darstellen', self::TEXT_DOMAIN ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-calendar-alt',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'termin', $args );

		// Admin Columns:
		add_filter( 'manage_termin_posts_columns', function ( $columns ) {
			return array_merge( $columns, array(
				'termin_datum' => __( 'Termin' ),
			) );
		} );
		add_action( 'manage_termin_posts_custom_column', function ( $column, $post_id ) {
			switch ( $column ) {
				case 'termin_datum':
					echo get_field( 'termin_datum', $post_id, true );
					break;
			}
		}, 10, 2 );

	}


	function modify_admin_columns() {
		// Add Projektnummer to Column:

		add_filter( 'manage_aroprojekte_posts_columns', function ( $columns ) {
			return
				[
					'cb'                         => '<input type="checkbox" />',
					'projektnr'                  => __( 'ProjektNr' ),
					'title'                      => __( 'Titel' ),
					'taxonomy-projektkategorien' => __( 'Projektkategorien' ),
					'taxonomy-arbeitskreise'     => __( 'Arbeitskreise' ),
					'taxonomy-projekttraeger'    => __( 'Projektträger' ),
					'location'                   => __( 'Loc' ),
					'date'                       => __( 'Datum' ),
				];
		} );

		add_filter( "manage_aroprojekte_posts_custom_column", function ( $column, $post_id ) {
			switch ( $column ) {
				case 'projektnr':
					echo get_field( 'projektnr', $post_id, true );
					break;
				case 'location':
					$a = get_field( 'geolocation', $post_id, true );
					if ( count( $a ) > 0 ) {
						$s = $a['lat'] . ' / ' . $a['lng'];
						echo( $s );
					}
					break;
			}
		}, 10, 3 );

		// Make it sortable:
		// 1st: Column Title clickable:
		add_filter( 'manage_edit-aroprojekte_sortable_columns', function ( $columns ) {
			$columns['projektnr'] = 'projektnr';

			return $columns;
		} );
		// 2nd: Hook into posts query (use it for searching too:)
		add_action( 'pre_get_posts', function ( $query ) {
			$screen    = get_current_screen();
			$post_type = $post_type = $query->get( 'post_type' );
			if ( ! is_admin() || ( isset( $screen->post_type ) && 'aroprojekte' != $screen->post_type ) || 'aroprojekte' != $post_type ) {
				return;
			}
			$orderby = $query->get( 'orderby' );
			if ( 'projektnr' == $orderby ) {
				$query->set( 'meta_key', 'projektnr' );
				$query->set( 'orderby', 'meta_value_num' );
			}
			$s = $query->get( 's' );


		} );


//		add_filter( "manage_projekttraeger_custom_column", 'manage_theme_columns', 10, 3 );
//
//		function manage_theme_columns( $out, $column_name, $theme_id ) {
//			$tax = get_term( $theme_id, 'projekttraeger' );
//			switch ( $column_name ) {
//				case 'url':
//					// get header image url
//					$out = get_field( 'url', $tax );
//					break;
//
//				default:
//					break;
//			}
//
//			return $out;
//		}
	}


	function register_aroprojekte() {

		$key_cpt               = 'aroprojekte';
		$key_tax_projekte      = 'projektkategorien';
		$key_tax_arbeitskreise = 'arbeitskreise';

		$text_domain = self::TEXT_DOMAIN;
		$labels      = array(
			'name'                  => _x( 'Aktivregion Projekte', 'Post Type General Name', $text_domain ),
			'singular_name'         => _x( 'Aktivregion Projekt', 'Post Type Singular Name', $text_domain ),
			'menu_name'             => __( 'ARO-Projekte', $text_domain ),
			'name_admin_bar'        => __( 'Aktivregion Projekt', $text_domain ),
			'archives'              => __( 'Archiv Aktivregion Projekte', $text_domain ),
			'attributes'            => __( 'Archiv Aktivregion Projekt', $text_domain ),
			'parent_item_colon'     => __( 'Parent Item:', $text_domain ),
			'all_items'             => __( 'Alle Projekte', $text_domain ),
			'add_new_item'          => __( 'Neu', $text_domain ),
			'add_new'               => __( 'Neu', $text_domain ),
			'new_item'              => __( 'Neu', $text_domain ),
			'edit_item'             => __( 'Edit Aktivregion Projekte', $text_domain ),
			'update_item'           => __( 'Update', $text_domain ),
			'view_item'             => __( 'Detail', $text_domain ),
			'view_items'            => __( 'Details', $text_domain ),
			'search_items'          => __( 'Suche', $text_domain ),
			'not_found'             => __( 'Not found', $text_domain ),
			'not_found_in_trash'    => __( 'Not found in Trash', $text_domain ),
			'featured_image'        => __( 'Featured Image', $text_domain ),
			'set_featured_image'    => __( 'Set featured image', $text_domain ),
			'remove_featured_image' => __( 'Remove featured image', $text_domain ),
			'use_featured_image'    => __( 'Use as featured image', $text_domain ),
			'insert_into_item'      => __( 'Insort into Aktivregion Projekte', $text_domain ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', $text_domain ),
			'items_list'            => __( 'Items list', $text_domain ),
			'items_list_navigation' => __( 'Items list navigation', $text_domain ),
			'filter_items_list'     => __( 'Filter items list', $text_domain ),
		);
		$rewrite     = array(
			'slug'       => 'aktivregion-ostseekueste-projekte',
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);
		$args        = array(
			'label'               => __( 'Aktivregion Projekt', $text_domain ),
			'description'         => __( 'Verwaltet die Daten für die Aktivregion-Projekte', $text_domain ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', ),
			'taxonomies'          => array( $key_tax_projekte, $key_tax_arbeitskreise, 'projekttraeger' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 3,
			'menu_icon'           => 'dashicons-admin-home',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);
		register_post_type( $key_cpt, $args );

		// Columns:


		// Projektkategorie
		$labels = array(
			'name'                       => _x( 'Projektkategorien', 'Taxonomy General Name', $text_domain ),
			'singular_name'              => _x( 'Projektkategorie', 'Taxonomy Singular Name', $text_domain ),
			'menu_name'                  => __( 'Projekt-Kategorien', $text_domain ),
			'all_items'                  => __( 'All Items', $text_domain ),
			'parent_item'                => __( 'Parent Item', $text_domain ),
			'parent_item_colon'          => __( 'Parent Item:', $text_domain ),
			'new_item_name'              => __( 'New Item Name', $text_domain ),
			'add_new_item'               => __( 'Add New Item', $text_domain ),
			'edit_item'                  => __( 'Edit Item', $text_domain ),
			'update_item'                => __( 'Update Item', $text_domain ),
			'view_item'                  => __( 'View Item', $text_domain ),
			'separate_items_with_commas' => __( 'Separate items with commas', $text_domain ),
			'add_or_remove_items'        => __( 'Add or remove items', $text_domain ),
			'choose_from_most_used'      => __( 'Choose from the most used', $text_domain ),
			'popular_items'              => __( 'Popular Items', $text_domain ),
			'search_items'               => __( 'Search Items', $text_domain ),
			'not_found'                  => __( 'Not Found', $text_domain ),
			'no_terms'                   => __( 'No items', $text_domain ),
			'items_list'                 => __( 'Items list', $text_domain ),
			'items_list_navigation'      => __( 'Items list navigation', $text_domain ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( $key_tax_projekte, array( $key_cpt ), $args );


		$labels = array(
			'name'                       => _x( 'Arbeitskreise', 'Taxonomy General Name', $text_domain ),
			'singular_name'              => _x( 'Arbeitskreis', 'Taxonomy Singular Name', $text_domain ),
			'menu_name'                  => __( 'Arbeitskreise', self::TEXT_DOMAIN ),
			'all_items'                  => __( 'All Items', $text_domain ),
			'parent_item'                => __( 'Parent Item', $text_domain ),
			'parent_item_colon'          => __( 'Parent Item:', $text_domain ),
			'new_item_name'              => __( 'New Item Name', $text_domain ),
			'add_new_item'               => __( 'Add New Item', $text_domain ),
			'edit_item'                  => __( 'Edit Item', $text_domain ),
			'update_item'                => __( 'Update Item', $text_domain ),
			'view_item'                  => __( 'View Item', $text_domain ),
			'separate_items_with_commas' => __( 'Separate items with commas', $text_domain ),
			'add_or_remove_items'        => __( 'Add or remove items', $text_domain ),
			'choose_from_most_used'      => __( 'Choose from the most used', $text_domain ),
			'popular_items'              => __( 'Popular Items', $text_domain ),
			'search_items'               => __( 'Search Items', $text_domain ),
			'not_found'                  => __( 'Not Found', $text_domain ),
			'no_terms'                   => __( 'No items', $text_domain ),
			'items_list'                 => __( 'Items list', $text_domain ),
			'items_list_navigation'      => __( 'Items list navigation', $text_domain ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( $key_tax_arbeitskreise, array( $key_cpt ), $args );

		$labels = array(
			'name'                       => _x( 'Projektträger', 'Taxonomy General Name', $text_domain ),
			'singular_name'              => _x( 'Projektträger', 'Taxonomy Singular Name', $text_domain ),
			'menu_name'                  => __( 'Projektträger', self::TEXT_DOMAIN ),
			'all_items'                  => __( 'Alle Projektträger', $text_domain ),
			'parent_item'                => __( 'Übergeordnet', $text_domain ),
			'parent_item_colon'          => __( 'Übergeordnet:', $text_domain ),
			'new_item_name'              => __( 'New Item Name', $text_domain ),
			'add_new_item'               => __( 'Add New Item', $text_domain ),
			'edit_item'                  => __( 'Bearbeiten', $text_domain ),
			'update_item'                => __( 'Aktualisieren', $text_domain ),
			'view_item'                  => __( 'Ansehen', $text_domain ),
			'separate_items_with_commas' => __( 'Separate items with commas', $text_domain ),
			'add_or_remove_items'        => __( 'Add or remove items', $text_domain ),
			'choose_from_most_used'      => __( 'Choose from the most used', $text_domain ),
			'popular_items'              => __( 'Popular Items', $text_domain ),
			'search_items'               => __( 'Suchen', $text_domain ),
			'not_found'                  => __( 'Not Found', $text_domain ),
			'no_terms'                   => __( 'No items', $text_domain ),
			'items_list'                 => __( 'Items list', $text_domain ),
			'items_list_navigation'      => __( 'Items list navigation', $text_domain ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => true,
		);
		register_taxonomy( self::TAX_PROJEKTTRAEGER, array( $key_cpt ), $args );

		// custom vcolumn URL:
		add_filter( "manage_edit-projekttraeger_columns", 'theme_columns' );
		function theme_columns( $theme_columns ) {
			$new_columns = array(
				'cb'    => '<input type="checkbox" />',
				'name'  => __( 'Name' ),
				'url'   => __( 'Url' ),
				'slug'  => __( 'Slug' ),
				'posts' => __( 'Posts' )
			);

			return $new_columns;
		}

		add_filter( "manage_projekttraeger_custom_column", 'manage_theme_columns', 10, 3 );

		function manage_theme_columns( $out, $column_name, $theme_id ) {
			$tax = get_term( $theme_id, 'projekttraeger' );
			switch ( $column_name ) {
				case 'url':
					// get header image url
					$out = get_field( 'url', $tax );
					break;
				default:
					break;
			}

			return $out;
		}


	}

}
