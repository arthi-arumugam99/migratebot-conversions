<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package _tw
 */

// MIGRATED: WP 6.x — FSE block themes can replace this PHP template with templates/search.html
// TODO: Manual review — Consider creating a templates/search.html block template to fully adopt FSE.
//       The block template equivalent would use:
//         <!-- wp:template-part {"slug":"header","tagName":"header"} /-->
//         <!-- wp:query {"query":{"inherit":true}} -->
//           <!-- wp:search-title /-->  (custom pattern or heading block with search query)
//           <!-- wp:post-template -->
//             <!-- wp:pattern {"slug":"_tw/content-excerpt"} /-->
//           <!-- /wp:post-template -->
//           <!-- wp:query-pagination /-->
//           <!-- wp:query-no-results -->
//             <!-- wp:pattern {"slug":"_tw/content-none"} /-->
//           <!-- /wp:query-no-results -->
//         <!-- /wp:query -->
//         <!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->

get_header();
?>

	<section id="primary">
		<main id="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				// TODO: Manual review — This search results heading is a candidate for a block pattern.
				//       Consider creating patterns/search-header.php with the following header comment:
				//
				//       <?php
				//       /**
				//        * Title: Search Results Header
				//        * Slug: _tw/search-header
				//        * Categories: _tw-search
				//        * Inserter: false
				//        */
				//       ?>
				//       <!-- wp:heading {"level":1,"className":"page-title"} -->
				//       <h1 class="wp-block-heading page-title">
				//         Search results for: <span><!-- Dynamic search query requires PHP/block binding --></span>
				//       </h1>
				//       <!-- /wp:heading -->
				//
				//       Note: Dynamic search query output requires the Query Title block in FSE:
				//       <!-- wp:query-title {"type":"search"} /-->
				printf(
					/* translators: 1: search result title. 2: search term. */
					'<h1 class="page-title">%1$s <span>%2$s</span></h1>',
					esc_html__( 'Search results for:', '_tw' ),
					get_search_query()
				);
				?>
			</header><!-- .page-header -->

			<?php
			// Start the Loop.
			while ( have_posts() ) :
				the_post();
				// TODO: Manual review — 'template-parts/content/content-excerpt' is a candidate for a
				//       block pattern. Consider creating patterns/content-excerpt.php with:
				//
				//       <?php
				//       /**
				//        * Title: Content Excerpt
				//        * Slug: _tw/content-excerpt
				//        * Categories: _tw-content
				//        * Inserter: false
				//        */
				//       ?>
				//       <!-- wp:group {"tagName":"article","layout":{"type":"constrained"}} -->
				//       <article class="wp-block-group">
				//         <!-- wp:post-title {"isLink":true} /-->
				//         <!-- wp:post-excerpt /-->
				//         <!-- wp:post-date /-->
				//       </article>
				//       <!-- /wp:group -->
				get_template_part( 'template-parts/content/content', 'excerpt' );

				// End the loop.
			endwhile;

			// Previous/next page navigation.
			// TODO: Manual review — _tw_the_posts_navigation() can be replaced by the
			//       <!-- wp:query-pagination /-->  block in an FSE template, or extracted into
			//       patterns/posts-navigation.php as a block pattern for classic theme use.
			_tw_the_posts_navigation();

		else :

			// If no content is found, get the `content-none` template part.
			// TODO: Manual review — 'template-parts/content/content-none' is a candidate for a
			//       block pattern. Consider creating patterns/content-none.php with:
			//
			//       <?php
			//       /**
			//        * Title: No Content Found
			//        * Slug: _tw/content-none
			//        * Categories: _tw-content
			//        * Inserter: false
			//        */
			//       ?>
			//       <!-- wp:group {"layout":{"type":"constrained"}} -->
			//       <div class="wp-block-group">
			//         <!-- wp:heading {"level":2} -->
			//         <h2 class="wp-block-heading">Nothing Found</h2>
			//         <!-- /wp:heading -->
			//         <!-- wp:paragraph -->
			//         <p>Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>
			//         <!-- /wp:paragraph -->
			//         <!-- wp:search {"label":"Search","buttonText":"Search"} /-->
			//       </div>
			//       <!-- /wp:group -->
			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();