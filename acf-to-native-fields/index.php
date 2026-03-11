<?php get_header(); ?>

	<section>
        	<?php
			/* Run the loop to output the page.
			 * If you want to overload this in a child theme then include a file
			 * called loop-page.php and that will be used instead.
			 */
			get_template_part( 'loop', 'index' );
			?>
    </section>

<?php get_footer(); ?>
