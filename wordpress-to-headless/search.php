// MIGRATED: search.php -> app/search/page.tsx
// Converts WordPress search template to Next.js App Router page
// Uses WP REST API /wp-json/wp/v2/posts?search= for data fetching

import Link from 'next/link';
import type { Metadata } from 'next';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import Sidebar from '@/components/Sidebar';

// MIGRATED: WordPress REST API response types
interface WPPost {
  id: number;
  slug: string;
  link: string;
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
  author: number;
  featured_media: number;
  categories: number[];
  tags: number[];
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

interface SearchPageProps {
  searchParams: {
    q?: string;
    page?: string;
  };
}

const WP_API_URL = process.env.NEXT_PUBLIC_WP_API_URL || 'https://your-wordpress-site.com/wp-json/wp/v2';
const POSTS_PER_PAGE = 10;

// MIGRATED: generateMetadata replaces wp_head() / wp_title() for search pages
export async function generateMetadata({ searchParams }: SearchPageProps): Promise<Metadata> {
  // MIGRATED: get_search_query() -> read from URL search params
  const query = searchParams.q || '';

  return {
    title: query ? `Search Results for: ${query}` : 'Search',
    description: query ? `Search results for "${query}"` : 'Search our site',
    robots: {
      index: false, // MIGRATED: Search result pages should not be indexed
      follow: true,
    },
  };
}

// MIGRATED: Replaces WP_Query with REST API fetch for search results
async function fetchSearchResults(
  query: string,
  page: number = 1
): Promise<{ posts: WPPost[]; totalPages: number; total: number }> {
  if (!query.trim()) {
    return { posts: [], totalPages: 0, total: 0 };
  }

  try {
    const params = new URLSearchParams({
      search: query,
      per_page: String(POSTS_PER_PAGE),
      page: String(page),
      _embed: '1',
      // MIGRATED: Limit fields for performance using _fields param
      // TODO: Adjust _fields based on what content-search template part was rendering
    });

    const response = await fetch(`${WP_API_URL}/posts?${params.toString()}`, {
      next: {
        // MIGRATED: ISR revalidation — search results revalidate every 60 seconds
        revalidate: 60,
      },
    });

    if (!response.ok) {
      if (response.status === 400) {
        // MIGRATED: WP REST API returns 400 for empty/invalid search queries
        return { posts: [], totalPages: 0, total: 0 };
      }
      throw new Error(`Failed to fetch search results: ${response.status} ${response.statusText}`);
    }

    // MIGRATED: Read X-WP-Total and X-WP-TotalPages headers for pagination
    // Replaces the_posts_navigation() / paginate_links()
    const total = parseInt(response.headers.get('X-WP-Total') || '0', 10);
    const totalPages = parseInt(response.headers.get('X-WP-TotalPages') || '0', 10);

    const posts: WPPost[] = await response.json();

    return { posts, totalPages, total };
  } catch (error) {
    console.error('Error fetching search results:', error);
    return { posts: [], totalPages: 0, total: 0 };
  }
}

// MIGRATED: Replaces get_template_part('template-parts/content', 'search')
// Renders individual search result item
function SearchResultItem({ post }: { post: WPPost }) {
  const featuredMedia = post._embedded?.['wp:featuredmedia']?.[0];
  const author = post._embedded?.author?.[0];
  const categories = post._embedded?.['wp:term']?.[0] ?? [];

  // MIGRATED: the_date('F j, Y') -> Intl.DateTimeFormat
  const formattedDate = new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }).format(new Date(post.date));

  return (
    <article id={`post-${post.id}`} className="post search-result">
      {/* MIGRATED: Conditional thumbnail — has_post_thumbnail() -> check featured_media */}
      {featuredMedia && post.featured_media !== 0 && (
        <div className="post-thumbnail">
          {/* TODO: Replace with next/image Image component for optimization */}
          {/* TODO: Add WordPress media domain to next.config.js images configuration */}
          <Link href={`/posts/${post.slug}`}>
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img
              src={featuredMedia.media_details?.sizes?.medium?.source_url || featuredMedia.source_url}
              alt={featuredMedia.alt_text || post.title.rendered}
              width={featuredMedia.media_details?.sizes?.medium?.width || featuredMedia.media_details?.width}
              height={featuredMedia.media_details?.sizes?.medium?.height || featuredMedia.media_details?.height}
            />
          </Link>
        </div>
      )}

      <div className="entry-header">
        {/* MIGRATED: the_title() -> {post.title.rendered} wrapped in Link */}
        <h2 className="entry-title">
          {/* MIGRATED: the_permalink() -> Next.js <Link href={`/posts/${post.slug}`}> */}
          <Link
            href={`/posts/${post.slug}`}
            // MIGRATED: esc_html(get_the_title()) -> JSX auto-escapes text
            dangerouslySetInnerHTML={{ __html: post.title.rendered }}
          />
        </h2>

        <div className="entry-meta">
          {/* MIGRATED: the_date() -> formatted date string */}
          <span className="posted-on">
            <time dateTime={post.date}>{formattedDate}</time>
          </span>

          {/* MIGRATED: the_author() -> author from _embedded data */}
          {author && (
            <span className="byline">
              {' '}
              by{' '}
              <Link href={`/author/${author.id}`} className="author-link">
                {author.name}
              </Link>
            </span>
          )}

          {/* MIGRATED: the_category() -> categories from _embedded wp:term[0] */}
          {categories.length > 0 && (
            <span className="cat-links">
              {' '}
              in{' '}
              {categories.map((cat, index) => (
                <span key={cat.id}>
                  <Link href={`/category/${cat.slug}`}>{cat.name}</Link>
                  {index < categories.length - 1 && ', '}
                </span>
              ))}
            </span>
          )}
        </div>
      </div>

      <div className="entry-summary">
        {/* MIGRATED: the_excerpt() -> dangerouslySetInnerHTML with excerpt.rendered */}
        {/* WordPress returns rendered HTML for excerpts including <p> tags */}
        <div dangerouslySetInnerHTML={{ __html: post.excerpt.rendered }} />
      </div>

      <div className="entry-footer">
        {/* MIGRATED: read_more link — was typically in content-search.php template part */}
        <Link href={`/posts/${post.slug}`} className="more-link">
          Read More<span className="screen-reader-text"> about {post.title.rendered}</span>
        </Link>
      </div>
    </article>
  );
}

// MIGRATED: Replaces the_posts_navigation() / paginate_links()
// Renders pagination links for search results
function SearchPagination({
  currentPage,
  totalPages,
  query,
}: {
  currentPage: number;
  totalPages: number;
  query: string;
}) {
  if (totalPages <= 1) return null;

  const buildPageUrl = (page: number) => {
    const params = new URLSearchParams({ q: query });
    if (page > 1) params.set('page', String(page));
    return `/search?${params.toString()}`;
  };

  return (
    <nav className="navigation posts-navigation" aria-label="Posts navigation">
      <h2 className="screen-reader-text">Posts navigation</h2>
      <div className="nav-links">
        {/* MIGRATED: Previous page link */}
        {currentPage > 1 && (
          <Link href={buildPageUrl(currentPage - 1)} className="prev page-numbers">
            &laquo; Previous
          </Link>
        )}

        {/* MIGRATED: Page number links — mid_size equivalent */}
        {Array.from({ length: totalPages }, (_, i) => i + 1)
          .filter(
            (page) =>
              page === 1 ||
              page === totalPages ||
              (page >= currentPage - 2 && page <= currentPage + 2)
          )
          .reduce<Array<number | '...'>>((acc, page, index, filtered) => {
            if (index > 0 && page - (filtered[index - 1] as number) > 1) {
              acc.push('...');
            }
            acc.push(page);
            return acc;
          }, [])
          .map((item, index) =>
            item === '...' ? (
              <span key={`ellipsis-${index}`} className="page-numbers dots">
                &hellip;
              </span>
            ) : (
              <Link
                key={item}
                href={buildPageUrl(item as number)}
                className={`page-numbers${item === currentPage ? ' current' : ''}`}
                aria-current={item === currentPage ? 'page' : undefined}
              >
                {item}
              </Link>
            )
          )}

        {/* MIGRATED: Next page link */}
        {currentPage < totalPages && (
          <Link href={buildPageUrl(currentPage + 1)} className="next page-numbers">
            Next &raquo;
          </Link>
        )}
      </div>
    </nav>
  );
}

// MIGRATED: Replaces get_template_part('template-parts/content', 'none')
// Renders the "no results found" message
function NoResults({ query }: { query: string }) {
  return (
    <section className="no-results not-found">
      <header className="page-header">
        <h1 className="page-title">Nothing Found</h1>
      </header>
      <div className="page-content">
        {query ? (
          <>
            <p>
              Sorry, but nothing matched your search terms for &ldquo;{query}&rdquo;. Please try
              again with some different keywords.
            </p>
            {/* MIGRATED: Inline search form for no-results state */}
            <SearchForm defaultValue="" />
          </>
        ) : (
          <>
            <p>
              It seems we can&apos;t find what you&apos;re looking for. Perhaps searching can help.
            </p>
            <SearchForm defaultValue="" />
          </>
        )}
      </div>
    </section>
  );
}

// MIGRATED: Search form component — reusable search widget
// Replaces get_search_form() WordPress function
function SearchForm({ defaultValue }: { defaultValue: string }) {
  return (
    <form
      role="search"
      method="get"
      className="search-form"
      action="/search"
    >
      <label htmlFor="search-field" className="screen-reader-text">
        Search for:
      </label>
      <input
        type="search"
        id="search-field"
        className="search-field"
        placeholder="Search &hellip;"
        name="q"
        defaultValue={defaultValue}
      />
      <button type="submit" className="search-submit">
        Search
      </button>
    </form>
  );
}

// MIGRATED: Main search page component
// Replaces search.php template
// is_search() conditional -> this file IS the search route (app/search/page.tsx)
export default async function SearchPage({ searchParams }: SearchPageProps) {
  // MIGRATED: get_search_query() -> read from URL search params
  const query = searchParams.q || '';
  // MIGRATED: Pagination — reads ?page= from URL search params
  const currentPage = parseInt(searchParams.page || '1', 10);

  // MIGRATED: WP_Query(['s' => $search_term]) -> fetch from REST API
  const { posts, totalPages, total } = await fetchSearchResults(query, currentPage);

  const hasResults = posts.length > 0;

  return (
    <>
      {/* MIGRATED: get_header() -> Header component imported from components/Header.tsx */}
      <Header />

      {/* MIGRATED: <section id="primary" class="content-area"> */}
      <section id="primary" className="content-area">
        <main id="main" className="site-main">

          {/* MIGRATED: if (have_posts()) conditional -> check posts array length */}
          {hasResults ? (
            <>
              {/* MIGRATED: .page-header with search query display */}
              {/* printf(esc_html__('Search Results for: %s'), get_search_query()) */}
              <header className="page-header">
                <h1 className="page-title">
                  {/* MIGRATED: esc_html__() + get_search_query() -> JSX with auto-escaping */}
                  Search Results for:{' '}
                  <span>{query}</span>
                </h1>
                {/* MIGRATED: Additional context showing result count */}
                {total > 0 && (
                  <p className="search-result-count">
                    Found {total} result{total !== 1 ? 's' : ''} for &ldquo;{query}&rdquo;
                  </p>
                )}
              </header>

              {/* MIGRATED: The Loop -> posts.map() with SearchResultItem component */}
              {/* while (have_posts()) : the_post(); get_template_part(...) endwhile; */}
              {posts.map((post) => (
                <SearchResultItem key={post.id} post={post} />
              ))}

              {/* MIGRATED: the_posts_navigation() -> SearchPagination component */}
              <SearchPagination
                currentPage={currentPage}
                totalPages={totalPages}
                query={query}
              />
            </>
          ) : (
            // MIGRATED: else -> get_template_part('template-parts/content', 'none')
            <NoResults query={query} />
          )}

        </main>
      </section>

      {/* MIGRATED: get_sidebar() -> Sidebar component */}
      <Sidebar />

      {/* MIGRATED: get_footer() -> Footer component */}
      {/* wp_footer() is removed — Next.js handles scripts automatically */}
      <Footer />
    </>
  );
}