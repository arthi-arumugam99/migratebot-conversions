// MIGRATED: template-parts/content-page.php -> Webflow Symbol / Static Page Content Block
// This template part renders the main content area for WordPress Pages (page.php).
// In Webflow, this becomes a reusable Symbol or inline section structure used on static pages.
// The Symbol should be named: "Page Content Block" or embedded directly in static page layouts.

// ============================================================
// WEBFLOW STRUCTURE BLUEPRINT: Page Content Block
// Source: template-parts/content-page.php
// Target: Webflow Static Page Body Section (or Symbol)
// ============================================================

// ------------------------------------------------------------
// ROOT ELEMENT
// ------------------------------------------------------------
// WordPress: <article id="post-{ID}" class="post-{ID} page type-page status-publish hentry ...">
// Webflow:   Section element
//   - Webflow class: "page-content-section"
//   - Tag: <section> or <div> depending on layout context
//   - No dynamic ID binding needed (Webflow pages are not loop-rendered)
//   - post_class() CSS classes are WordPress-specific; do NOT carry over
//     The following WP classes have no Webflow equivalent and should be dropped:
//       post-{ID}, page, type-page, status-publish, hentry
//   // MIGRATED: <article> semantic wrapper -> Webflow Section with class "page-content-section"

// ------------------------------------------------------------
// SECTION 1: ENTRY HEADER
// ------------------------------------------------------------
// WordPress:
//   <header class="entry-header">
//     <h1 class="entry-title">{Page Title}</h1>
//   </header>
//
// Webflow:
//   Container element (class: "page-header")
//     └── Heading (H1) element
//           - Webflow class: "entry-title"
//           - Content: Static text set per page in Webflow Designer
//           - For CMS-driven pages: bind to CMS "Name" field
//           // MIGRATED: the_title() -> Static Heading H1 with class "entry-title"
//           // NOTE: On static Webflow pages, page title is set manually in Page Settings
//           //       and also visible as a plain Heading element in the Designer canvas.
//           //       For CMS Collection Template pages, bind this Heading to the CMS "Name" field.

// ------------------------------------------------------------
// SECTION 2: FEATURED IMAGE (POST THUMBNAIL)
// ------------------------------------------------------------
// WordPress: _s_post_thumbnail() — custom theme function wrapping get_the_post_thumbnail()
//   Renders: <div class="post-thumbnail"><img src="..." alt="..." /></div>
//
// Webflow:
//   Div Block (class: "post-thumbnail")
//     └── Image element
//           - Webflow class: "featured-image"
//           - On static pages: upload image directly and set in Designer
//           - On CMS Collection Templates: bind src to CMS "Featured Image" field
//           - Alt text: bind to CMS "Featured Image Alt" field or set statically
//           - Responsive image settings: enable in Webflow image element settings
//           // MIGRATED: _s_post_thumbnail() -> Webflow Image element, class "post-thumbnail"
//           // TODO: Manual action in Webflow — if featured image is optional on some pages,
//           //       use Webflow conditional visibility: hide element when "Featured Image" field is empty

// ------------------------------------------------------------
// SECTION 3: ENTRY CONTENT (MAIN BODY)
// ------------------------------------------------------------
// WordPress:
//   <div class="entry-content">
//     <?php the_content(); ?>
//     <?php wp_link_pages(...); ?>
//   </div>
//
// Webflow:
//   Div Block (class: "entry-content")
//     └── Rich Text Block element
//           - Webflow class: "page-rich-text"
//           - On static pages: author content directly inside the Rich Text Block
//             using Webflow's built-in rich text editor (H2, H3, p, lists, images, embeds)
//           - On CMS Collection Template pages: bind to CMS "Content" Rich Text field
//           // MIGRATED: the_content() -> Webflow Rich Text Block, class "page-rich-text"
//
//   wp_link_pages() — multipage post pagination via <!--nextpage--> tag:
//   // TODO: Manual action in Webflow — Webflow has no equivalent to WordPress <!--nextpage--> splits.
//           If paginated page content exists, restructure as:
//           Option A: Single long-scroll page (remove pagination, consolidate content)
//           Option B: Multiple separate static Webflow pages with manual prev/next nav links
//           Option C: Tab component in Webflow with each "page" as a separate tab panel
//           Recommended: Option A unless content is extremely long.
//           The <div class="page-links"> wrapper and "Pages:" label should NOT be migrated.

// ------------------------------------------------------------
// SECTION 4: ENTRY FOOTER (EDIT LINK — ADMIN ONLY)
// ------------------------------------------------------------
// WordPress:
//   <?php if ( get_edit_post_link() ) : ?>
//     <footer class="entry-footer">
//       <span class="edit-link">
//         <a href="{edit_url}">Edit <span class="screen-reader-text">{Title}</span></a>
//       </span>
//     </footer>
//   <?php endif; ?>
//
// Webflow:
//   // MIGRATED: edit_post_link() -> intentionally omitted in Webflow output
//   // NOTE: This element is only visible to logged-in WordPress admins/editors.
//           Webflow does not have a native "logged-in admin" conditional display outside
//           of Webflow Memberships (if applicable). Since this is purely an editorial
//           convenience link back to wp-admin (which no longer exists post-migration),
//           this entire block should be DROPPED.
//   // TODO: Manual action in Webflow — if an editorial workflow is needed post-migration,
//           consider Webflow Editor (built-in CMS editing interface) or a Webflow Membership
//           role-gated edit button linking to the Webflow Editor for that CMS item.
//           For static pages, editing is done directly in Webflow Designer — no edit link needed.

// ============================================================
// COMPLETE WEBFLOW ELEMENT TREE (DESIGNER HIERARCHY)
// ============================================================
//
//  <section.page-content-section>                  // MIGRATED: <article> wrapper
//    <div.page-header>                              // MIGRATED: <header.entry-header>
//      <h1.entry-title>Page Title Here</h1>        // MIGRATED: the_title()
//    </div>
//    <div.post-thumbnail>                           // MIGRATED: _s_post_thumbnail()
//      <img.featured-image src="..." alt="..." />
//    </div>
//    <div.entry-content>                            // MIGRATED: <div.entry-content>
//      [Rich Text Block.page-rich-text]             // MIGRATED: the_content()
//        <p>...</p>
//        <h2>...</h2>
//        <ul>...</ul>
//        ... (all rich text content authored here)
//      [/Rich Text Block]
//      // NOTE: wp_link_pages() pagination dropped — see TODO above
//    </div>
//    // NOTE: <footer.entry-footer> edit link dropped — see NOTE above
//  </section>

// ============================================================
// CSS CLASSES EXTRACTED FROM SOURCE TEMPLATE
// ============================================================
// Map of original WordPress/theme CSS classes to Webflow class names:
//
//  WordPress Class          Webflow Class             Notes
//  ---------------------    ----------------------    ----------------------------------
//  (dynamic) post-{ID}      (dropped)                 WP-specific, not needed in Webflow
//  page                     (dropped)                 WP post type class, not needed
//  type-page                (dropped)                 WP-specific
//  status-publish           (dropped)                 WP-specific
//  hentry                   (dropped)                 Microformat class, not needed
//  entry-header             page-header               // MIGRATED: renamed for clarity
//  entry-title              entry-title               // MIGRATED: retained
//  post-thumbnail           post-thumbnail            // MIGRATED: retained
//  entry-content            entry-content             // MIGRATED: retained
//  page-links               (dropped)                 WP pagination, not applicable
//  entry-footer             (dropped)                 Only contained admin edit link
//  edit-link                (dropped)                 Admin-only, not applicable
//  screen-reader-text       (dropped)                 Re-implement via Webflow .sr-only if needed

// ============================================================
// WEBFLOW SYMBOL USAGE NOTES
// ============================================================
// This content block is used by: page.php (parent template)
// In Webflow, the equivalent is:
//   - Embedded directly inside each static page's canvas (most common for unique pages)
//   - OR extracted as a Symbol named "Page Content Block" if the structure is reused
//     across many pages with identical layout (e.g., legal pages, about, contact)
//
// Symbol parameters (Webflow does not support props natively):
//   - If using as a Symbol, leave the Rich Text Block unbound so each page instance
//     can have unique content authored inline
//   - If migrating to CMS-driven pages, create a "Pages" CMS Collection and bind fields
//
// Related Webflow pages that use this structure:
//   - page.php          -> any static Webflow page
//   - page-{slug}.php   -> specific static Webflow pages (About, Contact, etc.)
//   - front-page.php    -> Homepage (may use this block in combination with hero sections)

// ============================================================
// CONTENT MIGRATION CHECKLIST FOR THIS TEMPLATE PART
// ============================================================
// [ ] Export all WordPress Pages content via Tools > Export (Pages only)
// [ ] Strip Gutenberg block comments (<!-- wp:* --> markers) from exported content
// [ ] Convert any remaining shortcodes found in page content (catalog first)
// [ ] Verify wpautop() output: ensure <p> tags are clean with no double-wrapping
// [ ] Upload featured images to Webflow Assets; record old wp-content URL -> new Webflow URL mapping
// [ ] Rewrite any internal links in page content to new Webflow URL structure
// [ ] Re-enter or paste clean HTML into Webflow Rich Text Blocks per page
// [ ] Set page Title, Meta Description, OG image in Webflow Page Settings for each page
// [ ] Confirm no <!--nextpage--> tags exist in exported page content (if found, see TODO above)
// [ ] Remove all admin edit links — no equivalent needed in Webflow