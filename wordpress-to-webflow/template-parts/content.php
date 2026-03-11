// WEBFLOW MIGRATION: template-parts/content.php
// SOURCE: WordPress template part used in The Loop (index.php, archive.php, search.php)
// TARGET: Webflow CMS Collection List Item Template + CMS Collection Template Page sections
//
// ============================================================
// MIGRATION OVERVIEW
// ============================================================
//
// This template part serves dual purposes in WordPress:
//   1. Singular view (is_singular()): renders a full post with <h1> title (no link)
//   2. Archive/loop view (!is_singular()): renders a post card with <h2> linked title
//
// In Webflow, these two contexts become SEPARATE structures:
//   A. The Collection Template page (single post view) — /blog-posts/[slug]
//   B. The Collection List item card (archive/index/loop view) — used inside a Collection List
//
// There is NO single reusable Symbol that covers both cases directly,
// because CMS field bindings behave differently in template vs. list contexts.
// However, visual styling can be shared via Webflow global classes.
//
// ============================================================
// SECTION A: CMS COLLECTION TEMPLATE PAGE — single post view
// ============================================================
// Corresponds to: is_singular() == true
// Webflow page type: CMS Collection Template (created automatically when a Collection is made)
// Webflow location: CMS > Blog Posts > Template page
// URL pattern: /blog/[post-slug] (set in Collection settings)
//
// STRUCTURE BLUEPRINT:
//
// <Page: Blog Post Template>
//   ├── [Symbol] Navbar                          // MIGRATED: from get_header() — global Navbar Symbol
//   │
//   ├── <Section class="post-hero">              // NEW: wraps featured image + entry header
//   │     └── <Container class="post-hero__inner">
//   │           ├── <Div class="entry-header">
//   │           │     ├── <H1 class="entry-title">
//   │           │     │     └── [Bind: CMS Field → Name]
//   │           │     │         // MIGRATED: the_title('<h1 class="entry-title">','</h1>') on singular
//   │           │     │
//   │           │     └── <Div class="entry-meta">
//   │           │           // MIGRATED: _s_posted_on() + _s_posted_by()
//   │           │           // Only rendered when post type === 'post' — this is the Blog Posts collection
//   │           │           ├── <Span class="entry-meta__date">
//   │           │           │     └── [Bind: CMS Field → Created On / Published Date]
//   │           │           │         Format: "MMMM D, YYYY" (match original _s_posted_on output)
//   │           │           │         // MIGRATED: _s_posted_on() → CMS DateTime field binding
//   │           │           │
//   │           │           └── <Span class="entry-meta__author">
//   │           │                 // TODO: Manual action in Webflow — _s_posted_by() outputs the author
//   │           │                 // Webflow has no native Author field unless you create an "Authors"
//   │           │                 // CMS Collection and add a Reference field "Author" to Blog Posts.
//   │           │                 // Bind: CMS Reference Field → Author → Name
//   │           │                 └── [Bind: CMS Reference Field → Author → Name]
//   │           │
//   │           └── <Div class="post-thumbnail">
//   │                 // MIGRATED: _s_post_thumbnail() → CMS Image field binding
//   │                 // Conditionally rendered in WP only when a thumbnail exists.
//   │                 // In Webflow: set Conditional Visibility on this Div:
//   │                 //   "Featured Image is set" (not empty)
//   │                 └── <Image class="post-thumbnail__img attachment-post-thumbnail size-post-thumbnail wp-post-image">
//   │                       [Bind: CMS Field → Featured Image]
//   │                       Alt: [Bind: CMS Field → Featured Image → Alt Text]
//   │                       // MIGRATED: wp_get_attachment_image() with 'post-thumbnail' size
//   │                       // Webflow auto-generates responsive image sizes — no srcset config needed
//
//   ├── <Section class="post-body">
//   │     └── <Container class="post-body__inner">
//   │           ├── <Article class="entry-content">
//   │           │     // MIGRATED: the_content() → CMS Rich Text field binding
//   │           │     // NOTE: the_content() "Continue reading" link is not needed on singular view
//   │           │     // NOTE: wp_link_pages() multi-page post support:
//   │           │     //   TODO: Manual action in Webflow — Webflow CMS Rich Text does not support
//   │           │     //   WordPress <!--nextpage--> pagination. If posts use this feature,
//   │           │     //   split content into separate CMS fields (Page1Content, Page2Content)
//   │           │     //   or restructure content before import. Recommend removing <!--nextpage-->
//   │           │     //   tags during content export and treating each post as single-page.
//   │           │     └── [Bind: CMS Field → Content (Rich Text)]
//   │           │
//   │           └── <Footer class="entry-footer">
//   │                 // MIGRATED: _s_entry_footer() outputs categories, tags, edit link
//   │                 //
//   │                 // _s_entry_footer() typically renders:
//   │                 //   - Posted in: [category links]
//   │                 //   - Tagged: [tag links]
//   │                 //   - Edit Post link (admin only)
//   │                 //
//   │                 // In Webflow, replicate as:
//   │                 ├── <Div class="entry-footer__categories">
//   │                 │     <Span>Posted in:</Span>
//   │                 │     // MIGRATED: category links → CMS MultiReference binding
//   │                 │     // TODO: Manual action in Webflow — Add a Collection List inside this Div,
//   │                 │     //   Source: Current Blog Post → Categories (MultiReference field)
//   │                 │     //   Each item: <Link> bound to Category Name + Category Slug URL
//   │                 │     └── [Collection List: Categories MultiReference]
//   │                 │           └── <Link class="entry-footer__cat-link">
//   │                 │                 [Bind: Category → Name]
//   │                 │                 [Link: /category/[Category Slug] or Collection List page]
//   │                 │
//   │                 └── <Div class="entry-footer__tags">
//   │                       <Span>Tagged:</Span>
//   │                       // MIGRATED: tag links → CMS MultiReference binding
//   │                       // TODO: Manual action in Webflow — Add a Collection List inside this Div,
//   │                       //   Source: Current Blog Post → Tags (MultiReference field)
//   │                       //   Each item: <Link> bound to Tag Name + Tag Slug URL
//   │                       └── [Collection List: Tags MultiReference]
//   │                             └── <Link class="entry-footer__tag-link">
//   │                                   [Bind: Tag → Name]
//   │                                   [Link: /tag/[Tag Slug] or Collection List page]
//   │
//   // NOTE: Edit Post link from _s_entry_footer() — no Webflow equivalent for frontend edit links
//   // TODO: Manual action in Webflow — Omit the edit link entirely, or add a custom code embed
//   //   that shows an edit URL only when a logged-in Webflow Editor session is detected.
//   //   This is non-trivial and generally not recommended for Webflow-hosted sites.
//
//   └── [Symbol] Footer                          // MIGRATED: from get_footer() — global Footer Symbol
//
//
// ============================================================
// SECTION B: COLLECTION LIST ITEM CARD — archive/loop view
// ============================================================
// Corresponds to: is_singular() == false (index.php, archive.php, category.php, etc.)
// Webflow usage: Inside a Collection List component on index/archive/category pages
// The Collection List is placed on the Blog Index page, Category pages, Tag pages, etc.
//
// COLLECTION LIST SETTINGS (set in Webflow Designer):
//   Source Collection: Blog Posts
//   Items limit: 10 (match WordPress posts_per_page setting, typically 10)
//   Sort: Published Date → Newest First
//   Pagination: Enable (matches WordPress pagination)
//   Filters: Set per page context (e.g., on Category page, filter by Category Reference)
//
// COLLECTION LIST ITEM TEMPLATE STRUCTURE:
//
// <Collection List Wrapper class="wp-posts-list">
//   └── <Collection List class="posts-list__grid">        // or "posts-list__list" depending on layout
//         └── <Collection Item>                            // repeats per post
//               └── <Article class="post hentry">         // MIGRATED: post_class() adds 'post', 'hentry',
//                                                          //   type-post, status-publish, etc.
//                                                          // In Webflow: apply static class "post-card"
//                                                          // The dynamic post_class() IDs/slugs are not
//                                                          // replicable directly; use static Webflow classes.
//                                                          // MIGRATED: id="post-{ID}" → not needed in Webflow
//                     │
//                     ├── <Div class="post-card__thumbnail">
//                     │     // MIGRATED: _s_post_thumbnail() in loop context
//                     │     // Conditional Visibility: "Featured Image is set"
//                     │     └── <Link class="post-card__thumbnail-link">
//                     │           [Link: CMS Field → Slug (page URL)]
//                     │           // MIGRATED: the_permalink() wrapping thumbnail in archive context
//                     │           └── <Image class="post-card__img">
//                     │                 [Bind: CMS Field → Featured Image]
//                     │                 Alt: [Bind: CMS Field → Featured Image → Alt Text]
//                     │
//                     ├── <Header class="entry-header">
//                     │     ├── <H2 class="entry-title">
//                     │     │     // MIGRATED: the_title('<h2 class="entry-title"><a href="...">', '</a></h2>')
//                     │     │     // Non-singular: title is a link to the post permalink
//                     │     │     └── <Link class="entry-title__link">
//                     │     │           [Bind: CMS Field → Name]
//                     │     │           [Link: CMS Field → Slug (page URL)]
//                     │     │
//                     │     └── <Div class="entry-meta">
//                     │           // MIGRATED: _s_posted_on() + _s_posted_by()
//                     │           // Only shown when post type === 'post' — always true for Blog Posts collection
//                     │           ├── <Span class="entry-meta__date">
//                     │           │     [Bind: CMS Field → Created On]
//                     │           │     Format: "MMMM D, YYYY"
//                     │           │     // MIGRATED: _s_posted_on() → CMS DateTime binding
//                     │           │
//                     │           └── <Span class="entry-meta__author">
//                     │                 // TODO: Manual action in Webflow — same as singular view above:
//                     │                 // Requires Authors Collection + Reference field on Blog Posts
//                     │                 [Bind: CMS Reference → Author → Name]
//                     │
//                     ├── <Div class="entry-content entry-summary">
//                     │     // MIGRATED: the_content() in loop context outputs excerpt or full content
//                     │     // WordPress typically shows excerpt on archive/loop pages.
//                     │     // the_content() with "Continue reading" link in loop context:
//                     │     //   → Use CMS Plain Text field "Excerpt" or "Summary" bound here
//                     │     //   → The "Continue reading" link becomes a separate CTA link below
//                     │     // NOTE: If posts use <!--more--> tags, export content strips to excerpt.
//                     │     //   Recommend: Add a dedicated "Excerpt" Plain Text field to Blog Posts CMS Collection
//                     │     //   and populate it during content export (use WP excerpt or custom field).
//                     │     └── <Paragraph class="entry-summary__text">
//                     │           [Bind: CMS Field → Excerpt / Summary (Plain Text, 500 char)]
//                     │           // MIGRATED: the_excerpt() equivalent for loop view
//                     │
//                     ├── <Link class="entry-content__read-more">
//                     │     [Link: CMS Field → Slug (page URL)]
//                     │     Text: "Continue reading"
//                     │     // MIGRATED: "Continue reading" link from the_content() loop output
//                     │     // Includes screen-reader span:
//                     │     // <Span class="screen-reader-text"> " [Post Name]"</Span>
//                     │     //   TODO: Manual action in Webflow — add visually hidden span via custom code
//                     │     //   embed or use aria-label attribute on the link element instead:
//                     │     //   aria-label="Continue reading [Post Name]" (bind Post Name via CMS)
//                     │
//                     └── <Footer class="entry-footer">
//                           // MIGRATED: _s_entry_footer() in loop context
//                           // In archive/loop view, _s_entry_footer() typically renders
//                           // category and tag links (same as singular but more compact)
//                           // TODO: Manual action in Webflow — replicate using nested Collection Lists
//                           //   same pattern as Section A entry-footer above
//                           //   (Categories MultiReference List + Tags MultiReference List)
//
//
// ============================================================
// CMS COLLECTION FIELD SCHEMA — Blog Posts Collection
// ============================================================
// Required fields to support this template's bindings:
//
// FIELD NAME            WEBFLOW FIELD TYPE     MAPS FROM (WordPress)
// ─────────────────────────────────────────────────────────────────────
// Name (built-in)       Plain Text             post_title / the_title()
// Slug (built-in)       Plain Text             post_name (permalink slug)
// Created On (built-in) DateTime               post_date / _s_posted_on()
// Published On          DateTime               post_date_gmt (use for display date)
// Content               Rich Text              post_content / the_content()
// Excerpt               Plain Text (500)       post_excerpt / the_excerpt()
// Featured Image        Image                  _thumbnail_id / the_post_thumbnail()
// Author                Reference → Authors    post_author / _s_posted_by()
//                       // TODO: Create a separate "Authors" CMS Collection with Name, Bio, Avatar fields
//                       // then link via Reference field here
// Categories            MultiReference →       wp_terms (category taxonomy)
//                       Categories Collection  // Create "Categories" CMS Collection: Name, Slug, Description
// Tags                  MultiReference →       wp_terms (post_tag taxonomy)
//                       Tags Collection        // Create "Tags" CMS Collection: Name, Slug
//
// OPTIONAL / EXTENDED FIELDS (from post_class, entry-footer, SEO):
// SEO Title             Plain Text             _yoast_wpseo_title
// SEO Description       Plain Text (256)       _yoast_wpseo_metadesc
// OG Image              Image                  _yoast_wpseo_opengraph-image
// Is Featured           Switch (Bool)          custom meta / sticky post flag
//                                              // Use for "featured" filters in Collection Lists
//
//
// ============================================================
// CSS CLASS MIGRATION MAP
// ============================================================
// WordPress class           → Webflow class equivalent / notes
// ──────────────────────────────────────────────────────────────
// .entry-header             → .entry-header          (keep, apply to Div)
// .entry-title              → .entry-title           (keep, apply to H1/H2)
// .entry-meta               → .entry-meta            (keep, apply to Div)
// .entry-content            → .entry-content         (keep, apply to Div/Article)
// .entry-footer             → .entry-footer          (keep, apply to Footer)
// .entry-summary            → .entry-summary         (add alongside .entry-content in list view)
// .page-links               → .page-links            (TODO: not needed if <!--nextpage--> removed)
// .post-thumbnail           → .post-card__thumbnail  (rename for BEM clarity)
// .wp-post-image            → remove                 // WordPress utility class, not needed
// .screen-reader-text       → .sr-only               (replace with standard accessibility class)
//                                                    // Add to Webflow global CSS:
//                                                    //   .sr-only { position: absolute; width: 1px;
//                                                    //     height: 1px; padding: 0; margin: -1px;
//                                                    //     overflow: hidden; clip: rect(0,0,0,0);
//                                                    //     white-space: nowrap; border: 0; }
// post_class() dynamic IDs  → remove                 // id="post-123" not needed; use data-attributes if required
// .hentry                   → .hentry                (keep for microformat compatibility if desired)
// .type-post                → remove                 // WordPress-specific utility, not needed
// .status-publish           → remove                 // WordPress-specific utility, not needed
// .format-standard          → remove                 // WordPress-specific utility, not needed
//
//
// ============================================================
// SYMBOL BREAKDOWN (Webflow Symbols to create)
// ============================================================
//
// 1. Symbol: "Post Card" (used in Collection List on index/archive pages)
//    Contains: Section B structure above (thumbnail, header, meta, excerpt, read-more, footer)
//    Note: In Webflow, the Collection List item IS the repeating unit — the card structure
//    lives directly inside the Collection Item. You cannot nest a Symbol inside a Collection Item
//    and bind CMS fields through it. Build the card structure directly in the Collection List item.
//    // TODO: Manual action in Webflow — Style the Collection Item element with card layout directly.
//    //   Do not attempt to use a Symbol for the repeating card as CMS bindings won't pass through.
//
// 2. Symbol: "Post Meta Bar" (date + author, reused in both template and list card)
//    // TODO: Manual action in Webflow — Symbols with CMS bindings are not supported.
//    //   Replicate the meta bar structure manually in both the Collection Template page
//    //   and the Collection List item. Use the same Webflow class names for consistent styling.
//    //   Style changes via the shared class will apply to both locations.
//
// 3. Symbol: "Post Entry Footer" (categories + tags links section)
//    Same limitation as above — CMS bindings cannot pass through Symbols.
//    // TODO: Manual action in Webflow — build directly inside Collection Item / Template page.
//
//
// ============================================================
// PAGINATION — wp_link_pages() and the_posts_pagination()
// ============================================================
// wp_link_pages() — splits a SINGLE POST into multiple pages via <!--nextpage-->:
//   // TODO: Manual action in Webflow — Not supported in Webflow CMS.
//   Recommendation: During content export, search for <!--nextpage--> in post_content.
//   If found, either:
//     a) Remove <!--nextpage--> and treat post as single page (simplest approach), OR
//     b) Split content at <!--nextpage--> into separate CMS fields (Page1, Page2, Page3)
//        and show/hide sections with Webflow interactions — complex and not scalable
//   Document all posts using <!--nextpage--> before migration for client review.
//
// the_posts_pagination() — pagination between archive pages:
//   MIGRATED: Webflow Collection List has built-in pagination.
//   Enable in Collection List settings. Webflow generates /blog?offset=10 style URLs.
//   // TODO: Map old /page/2/ WordPress pagination URLs to Webflow's ?offset= pattern via 301 redirects.
//   //   Generate redirect rules: /blog/page/2/ → /blog?offset=10, /blog/page/3/ → /blog?offset=20, etc.
//   //   Add these to Webflow Hosting → 301 Redirects.
//
//
// ============================================================
// CONDITIONAL TEMPLATE LOGIC MIGRATION
// ============================================================
// is_singular() check in this file creates two rendering paths.
// Webflow handles this through separate page types — no conditionals needed:
//
// WordPress conditional           → Webflow equivalent
// ─────────────────────────────────────────────────────────
// is_singular() == true           → CMS Collection Template page (auto-created per Collection)
// is_singular() == false          → Collection List item on index/archive/category pages
// get_post_type() === 'post'      → Only Blog Posts Collection uses this template
//                                   (CPTs have their own Collection Templates)
// is_home() / is_front_page()     → Webflow Homepage (set in Pages panel)
// is_archive() / is_category()    → Webflow Collection List pages with filter settings
// is_tag()                        → Webflow Collection List page filtered by Tags MultiReference
//
// NOTE: The entry-meta Div is conditionally rendered only for post_type === 'post'.
// In Webflow, since this Collection Template is specifically for Blog Posts (the 'post' type),
// the entry-meta is always shown. If you migrate Custom Post Types to separate Collections,
// simply do not include entry-meta in their Collection Templates.
// MIGRATED: Conditional rendering via get_post_type() check → handled by Collection separation.
//
//
// ============================================================
// CONTENT EXPORT NOTES FOR THIS TEMPLATE
// ============================================================
// When exporting WordPress post content for Webflow CMS import (CSV or API):
//
// 1. post_content field:
//    - Strip all Gutenberg block comments: <!-- wp:* --> and <!-- /wp:* -->
//    - Convert wpautop() double-line-breaks to <p> tags (WordPress does this on output,
//      but raw DB content may have unformatted line breaks — run wpautop() before export)
//    - Remove <!--more--> tags (split content at this point for Excerpt field if no post_excerpt)
//    - Remove <!--nextpage--> tags (document posts affected)
//    - Convert [gallery] shortcodes to HTML figure/img grids or Webflow Embed HTML
//    - Convert [caption] shortcodes to <figure><img><figcaption> HTML
//    - Convert [embed] shortcodes to iframe HTML
//    - Replace all wp-content/uploads URLs with placeholder {{ASSET_URL}} for post-migration replacement
//    - Target field in Webflow CMS: Content (Rich Text)
//
// 2. post_excerpt field:
//    - If empty, generate from first 55 words of post_content (match WordPress default excerpt length)
//    - Strip all HTML tags from excerpt
//    - Target field in Webflow CMS: Excerpt (Plain Text)
//
// 3. Featured Image (_thumbnail_id):
//    - Export as full wp-content/uploads URL
//    - Upload to Webflow Assets post-migration
//    - Replace URL in CMS import data with new Webflow Asset URL
//    - Preserve original alt text from _wp_attachment_image_alt meta
//    - Target field in Webflow CMS: Featured Image (Image field)
//
// 4. post_date:
//    - Format as ISO 8601 for Webflow CMS API import: "2024-01-15T10:30:00Z"
//    - Target field in Webflow CMS: Published On (DateTime)
//
// 5. post_author → author display name:
//    - Join with Authors Collection items
//    - Export author display_name and user_login for Authors Collection
//    - Set Reference field value to matching Author Collection item ID
//    - Target field in Webflow CMS: Author (Reference → Authors)
//
// 6. Categories and Tags (taxonomies):
//    - Export as separate Collections first (Categories, Tags)
//    - Then reference by Webflow Collection Item ID in Blog Posts import
//    - Target fields: Categories (MultiReference), Tags (MultiReference)
//
//
// ============================================================
// 301 REDIRECT REQUIREMENTS FROM THIS TEMPLATE
// ============================================================
// Generated by this template's URL patterns:
//
// SOURCE (WordPress)                   DESTINATION (Webflow)         TYPE
// ──────────────────────────────────────────────────────────────────────────
// /[post-slug]/                     → /blog/[post-slug]             301
// /?p=[post-id]                     → /blog/[post-slug]             301
// /[category]/[post-slug]/          → /blog/[post-slug]             301  // if /%category%/%postname%/ permalink
// /[year]/[month]/[day]/[slug]/     → /blog/[post-slug]             301  // if date-based permalink
// /[year]/[month]/[slug]/           → /blog/[post-slug]             301
// /page/2/                          → /blog?offset=10               301  // loop archive pagination
// /category/[slug]/                 → /category/[slug]              301  // Collection List page (if URL matches)
// /category/[slug]/page/2/          → /category/[slug]?offset=10    301
// /tag/[slug]/                      → /tag/[slug]                   301
//
// TODO: Manual action — Generate complete 301 redirect CSV from WordPress export data:
//   1. Export all published posts with their current permalink and new Webflow URL
//   2. Export all category/tag archive URLs
//   3. Import redirect CSV into Webflow: Hosting → Redirects → Import
//   4. Test all redirects post-launch with a crawler (Screaming Frog or similar)