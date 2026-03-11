// ============================================================
// WEBFLOW MIGRATION: 404.php -> Webflow Custom 404 Page
// ============================================================
//
// MIGRATED: WordPress 404.php template converted to Webflow
// custom 404 page structure documentation and blueprint.
//
// In Webflow:
//   Project Settings -> Publishing -> 404 Page -> select this page
//   OR rename this page to "404" in Webflow Pages panel and
//   Webflow will automatically use it as the error page.
//
// SYMBOLS USED ON THIS PAGE:
//   - Navbar Symbol (from header.php migration)
//   - Footer Symbol (from footer.php migration)
//
// ============================================================


// ============================================================
// SECTION 1: PAGE SETTINGS (Webflow Page Settings Panel)
// ============================================================
//
// Page Name:        404
// Page Slug:        /404  (Webflow auto-assigns this)
// Title Tag:        Page Not Found | [Site Name]
// Meta Description: The page you are looking for could not be found.
//                   Please use the search or navigation to find what you need.
// Open Graph Title: Page Not Found | [Site Name]
// Exclude from sitemap: YES (toggle ON in Page Settings)
// Set as 404 page:  YES (in Project Settings -> Publishing)
//
// ============================================================


// ============================================================
// SECTION 2: FULL PAGE STRUCTURE (Webflow Designer Hierarchy)
// ============================================================
//
// The following represents the complete element tree to build
// in the Webflow Designer for the 404 page.
//
// ELEMENT TREE:
//
// <Body> .page-404-body
//   |
//   +-- [Symbol] Navbar
//   |     // MIGRATED: get_header() -> Webflow Navbar Symbol (shared)
//   |     // See navbar-symbol-blueprint.js for full Navbar documentation
//   |
//   +-- <Section> .section-404-hero
//   |     // MIGRATED: <section class="error-404 not-found"> -> Webflow Section
//   |     // CSS Classes: section-404-hero, error-404, not-found
//   |     // Background: solid color or subtle pattern (set in Webflow Style Panel)
//   |     // Padding: 120px top / 80px bottom (desktop), 80px / 60px (tablet), 60px / 40px (mobile)
//   |
//   |     +-- <Container> .container-404
//   |           // Max-width: 960px, centered (margin: auto)
//   |
//   |           +-- <Div Block> .error-404-header
//   |           |     // MIGRATED: <header class="page-header"> -> Div Block
//   |           |     // Text-align: center
//   |           |
//   |           |     +-- <Heading H1> .page-title-404
//   |           |           // MIGRATED: <h1 class="page-title"> with esc_html_e()
//   |           |           // Static text: "Oops! That page can't be found."
//   |           |           // Font-size: 48px (desktop), 36px (tablet), 28px (mobile)
//   |           |           // Font-weight: 700
//   |           |           // Color: [primary text color from style guide]
//   |           |           // Margin-bottom: 24px
//   |
//   |           +-- <Div Block> .page-content-404
//   |                 // MIGRATED: <div class="page-content"> -> Div Block
//   |
//   |                 +-- <Paragraph> .error-description
//   |                       // MIGRATED: esc_html_e() paragraph -> static Paragraph element
//   |                       // Static text: "It looks like nothing was found at this location.
//   |                       //               Maybe try one of the links below or a search?"
//   |                       // Text-align: center
//   |                       // Font-size: 18px
//   |                       // Color: [secondary text color]
//   |                       // Margin-bottom: 40px
//   |
//   +-- <Section> .section-404-search
//   |     // MIGRATED: get_search_form() -> Webflow Search Block
//   |     // Requires Webflow Business plan or higher for Site Search
//   |     // Background: [light background color]
//   |     // Padding: 60px top / 60px bottom
//   |
//   |     +-- <Container> .container-search
//   |
//   |           +-- <Heading H2> .search-section-title
//   |           |     // Static text: "Search Our Site"
//   |           |     // Font-size: 28px
//   |           |     // Text-align: center
//   |           |     // Margin-bottom: 24px
//   |
//   |           +-- <Div Block> .search-form-wrapper
//   |                 // TODO: Manual action in Webflow — Add native Webflow Search Element
//   |                 // (Add Element Panel -> Search -> Search Form)
//   |                 // Configure search results page in Site Settings
//   |                 // Alternatively: use a simple HTML form pointing to /search
//   |                 // as a custom code embed if search plan is not available:
//   |                 //
//   |                 // <form action="/search" method="GET" class="search-form-404">
//   |                 //   <input type="text" name="q" placeholder="Search..." class="search-input-404" />
//   |                 //   <button type="submit" class="search-btn-404">Search</button>
//   |                 // </form>
//   |                 //
//   |                 // Max-width: 560px, margin: 0 auto
//   |                 // Display: flex, align-items: stretch
//   |
//   |                 +-- [Webflow Search Input] .search-input-404
//   |                       // Width: 100%, flex-grow: 1
//   |                       // Border-radius: 4px 0 0 4px
//   |                       // Padding: 12px 16px
//   |                       // Font-size: 16px
//   |
//   |                 +-- [Webflow Search Button] .search-btn-404
//   |                       // Static text: "Search"
//   |                       // Background: [primary brand color]
//   |                       // Color: white
//   |                       // Border-radius: 0 4px 4px 0
//   |                       // Padding: 12px 24px
//   |
//   +-- <Section> .section-404-recent-posts
//   |     // MIGRATED: the_widget('WP_Widget_Recent_Posts') -> Webflow CMS Collection List
//   |     // Background: white
//   |     // Padding: 60px top / 60px bottom
//   |
//   |     +-- <Container> .container-recent-posts
//   |
//   |           +-- <Heading H2> .recent-posts-title
//   |           |     // MIGRATED: WP_Widget_Recent_Posts widget title -> static Heading
//   |           |     // Static text: "Recent Posts"
//   |           |     // Font-size: 28px
//   |           |     // Margin-bottom: 24px
//   |
//   |           +-- [Collection List Wrapper] .recent-posts-list
//   |                 // MIGRATED: WP_Widget_Recent_Posts (PHP widget) -> Webflow Collection List
//   |                 //
//   |                 // COLLECTION LIST SETTINGS:
//   |                 //   Source Collection:  Blog Posts
//   |                 //   Items Limit:        5
//   |                 //   Sort:               Published Date (Newest First)
//   |                 //   Filter:             None
//   |                 //   Pagination:         OFF
//   |                 //
//   |                 // Display: flex, flex-direction: column, gap: 16px
//   |
//   |                 +-- [Collection List] (each item)
//   |                       +-- <Div Block> .post-list-item
//   |                             // Display: flex, align-items: center, gap: 12px
//   |                             // Padding-bottom: 16px
//   |                             // Border-bottom: 1px solid [border color]
//   |
//   |                             +-- <Link Block> .post-item-link
//   |                             |     // MIGRATED: the_permalink() -> CMS Slug URL binding
//   |                             |     // Binding: Link -> Collection Page URL
//   |
//   |                             +-- <Text Block> .post-item-title
//   |                             |     // MIGRATED: the_title() -> CMS Name field binding
//   |                             |     // Binding: Text -> "Name" field
//   |                             |     // Font-weight: 600
//   |                             |     // Font-size: 16px
//   |
//   |                             +-- <Text Block> .post-item-date
//   |                                   // MIGRATED: post date -> CMS "Published Date" field binding
//   |                                   // Binding: Text -> "Published Date" (formatted: MMM DD, YYYY)
//   |                                   // Font-size: 14px
//   |                                   // Color: [muted text color]
//   |
//   +-- <Section> .section-404-categories
//   |     // MIGRATED: wp_list_categories() (WP_Widget_Categories) -> Webflow CMS Collection List
//   |     // Background: [light background color]
//   |     // Padding: 60px top / 60px bottom
//   |
//   |     +-- <Container> .container-categories
//   |
//   |           +-- <Heading H2> .categories-section-title
//   |           |     // MIGRATED: widget-title -> static Heading
//   |           |     // Static text: "Most Used Categories"
//   |           |     // Font-size: 28px
//   |           |     // Margin-bottom: 24px
//   |
//   |           +-- [Collection List Wrapper] .categories-list
//   |                 // MIGRATED: wp_list_categories(orderby=count, order=DESC, number=10)
//   |                 //           -> Webflow Collection List
//   |                 //
//   |                 // COLLECTION LIST SETTINGS:
//   |                 //   Source Collection:  Categories (or Blog Categories)
//   |                 //   Items Limit:        10
//   |                 //   Sort:               Post Count (Descending)
//   |                 //   Filter:             None
//   |                 //   Pagination:         OFF
//   |                 //
//   |                 // TODO: Manual action in Webflow — Webflow Collection List sort by post count
//   |                 // is not natively supported. Workaround: add a "Post Count" Number field
//   |                 // to the Categories Collection and manually maintain or sync via CMS API.
//   |                 // Sort the Collection List by this "Post Count" field (Highest First).
//   |                 //
//   |                 // Display: flex, flex-wrap: wrap, gap: 12px
//   |                 // List-style: none (if using List element)
//   |
//   |                 +-- [Collection List] (each item)
//   |                       +-- <Div Block> .category-list-item
//   |
//   |                             +-- <Link Block> .category-link
//   |                             |     // MIGRATED: wp_list_categories href -> CMS Collection Page URL
//   |                             |     // Binding: Link -> Collection Page URL
//   |                             |     // Display: inline-flex, align-items: center, gap: 6px
//   |                             |     // Padding: 6px 14px
//   |                             |     // Background: [light accent color]
//   |                             |     // Border-radius: 20px
//   |
//   |                             +-- <Text Block> .category-name
//   |                             |     // MIGRATED: category name output -> CMS "Name" field binding
//   |                             |     // Binding: Text -> "Name" field
//   |                             |     // Font-size: 14px, font-weight: 500
//   |
//   |                             +-- <Text Block> .category-count
//   |                                   // MIGRATED: show_count=1 post count display
//   |                                   //           -> CMS "Post Count" Number field binding
//   |                                   // Binding: Text -> "Post Count" field
//   |                                   // Static prefix text: "(" — suffix: ")"
//   |                                   // OR: wrap in parentheses using Webflow "Get text from" + prefix/suffix
//   |                                   // Font-size: 12px
//   |                                   // Color: [muted text color]
//   |
//   +-- <Section> .section-404-cta
//   |     // MIGRATED: Combination of WP_Widget_Archives (monthly archives dropdown)
//   |     //           and WP_Widget_Tag_Cloud -> simplified Webflow CTA section
//   |     //
//   |     // TODO: Manual action in Webflow — WordPress WP_Widget_Archives (monthly date
//   |     // dropdown) has NO direct Webflow equivalent because Webflow CMS does not support
//   |     // date-based archive filtering. Replace with a static "Browse by Topic" tags section
//   |     // using a Tags/Topics CMS Collection List.
//   |     //
//   |     // TODO: Manual action in Webflow — WP_Widget_Tag_Cloud (dynamically sized tag links
//   |     // based on frequency) has NO direct Webflow equivalent. Replace with a flat Tags
//   |     // Collection List where font-size is uniform or manually set per item.
//   |     //
//   |     // Background: white
//   |     // Padding: 60px top / 60px bottom
//   |
//   |     +-- <Container> .container-tag-cloud
//   |
//   |           +-- <Heading H2> .tags-section-title
//   |           |     // Static text: "Browse by Topic"
//   |           |     // Font-size: 28px
//   |           |     // Margin-bottom: 8px
//   |
//   |           +-- <Paragraph> .tags-section-description
//   |           |     // MIGRATED: WP_Widget_Archives "Try looking in the monthly archives" message
//   |           |     //           -> updated static copy for Webflow context
//   |           |     // Static text: "Try browsing by topic to find what you're looking for."
//   |           |     // Font-size: 16px
//   |           |     // Color: [secondary text color]
//   |           |     // Margin-bottom: 24px
//   |
//   |           +-- [Collection List Wrapper] .tags-cloud-list
//   |                 // MIGRATED: WP_Widget_Tag_Cloud -> Webflow Collection List (Tags Collection)
//   |                 //
//   |                 // COLLECTION LIST SETTINGS:
//   |                 //   Source Collection:  Tags (or Blog Tags)
//   |                 //   Items Limit:        20
//   |                 //   Sort:               Name (A to Z)
//   |                 //   Filter:             None
//   |                 //   Pagination:         OFF
//   |                 //
//   |                 // Display: flex, flex-wrap: wrap, gap: 8px
//   |
//   |                 +-- [Collection List] (each item)
//   |                       +-- <Link Block> .tag-cloud-item
//   |                             // Binding: Link -> Collection Page URL
//   |                             // Display: inline-block
//   |                             // Padding: 6px 14px
//   |                             // Background: [very light background]
//   |                             // Border: 1px solid [border color]
//   |                             // Border-radius: 4px
//   |                             // Font-size: 14px
//   |                             // Color: [primary text color]
//   |                             // Hover: background [primary brand color], color white
//   |
//   |                             +-- <Text Block> .tag-name
//   |                                   // Binding: Text -> "Name" field
//   |
//   +-- <Section> .section-404-back-home
//   |     // Additional CTA section to guide user back to safety
//   |     // Background: [primary brand color]
//   |     // Padding: 60px top / 60px bottom
//   |
//   |     +-- <Container> .container-back-home
//   |           // Text-align: center
//   |
//   |           +-- <Heading H2> .back-home-title
//   |           |     // Static text: "Ready to find your way back?"
//   |           |     // Font-size: 32px
//   |           |     // Color: white
//   |           |     // Margin-bottom: 24px
//   |
//   |           +-- <Div Block> .back-home-buttons
//   |                 // Display: flex, gap: 16px, justify-content: center
//   |                 // Flex-wrap: wrap (for mobile)
//   |
//   |                 +-- <Link Block> .btn-back-home
//   |                 |     // Link to: / (Homepage)
//   |                 |     // Static text: "Go to Homepage"
//   |                 |     // Background: white
//   |                 |     // Color: [primary brand color]
//   |                 |     // Padding: 14px 32px
//   |                 |     // Border-radius: 4px
//   |                 |     // Font-weight: 600
//   |
//   |                 +-- <Link Block> .btn-contact
//   |                       // Link to: /contact (Contact page)
//   |                       // Static text: "Contact Us"
//   |                       // Background: transparent
//   |                       // Color: white
//   |                       // Border: 2px solid white
//   |                       // Padding: 14px 32px
//   |                       // Border-radius: 4px
//   |                       // Font-weight: 600
//   |
//   +-- [Symbol] Footer
//         // MIGRATED: get_footer() -> Webflow Footer Symbol (shared)
//         // See footer-symbol-blueprint.js for full Footer documentation
//
// ============================================================


// ============================================================
// SECTION 3: CSS CLASS REFERENCE
// ============================================================
//
// The following classes from the original WordPress template
// are mapped to Webflow class equivalents:
//
//  WordPress Class          Webflow Class              Notes
//  ───────────────────────────────────────────────────────────
//  #primary                 (removed)                  Webflow uses sections/containers instead
//  .content-area            (removed)                  Replaced by page section layout
//  #main                    (removed)                  Replaced by section elements
//  .site-main               (removed)                  Replaced by section elements
//  .error-404               .error-404                 Retained on hero Section
//  .not-found               .not-found                 Retained on hero Section (add both classes)
//  .page-header             .error-404-header          Renamed for Webflow clarity
//  .page-title              .page-title-404            Renamed to avoid global style conflicts
//  .page-content            .page-content-404          Renamed for Webflow clarity
//  .widget                  (removed)                  No widget concept in Webflow
//  .widget_categories       .categories-list           Renamed, applied to Collection List
//  .widget-title            .categories-section-title  Applied to H2 element
//
// ============================================================


// ============================================================
// SECTION 4: CMS COLLECTIONS REQUIRED ON THIS PAGE
// ============================================================
//
// The following Webflow CMS Collections must exist and be
// populated before this page will render dynamic content:
//
// 1. BLOG POSTS Collection
//    Fields needed on this page:
//      - Name (Text)                  -> post title display
//      - Slug (Text, auto)            -> link URL generation
//      - Published Date (Date/Time)   -> date display in recent posts list
//
// 2. CATEGORIES Collection
//    Fields needed on this page:
//      - Name (Text)                  -> category name display
//      - Slug (Text, auto)            -> link URL generation
//      - Post Count (Number)          -> used for sort-by-popularity
//        // TODO: Manual action in Webflow — "Post Count" must be
//        // manually maintained or synced via Webflow CMS API / Zapier
//        // There is no auto-count of referenced items in a Webflow
//        // Collection List filter. Keep this field updated when
//        // publishing new posts.
//
// 3. TAGS Collection
//    Fields needed on this page:
//      - Name (Text)                  -> tag name display
//      - Slug (Text, auto)            -> link URL generation
//
// ============================================================


// ============================================================
// SECTION 5: INTERACTIONS & ANIMATIONS (Webflow Interactions Panel)
// ============================================================
//
// Recommended Webflow Interactions for the 404 page:
//
// 1. Page Load Animation
//    Trigger:  Page Load
//    Target:   .page-title-404
//    Animation: Fade In + Slide Up (opacity 0->1, translateY 30px->0)
//    Duration:  600ms, easing: ease-out
//    Delay:     0ms
//
// 2. Page Load Animation (staggered)
//    Trigger:  Page Load
//    Target:   .error-description
//    Animation: Fade In + Slide Up
//    Duration:  600ms, easing: ease-out
//    Delay:     150ms
//
// 3. Scroll Into View — Sections
//    Trigger:  Scroll Into View
//    Target:   .section-404-recent-posts, .section-404-categories,
//              .section-404-cta (apply individually)
//    Animation: Fade In (opacity 0->1)
//    Duration:  500ms, easing: ease-out
//    Once:     YES
//
// 4. Button Hover State
//    Trigger:  Hover
//    Target:   .btn-back-home, .btn-contact, .tag-cloud-item, .category-link
//    Animation: Use Webflow Hover state in Style Panel (no Interaction needed)
//              Set transition: all 200ms ease on base state
//
// ============================================================


// ============================================================
// SECTION 6: RESPONSIVE BREAKPOINTS
// ============================================================
//
// Configure these breakpoints in the Webflow Style Panel:
//
// Desktop (1280px+):
//   .container-404              max-width: 960px
//   .container-search           max-width: 560px (centered)
//   .container-recent-posts     max-width: 720px
//   .container-categories       max-width: 960px
//   .container-tag-cloud        max-width: 960px
//   .container-back-home        max-width: 720px
//   .page-title-404             font-size: 48px
//   .back-home-buttons          flex-direction: row
//
// Tablet (768px - 1279px):
//   .page-title-404             font-size: 36px
//   .section-404-hero           padding: 80px 24px
//   .section-404-*              padding: 48px 24px
//
// Mobile Landscape (480px - 767px):
//   .page-title-404             font-size: 28px
//   .back-home-buttons          flex-direction: column
//   .back-home-buttons          align-items: center
//   .section-404-hero           padding: 60px 16px
//   .search-form-wrapper        flex-direction: column
//   .search-input-404           border-radius: 4px
//   .search-btn-404             border-radius: 4px, width: 100%
//
// Mobile Portrait (< 480px):
//   .page-title-404             font-size: 24px
//   .categories-list            flex-direction: column
//
// ============================================================


// ============================================================
// SECTION 7: CUSTOM CODE EMBEDS
// ============================================================
//
// Add the following to Webflow Page Settings -> Custom Code
// for this 404 page:
//
// --- HEAD CODE ---
//
// <!-- 404 Page: Prevent indexing -->
// <meta name="robots" content="noindex, nofollow">
//
// --- BEFORE </body> CODE ---
//
// <script>
// // MIGRATED: Track 404 errors for diagnostics
// // Replace UA-XXXXXXXX-X with your Google Analytics 4 Measurement ID
// // or use your preferred analytics tracking method
// if (typeof gtag !== 'undefined') {
//   gtag('event', 'page_not_found', {
//     'page_path': window.location.pathname,
//     'page_referrer': document.referrer
//   });
// }
//
// // Optional: Log 404s to console for debugging during development
// console.warn('404 Error: Page not found ->', window.location.pathname);
// </script>
//
// TODO: Manual action in Webflow — If the WordPress site had a
// Redirect Manager plugin (Yoast, Redirection plugin), export
// all existing redirects and import them into:
//   Webflow Project Settings -> Hosting -> 301 Redirects
// This prevents old WordPress URLs from hitting the 404 page.
//
// ============================================================


// ============================================================
// SECTION 8: 301 REDIRECT CONSIDERATIONS
// ============================================================
//
// The 404 page itself does not need a redirect, but to minimize
// 404 occurrences after migration, ensure the following are
// configured in Webflow Project Settings -> Hosting -> Redirects:
//
// Source Pattern                       Destination          Type
// ──────────────────────────────────────────────────────────────
// /wp-login.php                        /                    301
// /wp-admin                            /                    301
// /wp-content/uploads/*                [Webflow Asset CDN]  301  (per-file mapping required)
// /?p=[id]                             /blog/[new-slug]     301  (per-post mapping required)
// /category/[name]                     /blog                301  (or filtered collection page)
// /tag/[name]                          /blog                301  (or filtered collection page)
// /author/[name]                       /blog                301
// /[year]/[month]/[day]/[postname]/    /blog/[postname]     301  (if date-based permalinks used)
// /feed                                /feed.xml            301
// /feed/                               /feed.xml            301
// /rss                                 /feed.xml            301
// /sitemap.xml                         /sitemap.xml         301  (Webflow auto-generates)
// /page/[n]/                           /blog                301  (pagination not preserved)
//
// TODO: Manual action in Webflow — Generate a complete redirect
// CSV from the WordPress Redirection plugin or by crawling the
// old site with Screaming Frog, then bulk-import into Webflow.
//
// ============================================================


// ============================================================
// SECTION 9: WEBFLOW DESIGNER BUILD CHECKLIST
// ============================================================
//
// Use this checklist when building the 404 page in Webflow Designer:
//
// SETUP:
//   [ ] Create new static page named "404"
//   [ ] Set page as the 404 error page in Project Settings -> Publishing
//   [ ] Set Title Tag: "Page Not Found | [Site Name]"
//   [ ] Set Meta Description
//   [ ] Toggle "Exclude from sitemap" to ON
//   [ ] Add <meta name="robots" content="noindex, nofollow"> in head code
//
// SYMBOLS:
//   [ ] Add Navbar Symbol (shared, inherited from header.php migration)
//   [ ] Add Footer Symbol (shared, inherited from footer.php migration)
//
// HERO SECTION (.section-404-hero):
//   [ ] Add Section element, apply class: section-404-hero, error-404, not-found
//   [ ] Add Container inside Section, apply class: container-404
//   [ ] Add Div Block for header, apply class: error-404-header
//   [ ] Add H1 inside Div, apply class: page-title-404
//   [ ] Set H1 text: "Oops! That page can't be found."
//   [ ] Add Div Block for content, apply class: page-content-404
//   [ ] Add Paragraph, text: "It looks like nothing was found..."
//
// SEARCH SECTION (.section-404-search):
//   [ ] Add new Section, apply class: section-404-search
//   [ ] Add Container, apply class: container-search
//   [ ] Add H2: "Search Our Site"
//   [ ] Add Webflow Search element (or HTML Embed as fallback)
//   [ ] Style search input and button
//
// RECENT POSTS SECTION (.section-404-recent-posts):
//   [ ] Add new Section, apply class: section-404-recent-posts
//   [ ] Add Container, apply class: container-recent-posts
//   [ ] Add H2: "Recent Posts"
//   [ ] Add Collection List, source: Blog Posts, limit: 5
//   [ ] Sort: Published Date, Descending
//   [ ] Inside Collection Item: add Div, Link, title Text, date Text
//   [ ] Bind Link href to Collection Page URL
//   [ ] Bind title Text to "Name" field
//   [ ] Bind date Text to "Published Date" field with date format
//
// CATEGORIES SECTION (.section-404-categories):
//   [ ] Add new Section, apply class: section-404-categories
//   [ ] Add Container, apply class: container-categories
//   [ ] Add H2: "Most Used Categories"
//   [ ] Add Collection List, source: Categories, limit: 10
//   [ ] Sort: Post Count field, Descending
//   [ ] Inside Collection Item: add Link, category name Text, count Text
//   [ ] Bind Link href to Collection Page URL
//   [ ] Bind name Text to "Name" field
//   [ ] Bind count Text to "Post Count" field
//
// TAG CLOUD SECTION (.section-404-cta):
//   [ ] Add new Section, apply class: section-404-cta
//   [ ] Add Container, apply class: container-tag-cloud
//   [ ] Add H2: "Browse by Topic"
//   [ ] Add Paragraph: "Try browsing by topic..."
//   [ ] Add Collection List, source: Tags, limit: 20
//   [ ] Sort: Name, A to Z
//   [ ] Inside Collection Item: add Link Block with tag name Text
//   [ ] Bind Link href to Collection Page URL
//   [ ] Bind Text to "Name" field
//   [ ] Style tag items as pill/badge buttons
//
// BACK HOME CTA SECTION (.section-404-back-home):
//   [ ] Add new Section, apply class: section-404-back-home
//   [ ] Set background to primary brand color
//   [ ] Add Container, apply class: container-back-home, text-align: center
//   [ ] Add H2: "Ready to find your way back?"
//   [ ] Add Div for button group, apply class: back-home-buttons
//   [ ] Add Link Block -> "/" with text "Go to Homepage", style as primary button
//   [ ] Add Link Block -> "/contact" with text "Contact Us", style as outline button
//
// INTERACTIONS:
//   [ ] Set up Page Load fade-in on .page-title-404 (0ms delay)
//   [ ] Set up Page Load fade-in on .error-description (150ms delay)
//   [ ] Set up Scroll Into View animations on dynamic sections
//   [ ] Set hover transitions on buttons and tag items
//
// RESPONSIVE:
//   [ ] Test and adjust all breakpoints (Tablet, Mobile Landscape, Mobile Portrait)
//   [ ] Verify search form stacks on mobile
//   [ ] Verify buttons stack on mobile
//   [ ] Verify category pills wrap correctly on mobile
//
// ANALYTICS & CODE:
//   [ ] Add 404 tracking script in Before </body> code section
//   [ ] Verify noindex meta tag is present in Head code
//
// ============================================================