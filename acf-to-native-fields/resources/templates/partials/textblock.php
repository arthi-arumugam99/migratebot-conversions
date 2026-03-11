<?php
// MIGRATED: ACF get_sub_field() -> get_post_meta() with numbered meta keys
// TODO: Manual review — this partial is used inside a repeater loop; $post_id and $i (row index) must be passed via set_query_var() or equivalent before including this template
$post_id        = get_query_var( 'repeater_post_id', get_the_ID() );
$repeater_name  = get_query_var( 'repeater_name', '' );
$repeater_index = get_query_var( 'repeater_index', 0 );

$headline = get_post_meta( $post_id, $repeater_name . '_' . $repeater_index . '_headline', true ); // MIGRATED: ACF get_sub_field() -> get_post_meta()
$text     = get_post_meta( $post_id, $repeater_name . '_' . $repeater_index . '_text', true );     // MIGRATED: ACF get_sub_field() -> get_post_meta()
?>
<div class="container">
    <div class="textblock">
        <?php if($headline): ?>
        <div class="headline">
          <?= esc_html( $headline ); ?>
        </div>
        <?php endif; ?>
        <div class="text">
          <?= wp_kses_post( $text ) ?>
        </div>
    </div>
</div>