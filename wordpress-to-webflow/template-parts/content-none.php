// MIGRATED: template-parts/content-none.php -> Webflow Symbol: "No Results / Empty State"
// This WordPress template part displays a "nothing found" message in three contexts:
//   1. Blog home with no posts (admin-only prompt to create first post)
//   2. Search results with no matches (search form re-shown)
//   3. All other cases (generic not-found message + search form)
// In Webflow, these three states become separate static sections or conditional Symbols
// placed on the relevant pages (Blog, Search Results, 404).

// ============================================================
// WEBFLOW SYMBOL: "Empty State - No Results"
// Symbol name: empty-state-no-results
// Usage: Embed on Search Results page and Blog Collection List page as fallback
// ============================================================

// ------------------------------------------------------------
// WEBFLOW PAGE PLACEMENT MAP
// ------------------------------------------------------------
// WordPress is_home() + admin prompt  -> NOT replicated in Webflow
//   // TODO: Manual action in Webflow — The WordPress admin-only "publish your first post"
//   // prompt has no Webflow equivalent. Webflow Collection Lists simply render empty when
//   // no items exist. No action required; remove this conditional entirely.
//
// WordPress is_search() empty results -> Webflow Search Results page empty state section
//   // TODO: Manual action in Webflow — Place the "Search No Results" Symbol/section inside
//   // the Search Results page. Webflow's native search component handles the empty state
//   // through its built-in "no results" slot (available in the Search component settings).
//   // Drag a "No Results" state element inside the Webflow Search Results element and
//   // add the messaging + a new Search Form component inside it.
//
// WordPress else (generic not found)  -> Webflow 404 page and/or Blog archive empty state
//   // TODO: Manual action in Webflow — Place the "Generic No Results" section on the
//   // custom 404 page and/or as a fallback empty state on archive/tag/category pages.

// ============================================================
// HTML STRUCTURE BLUEPRINT FOR WEBFLOW DESIGNER
// ============================================================
// The structure below is the clean HTML target to rebuild visually in Webflow Designer.
// CSS class names are preserved from the original theme for reference; rename to your
// Webflow project's naming convention (e.g., BEM, utility-first with Tailwind, etc.).
// The theme uses Tailwind CSS (see package.json devDependencies) — apply Tailwind utility
// classes in Webflow's custom class fields or via a custom code embed.

// ------------------------------------------------------------
// VARIANT A: Search Results Page — Empty State
// Webflow element: Section (class: no-results not-found)
//   └── Div Block (class: page-header)
//       └── Heading H1 (class: page-title) — static text: "Nothing Found"
//   └── Div Block (class: page-content)
//       └── Paragraph — static text:
//               "Sorry, but nothing matched your search terms.
//                Please try again with some different keywords."
//       └── Search Form Component
//               // TODO: Manual action in Webflow — Insert Webflow's native Search element
//               // (Add Elements panel -> Search). This replaces get_search_form().
//               // Webflow Search requires a Business plan or higher.
//               // If Search is unavailable on current plan, embed a custom search form
//               // pointing to /search?q={query} via an HTML Embed block.
// ------------------------------------------------------------

// ------------------------------------------------------------
// VARIANT B: 404 Page / Generic Not Found — Empty State
// Webflow element: Section (class: no-results not-found)
//   └── Div Block (class: page-header)
//       └── Heading H1 (class: page-title) — static text: "Nothing Found"
//   └── Div Block (class: page-content)
//       └── Paragraph — static text:
//               "It seems we can\u2019t find what you\u2019re looking for.
//                Perhaps searching can help."
//       └── Search Form Component
//               // TODO: Manual action in Webflow — Same as Variant A above.
//               // Insert Webflow native Search element or custom HTML Embed search form.
// ------------------------------------------------------------

// ============================================================
// CLEAN HTML OUTPUT (for Webflow Embed block or import reference)
// ============================================================
// Use the HTML below as a reference when manually building in Webflow Designer,
// or paste into an HTML Embed block as a starting scaffold before converting to
// native Webflow elements.

/*

VARIANT A — Search No Results:

<section class="no-results not-found">
  <header class="page-header">
    <h1 class="page-title">Nothing Found</h1>
  </header>

  <div class="page-content">
    <p>Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>

    <!-- MIGRATED: get_search_form() -> Webflow native Search component -->
    <!-- TODO: Manual action in Webflow — Replace this embed with the native Search element -->
    <form role="search" method="get" class="search-form" action="/">
      <label>
        <span class="screen-reader-text">Search for:</span>
        <input type="search" class="search-field" placeholder="Search &hellip;" name="search" />
      </label>
      <input type="submit" class="search-submit" value="Search" />
    </form>
  </div>
</section>


VARIANT B — Generic Not Found / 404:

<section class="no-results not-found">
  <header class="page-header">
    <h1 class="page-title">Nothing Found</h1>
  </header>

  <div class="page-content">
    <p>It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.</p>

    <!-- MIGRATED: get_search_form() -> Webflow native Search component -->
    <!-- TODO: Manual action in Webflow — Replace this embed with the native Search element -->
    <form role="search" method="get" class="search-form" action="/">
      <label>
        <span class="screen-reader-text">Search for:</span>
        <input type="search" class="search-field" placeholder="Search &hellip;" name="search" />
      </label>
      <input type="submit" class="search-submit" value="Search" />
    </form>
  </div>
</section>

*/

// ============================================================
// WEBFLOW DESIGNER STEP-BY-STEP REBUILD INSTRUCTIONS
// ============================================================

// STEP 1 — Create a Webflow Symbol named "Empty State - Search No Results"
//   1a. In the Webflow Designer, open the Pages panel.
//   1b. Navigate to the Search Results page (requires Business plan).
//   1c. Inside the Search Results component's "No Results" state slot, add:
//       - Section element, class: "no-results not-found"
//       - Inside: Div Block, class: "page-header"
//         - Inside: H1 Heading, class: "page-title", text: "Nothing Found"
//       - Inside Section: Div Block, class: "page-content"
//         - Inside: Paragraph with search-no-results message text (Variant A)
//         - Inside: Webflow Search Form element (native component)
//           // MIGRATED: get_search_form() replaced by Webflow native Search element
//   1d. Select the Section, right-click -> "Create Symbol" -> name: "Empty State - Search No Results"

// STEP 2 — Create a Webflow Symbol named "Empty State - Not Found"
//   2a. Open the 404 page in Webflow Designer (Pages panel -> Utility Pages -> 404).
//   2b. Add a Section element, class: "no-results not-found"
//   2c. Inside: Div Block, class: "page-header"
//       - H1 Heading, class: "page-title", text: "Nothing Found"
//   2d. Inside Section: Div Block, class: "page-content"
//       - Paragraph with generic not-found message text (Variant B)
//       - Webflow Search Form element (native component) or HTML Embed fallback
//         // MIGRATED: get_search_form() replaced by Webflow native Search element
//   2e. Select the Section, right-click -> "Create Symbol" -> name: "Empty State - Not Found"

// STEP 3 — Apply Symbols where needed
//   3a. Embed "Empty State - Not Found" on the 404 page (Utility Pages -> 404 Not Found).
//   3b. Embed "Empty State - Search No Results" within the Search Results page's no-results slot.
//   3c. Optionally embed "Empty State - Not Found" as a fallback on any CMS Collection List page
//       that may render empty (blog archive, category pages, tag pages).
//       // TODO: Manual action in Webflow — Webflow Collection Lists do not natively show a
//       // fallback empty state. Use CSS conditional visibility: set the empty-state div to
//       // display:none by default, then use Webflow Interactions or custom JS to show it
//       // when the Collection List has zero items, or use the Collection List's built-in
//       // "Empty State" slot if available in your Webflow plan.

// STEP 4 — Styling (Tailwind CSS)
//   // MIGRATED: Theme uses Tailwind CSS ^1 (see package.json).
//   // In Webflow, apply Tailwind utility classes via the custom class field on each element,
//   // OR include the Tailwind CDN in Project Settings -> Custom Code -> Head Code:
//   //   <script src="https://cdn.tailwindcss.com"></script>
//   // Recommended Tailwind utility classes for this component:
//   //   Section.no-results:        py-16 px-4 text-center
//   //   .page-header:              mb-6
//   //   .page-title:               text-3xl font-bold text-gray-800
//   //   .page-content p:           text-gray-600 mb-4
//   //   .search-form:              flex justify-center gap-2 mt-4
//   //   .search-field:             border border-gray-300 rounded px-3 py-2 w-full max-w-md
//   //   .search-submit:            bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700

// ============================================================
// CSS CLASS REFERENCE (from original WordPress template)
// ============================================================
// .no-results     — outer section wrapper for the entire empty state block
// .not-found      — modifier class, same element as .no-results
// .page-header    — header area containing the page title
// .page-title     — H1 heading "Nothing Found"
// .page-content   — body content area with message and search form
// .search-form    — search form wrapper (output by get_search_form())
// .search-field   — search text input
// .search-submit  — search submit button
// .screen-reader-text — visually hidden label text for accessibility
//                  // MIGRATED: preserve for accessibility; in Webflow set via custom class
//                  // with CSS: position:absolute; width:1px; height:1px; overflow:hidden;

// ============================================================
// REMOVED WORDPRESS-SPECIFIC LOGIC (no Webflow equivalent)
// ============================================================
// REMOVED: is_home() && current_user_can('publish_posts') conditional
//   // MIGRATED: Admin-only "publish your first post" link removed entirely.
//   // Webflow does not have a concept of logged-in admin states on the frontend.
//   // Webflow Editor users manage content through the Webflow Editor interface, not
//   // through frontend prompts. No replacement needed.
//
// REMOVED: esc_html_e(), esc_url(), wp_kses(), admin_url() PHP functions
//   // MIGRATED: All PHP escaping and URL generation functions are WordPress-only.
//   // In Webflow, content is static text entered directly in the Designer or CMS.
//   // No escaping functions are needed; Webflow handles output encoding internally.
//
// REMOVED: Translation function __() / _e() / esc_html_e() with text domain '_s'
//   // MIGRATED: Internationalization via gettext is WordPress-specific.
//   // TODO: Manual action in Webflow — If multi-language support is required, use
//   // Webflow Localization (available on certain plans) or a third-party solution
//   // such as Weglot or Localize.js. Static text strings should be entered directly
//   // in the target language(s) during content entry in Webflow Designer.
//
// REMOVED: get_search_form() PHP function call
//   // MIGRATED: Replaced by Webflow native Search component (Variant A & B above).
//   // If Webflow Search is unavailable, use an HTML Embed block with a standard
//   // HTML search form pointing to the Webflow site search URL.