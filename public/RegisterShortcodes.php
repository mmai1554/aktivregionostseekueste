<?php

namespace mnc;

use WP_Error;
use WP_Query;

final class RegisterShortcodes {

	/**
	 * wraps the given URL of acf field url around content,
	 * usage: [mnc_url]my website[/mnc_url]
	 */
	public static function MncUrl() {
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
	}

	/**
	 * get the URL (ACF Field) of Projektkategorie
	 * @deprecated is it used?
	 */
	public static function MncProjektKatUrl() {
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
	}

	public static function MncTraegerList() {
		add_shortcode( 'mnc_traeger_list', function ( $atts ) {
			global $post;
			// Attributes
			$atts = shortcode_atts(
				[],
				$atts,
				'mnc_traeger_list'
			);
			// Get Träger:
			$tax = wp_get_post_terms(
				$post->ID,
				\Aktivregionostseekueste_Admin::TAX_PROJEKTTRAEGER,
				[
					'orderby' => 'name',
					'order'   => 'ASC'
				]
			);
			if ( ! is_array( $tax ) ) {
				return '';
			}
			/** @var \WP_Term $traeger */
			$list = [];
			foreach ( $tax as $traeger ) {
				$url = get_field( 'url', $traeger );
				if ( $url ) {
					$list[] = Maln::alink( $url, $traeger->name, '_blank', 'Zur Website des Trägers ' . $traeger->name );
				} else {
					$list[] = $traeger->name;
				}
			}
			return implode("<br>", $list);
		} );
	}

	public static function MncGetTermine() {
		add_shortcode( 'mnc_aktuelle_termine', function () {
			$date_now = date( 'Y-m-d H:i:s' );
			$args  = array(
				'posts_per_page' => - 1,
				'post_type'      => 'termin',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => 'termin_datum',
						'compare' => '>=',
						'value'   => $date_now,
						'type'    => 'DATETIME'
					),
//			array(
//				'key'     => 'end_date',
//				'compare' => '>=',
//				'value'   => $date_now,
//				'type'    => 'DATETIME'
//			)
				),
				'order'          => 'ASC',
				'orderby'        => 'meta_value',
				'meta_key'       => 'termin_datum',
				'meta_type'      => 'DATE'
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {

				$html = [];
				while ( $query->have_posts() ) {
					$query->the_post();
					$html[] = '<div class="mnc_termin">';
					$html[] = '<h4>' . get_field( 'termin_datum' ) .  ' <a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h4>';
					$html[] = '<p>' . get_the_excerpt() . '</p>';
					$text   = get_the_content();
					if ( strlen( $text ) ) {
						$html[] = '<p><a href="' . get_the_permalink() . '">Details...</a></p>';
					}
					$html[] = '</div>';
				}
			} else {
				return '<div class="mnc_termin"><p>keine Aktuellen Termine vorhanden.</p></div>';
			}
			wp_reset_postdata();

			return implode( "\n", $html );

		} );
	}

}