<!DOCTYPE html>
<html <?php language_attributes();

?>>
<head>
    <!-- .META -->
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <link rel="favicon" href="<?php bloginfo('template_url'); ?>/public/img/favicon-256x256.png">
    <link rel="apple-touch-icon-precomposed"
          href="<?php bloginfo('template_url'); ?>/public/img/apple-icon-57x57.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
          href="<?php bloginfo('template_url'); ?>/public/img/apple-icon-72x72.png">
    <link rel="apple-touch-icon-precomposed" sizes="76x76"
          href="<?php bloginfo('template_url'); ?>/public/img/apple-icon-76x76.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="<?php bloginfo('template_url'); ?>/public/img/apple-icon-114x114.png">
    <link rel="apple-touch-icon-precomposed" sizes="120x120"
          href="<?php bloginfo('template_url'); ?>/public/img/apple-icon-120x120.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
          href="<?php bloginfo('template_url'); ?>/public/img/apple-icon-144x144.png">
    <link rel="apple-touch-icon-precomposed" sizes="152x152"
          href="<?php bloginfo('template_url'); ?>/public/img/apple-icon-152x152.png">
    <link rel="apple-touch-icon-precomposed" sizes="180x180"
          href="<?php bloginfo('template_url'); ?>/public/img/apple-icon-180x180.png">
    <link rel="icon" sizes="192x192" href="<?php bloginfo('template_url'); ?>/public/img/android-icon-192x192.png">

  <?php if (is_single()) { ?>
      <!-- if page is content page -->
      <meta property="og:url" content="<?php the_permalink() ?>"/>
      <meta property="og:title" content="<?php single_post_title(''); ?>"/>
      <meta property="og:description" content="<?php echo strip_tags(get_the_excerpt($post->ID)); ?>"/>
      <meta property="og:type" content="article"/>
      <meta property="og:image" content="<?php if (function_exists('wp_get_attachment_thumb_url')) {
        echo wp_get_attachment_thumb_url(get_post_thumbnail_id($post->ID), 'full');
      } ?>"/>

  <?php } else { ?>
      <!-- if page is others -->
      <meta property="og:title" content="<?php bloginfo('name'); ?>"/>
      <meta property="og:url" content="<?php the_permalink() ?>"/>
      <meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
      <meta property="og:description" content="<?php bloginfo('description'); ?>"/>
      <meta property="og:type" content="website"/>
      <meta property="og:image" content="<?php bloginfo('template_url'); ?>/public/img/facebook-250x250.jpg"/>
  <?php } ?>

    <title><?php
      /*
       * Print the <title> tag based on what is being viewed.
       */
      global $page, $paged;

      wp_title('|', true, 'right');

      // Add the blog name.
      bloginfo('name');

      // Add the blog description for the home/front page.
      $site_description = get_bloginfo('description', 'display');
      if ($site_description && (is_home() || is_front_page())) echo " | $site_description";

      // Add a page number if necessary:
      if ($paged >= 2 || $page >= 2) echo ' | ' . sprintf(__('Page %s', 'twentyten'), max($paged, $page));

      ?></title>

    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/public/css/stylesheet.css"/>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
  <?php
  /* Always have wp_head() just before the closing </head>
   * tag of your theme, or you will break many plugins, which
   * generally use this hook to add elements to <head> such
   * as styles, scripts, and meta tags.
   */
  wp_head();
  ?>
</head>

<body id="tinymce" <?php body_class('wp-editor content ' . get_post_field('post_name', get_post())); ?>>
<header>
    the header
    <div class="menu">
      <?php include(get_stylesheet_directory() . "/resources/templates/menu.php"); ?>
    </div>
</header>
