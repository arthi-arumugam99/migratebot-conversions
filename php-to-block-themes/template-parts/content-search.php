<!-- MIGRATED: Converted from template-parts/content-search.php -->
<!-- wp:post-template -->

<!-- wp:group {"tagName":"article","className":"search-result-entry","layout":{"type":"constrained"}} -->
<article class="wp-block-group search-result-entry">

	<!-- wp:group {"tagName":"header","className":"entry-header","layout":{"type":"constrained"}} -->
	<header class="wp-block-group entry-header">

		<!-- wp:post-title {"level":2,"isLink":true,"className":"entry-title"} /-->

		<!-- TODO: Manual conversion needed — conditional entry-meta shown only for 'post' post type; _s_posted_on() and _s_posted_by() are theme-specific functions with no direct block equivalent for all their output -->
		<!-- wp:group {"className":"entry-meta","layout":{"type":"flex","flexWrap":"wrap"}} -->
		<div class="wp-block-group entry-meta">
			<!-- wp:post-date /-->
			<!-- wp:post-author {"showAvatar":false} /-->
		</div>
		<!-- /wp:group -->

	</header>
	<!-- /wp:group -->

	<!-- wp:post-featured-image {"className":"post-thumbnail"} /-->

	<!-- wp:group {"className":"entry-summary","layout":{"type":"constrained"}} -->
	<div class="wp-block-group entry-summary">
		<!-- wp:post-excerpt /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"tagName":"footer","className":"entry-footer","layout":{"type":"flex","flexWrap":"wrap"}} -->
	<footer class="wp-block-group entry-footer">
		<!-- TODO: Manual conversion needed — _s_entry_footer() outputs categories, tags, and comments link; replace with blocks below -->
		<!-- wp:post-terms {"term":"category","className":"cat-links"} /-->
		<!-- wp:post-terms {"term":"post_tag","className":"tags-links"} /-->
	</footer>
	<!-- /wp:group -->

</article>
<!-- /wp:group -->

<!-- /wp:post-template -->