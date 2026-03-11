// ============================================================
// WEBFLOW MIGRATION BLUEPRINT: search.php -> Webflow Search Results Page
// ============================================================
// SOURCE: search.php (WordPress _s / Underscores theme)
// TARGET: Webflow Search Results Page (requires Webflow Business plan or higher for Site Search)
// PLAN REQUIREMENT: Webflow Business plan minimum for native site search functionality
// MIGRATED: Full template mapped to Webflow Search Results page structure with CMS bindings
// ============================================================

// ------------------------------------------------------------
// WEBFLOW PAGE SETUP
// ------------------------------------------------------------
// Page Name: Search Results
// Page Slug: /search (Webflow auto-handles search at this route)
// Page Type: Search Results Page (special page type in Webflow)
// Template: NOT a CMS Collection Template — this is a special Webflow Search page
// Navigate to: Pages Panel -> Utility Pages -> Search Page
// TODO: Manual action in Webflow — Enable site search in Project Settings -> Search tab
// TODO: Manual action in Webflow — Set this page as the designated Search Results page
// ------------------------------------------------------------

// ============================================================
// SYMBOL: NAVBAR (global, reused from header.php migration)
// ============================================================
// MIGRATED: get_header() -> Webflow Navbar Symbol (defined separately in header.php migration)
// Drop the Navbar Symbol at the top of this page
// Symbol Name: "Global Navbar"
// The Navbar Symbol is shared across all pages — no changes needed here
// See: header.php migration blueprint for full Navbar Symbol documentation

// ============================================================
// PAGE WRAPPER STRUCTURE
// ============================================================
// WordPress: <section id="primary" class="content-area"> wrapping <main id="main" class="site-main">
// Webflow: Page Wrapper Div -> Two-column layout Div (content area + sidebar)
//
// Webflow Element Tree:
// <Section>                         class="search-page-section"
//   <Container>                     class="search-page-container"
//     <Div>                         class="search-page-layout"        [Display: Flex or Grid, two columns]
//       <Div>                       class="search-content-area"       [Main content, ~70% width]
//         ... (search header + results, see below)
//       </Div>
//       <Div>                       class="search-sidebar-area"       [Sidebar, ~30% width]
//         ... (see Sidebar Symbol section below)
//       </Div>
//     </Div>
//   </Container>
// </Section>
//
// MIGRATED: id="primary" class="content-area" -> Webflow Section + Container layout
// MIGRATED: id="main" class="site-main" -> inner content Div, class="search-content-area"
// TODO: Manual action in Webflow — Set up responsive breakpoints:
//       Mobile: stack search-content-area above search-sidebar-area (Flex Direction: Column)
//       Tablet: same stacking behavior
//       Desktop: side-by-side Flex Row layout

// ============================================================
// SECTION 1: SEARCH RESULTS HEADER
// ============================================================
// WordPress: <header class="page-header">
//              <h1 class="page-title">Search Results for: <span>{query}</span></h1>
//            </header>
//
// Webflow: Search Results Header Block (inside search-content-area Div)
//
// Webflow Element Tree:
// <Div>                             class="page-header"
//   <Heading> (H1)                  class="page-title"
//     "Search Results for: "
//     <Search Query Text>           [Webflow native Search Query binding — auto-populated]
//   </Heading>
// </Div>
//
// MIGRATED: class="page-header" -> Webflow Div class="page-header"
// MIGRATED: class="page-title" -> Webflow H1 element class="page-title"
// MIGRATED: get_search_query() -> Webflow Search Query Text element (built-in binding)
//           In Webflow's Search Results page, add a "Search Query" text element:
//           Add Element -> Search -> "Search Query" — this auto-outputs the user's query string
// NOTE: The <span> wrapper around the query in WordPress is for styling the search term
//       In Webflow: bind the Search Query Text element and apply class="search-query-term"
//       for distinct styling (e.g., bold, colored, or quoted)
//
// Webflow Search Query Element Properties:
//   - Element Type: Search Query (special Webflow element, only available on Search Results page)
//   - Class: "search-query-term"
//   - Suggested styling: font-weight: 700 or color: [brand accent color] to visually distinguish
// TODO: Manual action in Webflow — Drag "Search Query" element from the Search section of Add Elements panel

// ============================================================
// SECTION 2: SEARCH RESULTS COUNT (RECOMMENDED ADDITION)
// ============================================================
// WordPress: No explicit result count in base _s theme, but common best practice
// Webflow: Add a Search Results Count element below the H1
//
// Webflow Element Tree:
// <Paragraph>                       class="search-results-count"
//   <Search Results Count>          [Webflow native — outputs "X results found"]
// </Paragraph>
//
// TODO: Manual action in Webflow — Add "Search Results Count" element from Search panel
//       This element is only available on the designated Search Results page
//       Suggested text template: "{count} results for {query}"

// ============================================================
// SECTION 3: SEARCH RESULTS LIST
// ============================================================
// WordPress: while ( have_posts() ) : the_post(); get_template_part('template-parts/content', 'search');
// Webflow: Search Results List element (native Webflow Search component)
//
// MIGRATED: WordPress Loop (have_posts / the_post) -> Webflow Search Results List (built-in)
// MIGRATED: get_template_part('template-parts/content', 'search') -> Webflow Search Result Item template
//
// Webflow Element Tree:
// <Search Results List>             class="search-results-list"
//   <Search Result Item>            class="search-result-item"       [Repeating template per result]
//     <Div>                         class="result-item-inner"
//       <Div>                       class="result-item-meta"
//         <Text Block>              class="result-item-type"         [Bound to: Result Type / Collection Name]
//         <Text Block>              class="result-item-date"         [Bound to: Result Published Date]
//       </Div>
//       <Heading> (H2)              class="result-item-title"
//         <Link>                    class="result-item-title-link"   [Bound to: Result URL]
//           [Bound to: Result Title]
//         </Link>
//       </Heading>
//       <Paragraph>                 class="result-item-excerpt"      [Bound to: Result Excerpt/Snippet]
//       </Paragraph>
//       <Link>                      class="result-item-readmore"     [Bound to: Result URL]
//         "Read More"               [Static text or "View Post" CTA]
//       </Link>
//     </Div>
//   </Search Result Item>
// </Search Results List>
//
// AVAILABLE WEBFLOW SEARCH RESULT BINDINGS (drag from search result item context):
//   - Result Title      -> bind to H2/Heading or Link text
//   - Result URL        -> bind to Link href (title link and "Read More" link)
//   - Result Excerpt    -> bind to Paragraph (snippet of matched content)
//   - Result Type       -> bind to Text Block (outputs Collection name or "Pages")
//   - Result Thumbnail  -> bind to Image (if the matched item has a featured image CMS field)
//   - Result Published  -> bind to Text Block (date of the result item)
//
// NOTE: Webflow Site Search indexes:
//   - Static pages (title + body text)
//   - CMS Collection items (all text fields included in search indexing)
//   - You can control which Collections and fields are indexed in Project Settings -> Search
// TODO: Manual action in Webflow — In Project Settings -> Search, enable indexing for:
//       Blog Posts Collection, Portfolio Collection, and any other searchable Collections
// TODO: Manual action in Webflow — Configure which CMS fields are included in search index
//       (e.g., Name, Excerpt, Body content — exclude internal/admin fields)
//
// CONTENT-SEARCH TEMPLATE PART (template-parts/content-search.php) -> Webflow Search Result Item
// The content-search.php template part typically renders:
//   - Post title as linked heading
//   - Post excerpt
//   - Post meta (date, category, author)
//   - Featured image (optional)
// All of these are handled by the Search Result Item bindings above
// MIGRATED: template-parts/content-search.php -> Webflow Search Result Item template (no separate file needed)

// ============================================================
// SECTION 4: NO RESULTS STATE
// ============================================================
// WordPress: else : get_template_part('template-parts/content', 'none');
// Webflow: Search Empty State element (built-in, displayed when no results found)
//
// MIGRATED: get_template_part('template-parts/content', 'none') -> Webflow Search Empty State block
// MIGRATED: template-parts/content-none.php -> Webflow "Empty State" element within Search Results List
//
// Webflow Element Tree (inside Search Results List, "No Results" state):
// The Search Results List in Webflow has a built-in "Empty State" slot.
// Click the Search Results List -> Settings -> "Empty State" tab to configure.
//
// <Search Empty State>              class="search-no-results"        [Auto-shown when 0 results]
//   <Div>                           class="no-results-content"
//     <Heading> (H2)                class="no-results-title"
//       "Nothing Found"
//     </Heading>
//     <Paragraph>                   class="no-results-text"
//       "Sorry, but nothing matched your search terms. Please try again with some different keywords."
//     </Paragraph>
//     <Div>                         class="no-results-search-form"
//       <Search Form>               class="no-results-search-widget"  [New search attempt form]
//         <Search Input>            class="no-results-search-input"
//         <Search Button>           class="no-results-search-button"
//           "Search Again"
//         </Search Button>
//       </Search Form>
//     </Div>
//   </Div>
// </Search Empty State>
//
// TODO: Manual action in Webflow — Select the Search Results List element -> go to Settings panel
//       -> configure the Empty State content with the "Nothing Found" messaging
// NOTE: The "Empty State" is only visible in the Webflow Designer when you preview with no results
//       or when you toggle the empty state view in the element's settings

// ============================================================
// SECTION 5: SEARCH PAGINATION
// ============================================================
// WordPress: the_posts_navigation() — previous/next page links for search results
// Webflow: Search Results List has built-in pagination controls
//
// MIGRATED: the_posts_navigation() -> Webflow Search Results List Pagination
//
// Webflow Element Tree (pagination — added inside or below Search Results List):
// <Search Results Pagination>       class="search-pagination"        [Built-in Webflow element]
//   <Previous Link>                 class="pagination-prev"          [Auto-disabled if on first page]
//     "← Previous"
//   </Previous Link>
//   <Page Numbers>                  class="pagination-numbers"       [Optional numbered pages]
//   </Page Numbers>
//   <Next Link>                     class="pagination-next"          [Auto-disabled if on last page]
//     "Next →"
//   </Next Link>
// </Search Results Pagination>
//
// TODO: Manual action in Webflow — Select the Search Results List -> Settings panel
//       -> enable "Pagination" and set items per page (recommended: 10 results per page)
// NOTE: Webflow search pagination uses a different URL pattern than WordPress (/page/2/)
//       WordPress: /?s=query&paged=2
//       Webflow:   /search?q=query&page=2  (or similar, Webflow-managed)
// TODO: SEO — Add 301 redirect rules for paginated WordPress search URLs if they were indexed:
//       /?s={query}&paged=2  ->  /search?q={query}&page=2

// ============================================================
// SECTION 6: SEARCH FORM (in page header area — recommended addition)
// ============================================================
// WordPress: The _s theme does not explicitly include a search form in search.php,
//            but it is standard UX to show the active search query form at the top of results
// Webflow: Add a Search Form above or below the results header
//
// Webflow Element Tree:
// <Div>                             class="search-form-wrapper"
//   <Form Block>                    class="search-form"              [Webflow native Search Form]
//     <Search Input>                class="search-input"
//       placeholder="Search..."
//       [Pre-populated with current search query via Search Query binding]
//     </Search Input>
//     <Search Button>               class="search-submit-button"
//       "Search"
//     </Search Button>
//   </Form Block>
// </Div>
//
// TODO: Manual action in Webflow — Add a Search element (not a regular Form) from Add Elements -> Search
//       The native Search element auto-populates with the current query string on the Search Results page
// NOTE: This is a different element from a regular Webflow Form Block —
//       use the dedicated Search component for proper query binding

// ============================================================
// SIDEBAR AREA
// ============================================================
// WordPress: get_sidebar() — loads sidebar.php with widget areas
// Webflow: Sidebar Symbol (right column of search-page-layout Div)
//
// MIGRATED: get_sidebar() -> Webflow Sidebar Symbol or static sidebar Div
// See: sidebar.php migration blueprint for full Sidebar Symbol documentation
//
// Webflow Element Tree (sidebar-area Div contents):
// <Div>                             class="search-sidebar-area"
//   <Symbol>                        "Global Sidebar"                 [Reusable Symbol from sidebar.php migration]
//     OR if sidebar is page-specific:
//   <Div>                           class="sidebar-widget-area"
//     <Div>                         class="sidebar-widget search-widget"
//       <Heading> (H3)              class="widget-title"
//         "Search"
//       </Heading>
//       <Search Form>               class="sidebar-search-form"      [Webflow Search element]
//     </Div>
//     <Div>                         class="sidebar-widget recent-posts-widget"
//       <Heading> (H3)              class="widget-title"
//         "Recent Posts"
//       </Heading>
//       <Collection List>           source="Blog Posts" limit=5 sort="Published Date DESC"
//         <Collection Item>         class="recent-post-item"
//           <Link>                  [Bound to: Item URL]
//             <Text>                [Bound to: Item Name/Title]
//             </Text>
//           </Link>
//         </Collection Item>
//       </Collection List>
//     </Div>
//   </Div>
// </Div>
//
// TODO: Manual action in Webflow — If using a shared Global Sidebar Symbol, drop it in the sidebar column
//       If this page needs a unique sidebar, build the sidebar-widget-area Div inline on this page

// ============================================================
// FULL WEBFLOW PAGE ELEMENT TREE SUMMARY
// ============================================================
//
// [Symbol] Global Navbar                          <- get_header()
//
// <Section>                  class="search-page-section"
//   <Container>              class="search-page-container"
//     <Div>                  class="search-page-layout" [Flex Row, gap: 48px]
//
//       <Div>                class="search-content-area" [Flex: 1 1 70%]
//
//         <Div>              class="page-header"
//           <H1>             class="page-title"
//             "Search Results for: "
//             [Search Query Text Element]  class="search-query-term"
//           </H1>
//         </Div>
//
//         <Paragraph>        class="search-results-count"
//           [Search Results Count Element]
//         </Paragraph>
//
//         <Div>              class="search-form-wrapper"
//           [Search Form Element]  class="search-form"
//         </Div>
//
//         [Search Results List]  class="search-results-list"
//           [Search Result Item]  class="search-result-item"
//             <Div>          class="result-item-inner"
//               <Div>        class="result-item-meta"
//                 <Text>     class="result-item-type"   [Bound: Result Type]
//                 <Text>     class="result-item-date"   [Bound: Result Published Date]
//               </Div>
//               <H2>         class="result-item-title"
//                 <Link>     class="result-item-title-link"  [Bound: Result URL]
//                   [Bound: Result Title]
//                 </Link>
//               </H2>
//               <Paragraph>  class="result-item-excerpt"  [Bound: Result Excerpt]
//               <Link>       class="result-item-readmore"  [Bound: Result URL] "Read More"
//             </Div>
//           [/Search Result Item]
//
//           [Empty State]    class="search-no-results"   <- get_template_part('content', 'none')
//             <Div>          class="no-results-content"
//               <H2>         class="no-results-title"  "Nothing Found"
//               <Paragraph>  class="no-results-text"   "Sorry, nothing matched..."
//               [Search Form] class="no-results-search-widget"
//             </Div>
//           [/Empty State]
//
//           [Pagination]     class="search-pagination"   <- the_posts_navigation()
//             [Prev Link]    class="pagination-prev"
//             [Page Numbers] class="pagination-numbers"
//             [Next Link]    class="pagination-next"
//           [/Pagination]
//
//         [/Search Results List]
//
//       </Div>  <!-- /.search-content-area -->
//
//       <Div>                class="search-sidebar-area" [Flex: 0 0 30%]
//         [Symbol: Global Sidebar]                       <- get_sidebar()
//       </Div>
//
//     </Div>  <!-- /.search-page-layout -->
//   </Container>
// </Section>  <!-- /.search-page-section -->
//
// [Symbol] Global Footer                          <- get_footer()

// ============================================================
// CSS CLASS MAPPING: WordPress -> Webflow
// ============================================================
// WordPress class         Webflow class                    Notes
// --------------------------------------------------------
// .content-area           .search-content-area             MIGRATED: renamed to avoid conflicts
// .site-main              (removed, structural only)        MIGRATED: not needed in Webflow layout
// .page-header            .page-header                     MIGRATED: retained
// .page-title             .page-title                      MIGRATED: retained
// (no class — query span) .search-query-term               MIGRATED: added for styling the query string
// .hentry                 .search-result-item              MIGRATED: renamed for clarity
// .entry-title            .result-item-title               MIGRATED: renamed for context
// .entry-summary          .result-item-excerpt             MIGRATED: renamed for context
// .entry-meta             .result-item-meta                MIGRATED: renamed for context
// .cat-links              .result-item-type                MIGRATED: repurposed for result type label
// .posted-on              .result-item-date                MIGRATED: retained semantics
// .no-results             .search-no-results               MIGRATED: retained semantics
// .page-content           .no-results-content              MIGRATED: renamed for specificity
// .search-form (sidebar)  .sidebar-search-form             MIGRATED: renamed to avoid collision
// .widget                 .sidebar-widget                  MIGRATED: retained partial
// .widget-title           .widget-title                    MIGRATED: retained

// ============================================================
// WEBFLOW PLAN REQUIREMENTS
// ============================================================
// TODO: Confirm Webflow plan supports Site Search before building this page
// - Webflow Free / Starter: NO site search
// - Webflow Basic: NO site search
// - Webflow CMS Plan: NO site search
// - Webflow Business Plan: YES — native site search included
// - Webflow Enterprise: YES
//
// ALTERNATIVE if Business plan is not available:
// TODO: Manual action in Webflow — Use a third-party search solution instead:
//   Option A: Algolia DocSearch or Algolia InstantSearch (embed via custom code)
//   Option B: Jetboost site search (Webflow-specific third-party tool)
//   Option C: Google Custom Search Engine (CSE) embedded via iframe/custom code
//   Option D: Swiftype embedded search widget
//
// If using a third-party search solution:
//   - Create a static Webflow page at slug /search
//   - Add a custom code Embed block containing the third-party search widget
//   - The search-results-list, search-query, and pagination elements are replaced by the embed
//   - The page header, sidebar, navbar, and footer Symbols remain the same

// ============================================================
// SEO CONSIDERATIONS FOR SEARCH RESULTS PAGE
// ============================================================
// WordPress: Search results pages are typically noindexed by Yoast/RankMath automatically
// Webflow: In Page Settings for the Search Results page:
//   - Check "Exclude this page from search results" (noindex) -> YES, recommended
//   - Title Tag: "Search Results for {query} | Site Name" — Webflow will auto-populate if using Search page
//   - Meta Description: Leave default or set to generic "Search our site..."
//
// TODO: Manual action in Webflow — Open Page Settings for the Search Results page
//       -> Enable "Exclude from Search Engines" (adds noindex, nofollow meta tag)
//       This matches WordPress/Yoast behavior of noindexing search result pages
//
// TODO: SEO 301 Redirects — Add to Webflow Redirects (Project Settings -> Hosting -> Redirects):
//   /?s=*                ->  /search?q=*          (WordPress search URL format to Webflow format)
//   Note: Webflow redirect rules support wildcard patterns; test query parameter handling
//   Most search URLs are not indexed by Google, so this is lower SEO priority than page/post URLs

// ============================================================
// INTERACTIONS & ANIMATIONS (OPTIONAL ENHANCEMENTS)
// ============================================================
// TODO: Manual action in Webflow — Consider adding these interactions on the Search Results page:
//
// 1. Results fade-in on load:
//    Trigger: Page Load
//    Target: .search-result-item (each)
//    Animation: Fade In + Slide Up, staggered 100ms per item
//
// 2. Search input focus state:
//    Trigger: Mouse Click / Focus on .search-input
//    Animation: Expand width, show border highlight (via CSS transition, not Webflow interaction)
//
// 3. "No Results" state illustration/animation:
//    Trigger: Page Load (only fires when Empty State is visible)
//    Animation: Lottie animation or SVG illustration fade-in for empty state UX

// ============================================================
// CUSTOM CODE (HEAD) — Search Results Page
// ============================================================
// Add to Page Settings -> Custom Code -> Head for the Search Results page:
//
// <script type="application/ld+json">
// {
//   "@context": "https://schema.org",
//   "@type": "SearchResultsPage",
//   "name": "Search Results",
//   "url": "https://yoursite.com/search",
//   "breadcrumb": {
//     "@type": "BreadcrumbList",
//     "itemListElement": [
//       {
//         "@type": "ListItem",
//         "position": 1,
//         "name": "Home",
//         "item": "https://yoursite.com"
//       },
//       {
//         "@type": "ListItem",
//         "position": 2,
//         "name": "Search Results"
//       }
//     ]
//   }
// }
// </script>
//
// MIGRATED: Schema markup for search results page (no WordPress equivalent in _s theme base)
// NOTE: Replace "https://yoursite.com" with your actual Webflow domain

// ============================================================
// MIGRATION CHECKLIST: search.php -> Webflow Search Results Page
// ============================================================
// [ ] 1. Confirm Webflow Business plan is active (required for native site search)
// [ ] 2. Enable Site Search in Webflow Project Settings -> Search
// [ ] 3. Navigate to Pages -> Utility Pages -> Search Page in Webflow Designer
// [ ] 4. Build the two-column layout: search-content-area + search-sidebar-area
// [ ] 5. Add Search Query Text element bound to current query (H1 suffix)
// [ ] 6. Add Search Results Count element below H1
// [ ] 7. Add Search Form element pre-populated with current query
// [ ] 8. Add Search Results List element with result item template:
//         - Result Title (linked)
//         - Result Excerpt
//         - Result Type label
//         - Result Date
//         - Read More link
// [ ] 9. Configure Search Results List Empty State with "Nothing Found" content
// [ ] 10. Enable Pagination on Search Results List (10 items per page recommended)
// [ ] 11. Drop Global Navbar Symbol at top of page
// [ ] 12. Drop Global Sidebar Symbol in sidebar column
// [ ] 13. Drop Global Footer Symbol at bottom of page
// [ ] 14. Set page SEO: noindex (Exclude from Search Engines) in Page Settings
// [ ] 15. Configure search index: Project Settings -> Search -> select Collections and fields to index
// [ ] 16. Add 301 redirect: /?s=* -> /search?q=* in Project Settings -> Hosting -> Redirects
// [ ] 17. Add JSON-LD SearchResultsPage schema to page head custom code
// [ ] 18. Apply responsive styles: mobile stacks content above sidebar
// [ ] 19. Test search with: (a) results found, (b) no results, (c) paginated results
// [ ] 20. Verify search query string is correctly displayed in the H1 Search Query element