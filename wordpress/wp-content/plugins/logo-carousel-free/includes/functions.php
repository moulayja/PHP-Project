<?php
/**
 * This file contains all the helper functions for Logo Carousel
 *
 * @since 3.0
 * @Package log-carousel-free
 */

/**
 * Generate Unique Number
 *
 * @package Logo Carousel
 * @since 3.1.1
 */
function sp_lc_get_unique() {
	static $unique = 0;
	$unique ++;

	return $unique;
}

/**
 * Change the post type
 */
function sp_lc_change_post_type() {
	global $wpdb;
	$old_post_types = array( 'logo-carousel-free' => 'wpl_logo_carousel' );
	foreach ( $old_post_types as $old_type => $type ) {
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET post_type = REPLACE(post_type, %s, %s) 
							WHERE post_type LIKE %s", $old_type, $type, $old_type
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) 
							WHERE guid LIKE %s", "post_type={$old_type}", "post_type={$type}", "%post_type={$type}%"
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) 
							WHERE guid LIKE %s", "/{$old_type}/", "/{$type}/", "%/{$old_type}/%"
			)
		);
	}
}
add_action( 'activated_plugin', 'sp_lc_change_post_type' );

/**
 * Logo and URL columns on admin panel
 *
 * @since 3.0.1
 * @param $columns
 *
 * @return array
 */
function sp_logo_carousel_add_columns( $columns ) {
	$columns = array(
		'cb'    => 'cb',
		'title' => __( 'Title', 'logo-carousel-free' ),
		'thumb' => __( 'Logo', 'logo-carousel-free' ),
		'date'  => __( 'Date', 'logo-carousel-free' ),
	);

	return $columns;
}
add_action( 'manage_wpl_logo_carousel_posts_columns', 'sp_logo_carousel_add_columns' );

function sp_logo_carousel_logo_thumb( $column ) {
	if ( $column == 'thumb' ) {
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'thumbnail', ['class' => 'sp-logo-carousel-logo-list'] );
		} else {
			echo '<span aria-hidden="true">—</span>';
		}
	}
}
add_action( 'manage_wpl_logo_carousel_posts_custom_column', 'sp_logo_carousel_logo_thumb', 10, 2 );

/**
 * Logo Meta Box
 *
 * @return void
 */
function sp_lc_add_meta_box() {
	remove_meta_box( 'postimagediv', 'wpl_logo_carousel', 'side' );
	add_meta_box( 'postimagediv', __( 'Logo Image', 'logo-carousel-free' ), 'post_thumbnail_meta_box', 'wpl_logo_carousel', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'sp_lc_add_meta_box' );

/**
 * Review Text
 *
 * @param $text
 *
 * @return string
 */
function sp_logo_carousel_admin_footer( $text ) {
	$screen = get_current_screen();
	if ( 'wpl_lcp_shortcodes' == get_post_type() || 'wpl_logo_carousel' == get_post_type() || $screen->id == 'wpl_logo_carousel_page_lc_category' ) {
		$url  = 'https://wordpress.org/support/plugin/logo-carousel-free/reviews/?filter=5#new-post';
		$text = sprintf( __( 'If you like <strong>Logo Carousel</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'logo-carousel-free' ), $url );
	}

	return $text;
}
add_filter( 'admin_footer_text', 'sp_logo_carousel_admin_footer', 1, 2 );

/**
 * Do Shortcode used as a function
 *
 * @since 3.1
 * @param $id
 */
function logocarousel( $id ) {
	echo do_shortcode( '[logocarousel id="' . $id . '"]' );
}

/**
 * Widget area support
 *
 * @since 3.0.1
 */
add_filter( 'widget_text', 'do_shortcode' );
