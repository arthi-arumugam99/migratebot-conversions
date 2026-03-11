import Image from 'next/image';
import Link from 'next/link';

// MIGRATED: WordPress REST API response types for page content
interface WPMediaDetails {
  width: number;
  height: number;
  sizes?: {
    thumbnail?: { source_url: string; width: number; height: number };
    medium?: { source_url: string; width: number; height: number };
    full?: { source_url: string; width: number; height: number };
  };
}

interface WPFeaturedMedia {
  id: number;
  source_url: string;
  alt_text: string;
  media_details: WPMediaDetails;
}

interface WPPage {
  id: number;
  slug: string;
  status: string;
  type: string;
  link: string;
  title: {
    rendered: string;
  };
  content: {
    rendered: string;
    protected: boolean;
  };
  excerpt: {
    rendered: string;
    protected: boolean;
  };
  featured_media: number;
  // MIGRATED: _embedded data replaces multiple PHP template tag calls
  _embedded?: {
    'wp:featuredmedia'?: WPFeaturedMedia[];
    author?: Array<{
      id: number;
      name: string;
      url: string;
      avatar_urls?: Record<string, string>;
    }>;
  };
  acf?: Record<string, unknown>;
  // MIGRATED: edit_post_link equivalent — REST API exposes _links for edit access
  _links?: {
    'wp:action-publish'?: Array<{ href: string }>;
    'wp:action-edit'?: Array<{ href: string }>;
  };
}

interface ContentPageProps {
  page: WPPage;
  // MIGRATED: isEditable replaces PHP get_edit_post_link() check; passed from parent
  isEditable?: boolean;
  // MIGRATED: editLink replaces PHP edit_post_link(); passed from parent server component
  editLink?: string | null;
}

// MIGRATED: template-parts/content-page.php -> React server component
// Previously rendered via get_template_part('template-parts/content', 'page')
// Now imported directly as a React component
export default function ContentPage({ page, isEditable = false, editLink = null }: ContentPageProps) {
  // MIGRATED: _s_post_thumbnail() -> extract featured media from _embedded data
  const featuredMedia = page._embedded?.['wp:featuredmedia']?.[0] ?? null;
  const hasFeaturedMedia = featuredMedia && page.featured_media !== 0;

  // MIGRATED: post_class() -> manually constructed className string
  // WordPress post_class() outputs dynamic classes like 'post-{id} page type-page status-publish'
  const articleClasses = [
    'post',
    `post-${page.id}`,
    'page',
    'type-page',
    'status-publish',
    hasFeaturedMedia ? 'has-post-thumbnail' : '',
  ]
    .filter(Boolean)
    .join(' ');

  return (
    // MIGRATED: <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    // the_ID() -> page.id; post_class() -> manually constructed className above
    <article id={`post-${page.id}`} className={articleClasses}>
      <header className="entry-header">
        {/* MIGRATED: the_title('<h1 class="entry-title">', '</h1>') -> JSX heading */}
        <h1
          className="entry-title"
          // MIGRATED: title.rendered may contain HTML entities — dangerouslySetInnerHTML
          // preserves WordPress title formatting (e.g., &amp;, <em> in titles)
          dangerouslySetInnerHTML={{ __html: page.title.rendered }}
        />
      </header>
      {/* .entry-header */}

      {/* MIGRATED: _s_post_thumbnail() -> Next.js Image component with _embedded media data */}
      {/* _s_post_thumbnail() was a theme-specific wrapper around the_post_thumbnail() */}
      {hasFeaturedMedia && featuredMedia && (
        <div className="post-thumbnail">
          <Image
            src={featuredMedia.source_url}
            alt={featuredMedia.alt_text || page.title.rendered.replace(/<[^>]*>/g, '')}
            width={
              featuredMedia.media_details?.sizes?.full?.width ??
              featuredMedia.media_details?.width ??
              1200
            }
            height={
              featuredMedia.media_details?.sizes?.full?.height ??
              featuredMedia.media_details?.height ??
              630
            }
            className="attachment-post-thumbnail size-post-thumbnail wp-post-image"
            priority
            // TODO: Add WordPress media domain to next.config.js images.remotePatterns
          />
        </div>
      )}

      <div className="entry-content">
        {/* MIGRATED: the_content() -> dangerouslySetInnerHTML with REST API rendered content */}
        {/* WordPress renders shortcodes and filters server-side before returning via REST */}
        {/* TODO: Audit content for interactive shortcodes that need client-side React replacements */}
        <div
          dangerouslySetInnerHTML={{ __html: page.content.rendered }}
        />

        {/* MIGRATED: wp_link_pages() -> pagination for multi-page content using <!--nextpage--> tag */}
        {/* TODO: Manual review needed -- wp_link_pages() splits content via <!--nextpage--> WordPress */}
        {/* quicktag. The REST API returns full content without splits. If multi-page posts are used, */}
        {/* a custom REST endpoint or WPGraphQL is required to handle <!--nextpage--> splits properly. */}
      </div>
      {/* .entry-content */}

      {/* MIGRATED: if (get_edit_post_link()) -> isEditable prop from parent component */}
      {/* get_edit_post_link() returns null for non-editors; pass isEditable based on session/role */}
      {/* TODO: Manual review needed -- isEditable should be derived from NextAuth session role check */}
      {/* Use getServerSession() in the parent page component to determine if user can edit */}
      {isEditable && editLink && (
        <footer className="entry-footer">
          {/* MIGRATED: edit_post_link() with wp_kses() and screen-reader span */}
          {/* wp_kses() sanitized the HTML; JSX renders the trusted markup directly */}
          <span className="edit-link">
            <Link href={editLink} rel="nofollow" target="_blank">
              Edit{' '}
              <span className="screen-reader-text">
                {/* MIGRATED: get_the_title() inside edit_post_link screen-reader text */}
                {/* Strip HTML tags from rendered title for plain text screen reader output */}
                {page.title.rendered.replace(/<[^>]*>/g, '')}
              </span>
            </Link>
          </span>
        </footer>
      )}
      {/* .entry-footer */}
    </article>
    /* #post-{page.id} */
  );
}