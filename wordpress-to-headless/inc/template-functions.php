// MIGRATED: inc/template-functions.php -> utility functions for Next.js body classes and metadata
// This file replaces WordPress body_class filter and wp_head pingback action with
// React/Next.js equivalents consumed by layout.tsx and other components.

import { headers } from 'next/headers';

// MIGRATED: _s_body_classes() body_class filter -> utility function for layout.tsx <body> className
// WordPress: add_filter('body_class', '_s_body_classes') added 'hfeed' and 'no-sidebar' dynamically
// Next.js: call getBodyClasses() in app/layout.tsx and spread onto <body className={...}>
export interface BodyClassOptions {
  isSingular: boolean;
  hasSidebar: boolean;
}

export function getBodyClasses(options: BodyClassOptions): string {
  const classes: string[] = [];

  // MIGRATED: if (!is_singular()) $classes[] = 'hfeed'
  // WordPress added 'hfeed' on archive/listing pages; replicate via isSingular flag
  if (!options.isSingular) {
    classes.push('hfeed');
  }

  // MIGRATED: if (!is_active_sidebar('sidebar-1')) $classes[] = 'no-sidebar'
  // WordPress checked widget area registration; replicate via hasSidebar flag from layout config
  if (!options.hasSidebar) {
    classes.push('no-sidebar');
  }

  return classes.join(' ');
}

// MIGRATED: _s_pingback_header() wp_head action -> metadata utility for generateMetadata()
// WordPress: add_action('wp_head', '_s_pingback_header') printed a <link rel="pingback"> tag
// Next.js: include pingback in the `alternates` or custom metadata; call getPingbackMetadata()
// in generateMetadata() for singular post/page routes (app/posts/[slug]/page.tsx, app/[slug]/page.tsx)
// TODO: Manual review needed -- Next.js Metadata API does not have a first-class pingback field;
// the link tag is injected via the `other` metadata key or a custom <head> element in layout.tsx

export interface PingbackMetadata {
  pingbackUrl: string | null;
}

// MIGRATED: pings_open() + get_bloginfo('pingback_url') -> fetch pingback URL from WP REST API
// WordPress root endpoint exposes pingback_url in the site info response
export async function getPingbackUrl(): Promise<string | null> {
  const wpApiUrl = process.env.NEXT_PUBLIC_WORDPRESS_API_URL;

  if (!wpApiUrl) {
    // TODO: Manual review needed -- set NEXT_PUBLIC_WORDPRESS_API_URL in .env.local
    return null;
  }

  try {
    const res = await fetch(`${wpApiUrl}/wp-json/`, {
      next: { revalidate: 3600 }, // cache site info for 1 hour
    });

    if (!res.ok) return null;

    // MIGRATED: get_bloginfo('pingback_url') -> siteInfo.pingback_url from WP REST root endpoint
    const siteInfo = await res.json();
    return siteInfo.pingback_url ?? null;
  } catch {
    return null;
  }
}

// MIGRATED: is_singular() && pings_open() guard -> boolean helper for singular route pages
// Call isSingularRoute() in singular page components to conditionally include pingback metadata
// WordPress: is_singular() returns true for single posts, pages, and attachments
// Next.js: file-based routing means app/posts/[slug]/page.tsx and app/[slug]/page.tsx are always singular
export function isSingularRoute(routeType: 'post' | 'page' | 'attachment' | 'archive' | 'home' | 'search' | '404'): boolean {
  // MIGRATED: WordPress is_singular() checks -> explicit route type comparison
  return routeType === 'post' || routeType === 'page' || routeType === 'attachment';
}

// MIGRATED: Convenience export combining pingback logic for use in generateMetadata()
// Usage in app/posts/[slug]/page.tsx:
//   import { buildPingbackMetadata } from '@/inc/template-functions';
//   const pingbackMeta = await buildPingbackMetadata('post');
//   // merge into generateMetadata() return value
export async function buildPingbackMetadata(
  routeType: 'post' | 'page' | 'attachment' | 'archive' | 'home' | 'search' | '404'
): Promise<{ other?: Record<string, string> }> {
  // MIGRATED: if (is_singular() && pings_open()) -> only fetch pingback URL for singular routes
  if (!isSingularRoute(routeType)) {
    return {};
  }

  const pingbackUrl = await getPingbackUrl();

  if (!pingbackUrl) {
    return {};
  }

  // MIGRATED: printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')))
  // Next.js Metadata API supports arbitrary <link> tags via the `other` field
  // TODO: Manual review needed -- verify Next.js renders `other` metadata as <link> tags correctly;
  // alternative is to add <link rel="pingback" href={pingbackUrl} /> directly in layout.tsx <head>
  return {
    other: {
      'pingback': pingbackUrl,
    },
  };
}