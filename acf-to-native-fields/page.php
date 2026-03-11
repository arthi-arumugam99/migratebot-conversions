<?php

/**
 * Template Name: Home Page
 */

// MIGRATED: ACF acf_form_head() removed — no native equivalent needed for display templates
get_header();

?>

<section class="content">
  <?php while (have_posts()) :
    the_post(); ?>
    <?php the_content(); ?>
    <?php
    // MIGRATED: ACF have_rows('content') + get_row_layout() -> numbered meta keys preserving ACF data structure
    $layout_count = (int) get_post_meta( get_the_ID(), 'content', true );
    if ( $layout_count > 0 ) :
    ?>
    <?php for ( $i = 0; $i < $layout_count; $i++ ) :
      $layout_type = get_post_meta( get_the_ID(), 'content_' . $i . '_acf_fc_layout', true );
      if ( empty( $layout_type ) ) : continue; endif;
      ?>
          <a name="<?= esc_attr( $layout_type ) ?>"></a>
      <?php
      // MIGRATED: get_row_layout() -> $layout_type; partial template receives layout data via query var
      // TODO: Manual review — each partial template (e.g. hero.php, cta_banner.php) must be updated
      //       to use get_post_meta( get_the_ID(), 'content_' . $i . '_{sub_field}', true )
      //       instead of get_sub_field(). Pass $i as a query var so partials can read the correct row.
      set_query_var( 'acf_layout_index', $i );
      set_query_var( 'acf_layout_type', $layout_type );
      include( get_stylesheet_directory() . '/resources/templates/partials/' . $layout_type . '.php' );
      ?>
    <?php endfor; ?>
  <?php endif; ?>
  <?php endwhile; // end of the loop. ?>
</section>

<?php get_footer(); ?>