// ============================================================
// WEBFLOW MIGRATION BLUEPRINT
// Source: template-parts/content-search.php
// Target: Webflow Symbol — "Search Result Card"
// Usage: Placed inside a Collection List on the Search Results page
// ============================================================
//
// OVERVIEW
// --------
// This WordPress template part renders a single search result item
// inside the search.php loop. In Webflow, this becomes a reusable
// Symbol called "Search Result Card" that is used as the item template
// inside a Collection List on the Search Results page.
//
// Because Webflow's native Site Search (requires Business plan or higher)
// renders its own result items, this Symbol blueprint covers TWO scenarios:
//   A) Webflow Native Search  — results rendered by Webflow's search engine
//   B) Custom CMS Search      — a Collection List filtered via query params
//                               using Finsweet CMS Filter or custom JS
//
// ============================================================
// WEBFLOW COMPONENT: Search Result Card (Symbol)
// ============================================================
//
// SYMBOL NAME: Search Result Card
// RECOMMENDED FILE LOCATION IN WEBFLOW: Symbols panel > "Search Result Card"
//
// ── HTML STRUCTURE & ELEMENT HIERARCHY ──────────────────────
//
// <article>  [Webflow: DIV element, tag overridden to <article>]
//   │  Classes: search-result-card
//   │  // MIGRATED: WordPress <article id="post-{ID}" class="post-{ID} post type-post ...">
//   │  //           The dynamic id="post-{ID}" is dropped — not needed in Webflow.
//   │  //           post_class() utility classes (hentry, type-post, status-publish, etc.)
//   │  //           are replaced by a single semantic Webflow class.
//   │
//   ├── <header>  [Webflow: DIV, tag overridden to <header>]
//   │   │  Classes: search-result-card__header  (also: entry-header for legacy CSS parity)
//   │   │  // MIGRATED: <header class="entry-header">
//   │   │
//   │   ├── <h2>  [Webflow: Heading element, H2]
//   │   │   │  Classes: search-result-card__title  (also: entry-title)
//   │   │   │  // MIGRATED: the_title('<h2 class="entry-title"><a href="%s" rel="bookmark">', '</a></h2>')
//   │   │   │
//   │   │   └── <a>  [Webflow: Link element]
//   │   │          Classes: search-result-card__title-link
//   │   │          href: [BIND → CMS Item URL / Search Result URL]
//   │   │          rel: bookmark
//   │   │          Text: [BIND → CMS Name field / Search Result Title]
//   │   │          // MIGRATED: get_permalink() → Webflow CMS item slug binding
//   │   │          // MIGRATED: the_title() → Webflow CMS Name field binding
//   │   │
//   │   └── <div>  [Webflow: DIV] — CONDITIONAL: visible for post type "post" only
//   │          Classes: search-result-card__meta  (also: entry-meta)
//   │          Visibility: [TODO — see Conditional Visibility note below]
//   │          // MIGRATED: if ( 'post' === get_post_type() ) → Webflow conditional visibility
//   │          //           on a Switch/Boolean CMS field "Is Blog Post" = true
//   │          //           OR: duplicate the Symbol for post vs. page result types
//   │          //
//   │          ├── <span>  [Webflow: Text element]
//   │          │      Classes: search-result-card__posted-on  (also: posted-on)
//   │          │      Content: [BIND → CMS "Published Date" DateTime field, formatted]
//   │          │      // MIGRATED: _s_posted_on() outputs:
//   │          │      //   <span class="posted-on">
//   │          │      //     <a href="{permalink}" rel="bookmark">
//   │          │      //       <time class="entry-date published" datetime="{ISO date}">
//   │          │      //         {formatted date}
//   │          │      //       </time>
//   │          │      //     </a>
//   │          │      //   </span>
//   │          │      // Webflow: Text Block bound to Published On (Date field)
//   │          │      // Format the date in Webflow field settings (e.g., "January 1, 2024")
//   │          │      // Wrap in a Link element bound to the item slug for parity
//   │          │
//   │          └── <span>  [Webflow: Text element]
//   │                 Classes: search-result-card__posted-by  (also: byline)
//   │                 Content: [BIND → CMS "Author" Reference field → Author Name]
//   │                 // MIGRATED: _s_posted_by() outputs:
//   │                 //   <span class="byline">
//   │                 //     <span class="author vcard">
//   │                 //       <a class="url fn n" href="{author_url}">{author_display_name}</a>
//   │                 //     </span>
//   │                 //   </span>
//   │                 // Webflow: Text Block bound to Author Reference field > Name
//   │                 // TODO: Author archive URLs (/author/name/) have no direct Webflow
//   │                 //       equivalent. Either link to a static author page or omit the link.
//
//   ├── <div>  [Webflow: DIV — Post Thumbnail wrapper]
//   │      Classes: search-result-card__thumbnail  (also: post-thumbnail)
//   │      // MIGRATED: _s_post_thumbnail() outputs:
//   │      //   <div class="post-thumbnail">
//   │      //     <a href="{permalink}">
//   │      //       <img src="{thumbnail_url}" alt="{alt_text}" />
//   │      //     </a>
//   │      //   </div>
//   │      // Conditional: only renders if a featured image exists.
//   │      // Webflow: Set Conditional Visibility on this DIV:
//   │      //   Condition → "Featured Image" is not empty
//   │      //
//   │      └── <a>  [Webflow: Link Block]
//   │             Classes: search-result-card__thumbnail-link
//   │             href: [BIND → CMS Item URL]
//   │             //
//   │             └── <img>  [Webflow: Image element]
//   │                    Classes: search-result-card__image
//   │                    src: [BIND → CMS "Featured Image" Image field]
//   │                    alt: [BIND → CMS "Featured Image" alt text]
//   │                    // MIGRATED: wp_get_attachment_image() with size 'post-thumbnail'
//   │                    // Webflow auto-generates responsive image variants — no manual
//   │                    // image size registration needed (replaces add_image_size())
//   │                    // TODO: After media migration, map old wp-content/uploads URLs
//   │                    //       to new Webflow Assets CDN URLs in the CMS import CSV
//
//   ├── <div>  [Webflow: DIV — Excerpt]
//   │      Classes: search-result-card__summary  (also: entry-summary)
//   │      // MIGRATED: <div class="entry-summary">
//   │      //
//   │      └── <p>  [Webflow: Text Block / Rich Text element]
//   │             Classes: search-result-card__excerpt
//   │             Content: [BIND → CMS "Excerpt" Plain Text field OR auto-generated excerpt]
//   │             // MIGRATED: the_excerpt() → Webflow CMS "Excerpt" or "Summary" field binding
//   │             // In WordPress, the_excerpt() falls back to a trimmed version of the_content()
//   │             // if no manual excerpt is set (default: 55 words).
//   │             // In Webflow CMS: create a Plain Text field named "Excerpt" (or "Summary").
//   │             // Populate it during content import. For items without a manual excerpt,
//   │             // generate a trimmed version during the export/import process.
//   │             // NOTE: Webflow does not auto-generate excerpts from Rich Text content.
//   │             //       All excerpt values must be explicitly populated in the CMS.
//
//   └── <footer>  [Webflow: DIV, tag overridden to <footer>]
//          Classes: search-result-card__footer  (also: entry-footer)
//          // MIGRATED: <footer class="entry-footer">
//          //           _s_entry_footer() outputs categories, tags, and edit link:
//          //
//          //   Categories:
//          //   <span class="cat-links">
//          //     <a href="{category_url}" rel="category tag">{Category Name}</a>, ...
//          //   </span>
//          //
//          //   Tags:
//          //   <span class="tags-links">
//          //     <a href="{tag_url}" rel="tag">{Tag Name}</a>, ...
//          //   </span>
//          //
//          //   Edit link (logged-in users only):
//          //   <span class="edit-link">
//          //     <a href="{edit_url}">Edit <span class="screen-reader-text">{title}</span></a>
//          //   </span>
//          //
//          ├── <div>  [Webflow: DIV — Category Links]
//          │      Classes: search-result-card__categories  (also: cat-links)
//          │      Visibility: Conditional — show only if "Categories" MultiReference is not empty
//          │      //
//          │      └── [Webflow: Collection List — nested, source: "Categories" MultiReference]
//          │             Item template:
//          │             <a>  [Link element]
//          │               Classes: search-result-card__category-link
//          │               href: [BIND → Category item slug/URL]
//          │               Text: [BIND → Category Name field]
//          │               // MIGRATED: get_category_link() / get_the_category_list()
//          │               // TODO: WordPress category archive URLs (/category/name/) must be
//          │               //       mapped to Webflow Collection List pages via 301 redirects.
//          │               //       Webflow Collection URLs follow: /categories/{slug}
//          │               //       Add redirect: /category/{slug} → /categories/{slug}
//
//          ├── <div>  [Webflow: DIV — Tag Links]
//          │      Classes: search-result-card__tags  (also: tags-links)
//          │      Visibility: Conditional — show only if "Tags" MultiReference is not empty
//          │      //
//          │      └── [Webflow: Collection List — nested, source: "Tags" MultiReference]
//          │             Item template:
//          │             <a>  [Link element]
//          │               Classes: search-result-card__tag-link
//          │               href: [BIND → Tag item slug/URL]
//          │               Text: [BIND → Tag Name field]
//          │               // MIGRATED: get_tag_link() / get_the_tag_list()
//          │               // TODO: WordPress tag archive URLs (/tag/name/) must be
//          │               //       mapped via 301 redirects.
//          │               //       Add redirect: /tag/{slug} → /tags/{slug}
//
//          └── // NOTE: Edit link (_s_entry_footer edit link for logged-in users)
//                 // TODO: Webflow has no equivalent for front-end edit links.
//                 //       Editors use the Webflow Editor (webflow.io/editor) to edit CMS items.
//                 //       Remove this element entirely from the Webflow implementation.
//
// ============================================================
// CMS COLLECTION FIELD BINDINGS SUMMARY
// ============================================================
// Collection: Blog Posts (maps from WordPress 'post' post type)
//
// Field Name          | WF Field Type     | Maps From (WordPress)
// --------------------|-------------------|-------------------------------
// Name                | Plain Text        | the_title() / post_title
// Slug                | (auto)            | get_permalink() / post_name
// Featured Image      | Image             | the_post_thumbnail() / _thumbnail_id meta
// Excerpt             | Plain Text        | the_excerpt() / post_excerpt
// Published On        | Date/Time         | _s_posted_on() / post_date
// Author              | Reference         | _s_posted_by() / post_author → WP_User
// Categories          | MultiReference    | get_the_category() / term relationships
// Tags                | MultiReference    | get_the_tags() / term relationships
// Is Blog Post        | Switch (Boolean)  | get_post_type() === 'post' conditional
//
// NOTE: "Is Blog Post" Switch field is used to conditionally show the entry-meta
//       block (date + author). Set to TRUE for all Blog Post collection items,
//       FALSE (or omit) for Page-type results. Alternatively, create two separate
//       Symbols: one for posts (with meta) and one for pages (without meta).
//
// ============================================================
// WEBFLOW SEARCH RESULTS PAGE INTEGRATION
// ============================================================
//
// SCENARIO A — Webflow Native Site Search (Business plan+):
//   Page: /search  (Webflow auto-creates this page when Search is enabled)
//   Structure:
//     Section.search-results-section
//       └── Container.search-results-container
//             ├── Heading: "Search Results for: {query}"  [BIND → search query]
//             ├── Webflow Search Results List
//             │     └── Search Result Item  [uses "Search Result Card" Symbol layout]
//             │           NOTE: Webflow native search renders its own item structure.
//             │           Map Webflow search result fields to card element bindings.
//             │           Webflow search result bindings available:
//             │             - Result Title      → h2 > a text + href
//             │             - Result URL        → a href
//             │             - Result Excerpt    → p text (auto-generated snippet)
//             │             - Result Image      → img src (if OG/featured image available)
//             └── Webflow Search Pagination
//
// SCENARIO B — Custom CMS Search via Collection List + Finsweet CMS Filter:
//   Page: /search  (static Webflow page)
//   Structure:
//     Section.search-results-section
//       └── Container.search-results-container
//             ├── Form.search-form  [Webflow Form with text input + submit]
//             │     Input: name="search", placeholder="Search…"
//             │     // Finsweet CMS Filter reads this input to filter the Collection List
//             ├── Paragraph.search-results-count  [BIND → result count via Finsweet]
//             ├── Collection List  [source: Blog Posts]
//             │     Filters: configured via Finsweet CMS Filter data attributes
//             │     └── Collection List Item → "Search Result Card" Symbol
//             └── Collection List Pagination
//             //
//             // TODO: Install Finsweet CMS Filter (cdn.finsweet.com/files/cms-filter.js)
//             //       and configure data attributes for live search filtering.
//             //       This is the recommended Webflow-native CMS search approach when
//             //       Webflow native search does not cover all use cases.
//
// ============================================================
// CSS CLASS MIGRATION MAP
// ============================================================
// WordPress class          → Webflow class
// ─────────────────────────────────────────────────────────────
// article#post-{ID}        → .search-result-card
// post_class() values      → .search-result-card (consolidated)
//   (hentry, post, type-post, status-publish,
//    format-standard, has-post-thumbnail,
//    category-{name}, tag-{name})
// .entry-header            → .search-result-card__header
// .entry-title             → .search-result-card__title
// .entry-meta              → .search-result-card__meta
// .posted-on               → .search-result-card__posted-on
// .byline                  → .search-result-card__posted-by
// .post-thumbnail          → .search-result-card__thumbnail
// .entry-summary           → .search-result-card__summary
// .entry-footer            → .search-result-card__footer
// .cat-links               → .search-result-card__categories
// .tags-links              → .search-result-card__tags
// .edit-link               → [REMOVED — no Webflow equivalent]
//
// ============================================================
// 301 REDIRECT REQUIREMENTS (SEO Preservation)
// ============================================================
// Add these to Webflow Hosting → 301 Redirects:
//
// Source (WordPress)              → Destination (Webflow)
// ─────────────────────────────────────────────────────────────
// /?s={query}                     → /search?q={query}
//   (WordPress search URL)           (Webflow search URL)
//   NOTE: URL pattern differs; set up JS redirect or server rule.
//
// /category/{slug}                → /categories/{slug}
// /tag/{slug}                     → /tags/{slug}
//
// ============================================================
// TODO ITEMS REQUIRING MANUAL ACTION IN WEBFLOW
// ============================================================
//
// TODO [1]: Conditional post type display
//   The PHP conditional `if ( 'post' === get_post_type() )` shows entry-meta
//   (date + author) only for blog posts, not pages.
//   Webflow approach: Add a Switch CMS field "Is Blog Post" and use
//   Conditional Visibility on the .search-result-card__meta element.
//   Alternatively, create two Symbol variants: one for post results, one for page results.
//
// TODO [2]: Author archive links
//   _s_posted_by() links to /author/{name}/ URLs.
//   Webflow has no native author archive pages.
//   Options: a) Remove the link, show author name as plain text.
//             b) Create static author profile pages and link manually.
//             c) Add 301 redirect from /author/{name}/ to an about page or homepage.
//
// TODO [3]: Excerpt auto-generation
//   WordPress auto-generates excerpts (55 words) from post content if not set manually.
//   Webflow does NOT auto-generate excerpts from Rich Text fields.
//   Action: During WP XML export processing, ensure every post has an explicit excerpt.
//   Use WP-CLI: `wp post list --field=ID | xargs -I{} wp post get {} --field=post_excerpt`
//   Or run a pre-export script to populate missing excerpts before generating the CSV import.
//
// TODO [4]: Nested Collection Lists (Categories + Tags in footer)
//   Webflow supports nested Collection Lists but they count against plan limits.
//   If plan limits are a concern, consider rendering category/tag names as a
//   Plain Text field (comma-separated string) populated during CMS import,
//   rather than linked MultiReference Collection Lists.
//
// TODO [5]: Media URL rewriting
//   All featured image URLs currently point to wp-content/uploads/{year}/{month}/{file}.
//   After uploading assets to Webflow, update the CMS "Featured Image" field values
//   in the import CSV to use new Webflow CDN URLs.
//   Generate mapping file: wp-content/uploads/* → assets.website.com/*
//
// TODO [6]: Search page URL pattern
//   WordPress uses /?s={query} for search URLs.
//   Webflow uses /search?q={query} (native) or a custom URL with Finsweet.
//   Add a server-level or JS redirect for incoming links/bookmarks to the old
//   WordPress search URL pattern.
//
// TODO [7]: post_class() dynamic classes
//   WordPress post_class() adds many dynamic utility classes (category-{name}, tag-{name},
//   has-post-thumbnail, etc.) that enable CSS targeting.
//   In Webflow, use Conditional Visibility and CMS field bindings instead of
//   dynamic class-based CSS targeting. Audit existing stylesheet for any rules
//   targeting these dynamic classes and replace with Webflow class equivalents.
//
// ============================================================
// END OF MIGRATION BLUEPRINT: template-parts/content-search.php
// ============================================================