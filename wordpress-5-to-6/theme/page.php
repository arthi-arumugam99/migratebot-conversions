<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default. Please note that
 * this is the WordPress construct of pages: specifically, posts with a post
 * type of `page`.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

// TODO: Manual review — In WP 6.x block themes, this PHP template (page.php) can be replaced
// by a block template at templates/page.html. Consider creating an FSE equivalent:
//
//   templates/page.html:
//   <!-- wp:template-part {"slug":"header","tagName":"header"} /-->
//   <!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
//   <main class="wp-block-group">
//     <!-- wp:post-content /-->
//     <!-- wp:comments /-->
//   </main>
//   <!-- /wp:group -->
//   <!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
//
// If your theme is a classic theme incrementally adopting FSE, this file can remain as-is.
// If converting to a block theme (with theme.json and templates/), remove this file and
// create templates/page.html instead.

// TODO: Manual review — The get_template_part( 'template-parts/content/content', 'page' ) call
// below loads the page content partial. In a block theme this is handled by the <!-- wp:post-content /-->
// block. If this partial contains a reusable layout (e.g. featured image + title + content),
// consider extracting it into a block pattern. Example pattern registration in functions.php:
//
//   register_block_pattern(
//     '_tw/page-content',
//     array(
//       'title'       => __( 'Page Content', '_tw' ),
//       'description' => __( 'Page layout with featured image, title, and content area.', '_tw' ),
//       'categories'  => array( 'text' ),
//       'inserter'    => false, // internal pattern, not shown in inserter
//       'content'     => '<!-- wp:post-featured-image /-->'
//                      . '<!-- wp:post-title {"level":1} /-->'
//                      . '<!-- wp:post-content {"layout":{"type":"constrained"}} /-->',
//     )
//   );
//
// Or, place a PHP file at patterns/page-content.php with the pattern header comments:
//
//   <?php
//   /**
//    * Title: Page Content
//    * Slug: _tw/page-content
//    * Categories: text
//    * Inserter: false
//    */
//   ?>
//   <!-- wp:post-featured-image /-->
//   <!-- wp:post-title {"level":1} /-->
//   <!-- wp:post-content {"layout":{"type":"constrained"}} /-->

get_header();
?>

	<section id="primary">
		<main id="main">

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content/content', 'page' );

				// If comments are open, or we have at least one comment, load
				// the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();