<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package _tw
 */

// TODO: Manual review — In a block-based (FSE) theme, this 404.php can be replaced by
// a block template at templates/404.html. The content below maps to a block pattern
// such as '_tw/404-not-found' (see patterns/ directory). For a classic theme continuing
// to use PHP templates, the structure below remains valid; register a block pattern for
// the inner content so editors can reuse or customise the 404 layout in the block editor.
//
// Suggested patterns/404-not-found.php header:
//   /**
//    * Title: 404 Not Found
//    * Slug: _tw/404-not-found
//    * Categories: _tw
//    * Inserter: false
//    */
//
// The pattern content would include wp:search (replacing get_search_form()) and
// wp:heading / wp:paragraph blocks for the page title and message.

get_header();
?>

	<section id="primary">
		<main id="main">

			<div>
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Page Not Found', '_tw' ); ?></h1>
				</header><!-- .page-header -->

				<div <?php _tw_content_class( 'page-content' ); ?>>
					<p><?php esc_html_e( 'This page could not be found. It might have been removed or renamed, or it may never have existed.', '_tw' ); ?></p>
					<?php get_search_form(); ?>
					<?php
					// TODO: Manual review — get_search_form() renders the classic search
					// form. In a block pattern or FSE template, replace this with the
					// <!-- wp:search --> block for full block-editor compatibility.
					?>
				</div><!-- .page-content -->
			</div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();