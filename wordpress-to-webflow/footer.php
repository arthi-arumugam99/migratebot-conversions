// ============================================================
// WEBFLOW MIGRATION: footer.php -> Webflow Footer Symbol
// ============================================================
// SOURCE FILE: footer.php (_s / Underscores starter theme)
// TARGET: Webflow Footer Symbol (global reusable component)
// MIGRATED: footer.php is a PHP template closing #content and rendering
//           a minimal site-info footer. In Webflow, this becomes a
//           Footer Symbol added to every page via the Symbol system.
//           wp_footer() hooks (scripts, admin bar) are replaced by
//           Webflow's own before-</body> custom code injection in
//           Project Settings > Custom Code.
// ============================================================


// ------------------------------------------------------------
// SECTION 1: CLOSING WRAPPER NOTE
// ------------------------------------------------------------
// MIGRATED: The `</div><!-- #content -->` closing tag in the original
//           footer.php closes the #content wrapper opened in header.php.
//           In Webflow, there is no manual open/close div pattern.
//           Each page Section/Container is self-contained in the
//           Webflow Designer canvas. No action needed here.
// ------------------------------------------------------------


// ------------------------------------------------------------
// SECTION 2: WEBFLOW FOOTER SYMBOL — COMPLETE STRUCTURE BLUEPRINT
// ------------------------------------------------------------
//
// SYMBOL NAME:      "Footer - Global"
// SYMBOL TAG:       <footer> (set element tag to <footer> in Webflow)
// ADDED TO:         Every static page and every CMS Template page
//                   by placing the Symbol in the page canvas.
//
// BASE CSS CLASS:   .footer-global
//
// NOTE: The original _s theme footer is a bare-minimum scaffold.
//       This blueprint expands it into a production-ready Webflow
//       Footer Symbol following Webflow best practices, while
//       preserving the semantic intent of the original template.
//       Replace placeholder copy/links with real site content.
// ------------------------------------------------------------


// ============================================================
// WEBFLOW FOOTER SYMBOL — HTML STRUCTURE BLUEPRINT
// ============================================================
// Copy the structure below as a reference when building the
// Footer Symbol in the Webflow Designer.
// ============================================================

/*

<footer id="colophon" class="footer-global">

  <!-- --------------------------------------------------------
    SUBSECTION 2A: FOOTER MAIN BODY
    Webflow element: Section
    Class: .footer-main
    Layout: Flexbox row (desktop) / column (mobile)
    Contains: Logo + tagline, nav columns, optional newsletter
  --------------------------------------------------------- -->

  <div class="footer-main">
    <div class="footer-container">

      <!-- COLUMN 1: Brand / Logo + Tagline
           Webflow element: Div Block
           Class: .footer-brand
      -->
      <div class="footer-brand">

        <!-- Logo
             Webflow element: Link Block wrapping Image
             Class: .footer-logo-link
             href: / (homepage)
             MIGRATED: No logo existed in original _s footer. Add site logo here.
        -->
        <a href="/" class="footer-logo-link" aria-label="Go to homepage">
          <!-- TODO: Manual action in Webflow — upload logo to Assets,
                     drag Image element inside this Link Block,
                     bind src to the uploaded Asset URL.
                     Recommended size: max-width 160px.
          -->
          <img
            src="[WEBFLOW_ASSET_URL_LOGO]"
            alt="[SITE NAME] logo"
            class="footer-logo"
            loading="lazy"
          />
        </a>

        <!-- Tagline / Description
             Webflow element: Paragraph
             Class: .footer-tagline
             MIGRATED: Static text — update with real site tagline.
        -->
        <p class="footer-tagline">
          [Site tagline or short description goes here.]
        </p>

        <!-- Social Icons Row
             Webflow element: Div Block
             Class: .footer-social-links
             Layout: Flexbox row, gap 16px
             MIGRATED: No social links in original _s footer.
                       Add real social URLs per client.
             TODO: Manual action in Webflow — create one Link Block
                   per social channel, embed SVG icon inside each,
                   or use an icon font via custom code.
        -->
        <div class="footer-social-links" aria-label="Social media links">

          <a href="https://twitter.com/[HANDLE]" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
            <!-- TODO: Manual action in Webflow — embed SVG icon or use HTML Embed for icon font -->
            <svg class="footer-social-icon" aria-hidden="true" focusable="false" width="20" height="20">
              <use href="#icon-twitter" />
            </svg>
          </a>

          <a href="https://facebook.com/[PAGE]" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
            <svg class="footer-social-icon" aria-hidden="true" focusable="false" width="20" height="20">
              <use href="#icon-facebook" />
            </svg>
          </a>

          <a href="https://instagram.com/[HANDLE]" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
            <svg class="footer-social-icon" aria-hidden="true" focusable="false" width="20" height="20">
              <use href="#icon-instagram" />
            </svg>
          </a>

          <a href="https://linkedin.com/company/[SLUG]" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
            <svg class="footer-social-icon" aria-hidden="true" focusable="false" width="20" height="20">
              <use href="#icon-linkedin" />
            </svg>
          </a>

        </div><!-- /.footer-social-links -->

      </div><!-- /.footer-brand -->


      <!-- COLUMN 2: Primary Navigation Links
           Webflow element: Div Block
           Class: .footer-nav-col .footer-nav-col--primary
           MIGRATED: Original _s footer had no navigation columns.
                     These are added as standard footer nav best practice.
                     Populate with real site page links.
      -->
      <div class="footer-nav-col footer-nav-col--primary">

        <!-- Column Heading
             Webflow element: Heading (H3 or H4)
             Class: .footer-nav-heading
        -->
        <h4 class="footer-nav-heading">Company</h4>

        <!-- Nav List
             Webflow element: List (set to unordered, no bullets via CSS)
             Class: .footer-nav-list
        -->
        <ul class="footer-nav-list" role="list">

          <li class="footer-nav-item">
            <!-- Webflow element: Link (Text Link)
                 Class: .footer-nav-link
                 TODO: Manual action in Webflow — set href for each link.
                       If linking to a CMS page, use Webflow's link picker.
            -->
            <a href="/about" class="footer-nav-link">About</a>
          </li>

          <li class="footer-nav-item">
            <a href="/services" class="footer-nav-link">Services</a>
          </li>

          <li class="footer-nav-item">
            <a href="/blog" class="footer-nav-link">Blog</a>
          </li>

          <li class="footer-nav-item">
            <a href="/contact" class="footer-nav-link">Contact</a>
          </li>

        </ul><!-- /.footer-nav-list -->

      </div><!-- /.footer-nav-col.footer-nav-col--primary -->


      <!-- COLUMN 3: Secondary / Legal Navigation Links
           Webflow element: Div Block
           Class: .footer-nav-col .footer-nav-col--legal
           MIGRATED: Legal/utility links separated for clarity.
      -->
      <div class="footer-nav-col footer-nav-col--legal">

        <h4 class="footer-nav-heading">Legal</h4>

        <ul class="footer-nav-list" role="list">

          <li class="footer-nav-item">
            <a href="/privacy-policy" class="footer-nav-link">Privacy Policy</a>
          </li>

          <li class="footer-nav-item">
            <a href="/terms-of-service" class="footer-nav-link">Terms of Service</a>
          </li>

          <li class="footer-nav-item">
            <a href="/sitemap.xml" class="footer-nav-link">Sitemap</a>
            <!-- NOTE: Webflow auto-generates sitemap.xml — link is safe to use -->
          </li>

        </ul><!-- /.footer-nav-list -->

      </div><!-- /.footer-nav-col.footer-nav-col--legal -->


      <!-- COLUMN 4: Newsletter Signup (Optional)
           Webflow element: Div Block
           Class: .footer-newsletter
           MIGRATED: No newsletter existed in original _s footer.
                     Include if site requires email capture.
           TODO: Manual action in Webflow — drag a Form Block here.
                 Connect to Mailchimp/ConvertKit via Webflow Integrations
                 or Zapier/Make webhook on form submission.
      -->
      <div class="footer-newsletter">

        <h4 class="footer-nav-heading">Stay Updated</h4>

        <p class="footer-newsletter-desc">
          Get the latest posts delivered straight to your inbox.
        </p>

        <!-- Webflow Form Block
             Webflow element: Form Block
             Class: .footer-form
             Form Name: "Footer Newsletter" (set in Webflow form settings)
             Notifications: configure recipient email in Webflow form settings
             MIGRATED: Replaces any WordPress newsletter widget/shortcode.
        -->
        <div class="footer-form-wrapper">

          <form
            id="footer-newsletter-form"
            class="footer-form"
            name="Footer Newsletter"
            data-name="Footer Newsletter"
            aria-label="Newsletter signup form"
          >
            <!-- Email Input
                 Webflow element: Email Input
                 Class: .footer-form-input
                 Required: true
            -->
            <input
              type="email"
              id="footer-newsletter-email"
              name="footer-newsletter-email"
              class="footer-form-input"
              placeholder="Your email address"
              required
              aria-label="Email address"
              aria-required="true"
            />

            <!-- Submit Button
                 Webflow element: Form Submit Button
                 Class: .footer-form-btn .btn .btn--primary
            -->
            <input
              type="submit"
              value="Subscribe"
              class="footer-form-btn btn btn--primary"
              aria-label="Subscribe to newsletter"
            />

          </form><!-- /.footer-form -->

          <!-- Success State
               Webflow element: Form Success Div (auto-created by Webflow)
               Class: .footer-form-success w-form-done
               TODO: Manual action in Webflow — edit success message text
                     in the Form settings panel.
          -->
          <div class="footer-form-success w-form-done" aria-live="polite">
            <p>Thanks for subscribing!</p>
          </div>

          <!-- Error State
               Webflow element: Form Error Div (auto-created by Webflow)
               Class: .footer-form-error w-form-fail
               TODO: Manual action in Webflow — edit error message text
                     in the Form settings panel.
          -->
          <div class="footer-form-error w-form-fail" aria-live="assertive">
            <p>Oops! Something went wrong. Please try again.</p>
          </div>

        </div><!-- /.footer-form-wrapper -->

      </div><!-- /.footer-newsletter -->

    </div><!-- /.footer-container -->
  </div><!-- /.footer-main -->


  <!-- --------------------------------------------------------
    SUBSECTION 2B: FOOTER BOTTOM BAR (site-info)
    Webflow element: Div Block
    Class: .footer-bottom
    Layout: Flexbox row, space-between, centered vertically
    MIGRATED: Replaces <div class="site-info"> from original _s footer.
              Original contained "Proudly powered by WordPress" and
              theme credit — both removed for production use.
              Replaced with standard copyright notice.
  --------------------------------------------------------- -->

  <div class="footer-bottom">
    <div class="footer-bottom-container">

      <!-- Copyright Notice
           Webflow element: Paragraph or Text Block
           Class: .footer-copyright
           MIGRATED: Replaces WordPress/theme credit text from .site-info.
                     Original: "Proudly powered by WordPress | Theme: _s by Automattic"
                     Replaced with: standard client copyright.
           TODO: Manual action in Webflow — replace [YEAR] with current year.
                 For auto-updating year, add a short HTML Embed:
                 <span id="footer-year"></span>
                 <script>document.getElementById('footer-year').textContent = new Date().getFullYear();</script>
      -->
      <p class="footer-copyright">
        &copy; <span id="footer-year"></span> [SITE NAME]. All rights reserved.
        <!-- Auto year script: place in Webflow HTML Embed below, or
             inject via Site-wide custom code in Project Settings -->
      </p>

      <!-- Auto-Year Embed
           Webflow element: HTML Embed Block
           Class: (none — embed handles its own inline script)
           Place this Embed adjacent to .footer-copyright paragraph,
           or inject the script via Project Settings > Custom Code > Before </body>
      -->
      <!--
        WEBFLOW HTML EMBED CONTENT:
        <script>
          (function() {
            var el = document.getElementById('footer-year');
            if (el) { el.textContent = new Date().getFullYear(); }
          })();
        </script>
      -->


      <!-- Bottom Legal Links (optional — mirrors .footer-nav-col--legal above for quick access)
           Webflow element: Div Block
           Class: .footer-bottom-links
           Layout: Flexbox row, gap 24px
      -->
      <div class="footer-bottom-links" role="navigation" aria-label="Footer legal links">

        <a href="/privacy-policy" class="footer-bottom-link">Privacy Policy</a>
        <a href="/terms-of-service" class="footer-bottom-link">Terms of Service</a>

      </div><!-- /.footer-bottom-links -->

    </div><!-- /.footer-bottom-container -->
  </div><!-- /.footer-bottom -->


</footer><!-- /.footer-global  |  MIGRATED from: #colophon.site-footer -->

*/
// ============================================================
// END WEBFLOW FOOTER SYMBOL HTML BLUEPRINT
// ============================================================


// ------------------------------------------------------------
// SECTION 3: CSS CLASS MAPPING
// ------------------------------------------------------------
// WordPress (_s) class         -> Webflow class
// -------------------------       -------------------------
// #page                        -> (removed; Webflow body is the page wrapper)
// #content                     -> (removed; Webflow sections are self-contained)
// #colophon                    -> .footer-global  (keep id="colophon" for anchor compat)
// .site-footer                 -> .footer-global
// .site-info                   -> .footer-bottom / .footer-copyright
// .sep                         -> (removed; layout handled by flexbox gap)
// ------------------------------------------------------------


// ------------------------------------------------------------
// SECTION 4: wp_footer() EQUIVALENT IN WEBFLOW
// ------------------------------------------------------------
// MIGRATED: wp_footer() in WordPress outputs enqueued scripts,
//           admin bar, and hook-based HTML just before </body>.
//           In Webflow, this is replaced by:
//
//   1. Webflow's own runtime JS (injected automatically by Webflow)
//   2. Project Settings > Custom Code > "Before </body> tag" field
//      -> Paste any third-party scripts here (analytics, chat widgets,
//         CRM tracking, etc.) that were previously enqueued via
//         wp_enqueue_script() in functions.php.
//
// TODO: Manual action in Webflow — audit all wp_enqueue_script()
//       calls in functions.php and move each script to one of:
//       a) Project Settings > Custom Code > Before </body> tag  (global)
//       b) Page Settings > Custom Code > Before </body> tag     (per-page)
//       c) An HTML Embed element placed where the script output appears
// ------------------------------------------------------------


// ------------------------------------------------------------
// SECTION 5: WEBFLOW SYMBOL SETUP INSTRUCTIONS
// ------------------------------------------------------------
// 1. In Webflow Designer, open any page.
// 2. Add a <footer> semantic element from the Add panel (Elements > Semantic).
//    Set the HTML tag to <footer> in Element Settings.
// 3. Assign class: .footer-global
// 4. Build the internal structure following the HTML blueprint in Section 2.
// 5. Once the structure is complete and styled, right-click the footer element
//    and choose "Create Symbol". Name it "Footer - Global".
// 6. Drag the "Footer - Global" Symbol onto every page (static and CMS templates)
//    from the Symbols panel.
// 7. Any edits to the Symbol automatically propagate to all pages.
//
// RESPONSIVE BREAKPOINTS TO CONFIGURE:
// - Desktop (1280px+): .footer-main as 4-column grid or flex row
// - Tablet (991px):    .footer-main as 2-column grid
// - Mobile Landscape (767px): .footer-main as 2-column grid
// - Mobile Portrait (479px):  .footer-main as single column stack
//
// TODO: Manual action in Webflow — set each breakpoint layout in the
//       Style panel for .footer-main using Grid or Flexbox controls.
// ------------------------------------------------------------


// ------------------------------------------------------------
// SECTION 6: TAILWIND CSS EQUIVALENTS (reference from package.json)
// ------------------------------------------------------------
// NOTE: package.json lists Tailwind CSS v1 as a devDependency.
//       Webflow does not natively support Tailwind utility classes.
//       If the Webflow project uses a custom code embed for Tailwind CDN,
//       the classes below can supplement Webflow's style panel.
//       Otherwise, use Webflow's visual style panel exclusively and
//       disregard Tailwind references.
//
// Tailwind equivalents for footer classes (informational only):
// .footer-global        -> bg-gray-900 text-white py-12
// .footer-main          -> max-w-7xl mx-auto px-6 grid grid-cols-4 gap-8
// .footer-brand         -> flex flex-col gap-4
// .footer-logo          -> h-8 w-auto
// .footer-tagline       -> text-sm text-gray-400
// .footer-social-links  -> flex gap-4
// .footer-social-icon   -> w-5 h-5 text-gray-400 hover:text-white
// .footer-nav-col       -> flex flex-col gap-3
// .footer-nav-heading   -> text-sm font-semibold text-white uppercase tracking-wider
// .footer-nav-list      -> list-none p-0 m-0 flex flex-col gap-2
// .footer-nav-link      -> text-sm text-gray-400 hover:text-white transition-colors
// .footer-newsletter    -> flex flex-col gap-4
// .footer-form          -> flex gap-2
// .footer-form-input    -> flex-1 px-4 py-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-blue-500
// .footer-form-btn      -> px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors
// .footer-bottom        -> border-t border-gray-800 mt-12 py-6
// .footer-bottom-container -> max-w-7xl mx-auto px-6 flex justify-between items-center
// .footer-copyright     -> text-sm text-gray-500
// .footer-bottom-link   -> text-sm text-gray-500 hover:text-white transition-colors
// ------------------------------------------------------------


// ------------------------------------------------------------
// SECTION 7: SEO & ACCESSIBILITY NOTES
// ------------------------------------------------------------
// MIGRATED: Webflow auto-generates semantic HTML when the correct
//           element tags are set. Ensure the following in Webflow Designer:
//
// - Set the footer element tag to <footer> (not <div>) via Element Settings
// - Set nav column headings to <h4> via Typography > Tag
// - Set nav lists to <ul> with role="list" (add via Custom Attributes panel)
// - Add aria-label to nav landmark via Custom Attributes:
//     Attribute: aria-label  |  Value: "Footer navigation"
// - Add aria-label to social links container:
//     Attribute: aria-label  |  Value: "Social media links"
// - All social icon links must have aria-label attributes (set in Custom Attributes)
// - All images must have descriptive alt text (set in Image Settings panel)
// - Footer form must have a unique form name set in Form Settings panel
//
// TODO: Manual action in Webflow — verify all landmark roles and
//       aria attributes are set after building the Symbol.
// ------------------------------------------------------------


// ------------------------------------------------------------
// SECTION 8: REDIRECT NOTE FOR REMOVED WORDPRESS CREDIT LINKS
// ------------------------------------------------------------
// MIGRATED: The original footer.php contained:
//   - Link to https://wordpress.org/  ("Proudly powered by WordPress")
//   - Link to https://automattic.com/ ("Theme: _s by Automattic")
//   Both are WordPress/theme attribution links with no SEO value for
//   the client site. These are removed in the Webflow Footer Symbol.
//   No 301 redirects are required since these are external outbound links.
// ------------------------------------------------------------


// ------------------------------------------------------------
// SECTION 9: CHECKLIST FOR WEBFLOW FOOTER SYMBOL COMPLETION
// ------------------------------------------------------------
// [ ] Footer Symbol created and named "Footer - Global"
// [ ] <footer> semantic tag set on root element
// [ ] Logo image uploaded to Webflow Assets and bound in .footer-logo-link
// [ ] Site tagline copy updated in .footer-tagline
// [ ] Social media hrefs updated with real profile URLs
// [ ] Navigation column links updated with real site page URLs
// [ ] Newsletter form connected to email marketing integration
//     (Webflow Integrations > Mailchimp, or Zapier webhook)
// [ ] Form success and error messages reviewed and customized
// [ ] Copyright year auto-update script added via HTML Embed
// [ ] [SITE NAME] placeholder replaced with real business name
// [ ] Footer Symbol added to all static pages
// [ ] Footer Symbol added to all CMS Collection Template pages
// [ ] Responsive layouts configured for all 4 Webflow breakpoints
// [ ] Accessibility attributes verified (aria-labels, roles, alt text)
// [ ] wp_footer() scripts audited and moved to Project Settings > Custom Code
// [ ] "Proudly powered by WordPress" attribution link removed (confirmed)
// [ ] Privacy Policy and Terms of Service pages created as static Webflow pages
// ------------------------------------------------------------