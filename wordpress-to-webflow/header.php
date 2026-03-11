// ============================================================
// WEBFLOW MIGRATION: header.php -> Webflow Navbar Symbol
// ============================================================
// SOURCE FILE: header.php (_s / Underscores starter theme)
// TARGET: Webflow Navbar Symbol (global reusable component)
// MIGRATED: WordPress PHP header template converted to Webflow
//           Navbar Symbol blueprint. All PHP template tags replaced
//           with Webflow Designer bindings and structural documentation.
// ============================================================


// ============================================================
// SECTION 1: <head> EQUIVALENT -> Webflow Project Settings
// ============================================================
// The WordPress <head> block is managed automatically by Webflow.
// Map each WordPress head element to its Webflow equivalent:
//
// <meta charset="...">
//   MIGRATED: Webflow auto-outputs UTF-8 charset meta tag.
//   No action required.
//
// <meta name="viewport" content="width=device-width, initial-scale=1">
//   MIGRATED: Webflow auto-outputs viewport meta tag.
//   No action required.
//
// <link rel="profile" href="https://gmpg.org/xfn/11">
//   TODO: Manual action in Webflow — XFN profile link has no
//         Webflow equivalent. Add manually via:
//         Project Settings -> Custom Code -> <head> tag:
//         <link rel="profile" href="https://gmpg.org/xfn/11">
//         Evaluate whether this is required for your use case
//         (XFN is largely deprecated; likely safe to omit).
//
// <?php wp_head(); ?>
//   MIGRATED: wp_head() outputs scripts, styles, and meta tags
//   registered by WordPress plugins and the theme. In Webflow:
//   - Webflow auto-handles its own CSS/JS bundles
//   - Google Analytics / GTM -> Webflow Project Settings -> Integrations
//   - Custom <head> scripts -> Project Settings -> Custom Code -> Head
//   - Per-page custom code -> Page Settings -> Custom Code -> Head
//   - SEO meta (Yoast/RankMath) -> Webflow Page Settings SEO tab
//   - Canonical tags -> Webflow auto-generates; override in Page Settings
//   - JSON-LD structured data -> Page Settings -> Custom Code -> Head
//   TODO: Manual action in Webflow — Audit all wp_head() output
//         from the live WordPress site (use View Source) and
//         migrate each registered script/style to Webflow's
//         custom code sections or native integrations.


// ============================================================
// SECTION 2: <body> TAG -> Webflow Body Settings
// ============================================================
// <?php body_class(); ?>
//   MIGRATED: WordPress body_class() outputs context-aware CSS
//   classes (e.g., home, single-post, page-id-123, logged-in).
//   Webflow does not use body_class() — page context is handled
//   by separate Webflow pages and CMS templates.
//   - Conditional styling based on page type -> use separate
//     Webflow pages/templates, each with their own classes
//   - "logged-in" class for member gating -> Webflow Memberships
//     or a third-party auth integration (Memberstack, etc.)
//   TODO: Manual action in Webflow — If custom CSS or JS
//         in the WordPress theme targets specific body classes
//         (e.g., .single-portfolio, .category-news), rewrite
//         those selectors to target Webflow page/element classes.


// ============================================================
// SECTION 3: SKIP LINK -> Webflow Accessibility Element
// ============================================================
// <a class="skip-link screen-reader-text" href="#content">Skip to content</a>
//   MIGRATED: Accessibility skip link for keyboard navigation.
//   TODO: Manual action in Webflow — Add a skip link manually
//         as the first element inside the <body> via:
//         Page Settings -> Custom Code -> Before </body> tag,
//         OR add as the first element on the Webflow canvas
//         with class "skip-link" styled with:
//           position: absolute;
//           left: -9999px;
//           top: auto;
//           width: 1px;
//           height: 1px;
//           overflow: hidden;
//         On :focus:
//           position: static;
//           width: auto;
//           height: auto;
//         The href="#content" should point to a Webflow Section
//         element with the custom ID "content".


// ============================================================
// SECTION 4: WEBFLOW NAVBAR SYMBOL — COMPLETE STRUCTURE
// ============================================================
//
// SYMBOL NAME: "Global Navbar" (or "Site Header")
// PLACEMENT: Add to every Webflow page above all content
//             sections. Webflow Symbols auto-sync changes
//             across all pages where the Symbol is used.
//
// SYMBOL STRUCTURE (Webflow Designer element hierarchy):
// ============================================================
//
// [Symbol: Global Navbar]
// └── <header> tag | class: "site-header"
//     │   // MIGRATED: <header id="masthead" class="site-header">
//     │   // Set element tag to <header> in Webflow Designer
//     │   // Add custom attribute: id="masthead" (for skip-link
//     │   // compatibility and legacy CSS targeting if needed)
//     │
//     ├── [Div Block] | class: "site-branding"
//     │   // MIGRATED: <div class="site-branding">
//     │   // Contains logo image and site title text
//     │   │
//     │   ├── [Link Block] | class: "site-logo-link"
//     │   │   // MIGRATED: the_custom_logo() outputs a linked
//     │   │   // image wrapped in <a href="/">
//     │   │   // In Webflow: Link Block with href set to "/" (home)
//     │   │   // TODO: Manual action in Webflow — Upload logo SVG
//     │   │   //       or PNG to Webflow Assets. Place an Image
//     │   │   //       element inside this Link Block. Set alt text
//     │   │   //       to the site name for accessibility.
//     │   │   └── [Image] | class: "site-logo"
//     │   │       // Webflow Image element
//     │   │       // Asset: Upload logo from WordPress Media Library
//     │   │       // (typically wp-content/uploads/ or theme /images/)
//     │   │       // Alt text: "[Site Name] logo"
//     │   │       // Recommended: Use SVG for crisp display at all sizes
//     │   │
//     │   ├── [Link] | class: "site-title"
//     │   │   // MIGRATED: bloginfo('name') with home URL link
//     │   │   // On the Homepage: WordPress renders this as <h1>
//     │   │   //   (is_front_page() && is_home() conditional)
//     │   │   // On all other pages: WordPress renders as <p>
//     │   │   // In Webflow: Use a Text Block or Heading
//     │   │   //   - On the Homepage page: set element tag to <h1>
//     │   │   //   - On all other pages/templates: set tag to <p>
//     │   │   //   OR: Use a single <p> tag consistently in the
//     │   │   //       Symbol (acceptable SEO trade-off since the
//     │   │   //       logo image with alt text serves the same
//     │   │   //       semantic purpose on the homepage)
//     │   │   // Text content: Set to actual site name (static text
//     │   │   //   in Symbol, e.g., "My Site Name")
//     │   │   // href: "/" (Webflow home link)
//     │   │   // NOTE: If logo image is present, site-title can be
//     │   │   //       visually hidden but kept for accessibility
//     │   │   //       (add class "sr-only" with screen-reader styles)
//     │   │   // TODO: Manual action in Webflow — Set the link href
//     │   │   //       to "/" and enter the site name as static text.
//     │   │
//     │   └── [Text Block] | class: "site-description"
//     │       // MIGRATED: get_bloginfo('description') — the
//     │       // WordPress site tagline from Settings -> General
//     │       // In Webflow: Static text block with the tagline
//     │       // NOTE: Only shown if a description is set.
//     │       //   Replicate this by either:
//     │       //   a) Always showing it with the actual tagline text
//     │       //   b) Hiding the element in Webflow if not needed
//     │       // TODO: Manual action in Webflow — Enter the site
//     │       //       tagline as static text. If no tagline is
//     │       //       desired, delete this element or set
//     │       //       display: none.
//
//     └── <nav> tag | class: "main-navigation" | id: "site-navigation"
//         // MIGRATED: <nav id="site-navigation" class="main-navigation">
//         // In Webflow: Use the built-in Navbar component, or
//         //   construct with a <nav> element containing a Webflow
//         //   Navbar component for responsive behavior.
//         // RECOMMENDED: Use Webflow's native Navbar component
//         //   for built-in responsive menu toggle behavior
//         //   (replaces the PHP menu-toggle button below).
//         //
//         // OPTION A (Recommended): Webflow Native Navbar Component
//         // ─────────────────────────────────────────────────────────
//         // Webflow Navbar | class: "main-navigation"
//         // ├── [Navbar Brand] (optional — can move logo here instead
//         // │    of site-branding div above; common Webflow pattern)
//         // │
//         // ├── [Navbar Menu] | class: "primary-menu"
//         // │   // MIGRATED: wp_nav_menu(['theme_location' => 'menu-1',
//         // │   //   'menu_id' => 'primary-menu'])
//         // │   // Webflow Navbar Menu is shown/hidden based on
//         // │   // breakpoint (replaces JS menu toggle in WordPress)
//         // │   │
//         // │   ├── [Nav Link] | class: "nav-link"
//         // │   │   // Each top-level menu item becomes a Nav Link
//         // │   │   // Text: Menu item label (e.g., "Home", "About")
//         // │   │   // href: Target page URL in Webflow
//         // │   │   // Current state: Webflow auto-applies "w--current"
//         // │   │   //   class to the active nav link (equivalent to
//         // │   │   //   WordPress "current-menu-item" CSS class)
//         // │   │   // TODO: Manual action in Webflow — Recreate all
//         // │   │   //   menu items from WordPress Appearance -> Menus
//         // │   │   //   -> menu-1 (Primary Menu). Document all items:
//         // │   │   //   label, URL, parent/child relationships.
//         // │   │
//         // │   ├── [Dropdown] | class: "nav-dropdown"
//         // │   │   // MIGRATED: Nested/child menu items in WordPress
//         // │   │   // become Webflow Dropdown components
//         // │   │   // ├── [Dropdown Toggle] | class: "nav-dropdown-toggle"
//         // │   │   // │    Text: Parent menu item label
//         // │   │   // └── [Dropdown List] | class: "nav-dropdown-list"
//         // │   │   //      └── [Dropdown Link] x N
//         // │   │   //           Each child menu item becomes a
//         // │   │   //           Dropdown Link element
//         // │   │   // TODO: Manual action in Webflow — For each
//         // │   │   //   WordPress menu item with children, replace
//         // │   │   //   Nav Link with a Dropdown component and add
//         // │   │   //   child links to the Dropdown List.
//         // │   │
//         // │   └── [Nav Link] x N ... (repeat for each menu item)
//         // │
//         // └── [Navbar Menu Button] | class: "menu-toggle"
//             // MIGRATED: <button class="menu-toggle" aria-controls="primary-menu"
//             //   aria-expanded="false">Primary Menu</button>
//             // Webflow's built-in Navbar Menu Button handles the
//             // responsive hamburger/toggle behavior automatically.
//             // No custom JavaScript required (replaces WordPress
//             // theme JS that toggled aria-expanded and menu visibility).
//             // Customize the hamburger icon appearance via:
//             //   Webflow Designer -> Navbar Menu Button -> Style
//             // ARIA attributes are auto-managed by Webflow.
//
//         // OPTION B: Custom <nav> with Manual JS Toggle
//         // ─────────────────────────────────────────────
//         // If precise control over markup is needed and Webflow's
//         // native Navbar component is not desired:
//         // <nav> tag | class: "main-navigation" | id: "site-navigation"
//         // ├── [Button] tag | class: "menu-toggle"
//         // │   // aria-controls="primary-menu" aria-expanded="false"
//         // │   // Text: "Primary Menu" (visually hidden, icon-based)
//         // │   // TODO: Manual action in Webflow — Add Webflow
//         // │   //       Interaction to toggle menu visibility on click.
//         // │   //       Set aria-expanded via custom JS embed.
//         // └── [Unordered List] | id: "primary-menu" | class: "menu nav-menu"
//             // Each <li> is a Nav Link wrapper
//             // └── [List Item] | class: "menu-item"
//             //     └── [Link] | class: "nav-link"
//             //         // Menu item links


// ============================================================
// SECTION 5: OUTER WRAPPER -> Webflow Body / Page Wrapper
// ============================================================
// <div id="page" class="site">
//   MIGRATED: WordPress outer page wrapper used for full-height
//   sticky footer layouts and general CSS scoping.
//   In Webflow: The <body> element itself serves this role.
//   Webflow wraps all page content in a <div class="w-body">
//   or similar automatically.
//   - Sticky footer (if applicable): Webflow achieves this with
//     CSS Flexbox on the Body element (flex-direction: column,
//     min-height: 100vh, footer margin-top: auto)
//   TODO: Manual action in Webflow — If any CSS in the WordPress
//         theme targets #page or .site, update those rules to
//         target the Webflow Body or a top-level wrapper Div
//         with equivalent classes.
//
// <div id="content" class="site-content">
//   MIGRATED: The main content wrapper that wraps all page
//   content between the header and footer.
//   In Webflow: Each page's content sections sit directly on
//   the canvas between the Navbar Symbol and Footer Symbol.
//   There is no single wrapping content div needed in Webflow,
//   but if legacy CSS or skip-link targets (#content) require it:
//   - Add a Section or Div Block with id="content" as the first
//     content element below the Navbar Symbol on each page.
//   TODO: Manual action in Webflow — Add custom attribute
//         id="content" to the first Section element below the
//         Navbar on each page template to support the skip link.


// ============================================================
// SECTION 6: CSS CLASS REFERENCE MAP
// ============================================================
// WordPress class          -> Webflow class (recommended naming)
// ─────────────────────────────────────────────────────────────
// .site                    -> body (Webflow body element)
// .site-header             -> .site-header (keep for consistency)
// .site-branding           -> .site-branding
// .site-title              -> .site-title
// .site-description        -> .site-description
// .site-logo               -> .site-logo (Image element)
// .site-logo-link          -> .site-logo-link (Link Block)
// .main-navigation         -> .main-navigation (nav element)
// .menu-toggle             -> .menu-toggle (Navbar Menu Button)
// #primary-menu / .menu    -> .primary-menu (Navbar Menu)
// .nav-menu                -> .primary-menu
// .menu-item               -> (handled by Webflow Navbar internally)
// .current-menu-item       -> .w--current (Webflow auto-applies)
// .screen-reader-text      -> .sr-only (rename; same CSS styles)
// .skip-link               -> .skip-link
// .site-content            -> .site-content (first Section below nav)
//
// NOTE on .current-menu-item:
//   WordPress outputs .current-menu-item on the active <li>
//   Webflow outputs .w--current on the active Nav Link <a>
//   Update any CSS that targets .current-menu-item to target
//   .w--current instead, or add .w--current to your Webflow
//   class styles.


// ============================================================
// SECTION 7: RESPONSIVE BEHAVIOR NOTES
// ============================================================
// WordPress theme responsive nav behavior (from functions.php /
// theme JS) -> Webflow Navbar component handles this natively:
//
// WordPress pattern:
//   - menu-toggle button hidden on desktop (CSS: display:none above breakpoint)
//   - menu-toggle button shown on mobile
//   - Click toggles .toggled class on .main-navigation
//   - Menu slides/fades in via CSS transition
//
// Webflow equivalent:
//   - Navbar Menu Button automatically shows/hides at the
//     breakpoint set in Webflow Designer (default: 991px / Tablet)
//   - Menu open/close animation set via Webflow Interactions
//     on the Navbar component (or use default Webflow behavior)
//   - Breakpoints: Desktop (992px+), Tablet (991px), Mobile
//     Landscape (767px), Mobile Portrait (479px)
//
// TODO: Manual action in Webflow — Set the Navbar breakpoint
//       (where hamburger menu appears) to match the WordPress
//       theme's responsive breakpoint. Style the Navbar Menu
//       Button icon to match the original theme design.
//       Configure open/close menu animation in Webflow
//       Interactions panel if a custom animation is needed.


// ============================================================
// SECTION 8: WEBFLOW SYMBOL CONFIGURATION CHECKLIST
// ============================================================
// Use this checklist when building the Global Navbar Symbol
// in Webflow Designer:
//
// [ ] Create new Symbol named "Global Navbar" (or "Site Header")
// [ ] Set root element tag to <header>
// [ ] Add custom attribute id="masthead" to root element
// [ ] Build site-branding div with logo Link Block + Image
//     [ ] Upload logo asset to Webflow Assets
//     [ ] Set logo image alt text
//     [ ] Set logo link href to "/"
//     [ ] Add site-title link element with site name text
//     [ ] Add site-description text (or remove if not needed)
// [ ] Add Webflow Navbar component inside the header
//     [ ] Set Navbar element class to "main-navigation"
//     [ ] Set Navbar menu div class to "primary-menu"
//     [ ] Set Navbar Menu Button class to "menu-toggle"
//     [ ] Recreate all primary menu items as Nav Links
//         [ ] Document all menu items from WordPress
//             Appearance -> Menus -> Primary Menu (menu-1)
//         [ ] Create Nav Links for top-level items
//         [ ] Create Dropdown components for items with children
//         [ ] Set correct href for each link
//         [ ] Verify .w--current state styles match original
//             .current-menu-item styles
// [ ] Add skip link as first element (see Section 3)
// [ ] Style all classes to match original theme
// [ ] Test responsive behavior at all Webflow breakpoints
// [ ] Add Symbol to all pages/templates
// [ ] Verify Symbol appears correctly in Webflow Editor
//     (for client content editing — Symbols are not directly
//      editable in Editor unless using Symbol overrides)
//
// NOTE ON SYMBOL OVERRIDES:
//   If different pages need slightly different nav states
//   (e.g., transparent header on homepage, solid on interior),
//   use Webflow's Symbol override feature or create a second
//   "Global Navbar - Transparent" Symbol variant for the homepage.
//   Alternatively, use Webflow Interactions to change navbar
//   styles on page load based on the page.


// ============================================================
// SECTION 9: ITEMS REQUIRING MANUAL MIGRATION FROM wp_head()
// ============================================================
// Audit the live WordPress site's <head> output (View Source)
// and migrate each item. Common items to check:
//
// Google Analytics / Google Tag Manager
//   -> Webflow: Project Settings -> Integrations -> Google Analytics
//      OR add GTM snippet to Project Settings -> Custom Code -> Head
//
// Facebook Pixel / Meta Pixel
//   -> Webflow: Project Settings -> Custom Code -> Head
//
// Google Fonts / Adobe Fonts (Typekit)
//   -> Webflow: Project Settings -> Fonts (add Google Fonts natively)
//      OR add @import to Project Settings -> Custom Code -> Head
//
// WordPress REST API link tags
//   -> Not needed in Webflow; omit.
//
// Emoji scripts (wp-emoji-release.min.js)
//   -> Not needed in Webflow; omit.
//
// jQuery
//   -> Webflow includes its own JS runtime. If custom scripts
//      depend on jQuery, add jQuery via CDN in Custom Code
//      OR rewrite scripts without jQuery dependency.
//      TODO: Manual action in Webflow — Audit all custom JS
//            in the WordPress theme for jQuery dependencies.
//
// WooCommerce scripts/styles
//   -> TODO: Evaluate Webflow Ecommerce or third-party
//            (Snipcart, Shopify Buy Button) as replacement.
//
// Contact Form 7 / Gravity Forms scripts
//   -> Not needed; Webflow native forms replace these.
//
// Yoast SEO meta tags
//   -> Webflow auto-generates: canonical, OG title, OG description,
//      OG image, Twitter Card meta from Page Settings.
//      Migrate custom SEO values per-page in Webflow Page Settings.
//
// Custom CSS (wp_enqueue_style)
//   -> Migrate CSS to Webflow Designer styles or
//      Project Settings -> Custom Code -> Head (global CSS)
//
// Custom JS (wp_enqueue_script)
//   -> Migrate to Webflow Interactions (where possible) or
//      Project Settings -> Custom Code -> Footer (global JS)


// ============================================================
// SECTION 10: TAILWIND CSS NOTE (from package.json context)
// ============================================================
// The WordPress theme uses a webpack build with Tailwind CSS v1.
// This indicates utility-class-based styling may be present.
//
// In Webflow:
//   OPTION A: Rebuild styles natively in Webflow Designer
//     -> Use Webflow's visual style panel; no Tailwind needed
//     -> Webflow generates its own optimized CSS
//
//   OPTION B: Import compiled Tailwind CSS into Webflow
//     -> Build the Tailwind CSS bundle (npm run build)
//     -> Paste minified CSS into Project Settings -> Custom Code -> Head
//        inside a <style> tag (not ideal for large files)
//     -> OR host the CSS file externally and link it in <head>
//     TODO: Manual action in Webflow — Evaluate whether to
//           rebuild styles in Webflow Designer or import the
//           compiled Tailwind bundle. Rebuilding natively is
//           recommended for full Webflow Designer integration.
//
//   OPTION C: Use Tailwind via CDN Play (prototyping only)
//     -> Add Tailwind CDN script to Custom Code -> Head
//     -> NOT recommended for production (large bundle size)
//
// NOTE: The webpack config (webpack, webpack-cli, webpack-dev-server,
//       html-webpack-plugin, css-loader, postcss-loader) is
//       entirely WordPress/build-tool-specific and has no
//       equivalent in Webflow. Webflow manages its own asset
//       pipeline internally.


// ============================================================
// END OF MIGRATION DOCUMENT: header.php -> Webflow Navbar Symbol
// ============================================================
// Next files to migrate:
//   footer.php   -> Webflow Footer Symbol
//   page.php     -> Webflow Static Page template structure
//   single.php   -> Webflow CMS Collection Template (Blog Posts)
//   archive.php  -> Webflow CMS Collection List page
//   front-page.php -> Webflow Homepage layout
// ============================================================