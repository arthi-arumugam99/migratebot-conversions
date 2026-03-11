<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package _tw
 */

// TODO: Manual review — WP 6.x FSE: This entire single.php template could be replaced by
// a block template at templates/single.html. Consider migrating if adopting a block theme.
// Example block template structure:
//   <!-- wp:template-part {"slug":"header","tagName":"header"} /-->
//   <!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
//   <main class="wp-block-group">
//     <!-- wp:post-content /-->
//     <!-- wp:post-navigation-link {"type":"previous"} /-->
//     <!-- wp:post-navigation-link {"type":"next"} /-->
//     <!-- wp:comments /-->
//   </main>
//   <!-- /wp:group -->
//   <!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->

get_header();
?>

	<section id="primary">
		<main id="main">

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				// TODO: Manual review — WP 6.x Block Patterns: template-parts/content/content-single.php
				// is a candidate for migration to a block pattern. Consider creating a pattern at
				// patterns/content-single.php with a header comment block:
				//
				//   <?php
				//   /**
				//    * Title: Single Post Content
				//    * Slug: _tw/content-single
				//    * Categories: _tw-post
				//    * Inserter: false
				//    */
				//   ?>
				//   <!-- wp:post-featured-image /-->
				//   <!-- wp:post-title {"level":1} /-->
				//   <!-- wp:post-meta /-->
				//   <!-- wp:post-content /-->
				//
				// Once the pattern is registered, this get_template_part() call can be removed
				// in favour of the block template approach described above.
				get_template_part( 'template-parts/content/content', 'single' );

				if ( is_singular( 'post' ) ) {
					// Previous/next post navigation.
					// TODO: Manual review — WP 6.x Block Patterns: The post navigation below is a
					// candidate for a block pattern using <!-- wp:post-navigation-link --> blocks.
					// Example pattern (patterns/post-navigation.php):
					//
					//   <?php
					//   /**
					//    * Title: Post Navigation
					//    * Slug: _tw/post-navigation
					//    * Categories: _tw-post
					//    * Inserter: false
					//    */
					//   ?>
					//   <!-- wp:group {"className":"post-navigation","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
					//   <div class="wp-block-group post-navigation">
					//     <!-- wp:post-navigation-link {"type":"previous","label":"Previous Post","showTitle":true,"linkLabel":true,"arrow":"arrow"} /-->
					//     <!-- wp:post-navigation-link {"type":"next","label":"Next Post","showTitle":true,"linkLabel":true,"arrow":"arrow"} /-->
					//   </div>
					//   <!-- /wp:group -->
					the_post_navigation(
						array(
							'next_text' => '<span aria-hidden="true">' . __( 'Next Post', '_tw' ) . '</span> ' .
								'<span class="sr-only">' . __( 'Next post:', '_tw' ) . '</span> <br/>' .
								'<span>%title</span>',
							'prev_text' => '<span aria-hidden="true">' . __( 'Previous Post', '_tw' ) . '</span> ' .
								'<span class="sr-only">' . __( 'Previous post:', '_tw' ) . '</span> <br/>' .
								'<span>%title</span>',
						)
					);
				}

				// If comments are open, or we have at least one comment, load
				// the comment template.
				// TODO: Manual review — WP 6.x Block Patterns: comments_template() can be replaced
				// by the core <!-- wp:comments --> block inside a block template or block pattern.
				// The comments block handles open/closed state natively; the PHP conditional below
				// is not needed in a block-based context.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

				// End the loop.
			endwhile;
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();