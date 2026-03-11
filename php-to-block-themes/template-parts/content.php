<!-- MIGRATED: Converted from template-parts/content.php -->

<!-- wp:group {"tagName":"article","className":"post-entry","layout":{"type":"constrained"}} -->
<article class="wp-block-group post-entry">

	<!-- wp:group {"tagName":"header","className":"entry-header","layout":{"type":"default"}} -->
	<header class="wp-block-group entry-header">

		<!-- TODO: Manual conversion needed — Conditional singular check: on singular templates use post-title as h1 (non-linked), on archive/loop templates use post-title as h2 (linked). Configure via block settings or separate templates (single.html vs index.html). -->

		<!-- wp:post-title {"isLink":true,"level":2,"className":"entry-title"} /-->

		<!-- wp:group {"className":"entry-meta","layout":{"type":"flex","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"0.5em"}}} -->
		<div class="wp-block-group entry-meta">

			<!-- TODO: Manual conversion needed — _s_posted_on() custom function. Replace with: -->
			<!-- wp:post-date {"className":"posted-on"} /-->

			<!-- TODO: Manual conversion needed — _s_posted_by() custom function. Replace with: -->
			<!-- wp:post-author {"showAvatar":false,"className":"byline"} /-->

		</div>
		<!-- /wp:group -->

		<!-- TODO: Manual conversion needed — entry-meta div is conditionally shown only when post type is 'post'. In FSE this is handled automatically per template; move post-type-specific meta to the post-specific template (single.html / home.html) and omit from page templates. -->

	</header>
	<!-- /wp:group -->

	<!-- TODO: Manual conversion needed — _s_post_thumbnail() custom function outputs the featured image with specific markup. Replace with: -->
	<!-- wp:post-featured-image {"className":"post-thumbnail"} /-->

	<!-- wp:group {"tagName":"div","className":"entry-content","layout":{"type":"constrained"}} -->
	<div class="wp-block-group entry-content">

		<!-- wp:post-content {"layout":{"type":"constrained"}} /-->

		<!-- TODO: Manual conversion needed — wp_link_pages() for paginated post navigation (<!--nextpage--> tag). No direct block equivalent exists. Consider using the core/page-list block or a custom block. -->

	</div>
	<!-- /wp:group -->

	<!-- wp:group {"tagName":"footer","className":"entry-footer","layout":{"type":"flex","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"0.5em"}}} -->
	<footer class="wp-block-group entry-footer">

		<!-- TODO: Manual conversion needed — _s_entry_footer() custom function typically outputs categories, tags, and edit link. Replace with: -->

		<!-- wp:post-terms {"term":"category","className":"cat-links"} /-->

		<!-- wp:post-terms {"term":"post_tag","className":"tags-links"} /-->

		<!-- TODO: Manual conversion needed — Edit post link (edit_post_link()) has no direct block equivalent. Consider using a custom block or omitting it from the FSE template. -->

	</footer>
	<!-- /wp:group -->

</article>
<!-- /wp:group -->