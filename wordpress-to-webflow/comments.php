// ============================================================
// WEBFLOW MIGRATION: comments.php
// ============================================================
// SOURCE: comments.php (WordPress comment template)
// TARGET: Webflow — No native comments system exists in Webflow.
//         This entire template must be replaced with a third-party
//         commenting solution embedded via custom code embed.
//
// RECOMMENDED APPROACH: Disqus, Hyvor Talk, Commento, or Giscus
// embedded inside the CMS Collection Template page (single.php equivalent).
//
// TODO: Manual action in Webflow — Choose a comment provider and
//       paste their embed script into a Webflow Embed block on the
//       CMS Collection Template page in place of this entire file.
// ============================================================


// ============================================================
// WEBFLOW SYMBOL / SECTION BLUEPRINT
// Page: CMS Collection Template (e.g., Blog Post Template)
// Placement: Below the Rich Text content block, above the Footer Symbol
// ============================================================

// ------------------------------------------------------------
// SECTION: Comments Area
// Webflow Element: Section
//   Class: comments-area-section
//   Visibility: This section should always be visible on the
//               CMS Collection Template page (Webflow has no
//               post_password_required() equivalent — password
//               protection for individual CMS items is not
//               natively supported).
//
// TODO: Manual action in Webflow — If post-level password
//       protection is required, implement via a third-party
//       service (MemberStack, Outseta) or custom JS that hides
//       content until a passphrase is entered. There is no
//       Webflow-native equivalent to WordPress post_password_required().
// ------------------------------------------------------------

// ELEMENT HIERARCHY:
//
// <section class="comments-area-section">
//
//   <!-- ─────────────────────────────────────────────────── -->
//   <!-- COMMENTS COUNT HEADING                             -->
//   <!-- MIGRATED: get_comments_number() + get_the_title() -->
//   <!-- WordPress dynamically outputs singular/plural      -->
//   <!-- comment count with post title. Webflow CMS has no  -->
//   <!-- native comment count field. This heading must be   -->
//   <!-- driven by the third-party comment provider's JS or -->
//   <!-- a custom script that updates the DOM after load.   -->
//   <!-- TODO: Manual action in Webflow — Use the Disqus    -->
//   <!-- (or chosen provider) comment count JS API to       -->
//   <!-- populate a heading element dynamically. Bind the   -->
//   <!-- post title from the CMS Name field if needed.      -->
//   <!-- ─────────────────────────────────────────────────── -->
//
//   <div class="comments-header">
//     <h2 class="comments-title">
//       <!-- DYNAMIC via JS: "[N] thoughts on "[Post Title]" -->
//       <!-- Webflow CMS binding: Name field for post title  -->
//       <!-- Comment count: injected by embed provider JS   -->
//       <span class="comment-count-label">Comments on</span>
//       <span class="comments-post-title">
//         <!-- TODO: Bind to CMS "Name" field in Webflow Designer -->
//         <!-- [CMS Field: Name] -->
//       </span>
//     </h2>
//   </div>
//
//
//   <!-- ─────────────────────────────────────────────────── -->
//   <!-- COMMENTS NAVIGATION (top)                          -->
//   <!-- MIGRATED: the_comments_navigation()                -->
//   <!-- WordPress outputs prev/next page links for comment -->
//   <!-- pagination. Third-party providers handle their own -->
//   <!-- comment pagination internally inside their embed.  -->
//   <!-- TODO: Manual action in Webflow — No action needed  -->
//   <!-- if using Disqus/Hyvor; they paginate internally.   -->
//   <!-- If building a custom solution, add nav links here. -->
//   <!-- ─────────────────────────────────────────────────── -->
//
//   <!-- Omitted: handled internally by embed provider -->
//
//
//   <!-- ─────────────────────────────────────────────────── -->
//   <!-- COMMENT LIST                                       -->
//   <!-- MIGRATED: wp_list_comments()                       -->
//   <!-- WordPress renders <ol class="comment-list"> with   -->
//   <!-- nested <li> comment items including avatar, author, -->
//   <!-- date, and comment text. This is fully replaced by  -->
//   <!-- the third-party embed block below.                  -->
//   <!-- ─────────────────────────────────────────────────── -->
//
//   <!-- Omitted: rendered by embed provider -->
//
//
//   <!-- ─────────────────────────────────────────────────── -->
//   <!-- COMMENTS CLOSED NOTICE                             -->
//   <!-- MIGRATED: !comments_open() notice                  -->
//   <!-- WordPress shows "Comments are closed." when the    -->
//   <!-- post's comment status is set to closed. Webflow    -->
//   <!-- has no CMS field for comment open/closed status.   -->
//   <!-- TODO: Manual action in Webflow — Add a Switch CMS  -->
//   <!-- field named "Comments Enabled" (Boolean) to the    -->
//   <!-- Blog Posts Collection. Use Webflow conditional     -->
//   <!-- visibility to show/hide the embed block and show   -->
//   <!-- the closed notice based on that field value.       -->
//   <!-- ─────────────────────────────────────────────────── -->
//
//   <div class="comments-closed-notice"
//        style="display: none;">
//     <!-- TODO: Bind visibility to CMS Switch field "Comments Enabled" -->
//     <!-- Show this div ONLY when "Comments Enabled" = false           -->
//     <!-- Hide the embed block below when "Comments Enabled" = false   -->
//     <p class="no-comments">Comments are closed.</p>
//   </div>
//
//
//   <!-- ─────────────────────────────────────────────────── -->
//   <!-- THIRD-PARTY COMMENT EMBED BLOCK                    -->
//   <!-- MIGRATED: comment_form() + wp_list_comments()      -->
//   <!-- This Webflow Embed block replaces the entire        -->
//   <!-- WordPress comment form and list rendering.          -->
//   <!-- ─────────────────────────────────────────────────── -->
//
//   <!-- TODO: Manual action in Webflow — Place a Webflow   -->
//   <!-- "Embed" element (</> icon) here on the CMS         -->
//   <!-- Collection Template page. Paste ONE of the         -->
//   <!-- following provider snippets. Bind the page URL     -->
//   <!-- and title to the CMS item's Slug and Name fields   -->
//   <!-- where the provider requires a page identifier.     -->
//
//   <div class="comments-embed-wrapper">
//
//     <!-- ─── OPTION A: Disqus ──────────────────────────── -->
//     <!-- TODO: Replace 'YOUR_DISQUS_SHORTNAME' with your  -->
//     <!-- actual Disqus shortname from disqus.com           -->
//     <!--
//     <div id="disqus_thread"></div>
//     <script>
//       var disqus_config = function () {
//         // TODO: Bind this.page.url to the CMS item's full URL
//         // In Webflow, use a hidden field or pass via custom attribute
//         this.page.url = window.location.href;
//         // TODO: Bind this.page.identifier to the CMS item's Slug field
//         this.page.identifier = window.location.pathname;
//       };
//       (function() {
//         var d = document, s = d.createElement('script');
//         s.src = 'https://YOUR_DISQUS_SHORTNAME.disqus.com/embed.js';
//         s.setAttribute('data-timestamp', +new Date());
//         (d.head || d.body).appendChild(s);
//       })();
//     </script>
//     <noscript>
//       Please enable JavaScript to view the
//       <a href="https://disqus.com/?ref_noscript">
//         comments powered by Disqus.
//       </a>
//     </noscript>
//     -->
//
//     <!-- ─── OPTION B: Hyvor Talk ─────────────────────── -->
//     <!-- TODO: Replace 'YOUR_WEBSITE_ID' with your        -->
//     <!-- Hyvor Talk website ID from talk.hyvor.com        -->
//     <!--
//     <hyvor-talk-comments
//       website-id="YOUR_WEBSITE_ID"
//       page-id="">
//     </hyvor-talk-comments>
//     <script type="module"
//       src="https://talk.hyvor.com/embed/embed.js">
//     </script>
//     -->
//
//     <!-- ─── OPTION C: Giscus (GitHub Discussions) ────── -->
//     <!-- TODO: Configure at giscus.app and replace attrs  -->
//     <!--
//     <script src="https://giscus.app/client.js"
//       data-repo="YOUR_GITHUB_ORG/YOUR_REPO"
//       data-repo-id="YOUR_REPO_ID"
//       data-category="Announcements"
//       data-category-id="YOUR_CATEGORY_ID"
//       data-mapping="pathname"
//       data-strict="0"
//       data-reactions-enabled="1"
//       data-emit-metadata="0"
//       data-input-position="bottom"
//       data-theme="preferred_color_scheme"
//       data-lang="en"
//       crossorigin="anonymous"
//       async>
//     </script>
//     -->
//
//   </div>
//   <!-- /.comments-embed-wrapper -->
//
// </section>
// <!-- /.comments-area-section -->


// ============================================================
// CSS CLASS MAPPING (WordPress -> Webflow)
// ============================================================
//
// WordPress Class          Webflow Class               Notes
// ──────────────────────── ─────────────────────────── ──────────────────────────────────────
// #comments                .comments-area-section      Section element wrapping the whole area
// .comments-area           .comments-area-section      Merged with above — use one wrapper
// .comments-title          .comments-title             H2 heading element class
// .comment-list            (removed)                   Rendered by embed provider; not needed
// .comment                 (removed)                   Rendered by embed provider; not needed
// .comment-author          (removed)                   Rendered by embed provider; not needed
// .comment-content         (removed)                   Rendered by embed provider; not needed
// .comment-metadata        (removed)                   Rendered by embed provider; not needed
// .comment-respond         (removed)                   Rendered by embed provider; not needed
// .comment-form            (removed)                   Rendered by embed provider; not needed
// .no-comments             .no-comments                Paragraph element for closed-comments msg
// .comments-navigation     (removed)                   Handled internally by embed provider
//
// NOTE: WordPress default comment CSS (comment-list, comment-author vcard,
//       reply link, etc.) is entirely handled by the chosen third-party
//       embed provider. No Webflow custom styles are needed to replicate
//       the WordPress comment list appearance.


// ============================================================
// CMS COLLECTION FIELD ADDITIONS (Blog Posts Collection)
// ============================================================
//
// To support comment open/closed state and to surface comment
// counts in Collection Lists, add these fields to the
// "Blog Posts" (or equivalent) Webflow CMS Collection:
//
// Field Name           Field Type    Purpose
// ──────────────────── ───────────── ───────────────────────────────────────────
// Comments Enabled     Switch        Toggle to show/hide comment embed block
//                                   (mirrors WordPress comment_status = open/closed)
// Comment Count        Number        Optional: manually updated or via Zapier/Make
//                                   from Disqus/Hyvor API for display in card lists
//
// TODO: Manual action in Webflow — Add "Comments Enabled" Switch field
//       to the Blog Posts Collection. On the CMS Collection Template page:
//       - Set the comments-embed-wrapper div conditional visibility:
//         SHOW when "Comments Enabled" is ON
//       - Set the comments-closed-notice div conditional visibility:
//         SHOW when "Comments Enabled" is OFF


// ============================================================
// WEBFLOW PAGE PLACEMENT SUMMARY
// ============================================================
//
// File: comments.php (WordPress)
// ↓
// Webflow Target: CMS Collection Template page for "Blog Posts"
//   (equivalent to WordPress single.php which calls comments_template())
//
// Page structure on Webflow CMS Collection Template:
//
//   [Navbar Symbol]
//   ├── [Hero Section]           <- Featured image + Post title (CMS bound)
//   ├── [Post Meta Section]      <- Author, Date, Category refs (CMS bound)
//   ├── [Content Section]        <- Rich Text block (CMS bound to "Content" field)
//   ├── [Comments Area Section]  <- THIS FILE → Embed block with provider script
//   │     ├── .comments-title    <- Heading (partially CMS bound to Name field)
//   │     ├── .comments-closed-notice  <- Conditional visibility (Switch field)
//   │     └── .comments-embed-wrapper  <- Webflow Embed with provider script
//   └── [Footer Symbol]
//
// TODO: Manual action in Webflow — On the CMS Collection Template page,
//       drag an Embed element below the Rich Text content block.
//       Paste the chosen comment provider script into the embed.
//       Set conditional visibility on the embed wrapper using the
//       "Comments Enabled" Switch CMS field.
//
// SEO NOTE: Webflow does not index comment content (it is loaded
//           client-side via JS embed). This matches modern best
//           practices where comment content is typically not
//           included in search engine indexing anyway.
//
// 301 REDIRECT NOTE: WordPress comment-specific URLs to map:
//   /[post-slug]/#comments         -> /[post-slug]/#comments (anchor — no redirect needed if slug preserved)
//   /[post-slug]/#respond           -> /[post-slug]/#comments (anchor redirect via JS if needed)
//   /[post-slug]/comment-page-2/   -> /[post-slug]/ + 301 redirect (provider handles pagination)
//   /?replytocom=123               -> /[post-slug]/ + 301 redirect (reply threading handled by provider)
//   /wp-comments-post.php          -> 301 redirect to homepage (this endpoint no longer exists)
//
// TODO: Manual action in Webflow — Add the following to the
//       Webflow 301 Redirects list in Hosting Settings:
//         Old Path                    New Path
//         /wp-comments-post.php    -> /
//         /[post-slug]/comment-page-*/  -> /[post-slug]/