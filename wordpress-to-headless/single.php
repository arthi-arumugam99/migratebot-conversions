import Link from 'next/link';
import { notFound } from 'next/navigation';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import Sidebar from '@/components/Sidebar';
import Comments from '@/components/Comments';

// MIGRATED: TypeScript types for WP REST API post response
interface WPPost {
  id: number;
  slug: string;
  type: string;
  title: {
    rendered: string;
  };
  content: {
    rendered: string;
  };
  excerpt: {
    rendered: string;
  };
  date: string;
  modified: string;
  author: number;
  featured_media: number;
  categories: number[];
  tags: number[];
  comment_status: 'open' | 'closed';
  _embedded?: {
    author?: Array<{
      id: number;
      name: string;
      slug: string;
      avatar_urls?: Record<string, string>;
      link: string;
    }>;
    'wp:featuredmedia'?: Array<{
      id: number;
      source_url: string;
      alt_text: string;
      media_details?: {
        width: number;
        height: number;
        sizes?: Record<string, { source_url: string; width: number; height: number }>;
      };
    }>;
    'wp:term'?: Array<
      Array<{
        id: number;
        name: string;
        slug: string;
        taxonomy: string;
        link: string;
      }>
    >;
  };
}

interface WPComment {
  id: number;
  post: number;
  parent: number;
  author_name: string;
  date: string;
  content: {
    rendered: string;
  };
  author_avatar_urls?: Record<string, string>;
}

interface AdjacentPost {
  id: number;
  slug: string;
  title: { rendered: string };
}

const API_URL = process.env.NEXT_PUBLIC_WORDPRESS_API_URL ?? 'https://your-wordpress-site.com';

async function getPostBySlug(slug: string): Promise<WPPost | null> {
  const res = await fetch(
    `${API_URL}/wp-json/wp/v2/posts?slug=${encodeURIComponent(slug)}&_embed`,
    {
      next: { revalidate: 60 }, // ISR: revalidate every 60 seconds
    }
  );

  if (!res.ok) return null;

  const posts: WPPost[] = await res.json();
  return posts.length > 0 ? posts[0] : null;
}

async function getAdjacentPosts(postId: number): Promise<{ prev: AdjacentPost | null; next: AdjacentPost | null }> {
  // MIGRATED: Replaces the_post_navigation() — fetch surrounding posts by date ordering
  const [prevRes, nextRes] = await Promise.all([
    fetch(
      `${API_URL}/wp-json/wp/v2/posts?per_page=1&before=${encodeURIComponent(new Date().toISOString())}&exclude=${postId}&orderby=date&order=desc&_fields=id,slug,title`,
      { next: { revalidate: 60 } }
    ),
    fetch(
      `${API_URL}/wp-json/wp/v2/posts?per_page=1&exclude=${postId}&orderby=date&order=asc&_fields=id,slug,title`,
      { next: { revalidate: 60 } }
    ),
  ]);

  const prevPosts: AdjacentPost[] = prevRes.ok ? await prevRes.json() : [];
  const nextPosts: AdjacentPost[] = nextRes.ok ? await nextRes.json() : [];

  return {
    prev: prevPosts.length > 0 ? prevPosts[0] : null,
    next: nextPosts.length > 0 ? nextPosts[0] : null,
  };
}

// MIGRATED: generateStaticParams replaces the WordPress template hierarchy's dynamic routing
// Pre-renders all published post slugs at build time
export async function generateStaticParams(): Promise<{ slug: string }[]> {
  let allPosts: { slug: string }[] = [];
  let page = 1;
  let totalPages = 1;

  do {
    const res = await fetch(
      `${API_URL}/wp-json/wp/v2/posts?per_page=100&page=${page}&_fields=slug`,
      { cache: 'force-cache' }
    );

    if (!res.ok) break;

    const posts: Array<{ slug: string }> = await res.json();
    allPosts = allPosts.concat(posts);

    // MIGRATED: Read X-WP-TotalPages header for pagination
    const wpTotalPages = res.headers.get('X-WP-TotalPages');
    totalPages = wpTotalPages ? parseInt(wpTotalPages, 10) : 1;
    page++;
  } while (page <= totalPages);

  return allPosts.map((post) => ({ slug: post.slug }));
}

// MIGRATED: generateMetadata replaces wp_title() and Yoast SEO meta tags in wp_head()
export async function generateMetadata({ params }: { params: { slug: string } }) {
  const post = await getPostBySlug(params.slug);

  if (!post) {
    return { title: 'Post Not Found' };
  }

  const featuredMedia = post._embedded?.['wp:featuredmedia']?.[0];
  const author = post._embedded?.author?.[0];

  // Strip HTML tags from excerpt for meta description
  const description = post.excerpt.rendered.replace(/<[^>]*>/g, '').trim();

  return {
    title: post.title.rendered,
    description,
    openGraph: {
      title: post.title.rendered,
      description,
      type: 'article',
      publishedTime: post.date,
      modifiedTime: post.modified,
      authors: author ? [author.name] : undefined,
      images: featuredMedia
        ? [
            {
              url: featuredMedia.source_url,
              width: featuredMedia.media_details?.width,
              height: featuredMedia.media_details?.height,
              alt: featuredMedia.alt_text,
            },
          ]
        : undefined,
    },
    twitter: {
      card: 'summary_large_image',
      title: post.title.rendered,
      description,
      images: featuredMedia ? [featuredMedia.source_url] : undefined,
    },
  };
}

// MIGRATED: PostNavigation replaces the_post_navigation() template tag
function PostNavigation({
  prev,
  next,
}: {
  prev: AdjacentPost | null;
  next: AdjacentPost | null;
}) {
  if (!prev && !next) return null;

  return (
    <nav className="navigation post-navigation" aria-label="Posts">
      <h2 className="screen-reader-text">Post navigation</h2>
      <div className="nav-links">
        {prev && (
          <div className="nav-previous">
            {/* MIGRATED: esc_url() + the_title() replaced by Next.js Link with rendered title */}
            <Link href={`/posts/${prev.slug}`} rel="prev">
              <span className="meta-nav" aria-hidden="true">
                &larr; Previous Post
              </span>
              <span className="screen-reader-text">Previous post:</span>
              <span
                className="post-title"
                dangerouslySetInnerHTML={{ __html: prev.title.rendered }}
              />
            </Link>
          </div>
        )}
        {next && (
          <div className="nav-next">
            {/* MIGRATED: esc_url() + the_title() replaced by Next.js Link with rendered title */}
            <Link href={`/posts/${next.slug}`} rel="next">
              <span className="meta-nav" aria-hidden="true">
                Next Post &rarr;
              </span>
              <span className="screen-reader-text">Next post:</span>
              <span
                className="post-title"
                dangerouslySetInnerHTML={{ __html: next.title.rendered }}
              />
            </Link>
          </div>
        )}
      </div>
    </nav>
  );
}

// MIGRATED: PostMeta renders author, date, categories, tags
// Replaces individual template tags: the_author(), the_date(), the_category(), the_tags()
function PostMeta({ post }: { post: WPPost }) {
  const author = post._embedded?.author?.[0];
  // MIGRATED: wp:term[0] = categories, wp:term[1] = tags (from _embed)
  const categories = post._embedded?.['wp:term']?.[0]?.filter(
    (term) => term.taxonomy === 'category'
  ) ?? [];
  const tags = post._embedded?.['wp:term']?.[1]?.filter(
    (term) => term.taxonomy === 'post_tag'
  ) ?? [];

  // MIGRATED: the_date('F j, Y') replaced by Intl.DateTimeFormat
  const formattedDate = new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }).format(new Date(post.date));

  return (
    <div className="entry-meta">
      {author && (
        <span className="byline">
          {/* MIGRATED: the_author_posts_link() replaced by Next.js Link */}
          By{' '}
          <Link href={`/author/${author.slug}`} className="author-link">
            {/* MIGRATED: esc_html(get_the_author()) replaced by plain text render */}
            {author.name}
          </Link>
        </span>
      )}
      {/* MIGRATED: the_date() / get_the_date() replaced by <time> with Intl formatting */}
      <span className="posted-on">
        <time dateTime={post.date}>{formattedDate}</time>
      </span>
      {categories.length > 0 && (
        <span className="cat-links">
          {/* MIGRATED: the_category() replaced by mapped Link components */}
          Categories:{' '}
          {categories.map((cat, index) => (
            <span key={cat.id}>
              <Link href={`/category/${cat.slug}`}>{cat.name}</Link>
              {index < categories.length - 1 ? ', ' : ''}
            </span>
          ))}
        </span>
      )}
      {tags.length > 0 && (
        <span className="tags-links">
          {/* MIGRATED: the_tags() replaced by mapped Link components */}
          Tags:{' '}
          {tags.map((tag, index) => (
            <span key={tag.id}>
              <Link href={`/tag/${tag.slug}`}>{tag.name}</Link>
              {index < tags.length - 1 ? ', ' : ''}
            </span>
          ))}
        </span>
      )}
    </div>
  );
}

// MIGRATED: FeaturedImage renders the post thumbnail
// Replaces the_post_thumbnail() — uses standard <img> here;
// TODO: Replace with next/image after adding WordPress domain to next.config.js images.remotePatterns
function FeaturedImage({ post }: { post: WPPost }) {
  const media = post._embedded?.['wp:featuredmedia']?.[0];

  // MIGRATED: has_post_thumbnail() replaced by checking featured_media !== 0 and _embedded data
  if (!media || post.featured_media === 0) return null;

  return (
    <figure className="post-thumbnail">
      {/* TODO: Replace <img> with next/image after configuring remotePatterns in next.config.js */}
      <img
        src={media.source_url}
        alt={media.alt_text}
        width={media.media_details?.width}
        height={media.media_details?.height}
        className="attachment-full size-full wp-post-image"
      />
    </figure>
  );
}

// MIGRATED: Single post page component
// Replaces single.php template with async server component + data fetching
export default async function SinglePostPage({
  params,
}: {
  params: { slug: string };
}) {
  // MIGRATED: WP_Query / get_queried_object() replaced by direct API fetch by slug
  const post = await getPostBySlug(params.slug);

  // MIGRATED: WordPress 404 handling replaced by notFound() which triggers not-found.tsx
  if (!post) {
    notFound();
  }

  const { prev, next } = await getAdjacentPosts(post.id);

  // MIGRATED: comments_open() check replaced by examining comment_status field from REST API
  const commentsEnabled = post.comment_status === 'open';

  return (
    <>
      {/* MIGRATED: get_header() replaced by imported Header component rendered in layout.tsx */}
      {/* Header is rendered in app/layout.tsx — kept here as reminder of original structure */}
      <Header />

      <div id="primary" className="content-area">
        <main id="main" className="site-main">

          {/* MIGRATED: while (have_posts()) : the_post() loop replaced by direct post render */}
          {/* MIGRATED: get_template_part('template-parts/content', get_post_type()) replaced
              by inline article markup below — extract to a ContentSingle component if reuse is needed */}
          <article
            id={`post-${post.id}`}
            className={`post-${post.id} post type-post status-publish format-standard hentry`}
          >
            <header className="entry-header">
              {/* MIGRATED: the_title() replaced by dangerouslySetInnerHTML for rendered title with HTML entities */}
              <h1
                className="entry-title"
                dangerouslySetInnerHTML={{ __html: post.title.rendered }}
              />
              {/* MIGRATED: Template tags for meta replaced by PostMeta component */}
              <PostMeta post={post} />
            </header>

            {/* MIGRATED: the_post_thumbnail() replaced by FeaturedImage component */}
            <FeaturedImage post={post} />

            <div className="entry-content">
              {/* MIGRATED: the_content() replaced by dangerouslySetInnerHTML
                  NOTE: Ensure WordPress content is trusted or sanitize before rendering
                  TODO: Consider using a sanitization library (e.g., DOMPurify) for extra security */}
              <div
                dangerouslySetInnerHTML={{ __html: post.content.rendered }}
              />
            </div>

            <footer className="entry-footer">
              <PostMeta post={post} />
            </footer>
          </article>

          {/* MIGRATED: the_post_navigation() replaced by PostNavigation component */}
          <PostNavigation prev={prev} next={next} />

          {/* MIGRATED: comments_open() || get_comments_number() check replaced by comment_status field */}
          {/* MIGRATED: comments_template() replaced by Comments component */}
          {commentsEnabled && (
            <Comments postId={post.id} />
          )}

        </main>{/* #main */}
      </div>{/* #primary */}

      {/* MIGRATED: get_sidebar() replaced by imported Sidebar component */}
      <Sidebar />

      {/* MIGRATED: get_footer() replaced by imported Footer component rendered in layout.tsx */}
      {/* Footer is rendered in app/layout.tsx — kept here as reminder of original structure */}
      <Footer />
    </>
  );
}