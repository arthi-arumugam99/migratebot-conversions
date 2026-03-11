// MIGRATED: functions.php -> Shopify theme configuration
// This WordPress functions.php has been decomposed into multiple Shopify theme files.
// Each section below maps to its Shopify equivalent location.
// WordPress hook/filter system has no direct equivalent in Shopify; all functionality is inlined.

// ============================================================
// FILE: layout/theme.liquid
// MIGRATED: Replaces wp_head(), wp_footer(), get_header(), get_footer()
// Google Fonts (previously wp_enqueue_scripts hook) -> moved inline to theme.liquid <head>
// Sidebars -> removed (Shopify uses sections, not widget areas)
// WooCommerce wrapper hooks -> inlined as div.main wrapper in layout
// ============================================================

// layout/theme.liquid content:
/*
<!DOCTYPE html>
<html lang="{{ request.locale.iso_code }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ page_title }} - {{ shop.name }}</title>

  <!-- MIGRATED: tutsplus_add_google_fonts() wp_enqueue_scripts hook -> inline link tag -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300">

  {{ content_for_header }}

  {{ 'theme.css' | asset_url | stylesheet_tag }}
</head>
<body>

  {% section 'header' %}

  <!-- MIGRATED: woocommerce_before_main_content wrapper -> inline div.main -->
  <div class="main">
    {{ content_for_layout }}
  </div>

  {% section 'footer' %}

  <!-- MIGRATED: tutsplus_after_footer hook + tutsplus_colophon() -> inline colophon section -->
  {% section 'colophon' %}

</body>
</html>
*/

// ============================================================
// FILE: sections/colophon.liquid
// MIGRATED: tutsplus_colophon() hooked to tutsplus_after_footer -> Shopify section
// apply_filters('tutsplus_colophon_link') -> section setting
// apply_filters('tutsplus_colophon_name') -> section setting (defaults to shop.name)
// ============================================================

// sections/colophon.liquid content:
/*
<section class="colophon" role="contentinfo">
  <small class="copyright half left">
    <!-- MIGRATED: apply_filters('tutsplus_colophon_link', home_url('/')) -> section setting or shop.url -->
    <!-- MIGRATED: apply_filters('tutsplus_colophon_name', get_bloginfo('name')) -> section setting or shop.name -->
    &copy; <a href="{{ section.settings.colophon_link | default: shop.url }}">
      {{ section.settings.colophon_name | default: shop.name }}
    </a> {{ 'now' | date: '%Y' }}
  </small>

  <small class="credits half right">
    <!-- MIGRATED: _e('Proudly powered by', 'tutsplus') -> {{ 'layout.colophon.powered_by' | t }} -->
    {{ 'layout.colophon.powered_by' | t }}
    <a href="https://www.shopify.com">Shopify</a>.
  </small>
</section>

{% schema %}
{
  "name": "Colophon",
  "settings": [
    {
      "type": "url",
      "id": "colophon_link",
      "label": "Colophon Link",
      "info": "Defaults to store URL if left blank."
    },
    {
      "type": "text",
      "id": "colophon_name",
      "label": "Colophon Name",
      "info": "Defaults to shop name if left blank."
    }
  ],
  "presets": [
    {
      "name": "Colophon"
    }
  ]
}
{% endschema %}
*/

// ============================================================
// FILE: sections/header.liquid
// MIGRATED: register_nav_menu('primary', 'Main Navigation Menu') -> Shopify navigation linklist
// Navigation is managed in Shopify Admin > Navigation > Main menu (handle: main-menu)
// ============================================================

// sections/header.liquid content:
/*
<header class="site-header">
  <div class="site-branding">
    {% if section.settings.logo != blank %}
      <!-- MIGRATED: get_bloginfo('name') with logo -> section image setting -->
      <a href="{{ shop.url }}">
        {{ section.settings.logo | image_url: width: 300 | image_tag: alt: shop.name }}
      </a>
    {% else %}
      <a href="{{ shop.url }}"><h1 class="site-title">{{ shop.name }}</h1></a>
    {% endif %}
  </div>

  <nav class="main-navigation" role="navigation">
    <!-- MIGRATED: wp_nav_menu(['theme_location' => 'primary']) -> Shopify linklist loop -->
    <!-- MIGRATED: register_nav_menu('primary') -> Shopify Admin > Navigation > Main menu -->
    <ul>
      {% for link in linklists['main-menu'].links %}
        <li {% if link.active %}class="current-menu-item"{% endif %}>
          <a href="{{ link.url }}">{{ link.title }}</a>
          {% if link.links.size > 0 %}
            <ul class="sub-menu">
              {% for child_link in link.links %}
                <li><a href="{{ child_link.url }}">{{ child_link.title }}</a></li>
              {% endfor %}
            </ul>
          {% endif %}
        </li>
      {% endfor %}
    </ul>
  </nav>

  <div class="header-cart">
    <a href="{{ routes.cart_url }}">
      {{ 'layout.cart.title' | t }} ({{ cart.item_count }})
    </a>
  </div>
</header>

{% schema %}
{
  "name": "Header",
  "settings": [
    {
      "type": "image_picker",
      "id": "logo",
      "label": "Logo Image"
    }
  ]
}
{% endschema %}
*/

// ============================================================
// FILE: sections/home-category-links.liquid
// MIGRATED: tutsplus_cat_image_links() hooked to woocommerce_before_main_content (on shop page)
// WooCommerce product_cat term links -> Shopify collection links via section settings
// get_term_link('Clothing', 'product_cat') -> collection URL via section block setting
// get_stylesheet_directory_uri() . '/images/clothing.jpg' -> section block image picker
// TODO: Map WooCommerce product categories (Clothing, Music, Posters) to Shopify collections
//       and set the collection handles in the section block settings.
// ============================================================

// sections/home-category-links.liquid content:
/*
<!-- MIGRATED: tutsplus_cat_image_links() -> Shopify section with configurable blocks -->
<!-- MIGRATED: is_shop() check -> this section is only added to the home/collection template as needed -->
<div class="full-width clear product-cat-links">
  {% for block in section.blocks %}
    {% case block.type %}
      {% when 'category_link' %}
        <div class="{{ block.settings.column_class }} left" {{ block.shopify_attributes }}>
          {% if block.settings.collection != blank %}
            {% assign linked_collection = collections[block.settings.collection] %}
            <a href="{{ linked_collection.url }}">
              {% if block.settings.image != blank %}
                <!-- MIGRATED: hardcoded /images/clothing.jpg -> block image picker setting -->
                {{ block.settings.image | image_url: width: 600 | image_tag: alt: linked_collection.title }}
              {% elsif linked_collection.image %}
                {{ linked_collection.image | image_url: width: 600 | image_tag: alt: linked_collection.title }}
              {% endif %}
            </a>
            <h3>
              <a href="{{ linked_collection.url }}">{{ linked_collection.title }}</a>
            </h3>
          {% endif %}
        </div>
    {% endcase %}
  {% endfor %}
</div>

{% schema %}
{
  "name": "Home Category Links",
  "max_blocks": 6,
  "blocks": [
    {
      "type": "category_link",
      "name": "Category Link",
      "settings": [
        {
          "type": "collection",
          "id": "collection",
          "label": "Collection",
          "info": "Select the collection for this category link. Replaces WooCommerce product_cat term."
        },
        {
          "type": "image_picker",
          "id": "image",
          "label": "Category Image",
          "info": "Override the collection image. Previously hardcoded in PHP template."
        },
        {
          "type": "select",
          "id": "column_class",
          "label": "Column Width",
          "options": [
            { "value": "one-third", "label": "One Third" },
            { "value": "half", "label": "Half" },
            { "value": "full-width", "label": "Full Width" }
          ],
          "default": "one-third"
        }
      ]
    }
  ],
  "presets": [
    {
      "name": "Home Category Links",
      "blocks": [
        {
          "type": "category_link",
          "settings": {
            "column_class": "one-third"
          }
        },
        {
          "type": "category_link",
          "settings": {
            "column_class": "one-third"
          }
        },
        {
          "type": "category_link",
          "settings": {
            "column_class": "one-third"
          }
        }
      ]
    }
  ]
}
{% endschema %}
*/

// ============================================================
// FILE: sections/sidebar.liquid
// MIGRATED: tutsplus_sidebar_products() hooked to tutsplus_sidebar action
// WP_Query for featured products (product_tag=Featured) -> Shopify collection by tag
// register_sidebar('sidebar-widget-area') -> Shopify section placed in sidebar
// TODO: Create a Shopify collection called "Featured" (automated, tag = featured) or
//       select a manual collection in the section settings to replicate the Featured tag query.
// TODO: WordPress sidebars do not exist in Shopify. Add this section to relevant templates
//       via the template JSON file or the theme editor.
// ============================================================

// sections/sidebar.liquid content:
/*
<!-- MIGRATED: tutsplus_sidebar_products() + WP_Query product_tag=Featured -> collection by tag -->
<!-- MIGRATED: register_sidebar widget areas -> Shopify sections in theme editor -->
{% if section.settings.featured_collection != blank %}
  {% assign featured = collections[section.settings.featured_collection] %}
  {% if featured.products.size > 0 %}
    <aside class="featured-products">
      <h3>{{ section.settings.heading | default: 'Featured Products' }}</h3>
      <ul>
        {% for product in featured.products limit: section.settings.product_limit %}
          <!-- MIGRATED: while($query->have_posts()) loop -> {% for product in collection.products %} -->
          <li id="product-{{ product.id }}" class="half left">
            {% if product.featured_image %}
              <!-- MIGRATED: the_post_thumbnail('thumbnail') -> product.featured_image | image_url | image_tag -->
              <a href="{{ product.url }}">
                {{ product.featured_image | image_url: width: 150 | image_tag:
                  class: 'left',
                  alt: product.featured_image.alt | default: product.title
                }}
              </a>
            {% endif %}
            <!-- MIGRATED: the_title() + the_permalink() -> product.title + product.url -->
            <a class="featured-product-text" href="{{ product.url }}" title="{{ product.title | escape }}">
              {{ product.title }}
            </a>
          </li>
        {% endfor %}
      </ul>
    </aside>
  {% endif %}
{% endif %}

{% schema %}
{
  "name": "Featured Products Sidebar",
  "settings": [
    {
      "type": "text",
      "id": "heading",
      "label": "Heading",
      "default": "Featured Products",
      "info": "Previously hardcoded as 'Featured Products' in PHP."
    },
    {
      "type": "collection",
      "id": "featured_collection",
      "label": "Featured Products Collection",
      "info": "Select the collection to display. Previously queried by WP_Query with product_tag=Featured. Create an automated collection filtered by tag 'featured' in Shopify Admin."
    },
    {
      "type": "range",
      "id": "product_limit",
      "label": "Number of Products",
      "min": 1,
      "max": 12,
      "step": 1,
      "default": 4
    }
  ],
  "presets": [
    {
      "name": "Featured Products Sidebar"
    }
  ]
}
{% endschema %}
*/

// ============================================================
// FILE: sections/collection-banner.liquid
// MIGRATED: tutsplus_product_cats_before_content() hooked to woocommerce_archive_description
// is_product_category() check + get_woocommerce_term_meta thumbnail -> collection.image
// This is now handled natively in Shopify via collection.image on the collection template.
// The section below can be added to templates/collection.json for banner display.
// ============================================================

// sections/collection-banner.liquid content:
/*
<!-- MIGRATED: tutsplus_product_cats_before_content() -> collection image displayed via Liquid -->
<!-- MIGRATED: get_woocommerce_term_meta($cat->term_id, 'thumbnail_id') -> collection.image -->
<!-- MIGRATED: is_product_category() check -> this section only exists on collection templates -->
{% if collection.image %}
  {{ collection.image | image_url: width: 1200 | image_tag:
    class: 'product-cat-image',
    alt: collection.image.alt | default: collection.title
  }}
{% endif %}

{% if collection.description != blank %}
  <div class="collection-description">
    {{ collection.description }}
  </div>
{% endif %}

{% schema %}
{
  "name": "Collection Banner",
  "settings": [],
  "presets": [
    {
      "name": "Collection Banner"
    }
  ]
}
{% endschema %}
*/

// ============================================================
// FILE: sections/checkout-notice.liquid
// MIGRATED: tutsplus_checkout_text() hooked to woocommerce_before_checkout_form
// TODO: CRITICAL -- Shopify controls the checkout flow entirely. This custom text
//       cannot be injected above the checkout form on standard Shopify plans.
//       Options:
//       (1) Shopify Plus: Use checkout.liquid or checkout UI extensions to add content.
//       (2) Standard Shopify: Add a notice on the cart page (sections/main-cart.liquid)
//           as a pre-checkout message to guide customers before they proceed.
//       (3) Standard Shopify: Edit the checkout thank you / order status page via
//           Admin > Settings > Checkout > Order status page additional scripts.
//       The section below is provided for use on the cart page as a pre-checkout notice.
// ============================================================

// sections/checkout-notice.liquid content:
/*
<!-- MIGRATED: tutsplus_checkout_text() woocommerce_before_checkout_form hook -->
<!-- TODO: CRITICAL -- Cannot inject into Shopify checkout on non-Plus plans. -->
<!-- This section is intended for use on the cart page as a pre-checkout message. -->
{% if section.settings.show_notice and section.settings.notice_text != blank %}
  <div class="checkout-notice">
    <p>{{ section.settings.notice_text }}</p>
  </div>
{% endif %}

{% schema %}
{
  "name": "Checkout Notice",
  "settings": [
    {
      "type": "checkbox",
      "id": "show_notice",
      "label": "Show Notice",
      "default": true
    },
    {
      "type": "richtext",
      "id": "notice_text",
      "label": "Notice Text",
      "default": "<p>Thanks for shopping with us. Please complete your details below.</p>",
      "info": "Previously rendered by tutsplus_checkout_text() hooked to woocommerce_before_checkout_form. On standard Shopify plans, place this section on the cart page."
    }
  ],
  "presets": [
    {
      "name": "Checkout Notice"
    }
  ]
}
{% endschema %}
*/

// ============================================================
// FILE: config/settings_schema.json (additions)
// MIGRATED: WordPress theme support declarations -> Shopify theme settings
// add_theme_support('post-thumbnails') -> Shopify always supports product images natively
// add_theme_support('woocommerce') -> Shopify is natively an ecommerce platform
// Google Fonts selection -> theme settings color/font section
// ============================================================

// Add to config/settings_schema.json:
/*
[
  {
    "name": "theme_info",
    "theme_name": "Tutsplus Shopify Theme",
    "theme_version": "1.0.0",
    "theme_author": "Migrated from WooCommerce",
    "theme_documentation_url": "",
    "theme_support_url": ""
  },
  {
    "name": "Typography",
    "settings": [
      {
        "type": "font_picker",
        "id": "body_font",
        "label": "Body Font",
        "default": "open_sans_n4",
        "info": "Previously loaded via Google Fonts API in tutsplus_add_google_fonts(). Open Sans 400 and 300 weights."
      },
      {
        "type": "font_picker",
        "id": "heading_font",
        "label": "Heading Font",
        "default": "open_sans_n3",
        "info": "Previously Open Sans 300 weight loaded via Google Fonts."
      }
    ]
  },
  {
    "name": "Colors",
    "settings": [
      {
        "type": "color",
        "id": "color_primary",
        "label": "Primary Color",
        "default": "#333333"
      },
      {
        "type": "color",
        "id": "color_accent",
        "label": "Accent Color",
        "default": "#0066cc"
      },
      {
        "type": "color",
        "id": "color_background",
        "label": "Background Color",
        "default": "#ffffff"
      }
    ]
  }
]
*/

// ============================================================
// MIGRATION SUMMARY
// ============================================================
// The following WordPress/WooCommerce functions.php responsibilities have been migrated:
//
// 1. tutsplus_add_google_fonts() [wp_enqueue_scripts]
//    -> Inline <link> tag in layout/theme.liquid <head>
//    -> Font selection via config/settings_schema.json font_picker settings
//
// 2. include(TEMPLATEPATH . '/includes/widgets.php')
//    -> TODO: Review widgets.php for any additional functionality to migrate separately
//
// 3. tutsplus_register_theme_menu() [init]
//    -> Shopify Admin > Navigation > Main menu (handle: main-menu)
//    -> Rendered via {% for link in linklists['main-menu'].links %} in sections/header.liquid
//
// 4. tutsplus_widgets_init() [widgets_init] - 6 sidebar/widget areas
//    -> TODO: WordPress widget areas have no direct equivalent in Shopify.
//       Shopify uses sections added to templates via the theme editor.
//       Each widget area should be replaced with an equivalent Shopify section:
//       - in-header-widget-area     -> header section (sections/header.liquid)
//       - sidebar-widget-area       -> sidebar section (sections/sidebar.liquid)
//       - first-footer-widget-area  -> footer section block 1
//       - second-footer-widget-area -> footer section block 2
//       - third-footer-widget-area  -> footer section block 3
//       - fourth-footer-widget-area -> footer section block 4
//
// 5. tutsplus_theme_support() [after_setup_theme]
//    -> add_theme_support('post-thumbnails') -> Shopify supports images natively
//    -> add_theme_support('woocommerce') -> Shopify is a native ecommerce platform
//    -> No migration needed; these are handled automatically by Shopify
//
// 6. tutsplus_colophon() [tutsplus_after_footer]
//    -> sections/colophon.liquid with configurable link and name settings
//
// 7. WooCommerce main content wrapper hooks
//    -> Inlined as <div class="main"> wrapper in layout/theme.liquid
//
// 8. tutsplus_cat_image_links() / tutsplus_main_shop_before_content()
//    -> sections/home-category-links.liquid
//    -> TODO: Create Shopify collections for Clothing, Music, and Posters categories
//       and configure them in the section block settings via the theme editor.
//
// 9. tutsplus_sidebar_products() [tutsplus_sidebar, priority 15]
//    -> sections/sidebar.liquid
//    -> TODO: Create a Shopify automated collection (e.g., handle: featured-products)
//       filtered by tag 'featured' to replicate the WP_Query product_tag=Featured query.
//
// 10. tutsplus_product_cats_before_content() [woocommerce_archive_description, priority 5]
//     -> sections/collection-banner.liquid
//     -> Collection image is natively supported by Shopify (collection.image)
//     -> Set collection images in Shopify Admin > Products > Collections
//
// 11. woocommerce_template_loop_product_thumbnail() override
//     -> TODO: This function override changes thumbnail rendering on product vs. archive pages.
//        In Shopify, use {% render 'product-card' %} snippet with conditional image sizing.
//        The product-card snippet should handle image size differences via parameters.
//
// 12. tutsplus_checkout_text() [woocommerce_before_checkout_form, priority 5]
//     -> sections/checkout-notice.liquid (for use on cart page)
//     -> TODO: CRITICAL -- Shopify controls checkout. Standard Shopify plans cannot inject
//        content into the checkout form. Use the cart page section or Shopify Plus
//        checkout extensions.
//
// ============================================================
// ADDITIONAL TODO ITEMS
// ============================================================
// TODO: Configure payment providers in Shopify Admin > Settings > Payments
// TODO: Configure shipping rates in Shopify Admin > Settings > Shipping and delivery
// TODO: Customize Shopify notification emails in Admin > Settings > Notifications
// TODO: Review includes/widgets.php for additional functionality requiring separate migration
// TODO: Create Shopify collections to replace WooCommerce product categories:
//       - Clothing (handle: clothing)
//       - Music (handle: music)
//       - Posters (handle: posters)
//       - Featured (handle: featured-products, automated by tag: featured)
// TODO: WooCommerce category hierarchy must be flattened into Shopify collections.
//       Use automated collections with tags to replicate hierarchy.
// TODO: Install carrier-specific Shopify apps for real-time shipping rate calculation if needed.