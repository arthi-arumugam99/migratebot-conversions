<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package _s
 *
 * MIGRATED: Converted from inc/template-functions.php
 * - Removed is_active_sidebar() check (sidebar-1 widget area replaced by parts/sidebar.html block template part)
 * - Removed no-sidebar body class logic tied to dynamic widget area presence
 * - Pingback header function retained (no block equivalent)
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function _s_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// NOTE: Removed is_active_sidebar( 'sidebar-1' ) check.
	// In FSE block themes, widget areas (register_sidebar) are replaced by
	// block template parts (parts/sidebar.html). Sidebar visibility is now
	// controlled by the block template structure in templates/*.html rather
	// than by dynamic sidebar registration. The 'no-sidebar' body class is
	// no longer needed; control layout via the Site Editor or template files.

	return $classes;
}
add_filter( 'body_class', '_s_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function _s_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', '_s_pingback_header' );