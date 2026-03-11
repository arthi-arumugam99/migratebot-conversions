// MIGRATED: inc/customizer.php -> lib/site-config.ts
// The WordPress Theme Customizer is a server-side WordPress admin feature that
// cannot be directly migrated to a headless Next.js frontend. Instead, this file
// is replaced by a utility module that fetches site identity settings from the
// WP REST API and exposes them for use in React components (Header, Footer, etc.).
//
// Original customizer settings handled here:
//   - blogname (site title)        -> fetched from WP REST API root endpoint
//   - blogdescription (tagline)    -> fetched from WP REST API root endpoint
//   - header_textcolor             -> hardcoded or moved to CSS/Tailwind config
//
// postMessage transport and selective_refresh partials are removed entirely --
// there is no WordPress Customizer in the headless architecture.
//
// The customizer.js enqueue (_s_customize_preview_js) is removed entirely --
// Next.js manages its own scripts via next/script or layout imports.
//
// TODO: The WordPress backend customizer.php file must remain in the WordPress theme
//       for any admin users who still manage site identity via the Customizer UI.
//       This Next.js module simply reads the values the Customizer saves to the DB.

import { cache } from 'react';

// TODO: Set NEXT_PUBLIC_WORDPRESS_URL in your .env.local file
const WORDPRESS_API_URL = process.env.NEXT_PUBLIC_WORDPRESS_URL ?? '';

// ---------------------------------------------------------------------------
// TypeScript types for the WP REST API root endpoint response
// Covers the fields used by the original customizer partials (blogname,
// blogdescription) plus common site identity fields.
// ---------------------------------------------------------------------------

export interface WPSiteInfo {
  /** blogname -- equivalent to bloginfo('name') */
  name: string;
  /** blogdescription -- equivalent to bloginfo('description') */
  description: string;
  /** Site root URL */
  url: string;
  /** Site home URL */
  home: string;
  /** GMT offset */
  gmt_offset: string | number;
  /** Timezone string */
  timezone_string: string;
  /** Site logo attachment ID (0 if not set) */
  site_logo?: number;
  /** Site icon attachment ID (0 if not set) */
  site_icon?: number;
  /** Site icon URL (favicon) */
  site_icon_url?: string;
}

// ---------------------------------------------------------------------------
// Fetch helpers
// ---------------------------------------------------------------------------

/**
 * Fetches site identity information from the WP REST API root endpoint.
 * Replaces:
 *   - bloginfo('name')        -> siteInfo.name
 *   - bloginfo('description') -> siteInfo.description
 *   - bloginfo('url')         -> siteInfo.url
 *   - get_option('blogname')  -> siteInfo.name
 *
 * Cached with React's cache() so it is deduplicated per request in Server Components.
 *
 * MIGRATED: replaces _s_customize_partial_blogname() and
 *           _s_customize_partial_blogdescription() render callbacks.
 */
export const getSiteInfo = cache(async (): Promise<WPSiteInfo> => {
  const res = await fetch(`${WORDPRESS_API_URL}/wp-json`, {
    next: {
      // MIGRATED: ISR revalidation replaces postMessage live-preview transport.
      // Site identity rarely changes; revalidate every hour.
      revalidate: 3600,
    },
  });

  if (!res.ok) {
    throw new Error(
      `Failed to fetch site info from WP REST API root endpoint: ${res.status} ${res.statusText}`
    );
  }

  const data = await res.json();

  return {
    name: data.name ?? '',
    description: data.description ?? '',
    url: data.url ?? '',
    home: data.home ?? '',
    gmt_offset: data.gmt_offset ?? 0,
    timezone_string: data.timezone_string ?? '',
    site_logo: data.site_logo ?? 0,
    site_icon: data.site_icon ?? 0,
    site_icon_url: data.site_icon_url ?? '',
  };
});

/**
 * Convenience accessor -- returns only the site title string.
 * Use in generateMetadata() or Header component.
 *
 * MIGRATED: replaces _s_customize_partial_blogname() / bloginfo('name')
 */
export async function getSiteTitle(): Promise<string> {
  const info = await getSiteInfo();
  return info.name;
}

/**
 * Convenience accessor -- returns only the site tagline/description string.
 * Use in Header component or generateMetadata().
 *
 * MIGRATED: replaces _s_customize_partial_blogdescription() / bloginfo('description')
 */
export async function getSiteDescription(): Promise<string> {
  const info = await getSiteInfo();
  return info.description;
}

// ---------------------------------------------------------------------------
// Site logo helper
// ---------------------------------------------------------------------------

export interface WPMediaItem {
  id: number;
  source_url: string;
  alt_text: string;
  media_details: {
    width: number;
    height: number;
    sizes?: Record<
      string,
      { source_url: string; width: number; height: number }
    >;
  };
}

/**
 * Fetches the site logo media object if a logo attachment ID is set.
 * Replaces the_custom_logo() / get_custom_logo() template tags.
 *
 * MIGRATED: replaces add_theme_support('custom-logo') rendering logic.
 *
 * TODO: Add the WordPress media domain to next.config.js images.remotePatterns
 *       so Next.js <Image> can optimize the logo URL.
 */
export const getSiteLogo = cache(async (): Promise<WPMediaItem | null> => {
  const info = await getSiteInfo();

  if (!info.site_logo || info.site_logo === 0) {
    return null;
  }

  const res = await fetch(
    `${WORDPRESS_API_URL}/wp-json/wp/v2/media/${info.site_logo}?_fields=id,source_url,alt_text,media_details`,
    {
      next: { revalidate: 3600 },
    }
  );

  if (!res.ok) {
    // Non-fatal: return null so components can fall back to a text logo
    console.warn(`Could not fetch site logo (media ID ${info.site_logo}): ${res.status}`);
    return null;
  }

  return res.json() as Promise<WPMediaItem>;
});

// ---------------------------------------------------------------------------
// Header text color
// ---------------------------------------------------------------------------

/**
 * The original customizer registered 'header_textcolor' with postMessage transport.
 * In the headless architecture this is handled via Tailwind / CSS variables rather
 * than inline WordPress Customizer output.
 *
 * If you need a dynamic header text color driven by a CMS value, expose it as an
 * ACF option or a custom REST endpoint field and read it here.
 *
 * MIGRATED: header_textcolor postMessage transport removed.
 * TODO: If a dynamic header color is required, create a custom REST endpoint or
 *       expose it via ACF Options Page and update this function accordingly.
 */
export function getHeaderTextColorClass(): string {
  // Return a Tailwind class or CSS variable name.
  // Replace with an API-fetched value if dynamic theming is required.
  return 'text-gray-900'; // default -- matches typical WordPress header_textcolor default
}