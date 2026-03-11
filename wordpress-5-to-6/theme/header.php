<?php
/**
 * The header for our theme
 *
 * This is the template that displays the `head` element and everything up
 * until the `#content` element.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _tw
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="page">
	<a href="#content" class="sr-only"><?php esc_html_e( 'Skip to content', '_tw' ); ?></a>

	<?php
	// MIGRATED: WP 6.x — `template-parts/layout/header-content.php` is a candidate for migration.
	// In FSE/block themes, structural header template parts move to `parts/header.html` as a block
	// template part, and reusable content sections (e.g. site logo + nav combos) become block patterns.
	//
	// TODO: Manual review — Evaluate `template-parts/layout/header-content.php`:
	//   1. If this file contains the site logo, navigation menu, and/or search form, convert it to
	//      a block template part at `parts/header.html` using core blocks such as:
	//        <!-- wp:site-logo /-->
	//        <!-- wp:navigation /-->
	//        <!-- wp:search /-->
	//   2. If it contains a reusable promotional/hero strip that appears inside the header, extract
	//      that sub-section into a block pattern registered via `patterns/header-promo.php`:
	//        <?php
	//        /**
	//         * Title: Header Promo Strip
	//         * Slug: _tw/header-promo-strip
	//         * Categories: header
	//         * Inserter: false
	//         */
	//        ?>
	//        <!-- wp:group {"layout":{"type":"constrained"}} -->
	//        ...
	//        <!-- /wp:group -->
	//   3. Once the block template part exists in `parts/header.html`, the templates/index.html (and
	//      other block templates) should reference it with:
	//        <!-- wp:template-part {"slug":"header","theme":"_tw","tagName":"header"} /-->
	//      and this `get_template_part()` call — along with the entire header.php file — can be
	//      removed entirely for full FSE compliance.
	//   4. If the theme remains a classic (non-FSE) theme for now, keep this call as-is and revisit
	//      when adopting a block-based theme structure.
	get_template_part( 'template-parts/layout/header', 'content' );
	?>

	<div id="content">