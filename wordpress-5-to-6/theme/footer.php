<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the `#content` element and all content thereafter.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _tw
 */

?>

	</div><!-- #content -->

	<?php
	// MIGRATED: WP 6.x — get_template_part() for footer-content is a candidate for a block template part.
	// In a block/FSE theme, this would be replaced by a block template part in `parts/footer.html`.
	// For a classic theme incrementally adopting FSE, this call remains functional.
	// TODO: Manual review — Consider migrating 'template-parts/layout/footer-content.php' to a
	// block template part at `parts/footer.html` using block markup. If the footer contains
	// reusable sections (e.g., newsletter CTA, social links, columns), extract those sections
	// into block patterns registered under a `patterns/` directory or via register_block_pattern().
	// Example pattern candidates within the footer:
	//   - Footer column layout        -> patterns/footer-columns.php
	//   - Newsletter/CTA section      -> patterns/footer-cta.php
	//   - Social links row            -> patterns/footer-social.php
	// Once converted, replace the template part with the appropriate block pattern or
	// a block template part reference: <!-- wp:template-part {"slug":"footer","theme":"_tw"} -->
	get_template_part( 'template-parts/layout/footer', 'content' );
	?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>