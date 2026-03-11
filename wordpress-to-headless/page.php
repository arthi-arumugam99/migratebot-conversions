// MIGRATED: page.php -> app/[slug]/page.tsx
// Converts WordPress static page template to Next.js dynamic route with App Router

import { Metadata } from 'next';
import { notFound } from 'next/navigation';
import Comments from '@/components/Comments';
import Sidebar from '@/components/Sidebar';

// TypeScript types for WordPress REST API page response
interface WPPage {
  id: number;
  slug: string;
  status: string;
  type: string;
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
  parent: number;
  menu_order: number;
  comment_status: 'open' | 'closed';
  date: string;
  modified: string;
  link: string;
  _embedded?: {
    'wp:featuredmedia'?: Array<{
      source_url: string;
      alt_text: string;
      media_details: {
        width: number;
        height: number;
      };
    }>;
    author?: Array<{
      name: string;
      avatar_urls: Record<string, string>;
    }>;
    replies?: Array<Array<{ id: number }>>;
  };
}

const API_BASE = process.env.NEXT_PUBLIC_WORDPRESS_API_URL ?? 'https://your-wordpress-site.com';

// MIGRATED: Replaces WP_Query / the Loop for pages; fetches by slug via REST API
async function getPageBySlug(slug: string): Promise<WPPage | null> {
  try {
    const res = await fetch(
      `${API_BASE}/wp-json/wp/v2/pages?slug=${encodeURIComponent(slug)}&_embed`,
      {
        next: { revalidate: 60 }, // ISR: revalidate every 60 seconds
      }
    );

    if (!res.ok) {
      return null;
    }

    const pages: WPPage[] = await res.json();

    if (!pages || pages.length === 0) {
      return null;
    }

    return pages[0];
  } catch (error) {
    console.error('Failed to fetch page:', error);
    return null;
  }
}

// MIGRATED: Replaces is_front_page / page template detection; generates static paths for all pages
export async function generateStaticParams(): Promise<Array<{ slug: string }>> {
  try {
    const res = await fetch(
      `${API_BASE}/wp-json/wp/v2/pages?per_page=100&_fields=slug`,
      {
        next: { revalidate: 3600 },
      }
    );

    if (!res.ok) {
      return [];
    }

    const pages: Array<{ slug: string }> = await res.json();

    return pages.map((page) => ({
      slug: page.slug,
    }));
  } catch (error) {
    console.error('Failed to generate static params for pages:', error);
    return [];
  }
}

// MIGRATED: Replaces wp_head() SEO output; uses Next.js Metadata API
// Previously: wp_title(), yoast_head, etc.
export async function generateMetadata({
  params,
}: {
  params: { slug: string };
}): Promise<Metadata> {
  const page = await getPageBySlug(params.slug);

  if (!page) {
    return {
      title: 'Page Not Found',
    };
  }

  // Strip HTML tags from excerpt for meta description
  const description = page.excerpt.rendered.replace(/<[^>]*>/g, '').trim();

  const featuredImage = page._embedded?.['wp:featuredmedia']?.[0];

  return {
    title: page.title.rendered,
    description: description || undefined,
    openGraph: {
      title: page.title.rendered,
      description: description || undefined,
      type: 'website',
      url: `${API_BASE}/${page.slug}`,
      ...(featuredImage && {
        images: [
          {
            url: featuredImage.source_url,
            width: featuredImage.media_details?.width,
            height: featuredImage.media_details?.height,
            alt: featuredImage.alt_text,
          },
        ],
      }),
    },
  };
}

// Determine if comments should be shown for this page
// MIGRATED: Replaces comments_open() || get_comments_number() PHP conditional
function shouldShowComments(page: WPPage): boolean {
  const isCommentsOpen = page.comment_status === 'open';
  const hasComments =
    page._embedded?.replies?.[0] != null &&
    page._embedded.replies[0].length > 0;

  return isCommentsOpen || hasComments;
}

// MIGRATED: Replaces page.php template; combines get_header(), loop, get_template_part('template-parts/content', 'page'),
// comments_template(), get_sidebar(), get_footer() into a single React Server Component
export default async function PageTemplate({
  params,
}: {
  params: { slug: string };
}) {
  const page = await getPageBySlug(params.slug);

  // MIGRATED: Replaces WordPress 404 handling for unknown page slugs
  if (!page) {
    notFound();
  }

  const showComments = shouldShowComments(page);
  const featuredImage = page._embedded?.['wp:featuredmedia']?.[0];

  return (
    // MIGRATED: Replaces <div id="primary" class="content-area"> wrapper from page.php
    <div id="primary" className="content-area">
      {/* MIGRATED: Replaces <main id="main" class="site-main"> from page.php */}
      <main id="main" className="site-main">

        {/* MIGRATED: Replaces get_template_part('template-parts/content', 'page') */}
        <article
          id={`page-${page.id}`}
          className={`page type-page status-${page.status} hentry`}
        >

          {/* MIGRATED: Replaces the_title() with entry-header markup */}
          <header className="entry-header">
            <h1
              className="entry-title"
              // WordPress encodes HTML entities in titles; dangerouslySetInnerHTML
              // preserves special characters (e.g., &amp; -> &)
              dangerouslySetInnerHTML={{ __html: page.title.rendered }}
            />
          </header>

          {/* MIGRATED: Replaces the_post_thumbnail() — only shown when featured media exists */}
          {featuredImage && (
            <div className="post-thumbnail">
              {/* TODO: Replace with Next.js <Image> component for optimization.
                  Add WordPress domain to next.config.js images.remotePatterns first.
                  Example:
                  import Image from 'next/image';
                  <Image
                    src={featuredImage.source_url}
                    alt={featuredImage.alt_text}
                    width={featuredImage.media_details?.width}
                    height={featuredImage.media_details?.height}
                    priority
                  />
              */}
              <img
                src={featuredImage.source_url}
                alt={featuredImage.alt_text}
                className="attachment-post-thumbnail size-post-thumbnail wp-post-image"
              />
            </div>
          )}

          {/* MIGRATED: Replaces the_content(); dangerouslySetInnerHTML is required for
              WordPress HTML content. The REST API returns server-rendered HTML including
              any shortcode output. Gutenberg blocks are also pre-rendered.
              WARNING: Only render trusted content from your own WordPress instance. */}
          <div className="entry-content">
            <div
              dangerouslySetInnerHTML={{ __html: page.content.rendered }}
            />
          </div>

          {/* MIGRATED: Replaces edit_post_link() — link to edit page in WP admin */}
          {/* TODO: Conditionally show edit link for authenticated admin users
              using session/NextAuth role check */}

        </article>

        {/* MIGRATED: Replaces comments_open() || get_comments_number() conditional
            and comments_template() call */}
        {showComments && (
          <Comments postId={page.id} />
        )}

      </main>

      {/* MIGRATED: Replaces get_sidebar() call */}
      <Sidebar />
    </div>
  );
}