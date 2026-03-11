// ============================================================
// WEBFLOW MIGRATION BLUEPRINT: archive.php
// Source: WordPress archive.php (_s / Underscores starter theme)
// Target: Webflow CMS Collection List Page
// Migration Scope: Category, Tag, Custom Taxonomy, Post Type Archive pages
// ============================================================
//
// OVERVIEW
// --------
// WordPress archive.php handles ALL archive-type pages via conditional
// template tags and The Loop. In Webflow, each distinct archive type
// becomes its own CMS Collection List page (or filtered static page).
// The WordPress Loop (while have_posts()) maps to a Webflow Collection List.
//
// WEBFLOW PAGE TYPE: CMS Collection List Page
// (Project Settings > CMS > Collections > [Collection Name] > Template Settings)
// OR a static page containing a Collection List component filtered by taxonomy.
//
// ============================================================
// SECTION 1: WORDPRESS ARCHIVE TYPES -> WEBFLOW PAGE MAPPING
// ============================================================
//
// WordPress archive.php handles these contexts (via template hierarchy):
//
//   is_category()   -> /category/[slug]/          -> Webflow: /blog/category/[slug] static page OR
//                                                    Webflow CMS Collection List page filtered by Category Reference
//                                                    // TODO: Webflow CMS does not support /category/[slug]/ URL pattern natively
//                                                    // Create a "Categories" Collection, add a Collection Template page
//                                                    // URL prefix: /blog/category/ (Webflow will append /{slug})
//                                                    // Set up 301 redirect: /category/{slug}/ -> /blog/category/{slug}/
//
//   is_tag()        -> /tag/[slug]/               -> Webflow: /blog/tag/[slug] static page OR
//                                                    Webflow CMS Collection List page filtered by Tag MultiReference
//                                                    // TODO: Same URL mismatch — create "Tags" Collection + Template page
//                                                    // Set up 301 redirect: /tag/{slug}/ -> /blog/tag/{slug}/
//
//   is_author()     -> /author/[slug]/            -> Webflow: /author/[slug] (Authors Collection Template page)
//                                                    // TODO: Create "Authors" CMS Collection
//                                                    // Set up 301 redirect: /author/{slug}/ -> /authors/{slug}/
//
//   is_date()       -> /[year]/[month]/[day]/     -> // TODO: Webflow has NO date-based archive support
//                                                    // Solution: Create a static "Archives" page with Collection List
//                                                    // sorted by date with visible date groupings via custom code
//                                                    // 301 redirect: /[year]/[month]/ -> /blog/ (or closest equivalent)
//
//   is_post_type_archive() -> /[cpt-slug]/        -> Webflow: Collection List page at /[cpt-slug]/
//                                                    // This is the closest 1:1 match — Webflow CMS Collection
//                                                    // archive pages use /[collection-url-prefix]/ automatically
//
//   is_tax()        -> /[taxonomy]/[term]/        -> Webflow: taxonomy Collection Template page
//                                                    // TODO: 301 redirect custom taxonomy URLs as needed
//
// ============================================================
// SECTION 2: WEBFLOW PAGE STRUCTURE — COLLECTION LIST ARCHIVE PAGE
// ============================================================
//
// Webflow Designer Structure (element tree):
//
// PAGE: "Blog Archive" (or "Category Archive", "Tag Archive", etc.)
// ├── [Symbol] Navbar                              // MIGRATED: get_header() -> Webflow Navbar Symbol
// │
// ├── Section.page-header-section
// │   └── Container.container
// │       ├── H1.page-title                        // MIGRATED: the_archive_title('<h1 class="page-title">', '</h1>')
// │       │   // Webflow: On CMS Collection Template page, bind H1 text to Collection Item "Name" field
// │       │   // On static filtered pages: set static text per page (e.g., "Category: News")
// │       │   // TODO: Manual action in Webflow — On taxonomy Collection Template page,
// │       │   //        bind this Heading to the taxonomy term's Name field
// │       │
// │       └── Div.archive-description              // MIGRATED: the_archive_description('<div class="archive-description">', '</div>')
// │           // Webflow: On CMS Collection Template page (for Categories/Tags Collections),
// │           // bind this Rich Text or Text Block to the taxonomy term's "Description" field
// │           // TODO: Manual action in Webflow — add a Plain Text or Rich Text field called
// │           //        "Description" to your Categories/Tags Collection and bind it here
// │           // NOTE: This element should be conditionally visible (hide when Description is empty)
// │           //        Use Webflow conditional visibility: "Hide if Description is empty"
//
// ├── Section.archive-content-section
// │   └── Container.container
// │       └── Div.archive-layout                   // Two-column layout: main content + sidebar
// │           │
// │           ├── Div.archive-main                 // Main content area (maps to #primary.content-area > #main.site-main)
// │           │   │
// │           │   ├── [COLLECTION LIST: Post Items]
// │           │   │   // MIGRATED: while (have_posts()) : the_post() -> Webflow Collection List component
// │           │   │   //
// │           │   │   // Webflow Collection List Settings:
// │           │   │   //   Source: "Blog Posts" Collection (or relevant CPT Collection)
// │           │   │   //   Items per page: 10 (match WordPress posts_per_page setting)
// │           │   │   //   Pagination: ON
// │           │   │   //   Sort: Published Date, Descending (newest first)
// │           │   │   //
// │           │   │   // FILTER CONFIGURATION (varies by archive type):
// │           │   │   //
// │           │   │   //   For Category Archive page (taxonomy Collection Template):
// │           │   │   //     Filter: Category Reference = Current Category (auto-bound on template page)
// │           │   │   //     // TODO: Manual action in Webflow — in Collection List settings,
// │           │   │   //     //        set filter "Category" equals "Current Category" (template-level binding)
// │           │   │   //
// │           │   │   //   For Tag Archive page (taxonomy Collection Template):
// │           │   │   //     Filter: Tags MultiReference contains Current Tag
// │           │   │   //     // TODO: Manual action in Webflow — MultiReference filtering requires
// │           │   │   //     //        the Collection List to be placed on the Tag Collection Template page
// │           │   │   //     //        and filter set to "Tags contains Current Tag item"
// │           │   │   //
// │           │   │   //   For Post Type Archive page:
// │           │   │   //     No additional filter needed — the Collection List source IS the CPT Collection
// │           │   │   //
// │           │   │   //   For Author Archive page:
// │           │   │   //     Filter: Author Reference = Current Author
// │           │   │   //     // TODO: Manual action in Webflow — place Collection List on Authors
// │           │   │   //     //        Collection Template page, filter "Author equals Current Author item"
// │           │   │   //
// │           │   │   // ──────────────────────────────────────────────────────────────────
// │           │   │   // COLLECTION LIST ITEM TEMPLATE (maps to get_template_part('template-parts/content'))
// │           │   │   // This is the card/row layout shown for each post in the archive loop
// │           │   │   // ──────────────────────────────────────────────────────────────────
// │           │   │   //
// │           │   │   // Webflow Collection List Item structure:
// │           │   │   //
// │           │   │   //   Div.post-card                        // Collection List Item wrapper
// │           │   │   //   ├── Link.post-card-link              // Wrap entire card in link
// │           │   │   //   │   // Webflow: Link element, href bound to Collection Item slug (automatic)
// │           │   │   //   │   // MIGRATED: the_permalink() -> Webflow CMS item link binding
// │           │   │   //   │
// │           │   │   //   ├── Div.post-card-thumbnail          // Featured image container
// │           │   │   //   │   └── Image.post-thumbnail         // MIGRATED: the_post_thumbnail()
// │           │   │   //   │       // Webflow: Image element, src bound to "Main Image" / "Featured Image" CMS field
// │           │   │   //   │       // Alt text: bound to image alt text field or post title
// │           │   │   //   │       // TODO: Manual action in Webflow — bind Image src to Featured Image field
// │           │   │   //   │       // Set conditional visibility: hide Div.post-card-thumbnail if Featured Image is empty
// │           │   │   //   │
// │           │   │   //   ├── Div.post-card-body
// │           │   │   //   │   ├── Div.post-card-meta           // Post metadata row
// │           │   │   //   │   │   ├── Div.post-category        // Category label
// │           │   │   //   │   │   │   // MIGRATED: the_category() -> Reference field binding
// │           │   │   //   │   │   │   // Webflow: Text element bound to Category Name (Reference field)
// │           │   │   //   │   │   │   // NOTE: For MultiReference (multiple categories), use a nested
// │           │   │   //   │   │   │   //        Collection List with filter = Current Item's Categories
// │           │   │   //   │   │   │
// │           │   │   //   │   │   └── Span.post-date           // Publication date
// │           │   │   //   │   │       // MIGRATED: the_date() -> DateTime CMS field binding
// │           │   │   //   │   │       // Webflow: Text element bound to "Published Date" field
// │           │   │   //   │   │       // Format: "MMM D, YYYY" (configurable in Webflow field settings)
// │           │   │   //   │   │
// │           │   │   //   │   ├── H2.post-card-title           // Post title
// │           │   │   //   │   │   // MIGRATED: the_title() -> Name CMS field binding
// │           │   │   //   │   │   // Webflow: Heading (H2) element bound to Collection Item "Name" field
// │           │   │   //   │   │
// │           │   │   //   │   ├── Div.post-card-excerpt        // Post excerpt/summary
// │           │   │   //   │   │   // MIGRATED: the_excerpt() -> Plain Text "Excerpt" CMS field binding
// │           │   │   //   │   │   // Webflow: Text Block element bound to "Excerpt" field
// │           │   │   //   │   │   // NOTE: If no dedicated excerpt field, use a truncated "Post Summary" plain text field
// │           │   │   //   │   │   // TODO: Manual action in Webflow — add "Excerpt" Plain Text field to Blog Posts Collection
// │           │   │   //   │   │   //        if not already present; populate via WP export data
// │           │   │   //   │   │
// │           │   │   //   │   ├── Div.post-card-author         // Author info (optional)
// │           │   │   //   │   │   // MIGRATED: the_author() -> Reference field binding
// │           │   │   //   │   │   // Webflow: Text element bound to "Author" Reference field -> "Name"
// │           │   │   //   │   │   // TODO: Manual action in Webflow — create "Authors" Collection,
// │           │   │   //   │   │   //        add Reference field "Author" to Blog Posts Collection
// │           │   │   //   │   │
// │           │   │   //   │   └── Link.read-more-link          // Read more CTA
// │           │   │   //   │       // MIGRATED: the_permalink() used as read-more link
// │           │   │   //   │       // Webflow: Link element, href bound to Collection Item slug
// │           │   │   //   │       // Text: "Read More" (static)
// │           │   │   //
// │           │   │   // ──────────────────────────────────────────────────────────────────
// │           │   │   // EMPTY STATE (maps to get_template_part('template-parts/content', 'none'))
// │           │   │   // ──────────────────────────────────────────────────────────────────
// │           │   │   //
// │           │   │   // Webflow Collection List: Empty State element (built-in)
// │           │   │   //   Div.no-posts-found                   // MIGRATED: content-none.php template part
// │           │   │   //   ├── H2 "No posts found"             // Static heading
// │           │   │   //   ├── P "Try a different search..."   // Static descriptive text
// │           │   │   //   └── Link -> /blog/  "Back to Blog"  // Navigation fallback
// │           │   │   //
// │           │   │   // NOTE: Webflow Collection List has a built-in "Empty State" slot —
// │           │   │   //        drag elements into the empty state area in the Designer
// │           │   │
// │           │   └── Div.posts-navigation                     // MIGRATED: the_posts_navigation()
// │           │       // Webflow: Collection List Pagination (enable in Collection List settings)
// │           │       //   Pagination style: Previous / Next links OR numbered pages
// │           │       //   Webflow auto-generates pagination controls when enabled
// │           │       //   CSS classes: .w-pagination-wrapper, .w-pagination-previous, .w-pagination-next
// │           │       //   TODO: Manual action in Webflow — enable Pagination in Collection List settings,
// │           │       //         set items per page to match WordPress reading settings (default: 10)
// │           │       //         Style pagination elements via .w-pagination-* classes in Designer
//
// │           └── [Symbol] Sidebar                             // MIGRATED: get_sidebar()
//                  // Webflow: Sidebar Symbol (reusable component)
//                  // See: sidebar.php migration blueprint for full Sidebar Symbol documentation
//                  // Structure: Search widget, Recent Posts widget, Categories widget, Tags widget
//                  // TODO: Manual action in Webflow — build Sidebar Symbol separately,
//                  //        place it in the archive layout column here
//
// ├── [Symbol] Footer                                          // MIGRATED: get_footer() -> Webflow Footer Symbol
//
// ============================================================
// SECTION 3: WEBFLOW CMS COLLECTIONS REQUIRED
// ============================================================
//
// To power this archive page, the following Webflow CMS Collections are required:
//
//   Collection: "Blog Posts"
//   ├── Name (built-in, maps to post title)
//   ├── Slug (built-in, maps to post_name)
//   ├── Published Date (DateTime, maps to post_date)
//   ├── Featured Image (Image, maps to _thumbnail_id)
//   ├── Excerpt (Plain Text, maps to post_excerpt)
//   ├── Post Content (Rich Text, maps to post_content)
//   ├── Author (Reference -> "Authors" Collection, maps to post_author)
//   ├── Category (Reference -> "Categories" Collection, maps to primary category)
//   ├── Tags (MultiReference -> "Tags" Collection, maps to post_tags)
//   └── [Any additional ACF/custom fields]
//
//   Collection: "Categories"
//   ├── Name (built-in)
//   ├── Slug (built-in)
//   └── Description (Plain Text or Rich Text, maps to category description)
//   // MIGRATED: register_taxonomy('category') -> Webflow CMS Collection
//   // Collection Template page URL prefix: /blog/category
//   // 301 redirect: /category/{slug}/ -> /blog/category/{slug}/
//
//   Collection: "Tags"
//   ├── Name (built-in)
//   ├── Slug (built-in)
//   └── Description (Plain Text, maps to tag description)
//   // MIGRATED: built-in WordPress tags -> Webflow CMS Collection
//   // Collection Template page URL prefix: /blog/tag
//   // 301 redirect: /tag/{slug}/ -> /blog/tag/{slug}/
//
//   Collection: "Authors" (if author archives are needed)
//   ├── Name (built-in)
//   ├── Slug (built-in)
//   ├── Bio (Rich Text, maps to user description)
//   ├── Avatar (Image, maps to Gravatar or custom avatar)
//   └── Role (Plain Text, maps to user role)
//   // 301 redirect: /author/{slug}/ -> /authors/{slug}/
//
// ============================================================
// SECTION 4: CSS CLASS MAPPING
// ============================================================
//
// WordPress class          -> Webflow class (Designer label)
// ──────────────────────────────────────────────────────────
// #primary.content-area    -> Div: archive-primary-wrapper
// #main.site-main          -> Main: archive-main-content
// .page-header             -> Section: page-header-section / Div: page-header
// .page-title (H1)         -> Heading: page-title  (H1 tag)
// .archive-description     -> Div: archive-description  (conditionally visible)
// .entry-header            -> Div: post-card-header
// .entry-title             -> Heading: post-card-title  (H2 tag)
// .entry-meta              -> Div: post-card-meta
// .entry-content           -> Div: post-card-excerpt
// .entry-footer            -> Div: post-card-footer
// .post-thumbnail          -> Image: post-card-thumbnail-image
// .cat-links               -> Div: post-card-categories
// .posted-on               -> Span: post-card-date
// .byline                  -> Span: post-card-author
// .nav-previous            -> Link: pagination-prev
// .nav-next                -> Link: pagination-next
// .posts-navigation        -> Div: posts-pagination
// .no-results              -> Div: no-posts-found  (Collection List Empty State)
// .not-found               -> Div: no-posts-found
//
// NOTE: Tailwind CSS is present in this project (see package.json).
// If the theme uses Tailwind utility classes, map them directly in Webflow
// using the Custom Attributes panel or Style Manager with matching utility classes.
// Webflow supports custom CSS class names — Tailwind classes can be added via
// the element's custom class field if Tailwind CSS is loaded via custom code embed.
//
// ============================================================
// SECTION 5: URL/PERMALINK 301 REDIRECT MAP
// ============================================================
//
// Generate this CSV for import into Webflow Hosting > 301 Redirects:
//
// Old WordPress URL (From)              -> New Webflow URL (To)          Status
// ──────────────────────────────────────────────────────────────────────────────
// /category/{slug}/                     -> /blog/category/{slug}/         301
// /category/{slug}/page/{n}/            -> /blog/category/{slug}/         301  // NOTE: Webflow pagination uses different URL pattern
// /tag/{slug}/                          -> /blog/tag/{slug}/              301
// /tag/{slug}/page/{n}/                 -> /blog/tag/{slug}/              301
// /author/{slug}/                       -> /authors/{slug}/               301
// /author/{slug}/page/{n}/              -> /authors/{slug}/               301
// /{year}/                              -> /blog/                         301  // Date archives -> flat blog
// /{year}/{month}/                      -> /blog/                         301
// /{year}/{month}/{day}/                -> /blog/                         301
// /?cat={id}                            -> /blog/category/{slug}/         301  // TODO: Resolve cat ID to slug before import
// /?tag={slug}                          -> /blog/tag/{slug}/              301
// /feed/                                -> /feed.xml                      301  // Webflow's built-in RSS feed
// /comments/feed/                       -> /feed.xml                      301
// /category/{slug}/feed/                -> /feed.xml                      301
//
// TODO: Export full redirect list from WordPress (Yoast/RankMath redirect manager
//       or WP All Export) and merge with the above patterns before importing to Webflow.
//
// ============================================================
// SECTION 6: WEBFLOW DESIGNER STEP-BY-STEP BUILD INSTRUCTIONS
// ============================================================
//
// Step 1: CREATE CMS COLLECTIONS
//   - In Webflow CMS, create "Blog Posts", "Categories", "Tags", "Authors" Collections
//   - Define all fields as documented in Section 3 above
//   - Add "Categories" Collection Template page, set URL prefix to /blog/category
//   - Add "Tags" Collection Template page, set URL prefix to /blog/tag
//   - Add "Authors" Collection Template page, set URL prefix to /authors
//
// Step 2: BUILD ARCHIVE PAGE LAYOUT (on Categories Collection Template page)
//   - Open Webflow Designer, navigate to Categories Collection Template page
//   - Add Section element, apply class "page-header-section"
//   - Inside Section, add Container, then H1 bound to "Name" (Category Name field)
//   - Add Div.archive-description below H1, bind text to "Description" field
//   - Set conditional visibility on Div.archive-description: hide when Description is empty
//
// Step 3: ADD COLLECTION LIST
//   - Add new Section "archive-content-section" below header section
//   - Add Container, then a 2-column grid Div (main + sidebar)
//   - In the main column, add a Collection List component
//   - Set Collection List source to "Blog Posts"
//   - Set filter: "Category" equals "Current Category" (auto-bound on template page)
//   - Set sort: Published Date, Descending
//   - Set items per page: 10
//   - Enable pagination
//
// Step 4: DESIGN COLLECTION LIST ITEM CARD
//   - Inside Collection List Item, build the card structure as documented in Section 2
//   - Bind each element to the appropriate CMS field
//   - Add conditional visibility to the thumbnail container (hide if Featured Image is empty)
//   - Link the entire card or the title to the Collection Item page (bound automatically)
//
// Step 5: DESIGN EMPTY STATE
//   - Click on Collection List, scroll to "Empty State" in Designer panel
//   - Add Div.no-posts-found with H2 "No posts found", descriptive paragraph, and a back link
//
// Step 6: ADD SIDEBAR
//   - In the sidebar column, place the pre-built Sidebar Symbol
//   - Configure any CMS-powered widgets (Recent Posts Collection List) within the Symbol
//
// Step 7: ADD NAVBAR AND FOOTER SYMBOLS
//   - Add Navbar Symbol at the top of the page
//   - Add Footer Symbol at the bottom of the page
//
// Step 8: DUPLICATE FOR OTHER ARCHIVE TYPES
//   - Repeat the above process for Tags Collection Template page
//   - Repeat for Authors Collection Template page
//   - For post type archives, the CPT Collection's built-in archive page handles this automatically
//
// Step 9: RESPONSIVE DESIGN
//   - Switch to Tablet, Mobile Landscape, Mobile Portrait breakpoints in Webflow Designer
//   - Adjust the 2-column archive layout to single column on mobile
//   - Stack the post cards vertically, adjust font sizes, image aspect ratios
//   - TODO: Manual action in Webflow — test responsive behavior on all breakpoints
//
// Step 10: SEO SETTINGS
//   - On each archive template page, configure Page Settings:
//     - Title Tag: "[Category Name] - [Site Name]" (use CMS binding for dynamic value)
//     - Meta Description: bind to taxonomy Description field or set static fallback
//     - Open Graph Image: bind to taxonomy Featured Image or use site-level default
//   - TODO: Manual action in Webflow — configure SEO fields on each Collection Template page
//
// ============================================================
// SECTION 7: LIMITATIONS & KNOWN GAPS
// ============================================================
//
// 1. Date-based archives (/2023/, /2023/05/, /2023/05/12/)
//    // TODO: Webflow has NO native date archive support.
//    // Workaround A: Redirect all date archive URLs to /blog/ (simplest, some SEO loss)
//    // Workaround B: Create a static "By Year" page with manually-segmented Collection Lists
//    //              (high maintenance, does not scale)
//    // Workaround C: Use a Finsweet CMS Filter solution with date range custom attributes
//    //              (requires third-party script: cdn.jsdelivr.net/npm/@finsweet/attributes-cmsfilter)
//
// 2. Nested category URLs (/category/parent/child/)
//    // TODO: Webflow CMS does not support hierarchical taxonomy URL nesting.
//    // Workaround: Flatten category structure, use unique slugs, redirect nested URLs to flat URLs.
//
// 3. Pagination URL pattern mismatch (/page/2/ in WordPress vs Webflow's built-in pattern)
//    // TODO: Webflow pagination does not use /page/N/ URL format.
//    // Redirect: /category/{slug}/page/{n}/ -> /blog/category/{slug}/ (redirect to page 1, acceptable SEO tradeoff)
//
// 4. Author archive pages
//    // TODO: WordPress generates author archives automatically.
//    // In Webflow, author pages require a dedicated "Authors" CMS Collection with Template page.
//    // This requires manually creating author records in the CMS.
//
// 5. Custom post type archive filtering
//    // TODO: get_template_part('template-parts/content', get_post_type()) dynamically loads
//    //        different card templates based on post type. In Webflow, each CPT archive
//    //        page uses its own Collection List with its own item card design.
//    //        There is no single "catch-all" archive template — each Collection needs its own page.
//
// 6. is_sticky() posts (sticky posts appearing at top of archive)
//    // TODO: Webflow Collection List has no "sticky post" concept.
//    // Workaround: Add a Boolean/Switch field "Featured" or "Sticky" to Blog Posts Collection.
//    //             On the archive page, add a second Collection List above the main one,
//    //             filtered by "Sticky = true", limited to a small count (e.g., 3).
//    //             This mimics WordPress sticky post behavior.
//
// 7. The Loop get_template_part('template-parts/content', get_post_type())
//    // MIGRATED: This PHP call dynamically includes a content partial based on post type.
//    // In Webflow, each Collection List item IS the template partial.
//    // For mixed post type archives (not common), use separate Collection Lists per post type
//    // on the same page, each with its own card design.
//
// ============================================================
// END OF WEBFLOW MIGRATION BLUEPRINT: archive.php
// ============================================================