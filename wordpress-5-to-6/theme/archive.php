<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

// TODO: Manual review — Consider migrating this archive template to a block template at
// templates/archive.html for Full Site Editing (FSE). The block equivalent would use
// <!-- wp:query --> with <!-- wp:post-template --> to replace the have_posts() loop.
// Example block template structure:
//
// <!-- wp:template-part {"slug":"header","tagName":"header"} /-->
// <!-- wp:group {"tagName":"main"} -->
// <!-- wp:query-title {"type":"archive"} /-->
// <!-- wp:query {"queryId":0,"query":{"inherit":true}} -->
// <!-- wp:post-template -->
//   <!-- wp:pattern {"slug":"_tw/content-excerpt"} /-->
// <!-- /wp:post-template -->
// <!-- wp:query-pagination /-->
// <!-- wp:query-no-results -->
//   <!-- wp:pattern {"slug":"_tw/content-none"} /-->
// <!-- /wp:query-no-results -->
// <!-- /wp:query -->
// <!-- /wp:group -->
// <!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->

get_header();
?>

	<section id="primary">
		<main id="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			</header><!-- .page-header -->

			<?php
			// Start the Loop.
			while ( have_posts() ) :
				the_post();
				// TODO: Manual review — 'template-parts/content/content-excerpt' is a candidate
				// for a block pattern. Consider creating a block pattern at
				// patterns/content-excerpt.php with a slug of '_tw/content-excerpt'.
				// Pattern header example:
				//
				// <?php
				// /**
				//  * Title: Content Excerpt
				//  * Slug: _tw/content-excerpt
				//  * Categories: _tw, posts
				//  * Inserter: false
				//  */
				// ?>
				// <!-- wp:group {"tagName":"article","layout":{"type":"constrained"}} -->
				// <article class="wp-block-group">
				//   <!-- wp:post-title {"isLink":true} /-->
				//   <!-- wp:post-excerpt /-->
				// </article>
				// <!-- /wp:group -->
				get_template_part( 'template-parts/content/content', 'excerpt' );

				// End the loop.
			endwhile;

			// Previous/next page navigation.
			_tw_the_posts_navigation();

		else :

			// If no content, include the "No posts found" template.
			// TODO: Manual review — 'template-parts/content/content-none' is a candidate
			// for a block pattern. Consider creating a block pattern at
			// patterns/content-none.php with a slug of '_tw/content-none'.
			// Pattern header example:
			//
			// <?php
			// /**
			//  * Title: No Posts Found
			//  * Slug: _tw/content-none
			//  * Categories: _tw, posts
			//  * Inserter: false
			//  */
			// ?>
			// <!-- wp:group {"layout":{"type":"constrained"}} -->
			// <div class="wp-block-group">
			//   <!-- wp:heading -->
			//   <h2>Nothing Found</h2>
			//   <!-- /wp:heading -->
			//   <!-- wp:paragraph -->
			//   <p>It seems we can&rsquo;t find what you&rsquo;re looking for.</p>
			//   <!-- /wp:paragraph -->
			// </div>
			// <!-- /wp:group -->
			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();