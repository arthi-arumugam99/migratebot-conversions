<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no `home.php` file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

// TODO: Manual review — In WP 6.x block themes, this index.php can be replaced by
// templates/index.html (a block template). If migrating to a full block theme (FSE),
// create templates/index.html with block markup and this file becomes the classic fallback.
// Example templates/index.html structure:
//   <!-- wp:template-part {"slug":"header","tagName":"header"} /-->
//   <!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
//   <main class="wp-block-group">
//     <!-- wp:query {"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
//     <!-- wp:post-template -->
//       <!-- wp:pattern {"slug":"_tw/content-post"} /-->
//     <!-- /wp:post-template -->
//     <!-- wp:query-pagination -->
//       <!-- wp:query-pagination-previous /-->
//       <!-- wp:query-pagination-numbers /-->
//       <!-- wp:query-pagination-next /-->
//     <!-- /wp:query-pagination -->
//     <!-- wp:query-no-results -->
//       <!-- wp:pattern {"slug":"_tw/content-none"} /-->
//     <!-- /wp:query-no-results -->
//     <!-- /wp:query -->
//   </main>
//   <!-- /wp:group -->
//   <!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->

get_header();
?>

	<section id="primary">
		<main id="main">

		<?php
		if ( have_posts() ) {

			if ( is_home() && ! is_front_page() ) :
				?>
				<header class="entry-header">
					<h1 class="entry-title"><?php single_post_title(); ?></h1>
				</header><!-- .entry-header -->
				<?php
				// TODO: Manual review — This blog title header is a candidate for a block pattern.
				// Consider creating patterns/blog-title-header.php with:
				// <?php
				// /**
				//  * Title: Blog Title Header
				//  * Slug: _tw/blog-title-header
				//  * Categories: _tw
				//  * Inserter: false
				//  */
				// ?>
				// <!-- wp:group {"tagName":"header","className":"entry-header","layout":{"type":"constrained"}} -->
				// <header class="wp-block-group entry-header">
				//   <!-- wp:query-title {"type":"archive"} /-->
				// </header>
				// <!-- /wp:group -->
			endif;

			// Load posts loop.
			// MIGRATED: WP 6.x — template-parts/content/content is a candidate for a block pattern.
			// Consider creating patterns/content-post.php in the patterns/ directory (WP 6.0+ auto-registration).
			// Example patterns/content-post.php:
			// <?php
			// /**
			//  * Title: Post Content
			//  * Slug: _tw/content-post
			//  * Categories: _tw
			//  * Inserter: false
			//  */
			// ?>
			// <!-- wp:group {"tagName":"article","className":"entry","layout":{"type":"constrained"}} -->
			// <article class="wp-block-group entry">
			//   <!-- wp:post-title {"isLink":true} /-->
			//   <!-- wp:post-featured-image {"isLink":true} /-->
			//   <!-- wp:post-excerpt /-->
			// </article>
			// <!-- /wp:group -->
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content/content' );
			}

			// Previous/next page navigation.
			_tw_the_posts_navigation();
			// TODO: Manual review — _tw_the_posts_navigation() can be replaced in a block template
			// by <!-- wp:query-pagination --> blocks. If staying in a classic theme, this call
			// is still valid. Consider registering a block pattern for the pagination if needed.

		} else {

			// If no content, include the "No posts found" template.
			// MIGRATED: WP 6.x — template-parts/content/content-none is a candidate for a block pattern.
			// Consider creating patterns/content-none.php in the patterns/ directory (WP 6.0+ auto-registration).
			// Example patterns/content-none.php:
			// <?php
			// /**
			//  * Title: No Posts Found
			//  * Slug: _tw/content-none
			//  * Categories: _tw
			//  * Inserter: false
			//  */
			// ?>
			// <!-- wp:group {"className":"no-results","layout":{"type":"constrained"}} -->
			// <div class="wp-block-group no-results">
			//   <!-- wp:heading -->
			//   <h2><?php esc_html_e( 'Nothing here', '_tw' ); ?></h2>
			//   <!-- /wp:heading -->
			//   <!-- wp:paragraph -->
			//   <p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for.', '_tw' ); ?></p>
			//   <!-- /wp:paragraph -->
			//   <!-- wp:search {"label":"Search","buttonText":"Search"} /-->
			// </div>
			// <!-- /wp:group -->
			get_template_part( 'template-parts/content/content', 'none' );

		}
		?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();