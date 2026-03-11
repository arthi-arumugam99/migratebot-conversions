// MIGRATED: header.php -> components/Header.tsx
// Converted from PHP template to React Server Component
// wp_head() -> Next.js Metadata API (in app/layout.tsx)
// wp_nav_menu() -> Navigation component with WP REST API menu fetch
// body_class() -> removed (handled in layout.tsx)
// wp_body_open() -> removed (not needed in React)
// bloginfo() -> fetched from WP REST API root endpoint
// the_custom_logo() -> fetched from WP REST API settings endpoint
// is_front_page() && is_home() -> replaced with Next.js usePathname() hook in client wrapper

import Link from 'next/link';
import Image from 'next/image';
import NavigationMenu from '@/components/NavigationMenu';

// TODO: Add WordPress media domain to next.config.js images configuration
// TODO: Install WP REST API Menus plugin or enable WPGraphQL for menu fetching
// TODO: Ensure CORS headers are configured on WordPress to allow frontend domain

const WP_API_URL = process.env.NEXT_PUBLIC_WORDPRESS_API_URL ?? '';

// TypeScript types for WordPress REST API responses
interface SiteInfo {
  name: string;
  description: string;
  url: string;
  site_logo?: number;
  site_icon_url?: string;
}

interface WPMedia {
  id: number;
  source_url: string;
  alt_text: string;
  media_details: {
    width: number;
    height: number;
    sizes?: {
      full?: { source_url: string; width: number; height: number };
      medium?: { source_url: string; width: number; height: number };
      thumbnail?: { source_url: string; width: number; height: number };
    };
  };
}

// Fetch site info from WP REST API root endpoint
// MIGRATED: bloginfo('name'), bloginfo('description') -> fetched from /wp-json/
async function getSiteInfo(): Promise<SiteInfo> {
  try {
    const res = await fetch(`${WP_API_URL}/wp-json/`, {
      next: { revalidate: 3600 }, // Cache for 1 hour (ISR)
    });

    if (!res.ok) {
      throw new Error(`Failed to fetch site info: ${res.status}`);
    }

    const data = await res.json();

    return {
      name: data.name ?? '',
      description: data.description ?? '',
      url: data.url ?? '',
      site_logo: data.site_logo ?? undefined,
      site_icon_url: data.site_icon_url ?? undefined,
    };
  } catch (error) {
    console.error('Error fetching site info:', error);
    return {
      name: '',
      description: '',
      url: WP_API_URL,
    };
  }
}

// Fetch custom logo media details from WP REST API
// MIGRATED: the_custom_logo() -> fetched from /wp-json/wp/v2/media/{id}
async function getCustomLogo(logoId: number): Promise<WPMedia | null> {
  try {
    const res = await fetch(`${WP_API_URL}/wp-json/wp/v2/media/${logoId}`, {
      next: { revalidate: 3600 },
    });

    if (!res.ok) {
      return null;
    }

    return res.json();
  } catch (error) {
    console.error('Error fetching custom logo:', error);
    return null;
  }
}

// SiteBranding component
// MIGRATED: PHP site-branding div with conditional h1/p and custom logo
// is_front_page() && is_home() -> handled via SiteBrandingWrapper client component
interface SiteBrandingProps {
  siteInfo: SiteInfo;
  logo: WPMedia | null;
  isHomePage?: boolean;
}

function SiteBranding({ siteInfo, logo, isHomePage = false }: SiteBrandingProps) {
  return (
    <div className="site-branding">
      {/* MIGRATED: the_custom_logo() -> Next.js Image component */}
      {logo && (
        <Link href="/" rel="home" className="custom-logo-link">
          <Image
            src={logo.source_url}
            alt={logo.alt_text || siteInfo.name}
            width={logo.media_details.width}
            height={logo.media_details.height}
            className="custom-logo"
            priority // Logo is above the fold
          />
        </Link>
      )}

      {/* MIGRATED: is_front_page() && is_home() conditional -> isHomePage prop */}
      {/* On homepage: render h1 for SEO; on other pages: render p tag */}
      {isHomePage ? (
        <h1 className="site-title">
          {/* MIGRATED: esc_url(home_url('/')) -> href="/" */}
          {/* MIGRATED: bloginfo('name') -> siteInfo.name */}
          <Link href="/" rel="home">
            {siteInfo.name}
          </Link>
        </h1>
      ) : (
        <p className="site-title">
          <Link href="/" rel="home">
            {siteInfo.name}
          </Link>
        </p>
      )}

      {/* MIGRATED: get_bloginfo('description', 'display') -> siteInfo.description */}
      {/* MIGRATED: is_customize_preview() -> removed (no WP Customizer in headless) */}
      {siteInfo.description && (
        <p className="site-description">{siteInfo.description}</p>
      )}
    </div>
  );
}

// Main Header Server Component
// MIGRATED: header.php -> Header.tsx React Server Component
// Removed: <!doctype html>, <html>, <head> (all handled by Next.js app/layout.tsx)
// Removed: <body> tag (handled by Next.js app/layout.tsx)
// Removed: wp_head() (replaced by Next.js Metadata API in app/layout.tsx)
// Removed: language_attributes() (handled by <html lang> in layout.tsx)
// Removed: get_header() call (this IS the header component now)
export default async function Header() {
  const siteInfo = await getSiteInfo();

  // MIGRATED: the_custom_logo() -> fetch logo media if site_logo ID exists
  const logo =
    siteInfo.site_logo ? await getCustomLogo(siteInfo.site_logo) : null;

  return (
    <>
      {/* MIGRATED: <div id="page" class="site"> -> wrapping div */}
      {/* NOTE: The closing </div> for #page and #content must be in app/layout.tsx */}
      <div id="page" className="site">
        {/* MIGRATED: skip link esc_html_e('Skip to content') -> static string */}
        <a className="skip-link screen-reader-text" href="#content">
          Skip to content
        </a>

        <header id="masthead" className="site-header">
          {/* MIGRATED: SiteBranding with server-fetched data */}
          {/* TODO: Manual review needed -- isHomePage prop requires client-side pathname check */}
          {/* For full is_front_page() behavior, wrap SiteBranding in a Client Component */}
          {/* that uses usePathname() from next/navigation */}
          <SiteBrandingWithPathname siteInfo={siteInfo} logo={logo} />

          {/* MIGRATED: wp_nav_menu() -> NavigationMenu React component */}
          {/* theme_location: 'menu-1' -> NavigationMenu fetches primary menu */}
          {/* menu_id: 'primary-menu' -> passed as prop */}
          <nav id="site-navigation" className="main-navigation">
            {/* MIGRATED: menu-toggle button with aria attributes */}
            {/* aria-expanded state now managed in NavigationMenu client component */}
            <NavigationMenu menuLocation="menu-1" menuId="primary-menu" />
          </nav>
          {/* #site-navigation */}
        </header>
        {/* #masthead */}

        <div id="content" className="site-content">
          {/* NOTE: This div is intentionally left open here */}
          {/* It must be closed in app/layout.tsx after {children} */}
          {/* MIGRATED: <div id="content" class="site-content"> opens here, closes in layout */}
        </div>
      </div>
    </>
  );
}

// TODO: Manual review needed -- SiteBrandingWithPathname requires a Client Component
// wrapper to replicate is_front_page() && is_home() PHP conditional.
// The server component passes data down; client component reads pathname.
// This pattern avoids making the entire Header a client component.

// Inline client wrapper for pathname-aware site branding
// In production, move this to a separate file: components/SiteBrandingWithPathname.tsx
// and mark it with 'use client'

// MIGRATED: is_front_page() && is_home() PHP conditional -> client component with usePathname
// NOTE: This is declared here for colocation but should be split to its own file
// marked 'use client' to avoid making the server component a client component

import type { FC } from 'react';

// Since we cannot use 'use client' inline in a server component file,
// this component is declared as a regular component that accepts isHomePage as a prop.
// The actual pathname detection is handled by importing the client wrapper below.
// TODO: Create components/SiteBrandingWithPathname.tsx as a separate 'use client' file

interface SiteBrandingWithPathnameProps {
  siteInfo: SiteInfo;
  logo: WPMedia | null;
}

// Temporary server-side fallback: renders non-homepage version
// Replace with proper client component that uses usePathname()
function SiteBrandingWithPathname({ siteInfo, logo }: SiteBrandingWithPathnameProps) {
  // TODO: Manual review needed -- replace with client component using usePathname()
  // to replicate is_front_page() && is_home() behavior
  // Example client component:
  //
  // 'use client';
  // import { usePathname } from 'next/navigation';
  // export function SiteBrandingWithPathname({ siteInfo, logo }) {
  //   const pathname = usePathname();
  //   const isHomePage = pathname === '/';
  //   return <SiteBranding siteInfo={siteInfo} logo={logo} isHomePage={isHomePage} />;
  // }

  return <SiteBranding siteInfo={siteInfo} logo={logo} isHomePage={false} />;
}