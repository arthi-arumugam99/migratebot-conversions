<!-- MIGRATED: Converted from template-parts/content-page.php -->
<!-- wp:group {"tagName":"article","className":"post-template-part","layout":{"type":"constrained"}} -->
<article class="wp-block-group post-template-part">

	<!-- wp:group {"tagName":"header","className":"entry-header","layout":{"type":"constrained"}} -->
	<header class="wp-block-group entry-header">
		<!-- wp:post-title {"level":1,"className":"entry-title"} /-->
	</header>
	<!-- /wp:group -->

	<!-- wp:post-featured-image /-->

	<!-- wp:group {"tagName":"div","className":"entry-content","layout":{"type":"constrained"}} -->
	<div class="wp-block-group entry-content">
		<!-- wp:post-content {"layout":{"type":"constrained"}} /-->
		<!-- TODO: Manual conversion needed — wp_link_pages() pagination for multi-page posts (<!--nextpage--> tag support); no direct block equivalent. Consider using the post-content block which may handle this natively in some configurations. -->
	</div>
	<!-- /wp:group -->

	<!-- TODO: Manual conversion needed — edit_post_link() conditionally rendered in entry-footer for logged-in users with edit permissions. WordPress core block themes typically handle this via the post-content block context or a custom block. No direct FSE block equivalent for inline edit links. -->

</article>
<!-- /wp:group -->