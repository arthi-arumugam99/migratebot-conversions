<?php
/**
 * Custom template tags for this theme
 *
 * NOTE: This file has been partially migrated to FSE block templates.
 * Functions that have direct block equivalents are documented below
 * with their block markup replacements. Functions retained here are
 * those with no direct block equivalent or needed for PHP-level logic.
 *
 * MIGRATED: Converted from inc/template-tags.php
 *
 * BLOCK EQUIVALENTS (use in HTML templates instead of these functions):
 *
 * _s_posted_on()  ->
 *   <!-- wp:post-date /-->
 *
 * _s_posted_by()  ->
 *   <!-- wp:post-author {"showAvatar":false} /-->
 *
 * _s_post_thumbnail() (singular) ->
 *   <!-- wp:post-featured-image /-->
 *
 * _s_post_thumbnail() (archive/index, linked) ->
 *   <!-- wp:post-featured-image {"isLink":true,"rel":"bookmark"} /-->
 *
 * _s_entry_footer() categories ->
 *   <!-- wp:post-terms {"term":"category"} /-->
 *
 * _s_entry_footer() tags ->
 *   <!-- wp:post-terms {"term":"post_tag"} /-->
 *
 * _s_entry_footer() comments link ->
 *   <!-- wp:comments -->
 *     <!-- wp:comments-title /-->
 *     <!-- wp:comment-template -->
 *       <!-- wp:comment-author-name /-->
 *       <!-- wp:comment-date /-->
 *       <!-- wp:comment-content /-->
 *     <!-- /wp:comment-template -->
 *     <!-- wp:comments-pagination -->
 *       <!-- wp:comments-pagination-previous /-->
 *       <!-- wp:comments-pagination-numbers /-->
 *       <!-- wp:comments-pagination-next /-->
 *     <!-- /wp:comments-pagination -->
 *     <!-- wp:post-comments-form /-->
 *   <!-- /wp:comments -->
 *
 * edit_post_link() -> NOT needed in block templates (handled by WordPress core toolbar)
 *
 * @package _s
 */

// TODO: Manual conversion needed — _s_posted_on(): The published vs. updated
// dual <time> element logic (showing both original and modified dates when they
// differ) has no direct block equivalent. <!-- wp:post-date /-> renders only one
// date. If distinct published/updated display is required, consider a custom block
// or block pattern with wp:post-date and wp:post-date {"displayType":"modified"}.

// TODO: Manual conversion needed — _s_entry_footer() comments_popup_link():
// The inline comments count link (outside of the full comments block) has no
// direct block equivalent for archive/index views. Use <!-- wp:post-comments-count /-->
// if available, or handle via custom block.

// TODO: Manual conversion needed — _s_post_thumbnail() attachment check:
// The is_attachment() conditional guard has no block-level equivalent.
// Block templates use template hierarchy (attachment.html) to handle this instead.

/**
 * Retained for backward compatibility with any plugins or child themes
 * that may call these functions directly. In FSE templates, use the
 * block markup equivalents documented above.
 */

if ( ! function_exists( '_s_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 *
	 * @deprecated Use <!-- wp:post-date /--> in block templates.
	 *             For modified date: <!-- wp:post-date {"displayType":"modified"} /-->
	 */
	function _s_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', '_s' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
	}
endif;

if ( ! function_exists( '_s_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 *
	 * @deprecated Use <!-- wp:post-author {"showAvatar":false} /--> in block templates.
	 */
	function _s_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', '_s' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
	}
endif;

if ( ! function_exists( '_s_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 *
	 * @deprecated Use the following blocks in block templates:
	 *   <!-- wp:post-terms {"term":"category"} /-->
	 *   <!-- wp:post-terms {"term":"post_tag"} /-->
	 *   <!-- wp:post-comments-count /-->
	 *   edit_post_link() is NOT needed — handled by WP core block toolbar.
	 */
	function _s_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', '_s' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', '_s' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', '_s' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', '_s' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', '_s' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		// edit_post_link() is not needed in block themes — WP core handles edit
		// links via the admin toolbar overlay on the front end.
		// NOT MIGRATED intentionally: edit_post_link() call removed.
	}
endif;

if ( ! function_exists( '_s_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * @deprecated Use the following blocks in block templates:
	 *   Singular:      <!-- wp:post-featured-image /-->
	 *   Archive/Index: <!-- wp:post-featured-image {"isLink":true,"rel":"bookmark"} /-->
	 *
	 * Note: password protection and attachment guards are handled via
	 * template hierarchy and block visibility settings in FSE.
	 */
	function _s_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>
			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->
		<?php else : ?>
			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'alt' => the_title_attribute(
							array(
								'echo' => false,
							)
						),
					)
				);
				?>
			</a>
		<?php
		endif; // End is_singular().
	}
endif;