<div class="menucontainer">
    the menu
  <?php
  $excludedPages = "";
  foreach (get_pages() as $page) {
    if ($page->menu_order < 0) {
      $ids .= "{$page->ID},";
    }
  }
  wp_nav_menu(array('exclude' => $ids));
  ?>
</div>
