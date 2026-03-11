// ============================================================
// WEBFLOW MIGRATION: sidebar.php -> Webflow Sidebar Symbol
// ============================================================
// MIGRATED: WordPress dynamic_sidebar('sidebar-1') has no direct
// Webflow equivalent. Each widget area must be rebuilt as a
// Webflow Symbol containing static or CMS-powered content blocks.
//
// WordPress Source:
//   <aside id="secondary" class="widget-area">
//     <?php dynamic_sidebar( 'sidebar-1' ); ?>
//   </aside>
//
// Webflow Target:
//   A reusable Symbol named "Sidebar" placed on relevant pages
//   (single post templates, static pages). The sidebar contains
//   individual widget Symbols stacked vertically inside it.
// ============================================================


// ============================================================
// WEBFLOW SYMBOL: "Sidebar" (outer wrapper)
// ============================================================
// Webflow Element Type : Section or Div Block (no semantic aside
//   equivalent in Webflow UI — use a Div Block with role="complementary"
//   added via custom attribute for accessibility)
// Webflow Class        : widget-area
// Custom Attribute     : role="complementary"  (replaces <aside>)
// Custom Attribute     : id="secondary"        (preserve for any
//                          CSS/JS that targets #secondary)
//
// Webflow Designer Steps:
//   1. Create a new Symbol named "Sidebar"
//   2. Add a Div Block as the root element
//      - Class: widget-area
//      - Custom Attributes: role="complementary", id="secondary"
//   3. Inside "Sidebar" Symbol, nest individual widget Symbols
//      (see widget Symbol definitions below)
//   4. Place the "Sidebar" Symbol in a two-column grid layout on:
//      - CMS Collection Template pages (single.php equivalent)
//      - Static pages that previously used a sidebar layout
// ============================================================


// ============================================================
// LAYOUT CONTEXT: Where the Sidebar Symbol is used
// ============================================================
// WordPress conditionally rendered the sidebar via:
//   if ( ! is_active_sidebar( 'sidebar-1' ) ) { return; }
//   get_sidebar() called from single.php, page.php, etc.
//
// Webflow Equivalent:
//   - The Sidebar Symbol is manually included on pages that need it
//   - Pages that do NOT need a sidebar use a full-width layout
//   - There is no dynamic "is_active_sidebar" check in Webflow;
//     visibility is controlled at the page/template level
//
// TODO: Manual action in Webflow — For each page type that
//   previously called get_sidebar(), update the page layout to a
//   two-column grid: main content column + sidebar column.
//   Recommended grid: 8/12 + 4/12 columns (or 2fr + 1fr).
//   Place the "Sidebar" Symbol in the right column.
// ============================================================


// ============================================================
// RECOMMENDED PAGE LAYOUT STRUCTURE (for pages using sidebar)
// ============================================================
//
// Webflow Element Hierarchy:
//
//   Section.content-area
//   └── Container.container                  (max-width wrapper)
//       └── Div.content-with-sidebar         (CSS Grid or Flexbox row)
//           ├── Main.site-main               (primary content column)
//           │   └── [page/post content here]
//           └── Symbol: "Sidebar"            (sidebar column)
//               └── Div.widget-area          [role="complementary"]
//                   ├── Symbol: Widget - Search
//                   ├── Symbol: Widget - Recent Posts
//                   ├── Symbol: Widget - Categories
//                   ├── Symbol: Widget - Recent Comments
//                   ├── Symbol: Widget - Archives
//                   └── Symbol: Widget - Tag Cloud
//                       (see each widget Symbol definition below)
//
// Webflow CSS (set on Div.content-with-sidebar):
//   Display        : Grid
//   Grid Template  : 2fr 1fr   (or 8/12 + 4/12 equivalent)
//   Column Gap     : 2rem (adjust to match original theme spacing)
//   Align Items    : Start
//   Responsive:
//     Tablet/Mobile: Grid Template -> 1fr (stack vertically)
// ============================================================


// ============================================================
// WIDGET SYMBOL DEFINITIONS
// ============================================================
// WordPress sidebar-1 widget area typically contains a set of
// registered widgets. The _s (Underscores) starter theme registers
// the following default widgets. Each must become its own
// Webflow Symbol so it can be reused independently.
// ============================================================


// ------------------------------------------------------------
// WIDGET SYMBOL 1: "Widget - Search"
// ------------------------------------------------------------
// WordPress Source  : WP_Widget_Search
// Webflow Equivalent: Webflow Search Block (requires Business plan)
//                     OR a styled form that submits to site search
//
// Webflow Element Hierarchy:
//   Div.widget.widget_search
//   ├── H2.widget-title            "Search"  (static text label)
//   └── Form.search-form           (Webflow Form Block)
//       ├── Label.screen-reader-text [for="s"]  "Search for:"
//       │     (visually hidden — add class: sr-only)
//       ├── Input.search-field
//       │     Type     : Search
//       │     Name     : s
//       │     Placeholder: "Search …"
//       │     aria-label: "Search for:"
//       └── Button.search-submit
//             Type: Submit
//             Text: "Search"  (or magnifier icon SVG)
//
// TODO: Manual action in Webflow — Webflow's native Search
//   component requires Business plan or higher. On lower plans,
//   use a custom HTML Embed with a search form that posts to
//   the site's search URL. Alternatively, integrate Algolia
//   DocSearch or a third-party search widget via Embed block.
// ------------------------------------------------------------


// ------------------------------------------------------------
// WIDGET SYMBOL 2: "Widget - Recent Posts"
// ------------------------------------------------------------
// WordPress Source  : WP_Widget_Recent_Posts
// Webflow Equivalent: CMS Collection List filtered/sorted by date
//
// Webflow Element Hierarchy:
//   Div.widget.widget_recent_entries
//   ├── H2.widget-title            "Recent Posts"  (static text)
//   └── Collection List Wrapper    (source: Blog Posts Collection)
//         Settings:
//           Source  : Blog Posts (or equivalent Collection name)
//           Sort    : Created Date — Newest First
//           Limit   : 5 items
//           Filter  : none (show all published)
//       └── Collection List        (Collection List element)
//           └── Collection Item    (template for each post)
//               └── List Item.recentcomments  (or li equivalent)
//                   ├── Link Block.recentpost-link
//                   │     Href binding: Item Slug (Post URL)
//                   │     Text binding: Item Name (Post Title)  // MIGRATED: the_title()
//                   └── Div.post-date
//                         Text binding: Created Date (formatted)  // MIGRATED: get_the_date()
//
// TODO: Manual action in Webflow — Set Collection List source to
//   your "Blog Posts" Collection. Bind the link href to the post
//   URL field and the text to the post Name field.
// ------------------------------------------------------------


// ------------------------------------------------------------
// WIDGET SYMBOL 3: "Widget - Recent Comments"
// ------------------------------------------------------------
// WordPress Source  : WP_Widget_Recent_Comments
// Webflow Equivalent: // TODO: Webflow has no native comments system.
//
// TODO: Manual action in Webflow — If using Disqus for comments,
//   this widget has no direct equivalent. Options:
//   (a) Remove the widget entirely from the sidebar
//   (b) Replace with a "Recent Posts" or "Popular Posts" widget
//   (c) Use a third-party commenting service API to pull recent
//       comments via a custom HTML Embed + JavaScript fetch
// ------------------------------------------------------------


// ------------------------------------------------------------
// WIDGET SYMBOL 4: "Widget - Archives"
// ------------------------------------------------------------
// WordPress Source  : WP_Widget_Archives
//   Outputs a list of monthly archive links: /2024/01/, /2023/12/
// Webflow Equivalent: // TODO: Date-based archives not supported natively
//
// Webflow Element Hierarchy (static fallback):
//   Div.widget.widget_archive
//   ├── H2.widget-title            "Archives"  (static text)
//   └── Div.archive-list
//       └── [Static links added manually, e.g.:]
//           ├── Link.archive-link  "January 2024"  href="/blog?month=2024-01"
//           ├── Link.archive-link  "December 2023" href="/blog?month=2023-12"
//           └── ...
//
// TODO: Manual action in Webflow — Webflow has no date-based
//   archive pages. Options:
//   (a) Remove this widget; replace with "Recent Posts" Collection List
//   (b) Build static archive links manually and update monthly
//   (c) Implement date filtering via URL parameters + Finsweet
//       CMS Filter (third-party Webflow attribute library)
//   (d) Set up 301 redirects for all /year/month/ WordPress URLs
//       pointing to the main blog Collection List page
// ------------------------------------------------------------


// ------------------------------------------------------------
// WIDGET SYMBOL 5: "Widget - Categories"
// ------------------------------------------------------------
// WordPress Source  : WP_Widget_Categories
//   Outputs a list of post category links
// Webflow Equivalent: CMS Collection List of Categories
//
// Webflow Element Hierarchy:
//   Div.widget.widget_categories
//   ├── H2.widget-title            "Categories"  (static text)
//   └── Collection List Wrapper    (source: Categories Collection)
//         Settings:
//           Source  : Categories (Webflow CMS Collection)
//           Sort    : Name — A to Z
//           Limit   : 20 items (adjust as needed)
//       └── Collection List
//           └── Collection Item
//               └── Div.cat-item
//                   └── Link.cat-link
//                         Href binding: Category Slug URL  // MIGRATED: get_category_link()
//                         Text binding: Category Name      // MIGRATED: cat->name
//
// NOTE: The "Categories" Collection in Webflow must be created
//   first with at minimum: Name (built-in), Slug (built-in).
//   Blog Posts Collection should have a Reference field pointing
//   to Categories.
//
// TODO: Manual action in Webflow — Create a "Categories" CMS
//   Collection. Add a Reference field "Category" to Blog Posts.
//   Set up a Collection List page for each category (or use
//   Finsweet CMS Filter for filtering the main blog page).
// ------------------------------------------------------------


// ------------------------------------------------------------
// WIDGET SYMBOL 6: "Widget - Meta" (Login/RSS links)
// ------------------------------------------------------------
// WordPress Source  : WP_Widget_Meta
//   Outputs: Site Admin link, RSS feed link, WordPress.org link
// Webflow Equivalent: Remove or replace with relevant links
//
// Webflow Element Hierarchy (optional static replacement):
//   Div.widget.widget_meta
//   ├── H2.widget-title            "Meta"  (static text)
//   └── Div.meta-links
//       ├── Link  "RSS Feed"   href="/feed.xml"    // MIGRATED: WordPress /feed/ -> Webflow /feed.xml
//       └── Link  "Site Admin" href="/login"       // TODO: only if admin login is needed
//
// TODO: Manual action in Webflow — The Meta widget typically
//   contains WordPress-specific links (wp-login, wp-admin, feeds).
//   Evaluate whether any of these are needed on the Webflow site.
//   WordPress /feed/ -> Webflow /feed.xml (set up 301 redirect).
//   Remove wp-admin and WordPress.org links entirely.
// ------------------------------------------------------------


// ------------------------------------------------------------
// WIDGET SYMBOL 7: "Widget - Tag Cloud"
// ------------------------------------------------------------
// WordPress Source  : WP_Widget_Tag_Cloud
//   Outputs a weighted list of tag links
// Webflow Equivalent: CMS Collection List of Tags (uniform size)
//
// Webflow Element Hierarchy:
//   Div.widget.widget_tag_cloud
//   ├── H2.widget-title            "Tags"  (static text)
//   └── Div.tagcloud
//       └── Collection List Wrapper (source: Tags Collection)
//             Settings:
//               Source  : Tags (Webflow CMS Collection)
//               Sort    : Name — A to Z
//               Limit   : 45 items
//             Display : Flex, Wrap
//           └── Collection List
//               └── Collection Item
//                   └── Link.tag-cloud-link
//                         Href binding: Tag Slug URL   // MIGRATED: get_tag_link()
//                         Text binding: Tag Name       // MIGRATED: tag->name
//                         Class: tag-link (static size — weighted cloud not supported)
//
// TODO: Manual action in Webflow — Webflow Collection Lists cannot
//   dynamically size elements based on post count (no weighted tag
//   cloud equivalent). All tags will render at uniform size/style.
//   If weighted sizing is critical, use a custom HTML Embed that
//   fetches from the Webflow CMS API and renders a weighted cloud
//   via JavaScript.
// ------------------------------------------------------------


// ------------------------------------------------------------
// WIDGET SYMBOL 8: "Widget - Text / HTML"
// ------------------------------------------------------------
// WordPress Source  : WP_Widget_Text (arbitrary HTML/text widget)
// Webflow Equivalent: Rich Text Block or HTML Embed within sidebar
//
// Webflow Element Hierarchy:
//   Div.widget.widget_text
//   ├── H2.widget-title            [Widget title]  (static text)
//   └── Div.textwidget
//       └── Rich Text Block        (for formatted text/HTML content)
//             OR
//       └── HTML Embed             (for widgets with scripts/iframes)
//
// TODO: Manual action in Webflow — Copy the content from each
//   Text widget in WordPress (Appearance -> Widgets) and paste into
//   the corresponding Rich Text Block or HTML Embed in Webflow.
//   Widgets containing scripts (e.g., ad code, social embeds)
//   must use Webflow's HTML Embed element.
// ------------------------------------------------------------


// ============================================================
// CSS CLASS REFERENCE
// ============================================================
// The following CSS classes from the original sidebar.php and
// its widget output must be recreated or mapped in Webflow:
//
// #secondary          -> Webflow custom attribute id="secondary"
// .widget-area        -> Webflow class: widget-area
//                         (root wrapper Div Block in Sidebar Symbol)
// .widget             -> Webflow class: widget
//                         (root wrapper for each widget Symbol)
// .widget-title       -> Webflow class: widget-title
//                         (H2 heading inside each widget Symbol)
// .widget_search      -> Webflow class: widget_search
// .widget_recent_entries -> Webflow class: widget_recent_entries
// .widget_categories  -> Webflow class: widget_categories
// .widget_archive     -> Webflow class: widget_archive
// .widget_tag_cloud   -> Webflow class: widget_tag_cloud
// .widget_meta        -> Webflow class: widget_meta
// .widget_text        -> Webflow class: widget_text
// .search-form        -> Webflow class: search-form  (Form Block)
// .search-field       -> Webflow class: search-field (Input element)
// .search-submit      -> Webflow class: search-submit (Submit Button)
// .screen-reader-text -> Webflow class: screen-reader-text
//                         (visually hidden, accessible label)
//                         CSS: position:absolute; clip:rect(1px,1px,1px,1px);
//                              width:1px; height:1px; overflow:hidden;
// .tagcloud           -> Webflow class: tagcloud (Div, display:flex, flex-wrap:wrap)
// .tag-cloud-link     -> Webflow class: tag-cloud-link (Link inside tagcloud)
// .cat-item           -> Webflow class: cat-item
// .recentcomments     -> Webflow class: recentcomments
// ============================================================


// ============================================================
// TAILWIND CSS NOTE (from package.json context)
// ============================================================
// The build system uses Tailwind CSS v1. If the Webflow project
// uses custom code with Tailwind utility classes, the sidebar
// layout can be expressed as:
//
//   Sidebar wrapper  : class="w-full lg:w-1/3 pl-0 lg:pl-8"
//   Widget wrapper   : class="mb-8"
//   Widget title     : class="text-lg font-semibold mb-4 border-b pb-2"
//   Tag cloud        : class="flex flex-wrap gap-2"
//   Tag link         : class="text-sm bg-gray-100 px-2 py-1 rounded hover:bg-gray-200"
//
// In Webflow, these utility styles are set via the Style Panel
// rather than utility classes. The Tailwind classes above serve
// as a reference for the visual styling intent.
// ============================================================


// ============================================================
// 301 REDIRECT NOTES (SEO preservation)
// ============================================================
// The WordPress sidebar itself does not have a public URL, so
// no redirects are needed for sidebar.php specifically.
// However, widgets within the sidebar link to URLs that DO
// require redirects:
//
// WordPress URL Pattern       -> Webflow URL / Action
// /category/[slug]/           -> /blog?category=[slug] or /[slug]-posts/
//                                + 301 redirect from old URL
// /tag/[slug]/                -> /blog?tag=[slug] or /tag/[slug]/
//                                + 301 redirect from old URL
// /[year]/[month]/            -> /blog (date archives not supported)
//                                + 301 redirect to /blog
// /feed/                      -> /feed.xml  + 301 redirect
// /search/?s=[query]          -> Webflow search URL pattern
//
// TODO: Manual action in Webflow — Add all redirect rules in:
//   Webflow Project Settings -> Hosting -> 301 Redirects
// ============================================================