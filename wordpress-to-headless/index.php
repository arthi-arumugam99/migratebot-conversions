import Link from 'next/link';
import Image from 'next/image';

// MIGRATED: WordPress REST API types replacing WP_Query/Loop data structures
interface WPPost {
  id: number;
  slug: string;
  title: {
    rendered: string;
  };
  excerpt: {
    rendered: string;
  };
  content: {
    rendered: string;
  };
  date: string;
  modified: string;
  type: string;
  categories: number[];
  tags: number[];
  featured_media: number;
  author: number;
  _embedded?: {
    author?: Array<{
      id: number;
      name: string;
      avatar_urls?: Record<string, string>;
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
      }>
    >;
  };
}

interface PostsResponse {
  posts: WPPost[];
  totalPages: number;
  total: number;
}

// MIGRATED: WP REST API base URL from environment variable replacing hardcoded WP paths
const WP_API_URL = process.env.NEXT_PUBLIC_WP_API_URL || 'http://localhost/wp-json';

// MIGRATED: Data fetching function replacing WP_Query / have_posts() / the_post() loop
async function getPosts(page: number = 1): Promise<PostsResponse> {
  const res = await fetch(
    `${WP_API_URL}/wp/v2/posts?per_page=10&page=${page}&_embed&orderby=date&order=desc`,
    {
      // MIGRATED: ISR revalidation replacing WordPress page cache
      next: { revalidate: 60 },
    }
  );

  if (!res.ok) {
    throw new Error(`Failed to fetch posts: ${res.status} ${res.statusText}`);
  }

  // MIGRATED: Reading X-WP-TotalPages header replacing WP pagination globals
  const totalPages = parseInt(res.headers.get('X-WP-TotalPages') || '1', 10);
  const total = parseInt(res.headers.get('X-WP-Total') || '0', 10);
  const posts: WPPost[] = await res.json();

  return { posts, totalPages, total };
}

// MIGRATED: getSiteInfo replacing bloginfo() calls for site name/description
async function getSiteInfo(): Promise<{ name: string; description: string }> {
  const res = await fetch(`${WP_API_URL}/`, {
    next: { revalidate: 3600 },
  });

  if (!res.ok) {
    return { name: '', description: '' };
  }

  const data = await res.json();
  return {
    name: data.name || '',
    description: data.description || '',
  };
}

// MIGRATED: Template part content-none replacing get_template_part('template-parts/content', 'none')
function NoPostsFound() {
  return (
    <section className="no-results not-found">
      <header className="page-header">
        {/* MIGRATED: Static heading replacing dynamic WP not-found messaging */}
        <h1 className="page-title">Nothing Here</h1>
      </header>
      <div className="page-content">
        <p>It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.</p>
        {/* MIGRATED: Search form replacing get_search_form() */}
        <form role="search" method="get" className="search-form" action="/search">
          <label>
            <span className="screen-reader-text">Search for:</span>
            <input
              type="search"
              className="search-field"
              placeholder="Search &hellip;"
              name="query"
              title="Search for:"
            />
          </label>
          <button type="submit" className="search-submit">
            Search
          </button>
        </form>
      </div>
    </section>
  );
}

// MIGRATED: PostCard replaces get_template_part('template-parts/content', get_post_type())
// handling post rendering previously delegated to content-post.php, content-page.php, etc.
function PostCard({ post }: { post: WPPost }) {
  // MIGRATED: _embedded author replacing the_author() / get_the_author()
  const author = post._embedded?.author?.[0];

  // MIGRATED: _embedded featured media replacing the_post_thumbnail() / get_the_post_thumbnail()
  const featuredMedia = post._embedded?.['wp:featuredmedia']?.[0];

  // MIGRATED: _embedded terms replacing the_category() / the_tags()
  const categories = post._embedded?.['wp:term']?.[0]?.filter(
    (term) => term.taxonomy === 'category'
  );

  // MIGRATED: JS date formatting replacing the_date() / get_the_date()
  const formattedDate = new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }).format(new Date(post.date));

  // MIGRATED: ISO date string for datetime attribute replacing get_the_date('c')
  const isoDate = new Date(post.date).toISOString();

  return (
    <article
      id={`post-${post.id}`}
      // MIGRATED: Static class names replacing body_class() / post_class() PHP helpers
      className={`post-${post.id} post type-${post.type} status-publish hentry`}
    >
      {/* MIGRATED: Next.js Image replacing the_post_thumbnail() with wp-post-image class */}
      {featuredMedia && featuredMedia.source_url && (
        <div className="post-thumbnail">
          <Link href={`/posts/${post.slug}`} tabIndex={-1}>
            <Image
              // MIGRATED: source_url from _embedded replacing wp_get_attachment_image_src()
              src={featuredMedia.source_url}
              alt={featuredMedia.alt_text || post.title.rendered}
              width={
                featuredMedia.media_details?.sizes?.['medium_large']
                  ? featuredMedia.media_details.sizes['medium_large'].width
                  : featuredMedia.media_details?.width || 800
              }
              height={
                featuredMedia.media_details?.sizes?.['medium_large']
                  ? featuredMedia.media_details.sizes['medium_large'].height
                  : featuredMedia.media_details?.height || 450
              }
              // TODO: Add WordPress media domain to next.config.js images.remotePatterns
              style={{ width: '100%', height: 'auto' }}
            />
          </Link>
        </div>
      )}

      <header className="entry-header">
        {/* MIGRATED: categories replacing is_sticky() / has_category() checks */}
        {categories && categories.length > 0 && (
          <div className="entry-meta cat-links">
            {categories.map((cat, index) => (
              <span key={cat.id}>
                {/* MIGRATED: Next.js Link replacing get_category_link() */}
                <Link href={`/category/${cat.slug}`} rel="category tag">
                  {cat.name}
                </Link>
                {index < categories.length - 1 && ', '}
              </span>
            ))}
          </div>
        )}

        {/* MIGRATED: post.title.rendered replacing the_title() */}
        <h2 className="entry-title">
          {/* MIGRATED: Next.js Link replacing the_permalink() */}
          <Link href={`/posts/${post.slug}`} rel="bookmark">
            {/* MIGRATED: JSX text content auto-escapes, replacing esc_html(get_the_title()) */}
            {post.title.rendered}
          </Link>
        </h2>

        <div className="entry-meta">
          {/* MIGRATED: JS Date replacing the_date() / get_the_date() */}
          <span className="posted-on">
            <time className="entry-date published" dateTime={isoDate}>
              {formattedDate}
            </time>
          </span>

          {/* MIGRATED: _embedded author replacing get_the_author() / get_author_posts_url() */}
          {author && (
            <span className="byline">
              {' '}
              by{' '}
              <span className="author vcard">
                <Link className="url fn n" href={`/author/${author.name.toLowerCase().replace(/\s+/g, '-')}`}>
                  {author.name}
                </Link>
              </span>
            </span>
          )}
        </div>
      </header>

      <div className="entry-summary">
        {/* MIGRATED: dangerouslySetInnerHTML replacing the_excerpt() */}
        {/* JSX cannot render raw HTML strings safely without this — excerpt is trusted WP content */}
        <div
          dangerouslySetInnerHTML={{ __html: post.excerpt.rendered }}
        />
      </div>

      <footer className="entry-footer">
        <Link href={`/posts/${post.slug}`} className="more-link">
          {/* MIGRATED: Static 'Read more' replacing the_content() more tag or read_more string */}
          Continue reading <span className="screen-reader-text">{post.title.rendered}</span>
        </Link>
      </footer>
    </article>
  );
}

// MIGRATED: Pagination component replacing the_posts_navigation() / paginate_links()
function PostsNavigation({
  currentPage,
  totalPages,
}: {
  currentPage: number;
  totalPages: number;
}) {
  if (totalPages <= 1) return null;

  return (
    // MIGRATED: Static nav replacing dynamic the_posts_navigation() output
    <nav className="navigation posts-navigation" aria-label="Posts">
      <h2 className="screen-reader-text">Posts navigation</h2>
      <div className="nav-links">
        {/* MIGRATED: Previous page link replacing previous_posts_link() */}
        {currentPage > 1 && (
          <div className="nav-previous">
            <Link href={currentPage - 1 === 1 ? '/posts' : `/posts?page=${currentPage - 1}`}>
              &larr; Newer posts
            </Link>
          </div>
        )}
        {/* MIGRATED: Next page link replacing next_posts_link() */}
        {currentPage < totalPages && (
          <div className="nav-next">
            <Link href={`/posts?page=${currentPage + 1}`}>
              Older posts &rarr;
            </Link>
          </div>
        )}
      </div>
    </nav>
  );
}

// MIGRATED: Page props for reading URL search params (page number)
interface HomePageProps {
  searchParams?: Promise<{ page?: string }>;
}

// MIGRATED: Async Server Component replacing index.php + get_header() + get_footer() + get_sidebar()
// This is the homepage (app/page.tsx) — equivalent to index.php when no home.php exists
// is_home() / is_front_page() PHP conditionals are handled by Next.js file-based routing
export default async function HomePage({ searchParams }: HomePageProps) {
  const resolvedSearchParams = await searchParams;
  // MIGRATED: URL search params replacing $paged / get_query_var('paged')
  const currentPage = parseInt(resolvedSearchParams?.page || '1', 10);

  let postsData: PostsResponse;
  let fetchError: string | null = null;

  try {
    // MIGRATED: Async data fetch replacing WP_Query instantiation and have_posts() check
    postsData = await getPosts(currentPage);
  } catch (err) {
    fetchError = err instanceof Error ? err.message : 'Failed to load posts';
    postsData = { posts: [], totalPages: 0, total: 0 };
  }

  const { posts, totalPages } = postsData;

  // MIGRATED: get_header() removed — layout.tsx provides the wrapping Header component
  // get_footer() removed — layout.tsx provides the wrapping Footer component
  // get_sidebar() removed — Sidebar component should be included in layout or co-located here
  return (
    // MIGRATED: #primary / .content-area replacing WordPress content wrapper divs
    <div id="primary" className="content-area">
      <main id="main" className="site-main">

        {fetchError ? (
          // MIGRATED: Error state replacing WP's native error handling
          <div className="error-message">
            <p>Sorry, there was a problem loading posts. Please try again later.</p>
          </div>
        ) : posts.length > 0 ? (
          <>
            {/*
              MIGRATED: is_home() && !is_front_page() conditional replaced by:
              - This file (app/page.tsx) IS the front page
              - A separate app/blog/page.tsx would handle the blog index when a static front page is set
              - The <header> block below is kept for parity but will only render on /blog route in full setup
              TODO: If WordPress is configured with a static front page and separate blog page,
              create app/blog/page.tsx for the blog index with this header visible
            */}

            {/* MIGRATED: Loop posts.map() replacing while(have_posts()) : the_post() */}
            <div className="posts-list">
              {posts.map((post) => (
                // MIGRATED: get_template_part('template-parts/content', get_post_type())
                // replaced by PostCard component — handles post, page, and CPT rendering
                <PostCard key={post.id} post={post} />
              ))}
            </div>

            {/* MIGRATED: the_posts_navigation() replaced by PostsNavigation component */}
            <PostsNavigation currentPage={currentPage} totalPages={totalPages} />
          </>
        ) : (
          // MIGRATED: get_template_part('template-parts/content', 'none') replaced by NoPostsFound
          <NoPostsFound />
        )}

      </main>
      {/* MIGRATED: get_sidebar() removed from this template */}
      {/* TODO: Add <Sidebar /> component here if a sidebar layout is needed */}
    </div>
  );
}