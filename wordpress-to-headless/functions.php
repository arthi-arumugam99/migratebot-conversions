// next.config.js
// MIGRATED: Replaces functions.php theme configuration for Next.js headless frontend

/** @type {import('next').NextConfig} */
const nextConfig = {
  // MIGRATED: add_theme_support('post-thumbnails') + add_image_size() -> Next.js Image
  // optimization handles resizing; WordPress domain must be allowlisted here
  images: {
    remotePatterns: [
      {
        // TODO: Replace 'your-wordpress-site.com' with your actual WordPress domain
        protocol: 'https',
        hostname: process.env.NEXT_PUBLIC_WP_HOSTNAME || 'your-wordpress-site.com',
        port: '',
        // Allows all WordPress media uploads and any other WP-served images
        pathname: '/**',
      },
      {
        // Allow local WordPress development server
        // TODO: Remove this entry when deploying to production
        protocol: 'http',
        hostname: 'localhost',
        port: process.env.WP_LOCAL_PORT || '8080',
        pathname: '/**',
      },
    ],
  },

  // MIGRATED: wp_localize_script() -> environment variables accessed via process.env
  // All NEXT_PUBLIC_* variables are inlined at build time and safe for browser access.
  // Non-prefixed variables are server-only and never sent to the browser.
  env: {
    // TODO: Set these in your .env.local file (never commit .env.local to version control)
    // NEXT_PUBLIC_WP_API_URL=https://your-wordpress-site.com/wp-json/wp/v2
    // NEXT_PUBLIC_WP_URL=https://your-wordpress-site.com
    // WP_USERNAME=your-wp-username           (server-only, for authenticated API calls)
    // WP_APP_PASSWORD=your-app-password      (server-only, for authenticated API calls)
    // NEXTAUTH_SECRET=your-nextauth-secret   (server-only, for JWT/NextAuth)
    // NEXTAUTH_URL=https://your-nextjs-site.com
  },

  // MIGRATED: Optional API proxy rewrites. Uncomment to proxy /api/wp/* -> WordPress REST API.
  // This hides the WordPress backend URL from the browser and avoids CORS issues.
  // TODO: Decide whether to use direct REST API calls or proxy rewrites.
  //       If using direct calls, ensure WordPress CORS headers allow your frontend domain.
  async rewrites() {
    const wpBaseUrl = process.env.NEXT_PUBLIC_WP_URL || 'https://your-wordpress-site.com';
    return [
      // Proxy WordPress REST API through Next.js to avoid CORS and hide WP origin
      {
        source: '/api/wp/:path*',
        destination: `${wpBaseUrl}/wp-json/:path*`,
      },
      // Proxy WordPress media uploads (optional — use only if you do not have a CDN)
      // TODO: Remove this rewrite if WordPress media is served from a CDN
      {
        source: '/wp-content/:path*',
        destination: `${wpBaseUrl}/wp-content/:path*`,
      },
    ];
  },

  // MIGRATED: wp_enqueue_scripts (add_action) -> removed entirely.
  // Scripts are handled via next/script in layout.tsx; styles via CSS imports.

  // Strict mode catches common React mistakes during development
  reactStrictMode: true,

  // MIGRATED: Webpack config replaces the legacy webpack 4 setup in package.json
  // The old webpack pipeline (css-loader, postcss-loader, tailwindcss v1) is superseded
  // by Next.js's built-in Tailwind CSS support (install tailwindcss v3+ separately).
  // TODO: Run: npm install tailwindcss@latest postcss@latest autoprefixer@latest
  // TODO: Run: npx tailwindcss init -p
  // TODO: Import globals.css in app/layout.tsx instead of wp_enqueue_style()
};

module.exports = nextConfig;

// ---------------------------------------------------------------------------
// MIGRATION NOTES — what happened to each functions.php concern
// ---------------------------------------------------------------------------
//
// 1. _s_setup() / add_action('after_setup_theme') -> REMOVED
//    - add_theme_support('title-tag')           -> Next.js Metadata API (generateMetadata)
//    - add_theme_support('post-thumbnails')      -> REST API ?_embed gives featured media
//    - add_theme_support('html5', [...])         -> inherent in JSX / React
//    - add_theme_support('custom-background')    -> CSS variable or Tailwind class in layout
//    - add_theme_support('customize-selective-refresh-widgets') -> not applicable headless
//    - add_theme_support('custom-logo')          -> fetch from /wp-json/ root or hardcode
//      TODO: GET https://your-wordpress-site.com/wp-json/ and read `.site_logo` or
//            site_icon_url from the response, then render with next/image in Header component
//    - register_nav_menus(['menu-1' => 'Primary']) -> REMOVED from frontend
//      TODO: Fetch Primary menu from WordPress:
//            GET /wp-json/wp/v2/menu-items?menus=<menuId>
//            Requires WP REST API Menus plugin (https://wordpress.org/plugins/wp-api-menus/)
//            OR WPGraphQL + WPGraphQL for ACF.
//            Alternatively use WP 5.9+ menu-items endpoint with the menu slug.
//
// 2. _s_widgets_init() / register_sidebar() -> REMOVED
//    - Sidebars become React components that fetch their own data.
//    - Widget content: GET /wp-json/wp/v2/widgets (requires WP 5.8+)
//    - TODO: Build a <Sidebar /> React component in components/Sidebar.tsx
//
// 3. _s_scripts() / add_action('wp_enqueue_scripts') -> REMOVED entirely
//    - wp_enqueue_style('main.css', '.../dist/src/style.css') ->
//      import '@/styles/globals.css' at the top of app/layout.tsx
//      TODO: Copy/migrate CSS from dist/src/style.css into styles/globals.css
//    - wp_enqueue_script('main.js', '.../dist/main.js') ->
//      Remove jQuery-dependent JS; replace with React event handlers and hooks.
//      Any third-party scripts -> <Script src="..." strategy="afterInteractive" />
//      in app/layout.tsx using next/script.
//      TODO: Audit main.js for jQuery usage and rewrite as React components/hooks.
//
// 4. require 'inc/custom-header.php'    -> REMOVED (custom header = React Header component)
//    require 'inc/template-tags.php'    -> convert utility functions to lib/utils.ts
//    require 'inc/template-functions.php' -> convert frontend helpers to TypeScript utils
//    require 'inc/customizer.php'       -> REMOVED (Customizer is WP-admin only; any
//                                         customizer settings exposed via REST API should
//                                         be fetched in layout or a settings utility)
//    TODO: Create lib/api.ts for all WordPress REST API fetch helpers
//    TODO: Create lib/utils.ts for template-tag equivalents (formatDate, stripHtml, etc.)
//
// 5. register_post_type() / register_taxonomy() -> MUST STAY in WordPress backend PHP
//    TODO: Ensure every CPT and taxonomy is registered with show_in_rest: true
//          so they appear in the REST API.
//
// 6. AJAX handlers (wp_ajax_*) -> Replace with Next.js API routes in app/api/
//    TODO: Create app/api/<endpoint>/route.ts for each custom AJAX action.
//
// 7. ACF Options pages / get_field('field', 'options') ->
//    TODO: Register a custom WP REST endpoint (functions.php on the backend) that returns
//          all ACF options fields. Fetch in Next.js from /wp-json/custom/v1/options
//
// ---------------------------------------------------------------------------
// REQUIRED .env.local variables (create this file in your Next.js project root)
// ---------------------------------------------------------------------------
//
// NEXT_PUBLIC_WP_URL=https://your-wordpress-site.com
// NEXT_PUBLIC_WP_API_URL=https://your-wordpress-site.com/wp-json/wp/v2
// NEXT_PUBLIC_WP_HOSTNAME=your-wordpress-site.com
// NEXT_PUBLIC_SITE_NAME=My Site Name
// NEXT_PUBLIC_SITE_URL=https://your-nextjs-site.com
//
// # Server-only (authenticated WP REST API requests, e.g. draft posts, user data)
// WP_APP_PASSWORD=xxxx xxxx xxxx xxxx xxxx xxxx
// WP_USERNAME=headless-api-user
//
// # NextAuth.js (if implementing WordPress-backed authentication)
// NEXTAUTH_URL=https://your-nextjs-site.com
// NEXTAUTH_SECRET=generate-with-openssl-rand-base64-32
//
// ---------------------------------------------------------------------------