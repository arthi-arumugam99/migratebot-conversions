// MIGRATED: functions.php -> Shopify theme configuration
// This file has no direct Shopify equivalent. WordPress functions.php handled theme setup,
// asset enqueueing, widget registration, nav menus, and WooCommerce wrappers.
// Each concern is handled differently in Shopify as documented below.

// ============================================================
// 1. GOOGLE FONTS
// MIGRATED: wp_enqueue_style for Google Fonts -> add directly to layout/theme.liquid
// In Shopify, add the following inside the <head> of layout/theme.liquid:
//
//   <link rel="preconnect" href="https://fonts.googleapis.com">
//   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
//   <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
//
// Alternatively, use Shopify's built-in font picker setting in a section schema:
//   In settings_schema.json (theme settings), add a font_picker type setting.
//   Then reference it in theme.liquid via: {{ settings.body_font | font_face }}
// ============================================================

// ============================================================
// 2. NAVIGATION MENU
// MIGRATED: register_nav_menu('primary') -> Shopify navigation is configured in
// Admin > Online Store > Navigation. Create a menu with handle "main-menu".
//
// In Shopify Liquid templates, render the menu like this:
//
//   <nav>
//     {% for link in linklists['main-menu'].links %}
//       <a href="{{ link.url }}">{{ link.title }}</a>
//       {% if link.links != blank %}
//         <ul>
//           {% for child_link in link.links %}
//             <a href="{{ child_link.url }}">{{ child_link.title }}</a>
//           {% endfor %}
//         </ul>
//       {% endif %}
//     {% endfor %}
//   </nav>
//
// TODO: Create menus in Shopify Admin > Online Store > Navigation:
//   - "main-menu" (replaces 'primary' / 'Main Navigation Menu')
//   - "footer-menu" (replaces any footer navigation)
// ============================================================

// ============================================================
// 3. SIDEBAR & WIDGET AREAS
// MIGRATED: register_sidebar() -> Shopify does not have a widget system.
// Sidebars and widget areas are replaced by Shopify sections and blocks.
//
// The following WordPress widget areas have been mapped to Shopify sections:
//
//   'in-header-widget-area'        -> Header section blocks (sections/header.liquid)
//   'sidebar-widget-area'          -> Sidebar section (sections/sidebar.liquid) or remove if unused
//   'first-footer-widget-area'     -> Footer section block column 1 (sections/footer.liquid)
//   'second-footer-widget-area'    -> Footer section block column 2 (sections/footer.liquid)
//   'third-footer-widget-area'     -> Footer section block column 3 (sections/footer.liquid)
//   'fourth-footer-widget-area'    -> Footer section block column 4 (sections/footer.liquid)
//
// TODO: Implement these as sections with blocks in sections/footer.liquid and sections/header.liquid.
// Example footer section schema approach:
//
//   {% schema %}
//   {
//     "name": "Footer",
//     "blocks": [
//       {
//         "type": "footer_column",
//         "name": "Footer Column",
//         "settings": [
//           { "type": "text", "id": "heading", "label": "Heading" },
//           { "type": "richtext", "id": "content", "label": "Content" }
//         ]
//       }
//     ],
//     "max_blocks": 4
//   }
//   {% endschema %}
//
// TODO: Migrate actual widget content (menus, text, images) into section/block settings
// via the Shopify theme editor (Online Store > Themes > Customize).
// ============================================================

// ============================================================
// 4. FEATURED IMAGES (POST THUMBNAILS)
// MIGRATED: add_theme_support('post-thumbnails') -> Shopify natively supports product images,
// collection images, article images, and page metafields images.
// No configuration needed. Access images in Liquid like:
//
//   {{ product.featured_image | image_url: width: 800 | image_tag }}
//   {{ article.image | image_url: width: 800 | image_tag }}
//   {{ collection.image | image_url: width: 800 | image_tag }}
// ============================================================

// ============================================================
// 5. COLOPHON / FOOTER COPYRIGHT
// MIGRATED: tutsplus_colophon() hooked to 'tutsplus_after_footer' ->
// WordPress custom action hooks do not exist in Shopify. This content must be
// inlined directly into sections/footer.liquid.
//
// TODO: WooCommerce hooks cannot be replicated in Shopify. Inline the functionality
// that was hooked. Add the following to sections/footer.liquid:
//
//   <section class="colophon" role="contentinfo">
//     <small class="copyright half left">
//       &copy; <a href="{{ shop.url }}">{{ shop.name }}</a> {{ 'now' | date: '%Y' }}
//     </small>
//     <small class="credits half right">
//       {{ 'layout.footer.powered_by_shopify' | t }}
//     </small>
//   </section>
//
// Notes on mapping:
//   home_url('/') -> {{ shop.url }}
//   get_bloginfo('name') -> {{ shop.name }}
//   _e('Proudly powered by', 'tutsplus') -> {{ 'layout.footer.powered_by_shopify' | t }}
//   "Proudly powered by WordPress" -> update branding to Shopify in locales/en.default.json
//
// TODO: Add the following key to locales/en.default.json if it does not exist:
//   "layout": { "footer": { "powered_by_shopify": "Proudly powered by Shopify" } }
// ============================================================

// ============================================================
// 6. WOOCOMMERCE WRAPPER / THEME SUPPORT
// MIGRATED: remove_action / add_action for woocommerce_before_main_content ->
// Shopify does not use WooCommerce wrapper hooks. The main content wrapper
// is controlled by layout/theme.liquid and individual section files.
//
// The PHP equivalent:
//   function my_theme_wrapper_start() { echo '<div class="main">'; }
//   add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
//   function my_theme_wrapper_end() { echo '</div>'; }
//   add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);
//
// Is handled in layout/theme.liquid as:
//   <div class="main">
//     {{ content_for_layout }}
//   </div>
//
// TODO: WooCommerce hooks cannot be replicated in Shopify. Inline the
// <div class="main"> wrapper directly in layout/theme.liquid around
// {{ content_for_layout }}.
//
// add_theme_support('woocommerce') -> not applicable in Shopify.
// All WooCommerce product/cart/account templates are replaced by Shopify
// Liquid templates under templates/, sections/, and snippets/.
// ============================================================

// ============================================================
// 7. INCLUDED FILES
// MIGRATED: include(TEMPLATEPATH . '/includes/widgets.php') ->
// Shopify does not support PHP includes. Widget logic from widgets.php
// must be converted to Shopify snippets or sections individually.
//
// TODO: Review /includes/widgets.php and convert each widget class/function
// to the appropriate Shopify equivalent:
//   - Custom widget output -> snippets/widget-{name}.liquid
//   - Widget registration -> section schema blocks
//   - Widget areas -> sections with configurable blocks in the theme editor
// ============================================================

// ============================================================
// SUMMARY: files to create in Shopify theme
// ============================================================
// layout/theme.liquid
//   - Add Google Fonts <link> tags in <head>
//   - Wrap {{ content_for_layout }} in <div class="main">...</div>
//   - Add colophon markup above closing </body> (or delegate to footer section)
//
// sections/header.liquid
//   - Navigation rendered via linklists['main-menu'].links
//   - Header widget area converted to section blocks
//   - {% schema %} with blocks for header content
//
// sections/footer.liquid
//   - Four footer widget column blocks via {% schema %} block definitions
//   - Colophon / copyright markup inlined at bottom
//   - {% schema %} with max_blocks: 4 for footer columns
//
// config/settings_schema.json
//   - Add font_picker setting for body/heading fonts (replaces Google Fonts enqueue)
//   - Add color settings for brand colors
//
// locales/en.default.json
//   - Add translation keys for footer copyright, navigation labels, etc.
//
// snippets/ (from widgets.php conversion)
//   - Review and create per widget as needed
// ============================================================