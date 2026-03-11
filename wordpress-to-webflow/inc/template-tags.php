// ============================================================
// WEBFLOW MIGRATION: inc/template-tags.php
// WordPress Source: _s (Underscores) starter theme
// Migration Target: Webflow CMS Collection Template + Symbol Components
// ============================================================
//
// This file contained PHP template tag functions that output dynamic HTML
// for post metadata (date, author, categories, tags, comments, thumbnail).
// In Webflow, these are NOT PHP functions — they are structural HTML
// blueprints bound to CMS Collection fields in the Webflow Designer.
//
// Each function below is documented as:
//   1. The original PHP behavior
//   2. The Webflow structural equivalent (HTML + CMS bindings)
//   3. CSS class mapping for Webflow class naming
//   4. TODO items for manual action in Webflow Designer
// ============================================================


// ============================================================
// FUNCTION: _s_posted_on()
// WordPress: Outputs publication date and modified date as <time> elements
// Webflow Target: "Post Meta - Date" Symbol or inline element group
//                 Used inside: Blog Post Collection Template page
// ============================================================
//
// ORIGINAL BEHAVIOR:
//   - Renders a <time> element with datetime attribute (ISO 8601 / W3C format)
//   - If post was modified after publish, renders TWO <time> elements:
//       one with class="entry-date published"
//       one with class="updated"
//   - Wraps both in a <span class="posted-on">
//   - The date is linked to the post permalink via <a rel="bookmark">
//
// WEBFLOW EQUIVALENT STRUCTURE:
//
//   <div class="posted-on">                          <!-- Webflow: Div Block, class: posted-on -->
//     <span class="posted-on__label">Posted on</span> <!-- Webflow: Text Block, static text -->
//     <a class="posted-on__link"                     <!-- Webflow: Link Block bound to CMS Slug -->
//        href="{CMS: Slug / Permalink}">
//       <time class="entry-date published"           <!-- Webflow: Text Block -->
//             datetime="{CMS: Published Date ISO}">  <!--   - Bind datetime attr via Attribute: CMS Published Date (formatted: YYYY-MM-DDTHH:MM:SSZ) -->
//         {CMS: Published Date}                      <!--   - Bind text content to: CMS Published Date (formatted: Month DD, YYYY) -->
//       </time>
//     </a>
//   </div>
//
// CMS FIELD BINDINGS:
//   - "Published Date" -> Webflow built-in "Created On" DateTime field
//     - Display format: "Month DD, YYYY" (e.g., January 15, 2024)
//     - Attribute binding for datetime: ISO 8601 format (YYYY-MM-DDTHH:MM:SSZ)
//   - "Modified Date" -> Webflow built-in "Updated On" DateTime field
//     - Display format: "Month DD, YYYY"
//     - Attribute binding for datetime: ISO 8601 format
//   - Post permalink -> Webflow auto-generates from Collection slug (bound to Link element)
//
// CSS CLASS MAPPING:
//   .posted-on           -> Webflow class: "posted-on"       (wrapper span/div)
//   .entry-date          -> Webflow class: "entry-date"      (time element or text block)
//   .published           -> Webflow class: "published"       (modifier class on date element)
//   .updated             -> Webflow class: "updated"         (modifier class on modified date)
//
// TODO: Manual action in Webflow Designer —
//   Webflow does not natively support conditional rendering of the "updated" date
//   only when it differs from the published date. Options:
//   a) Always show both "Published" and "Last Updated" fields side by side
//      using two separate Text elements each bound to their respective CMS DateTime fields.
//   b) Use custom code embed with JavaScript to conditionally show/hide
//      the updated date based on field value comparison.
//   c) Add a custom Boolean/Switch CMS field "Show Updated Date" and use
//      Webflow conditional visibility on the updated <time> element.
//
// TODO: Manual action in Webflow Designer —
//   The <time> HTML element is not a native Webflow element type.
//   Use a Text Block element and add a custom attribute:
//     Attribute Name: "datetime"
//     Attribute Value: bind to CMS "Created On" field with ISO date format
//   Then wrap in a <div> or use Webflow Embed block for semantic <time> tag output.
//
// RECOMMENDED SYMBOL STRUCTURE (Webflow Symbol: "Post Meta Date"):
//   Symbol wraps the entire posted-on block so it can be reused on:
//     - Blog Post Collection Template (single.php equivalent)
//     - Blog Archive Collection List item cards (archive.php equivalent)
//     - Homepage featured posts Collection List items
// ============================================================


// ============================================================
// FUNCTION: _s_posted_by()
// WordPress: Outputs author name linked to author archive page
// Webflow Target: "Post Meta - Author" Symbol or inline element
//                 Used inside: Blog Post Collection Template page
// ============================================================
//
// ORIGINAL BEHAVIOR:
//   - Renders: "by [Author Name]" where Author Name is a link to author archive
//   - Outer wrapper: <span class="byline">
//   - Inner author: <span class="author vcard"><a class="url fn n" href="/author/[slug]/">
//   - Uses microformats classes: vcard, url, fn, n
//
// WEBFLOW EQUIVALENT STRUCTURE:
//
//   <div class="byline">                             <!-- Webflow: Div Block, class: byline -->
//     <span class="byline__prefix">by </span>        <!-- Webflow: Text Block, static text "by " -->
//     <a class="author-link url fn n"                <!-- Webflow: Link Block -->
//        href="{CMS: Author Page URL}">              <!--   - href bound to Author Reference field URL -->
//       <span class="author vcard">                  <!-- Webflow: Text Inline / Span -->
//         {CMS: Author Name}                         <!--   - Bind to Author Reference -> Name field -->
//       </span>
//     </a>
//   </div>
//
// CMS FIELD BINDINGS:
//   - Author Name -> Webflow CMS Reference field "Author" -> linked Collection "Authors" -> "Name" field
//   - Author URL  -> Webflow CMS Reference field "Author" -> linked Collection "Authors" -> "Slug" field
//                    (construct URL as /authors/{slug} or link directly to Author page)
//
// WEBFLOW CMS COLLECTIONS REQUIRED:
//   Collection: "Authors"
//   Fields:
//     - Name          (Plain Text, required)        // MIGRATED: from get_the_author()
//     - Slug          (Slug, auto-generated)         // MIGRATED: from get_author_posts_url()
//     - Bio           (Plain Text or Rich Text)      // MIGRATED: from author description meta
//     - Avatar        (Image)                        // MIGRATED: from get_avatar()
//     - Website URL   (Link)                         // MIGRATED: from author URL meta
//
//   Blog Posts Collection:
//     - Author        (Reference -> Authors Collection) // MIGRATED: from post_author
//
// CSS CLASS MAPPING:
//   .byline          -> Webflow class: "byline"       (wrapper)
//   .author          -> Webflow class: "author"       (inner span)
//   .vcard           -> Webflow class: "vcard"        (microformat — optional, keep for semantic value)
//   .url             -> Webflow class: "url"          (microformat — on the link element)
//   .fn              -> Webflow class: "fn"           (microformat — on the name span)
//   .n               -> Webflow class: "n"            (microformat — on the name span)
//
// TODO: Manual action in Webflow Designer —
//   WordPress author archive pages (/author/[slug]/) have no direct Webflow equivalent.
//   Options:
//   a) Create a Webflow CMS Collection page for "Authors" — each author gets their own
//      page at /authors/[slug]/ with a Collection List of their posts filtered by Author.
//   b) If author pages are not needed, link to a filtered blog page or remove the link entirely.
//   c) Set up 301 redirects: /author/[slug]/ -> /authors/[slug]/ in Webflow Hosting settings.
//
// TODO: Manual action in Webflow Designer —
//   Microformat classes (vcard, url, fn, n) are purely semantic.
//   Re-apply these as additional Webflow classes on the appropriate elements
//   if structured data / microformat support is needed for SEO.
//   Consider replacing with JSON-LD Person schema in page <head> custom code instead.
// ============================================================


// ============================================================
// FUNCTION: _s_entry_footer()
// WordPress: Outputs categories, tags, comments link, and edit link
// Webflow Target: "Post Entry Footer" Symbol
//                 Used inside: Blog Post Collection Template page
//                              Blog Archive Collection List item cards
// ============================================================
//
// ORIGINAL BEHAVIOR:
//   - For posts only (not pages): outputs category list and tag list
//   - Category list: <span class="cat-links">Posted in [cat1], [cat2]</span>
//   - Tag list: <span class="tags-links">Tagged [tag1], [tag2]</span>
//   - For non-single views: outputs comments link <span class="comments-link">
//   - For logged-in editors: outputs edit link <span class="edit-link">Edit [title]</span>
//
// WEBFLOW EQUIVALENT STRUCTURE:
//
//   <div class="entry-footer">                       <!-- Webflow: Div Block, class: entry-footer -->
//
//     <!-- CATEGORIES -->
//     <div class="cat-links">                        <!-- Webflow: Div Block, class: cat-links -->
//       <span class="cat-links__label">Posted in </span>  <!-- Webflow: Text Block, static -->
//       <!-- Webflow: Nested Collection List bound to "Categories" MultiReference field -->
//       <div class="cat-links__list">               <!-- Webflow: Collection List Wrapper -->
//         <!-- Collection List Item: -->
//         <a class="cat-links__item"                 <!-- Webflow: Link Block bound to Category slug -->
//            href="{CMS: Category Slug URL}">
//           {CMS: Category Name}                     <!--   - Bind to Category Name field -->
//         </a>
//       </div>
//     </div>
//
//     <!-- TAGS -->
//     <div class="tags-links">                       <!-- Webflow: Div Block, class: tags-links -->
//       <span class="tags-links__label">Tagged </span>    <!-- Webflow: Text Block, static -->
//       <!-- Webflow: Nested Collection List bound to "Tags" MultiReference field -->
//       <div class="tags-links__list">              <!-- Webflow: Collection List Wrapper -->
//         <!-- Collection List Item: -->
//         <a class="tags-links__item"                <!-- Webflow: Link Block bound to Tag slug -->
//            href="{CMS: Tag Slug URL}">
//           {CMS: Tag Name}                          <!--   - Bind to Tag Name field -->
//         </a>
//       </div>
//     </div>
//
//     <!-- COMMENTS LINK (archive/loop views only — not on single post page) -->
//     <!-- TODO: See comments note below — Webflow has no native comments system -->
//     <div class="comments-link" style="display:none">  <!-- Webflow: Hide this element -->
//       <!-- Replaced by Disqus or third-party comment embed on single post template -->
//     </div>
//
//     <!-- EDIT LINK (admin/editor only — not applicable in Webflow frontend) -->
//     <!-- NOTE: Webflow has its own Editor mode — no equivalent "edit post link" for frontend -->
//     <!-- This element is intentionally omitted from Webflow output -->
//
//   </div>
//
// CMS FIELD BINDINGS:
//   - Categories -> Webflow CMS MultiReference field "Categories" -> "Blog Categories" Collection
//       Blog Categories Collection fields:
//         - Name   (Plain Text, required)            // MIGRATED: from category name
//         - Slug   (Slug, auto-generated)            // MIGRATED: from category_nicename
//         - Description (Plain Text)                 // MIGRATED: from category description
//   - Tags -> Webflow CMS MultiReference field "Tags" -> "Blog Tags" Collection
//       Blog Tags Collection fields:
//         - Name   (Plain Text, required)            // MIGRATED: from tag name
//         - Slug   (Slug, auto-generated)            // MIGRATED: from tag slug
//
// CSS CLASS MAPPING:
//   .entry-footer    -> Webflow class: "entry-footer"   (wrapper)
//   .cat-links       -> Webflow class: "cat-links"      (categories block)
//   .tags-links      -> Webflow class: "tags-links"     (tags block)
//   .comments-link   -> Webflow class: "comments-link"  (omit or hide)
//   .edit-link       -> Webflow class: "edit-link"      (omit entirely)
//
// TODO: Manual action in Webflow Designer —
//   Webflow CMS does not support nested Collection Lists that filter based on
//   a parent item's MultiReference field in all plan types.
//   Nested Collection Lists (Collections within Collections) require:
//     - Webflow CMS plan or higher
//     - The nested list references a MultiReference field on the parent item
//   Verify plan supports nested Collection Lists before building category/tag link lists.
//
// TODO: Manual action in Webflow Designer —
//   Comments are not supported natively in Webflow.
//   Replace comments_popup_link() output with one of:
//   a) Disqus embed — add Disqus universal code in a Webflow Embed block
//      on the Blog Post Collection Template page.
//   b) Commento, Hyvor Talk, or similar privacy-focused comment systems via Embed block.
//   c) Omit comments entirely if not required.
//   Add Embed block at the bottom of the Blog Post Collection Template content area.
//
// TODO: Manual action in Webflow Designer —
//   The edit_post_link() function renders only for logged-in WordPress users with edit capability.
//   In Webflow, content editing happens in the Webflow Editor (webflow.io/dashboard or
//   the Editor mode accessed via the site URL with ?edit parameter).
//   No frontend "Edit" link is needed or supported in Webflow's published output.
//   This element should be omitted from the Webflow design entirely.
//
// TODO: Manual action in Webflow Designer —
//   Category and Tag archive URLs in WordPress:
//     /category/[slug]/  -> Create Webflow CMS Collection page for "Blog Categories"
//                           at URL path: /category/[slug]/
//                           Add Collection List filtered by matching category.
//     /tag/[slug]/       -> Create Webflow CMS Collection page for "Blog Tags"
//                           at URL path: /tag/[slug]/
//                           Add Collection List filtered by matching tag.
//   Set up 301 redirects in Webflow Hosting for any changed URL patterns.
// ============================================================


// ============================================================
// FUNCTION: _s_post_thumbnail()
// WordPress: Displays post featured image, linked on archive, plain on singular
// Webflow Target: "Post Thumbnail" Symbol — two variants:
//                   Variant A: Singular view (div wrapper, no link)
//                   Variant B: Archive/loop view (anchor wrapper, linked to post)
//                 Used inside:
//                   - Blog Post Collection Template (single.php equivalent) — Variant A
//                   - Blog Archive Collection List items (archive.php equivalent) — Variant B
//                   - Homepage featured posts Collection List — Variant B
// ============================================================
//
// ORIGINAL BEHAVIOR:
//   - Returns early (outputs nothing) if:
//       * Post is password protected
//       * Post is an attachment
//       * Post has no featured image
//   - On singular views (is_singular()): wraps image in <div class="post-thumbnail">
//   - On loop/archive views: wraps image in <a class="post-thumbnail" href="{permalink}">
//     with aria-hidden="true" and tabindex="-1" (decorative link, hidden from assistive tech)
//
// WEBFLOW EQUIVALENT — VARIANT A: Single/Singular Post Template
// (Used on: Blog Post CMS Collection Template page)
//
//   <div class="post-thumbnail post-thumbnail--single">  <!-- Webflow: Div Block -->
//     <img                                               <!-- Webflow: Image element -->
//          src="{CMS: Featured Image}"                   <!--   - Bind src to CMS "Featured Image" field -->
//          alt="{CMS: Featured Image Alt}"               <!--   - Bind alt to CMS "Featured Image" alt text -->
//          class="post-thumbnail__image"                 <!--   - Webflow class: post-thumbnail__image -->
//          loading="lazy"                                <!--   - Webflow auto-adds loading="lazy" -->
//     />
//   </div>
//
//   Conditional Visibility:
//     - Set conditional visibility on the entire <div class="post-thumbnail">:
//       Show element only when: "Featured Image" field is NOT empty
//     // MIGRATED: has_post_thumbnail() check -> Webflow conditional visibility on Image field
//
// WEBFLOW EQUIVALENT — VARIANT B: Archive/Loop Collection List Item Card
// (Used on: Blog Archive Collection List page, Homepage featured posts)
//
//   <a class="post-thumbnail post-thumbnail--archive"    <!-- Webflow: Link Block -->
//      href="{CMS: Slug / Post URL}"                     <!--   - Bind href to CMS item slug URL -->
//      aria-hidden="true"                                <!--   - Custom attribute: aria-hidden = true -->
//      tabindex="-1">                                    <!--   - Custom attribute: tabindex = -1 -->
//     <img                                               <!-- Webflow: Image element (child of Link Block) -->
//          src="{CMS: Featured Image}"                   <!--   - Bind src to CMS "Featured Image" field -->
//          alt="{CMS: Featured Image Alt}"               <!--   - Bind alt to CMS "Featured Image" alt text -->
//          class="post-thumbnail__image"                 <!--   - Webflow class: post-thumbnail__image -->
//          loading="lazy"
//     />
//   </a>
//
//   Conditional Visibility:
//     - Set conditional visibility on the entire <a class="post-thumbnail">:
//       Show element only when: "Featured Image" field is NOT empty
//     // MIGRATED: has_post_thumbnail() check -> Webflow conditional visibility on Image field
//
// CMS FIELD BINDINGS:
//   - Featured Image src  -> Webflow CMS Image field "Featured Image"
//   - Featured Image alt  -> Webflow CMS Image field alt text property (set per-item in CMS)
//   - Post permalink/URL  -> Webflow auto-generated Collection item URL (bound to Link element)
//
// CSS CLASS MAPPING:
//   .post-thumbnail          -> Webflow class: "post-thumbnail"          (both variants)
//   [singular modifier]      -> Webflow class: "post-thumbnail--single"  (Variant A)
//   [archive modifier]       -> Webflow class: "post-thumbnail--archive" (Variant B)
//   .post-thumbnail img      -> Webflow class: "post-thumbnail__image"
//
// TODO: Manual action in Webflow Designer —
//   Password-protected post check (post_password_required()) has no Webflow equivalent.
//   Webflow does not support password-protected individual CMS posts.
//   If password protection is required:
//   a) Use Webflow's page password protection (per-page setting) for static pages.
//   b) Use Memberstack, Webflow Memberships, or similar for CMS item-level access control.
//   c) Omit this feature if not needed.
//
// TODO: Manual action in Webflow Designer —
//   Attachment post type check (is_attachment()) has no Webflow equivalent.
//   WordPress "attachment" posts are not migrated to Webflow CMS.
//   Media files are migrated to Webflow Assets.
//   No action needed — attachment post type is not replicated in Webflow.
//
// TODO: Manual action in Webflow Designer —
//   The aria-hidden="true" and tabindex="-1" attributes on the archive thumbnail link
//   must be added manually as Custom Attributes on the Link Block element in Webflow Designer:
//     Attribute 1: Name = "aria-hidden"  | Value = "true"
//     Attribute 2: Name = "tabindex"     | Value = "-1"
//   This is important for accessibility — the image link is decorative (the post title
//   link adjacent to it also navigates to the post, so the image link is redundant for
//   screen reader users).
//
// TODO: Manual action in Webflow Designer —
//   WordPress registers multiple image sizes (thumbnail, medium, large, full, post-thumbnail).
//   Webflow does not have named image size variants — it serves the original uploaded image
//   and uses responsive srcset automatically.
//   Action: In Webflow Designer, set Image element sizing via CSS:
//     - Use "object-fit: cover" for consistent cropping behavior
//     - Set explicit width/height or aspect ratio on the image wrapper
//     - Webflow's responsive image handling replaces WordPress image size variants
//
// ============================================================
// END OF MIGRATION: inc/template-tags.php
// ============================================================
//
// SUMMARY OF WEBFLOW COMPONENTS TO CREATE:
//
//   Symbols:
//     1. "Post Meta - Date"      -> posted-on element group (date + permalink)
//     2. "Post Meta - Author"    -> byline element group (author name + link)
//     3. "Post Entry Footer"     -> entry-footer block (categories, tags)
//     4. "Post Thumbnail Single" -> featured image div (singular view)
//     5. "Post Thumbnail Card"   -> featured image link (archive/loop view)
//
//   CMS Collections Required:
//     1. Blog Posts              -> main content collection
//        Fields: Name, Slug, Content (RT), Excerpt, Featured Image,
//                Published Date (Created On), Author (Ref), Categories (Multi-Ref), Tags (Multi-Ref)
//     2. Authors                 -> author profiles
//        Fields: Name, Slug, Bio, Avatar, Website URL
//     3. Blog Categories         -> hierarchical taxonomy equivalent
//        Fields: Name, Slug, Description
//     4. Blog Tags               -> flat taxonomy equivalent
//        Fields: Name, Slug
//
//   301 Redirects to Configure in Webflow Hosting:
//     /author/[slug]/         -> /authors/[slug]/
//     /category/[slug]/       -> /category/[slug]/   (if URL preserved) OR new path
//     /tag/[slug]/            -> /tag/[slug]/         (if URL preserved) OR new path
//
//   Third-party Integrations Needed:
//     - Comments system: Disqus / Commento / Hyvor Talk (Embed block)
//
//   Custom Code Required:
//     - JSON-LD Author schema markup (Person) in page <head>
//     - Conditional updated-date display logic (if needed)
//     - datetime attribute binding workaround for semantic <time> elements
// ============================================================