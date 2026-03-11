<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

?>

<footer id="colophon">

	<?php
	// TODO: Manual review — WP 6.x widget migration
	// `is_active_sidebar( 'sidebar-1' )` and `dynamic_sidebar( 'sidebar-1' )` continue to work
	// in classic/hybrid themes with the block-based widget editor, but in a block theme (FSE)
	// this entire <aside> widget area should be replaced by a template part block, e.g.:
	//
	//   <!-- wp:template-part {"slug":"footer-widgets","tagName":"aside","className":"footer-widgets"} /-->
	//
	// Steps to migrate:
	// 1. Create `parts/footer-widgets.html` in your block theme.
	// 2. Add the desired blocks (e.g., Latest Posts, Search, Custom HTML) inside that file.
	// 3. Register the template part in theme.json under "templateParts".
	// 4. Remove this `register_sidebar( 'sidebar-1' )` call from functions.php once migrated.
	//
	// Widget-to-block mappings for common widgets placed in sidebar-1:
	//   WP_Widget_Text           -> <!-- wp:paragraph --> or <!-- wp:html -->
	//   WP_Widget_Custom_HTML    -> <!-- wp:html -->
	//   WP_Widget_Recent_Posts   -> <!-- wp:latest-posts -->
	//   WP_Widget_Categories     -> <!-- wp:categories -->
	//   WP_Widget_Archives       -> <!-- wp:archives -->
	//   WP_Widget_Search         -> <!-- wp:search -->
	//   WP_Widget_Tag_Cloud      -> <!-- wp:tag-cloud -->
	//   WP_Widget_Calendar       -> <!-- wp:calendar -->
	//   WP_Widget_Media_Image    -> <!-- wp:image -->
	//   WP_Widget_Media_Gallery  -> <!-- wp:gallery -->
	//   WP_Widget_Media_Video    -> <!-- wp:video -->
	if ( is_active_sidebar( 'sidebar-1' ) ) : // MIGRATED: WP 6.x — flag for FSE template part migration; still functional in hybrid/classic themes
		?>
		<aside role="complementary" aria-label="<?php esc_attr_e( 'Footer', '_tw' ); ?>">
			<?php dynamic_sidebar( 'sidebar-1' ); // MIGRATED: WP 6.x — in block themes replace with a template part block (see TODO above) ?>
		</aside>
	<?php endif; ?>

	<?php
	// TODO: Manual review — WP 6.x navigation migration
	// `wp_nav_menu()` with a `theme_location` continues to work in classic/hybrid themes,
	// but in a block theme (FSE) this <nav> should be replaced by the Navigation block, e.g.:
	//
	//   <!-- wp:navigation {"ref":0,"textColor":"foreground","overlayMenu":"never"} /-->
	//
	// Steps to migrate:
	// 1. In the Site Editor (Appearance > Editor), create or assign a Navigation block
	//    for the footer menu location.
	// 2. In theme.json, you can define nav element styles under "styles.elements.link".
	// 3. Remove the `register_nav_menus()` entry for 'menu-2' in functions.php if fully
	//    migrated to FSE, as Navigation blocks manage menus independently.
	//
	// WP_Nav_Menu_Widget -> <!-- wp:navigation -->
	if ( has_nav_menu( 'menu-2' ) ) : // MIGRATED: WP 6.x — flag for FSE Navigation block migration; still functional in hybrid/classic themes
		?>
		<nav aria-label="<?php esc_attr_e( 'Footer Menu', '_tw' ); ?>">
			<?php
			wp_nav_menu( // MIGRATED: WP 6.x — in block themes replace with <!-- wp:navigation --> block (see TODO above)
				array(
					'theme_location' => 'menu-2',
					'menu_class'     => 'footer-menu',
					'depth'          => 1,
				)
			);
			?>
		</nav>
	<?php endif; ?>

	<div>
		<?php
		$_tw_blog_info = get_bloginfo( 'name' );
		if ( ! empty( $_tw_blog_info ) ) :
			?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>,
			<?php
		endif;

		/* translators: 1: WordPress link, 2: WordPress. */
		printf(
			'<a href="%1$s">proudly powered by %2$s</a>.',
			esc_url( __( 'https://wordpress.org/', '_tw' ) ),
			'WordPress'
		);
		?>
	</div>

</footer><!-- #colophon -->