// MIGRATED: inc/wpcom.php is a WordPress.com-specific backend file that sets global $themecolors
// for third-party services via the after_setup_theme hook. In headless Next.js, this
// WordPress.com-specific theme color configuration has no frontend equivalent — theme colors
// are handled via CSS custom properties, Tailwind config, or a design token system instead.
// The $themecolors global and add_action hook are WordPress-only constructs with no Next.js
// counterpart. This file is intentionally omitted from the Next.js migration.

// TODO: Manual review needed — if $themecolors values were used to configure third-party
// services (e.g., Jetpack sharing widgets, WordPress.com stats), verify those services
// are either replaced with headless alternatives or configured directly in their respective
// APIs/dashboards without relying on WordPress theme color globals.

// TODO: Manual review needed — if any colors from $themecolors['bg'], $themecolors['text'],
// $themecolors['link'], etc. were exposed via the WordPress Customizer or REST API and
// consumed by the frontend, fetch them from /wp-json/wp/v2/settings or a custom REST
// endpoint and apply them as CSS custom properties in app/layout.tsx or globals.css.

// This file produces no Next.js output. All WordPress.com theme compatibility logic
// (_s_wpcom_setup, add_action after_setup_theme) remains on the WordPress backend and
// does not require a corresponding Next.js file. Delete this file from the Next.js project.

export {};