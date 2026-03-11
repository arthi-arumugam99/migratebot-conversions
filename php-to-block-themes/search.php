<!-- MIGRATED: Converted from search.php -->
<!-- wp:template-part {"slug":"header","area":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">

	<!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
	<div class="wp-block-query">

		<!-- wp:group {"tagName":"header","className":"page-header","layout":{"type":"constrained"}} -->
		<header class="wp-block-group page-header">
			<!-- wp:search-results-title /-->
			<!-- wp:paragraph -->
			<p><!-- TODO: Manual conversion needed — dynamic "Search Results for: %s" heading using get_search_query(); use wp:search-results-title or wp:heading with query title if supported by your setup --></p>
			<!-- /wp:paragraph -->
			<!-- wp:query-title {"type":"search","level":1,"className":"page-title"} /-->
		</header>
		<!-- /wp:group -->

		<!-- wp:post-template -->
			<!-- wp:post-title {"isLink":true} /-->
			<!-- wp:post-date /-->
			<!-- wp:post-excerpt /-->
		<!-- /wp:post-template -->

		<!-- wp:query-pagination -->
			<!-- wp:query-pagination-previous /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph -->
			<p>No results found. Please try a different search.</p>
			<!-- /wp:paragraph -->
			<!-- wp:search {"label":"Search","buttonText":"Search"} /-->
		<!-- /wp:query-no-results -->

	</div>
	<!-- /wp:query -->

</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"sidebar","area":"uncategorized"} /-->

<!-- wp:template-part {"slug":"footer","area":"footer"} /-->