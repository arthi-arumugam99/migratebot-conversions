import Link from 'next/link';
import { notFound } from 'next/navigation';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import Sidebar from '@/components/Sidebar';
import Pagination from '@/components/Pagination';

// MIGRATED: TypeScript interfaces for WordPress REST API post response objects
interface WPPost {
  id: number;
  slug: string;
  type: string;
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
  link: string;
  _embedded?: {
    author?: Array<{
      id: number;
      name: string;
      slug: string;
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

interface WPCategory {
  id: number;
  name: string;
  slug: string;
  description: string;
  count: number;
}

interface WPTag {
  id: number;
  name: string;
  slug: string;
  description: string;
  count: number;
}

interface WPAuthor {
  id: number;
  name: string;
  slug: string;
  description: string;
  avatar_urls?: Record<string, string>;
}

// MIGRATED: Archive page props — supports category, tag, author, date, and generic post archives
interface ArchivePageProps {
  searchParams?: {
    page?: string;
    category?: string;
    tag?: string;
    author?: string;
    year?: string;
    month?: string;
  };
}

const API_URL = process.env.NEXT_PUBLIC_WORDPRESS_API_URL || 'https://your-wordpress-site.com';
const PER_PAGE = 10;

// MIGRATED: Replaces WP_Query archive fetch — determines archive type and builds REST API URL
async function fetchArchivePosts(
  page: number,
  filters: {
    categoryId?: number;
    tagId?: number;
    authorId?: number;
    year?: string;
    month?: string;
  }
): Promise<{ posts: WPPost[]; total: number; totalPages: number }> {
  const params = new URLSearchParams({
    per_page: String(PER_PAGE),
    page: String(page),
    orderby: 'date',
    order: 'desc',
    _embed: '1',
    _fields: 'id,slug,type,title,excerpt,date,modified,link,_links,_embedded',
  });

  if (filters.categoryId) {
    params.set('categories', String(filters.categoryId));
  }
  if (filters.tagId) {
    params.set('tags', String(filters.tagId));
  }
  if (filters.authorId) {
    params.set('author', String(filters.authorId));
  }
  // MIGRATED: Date archive — replaces WP_Query date params (year, monthnum)
  if (filters.year && filters.month) {
    const paddedMonth = filters.month.padStart(2, '0');
    const after = `${filters.year}-${paddedMonth}-01T00:00:00`;
    const lastDay = new Date(Number(filters.year), Number(filters.month), 0).getDate();
    const before = `${filters.year}-${paddedMonth}-${lastDay}T23:59:59`;
    params.set('after', after);
    params.set('before', before);
  } else if (filters.year) {
    params.set('after', `${filters.year}-01-01T00:00:00`);
    params.set('before', `${filters.year}-12-31T23:59:59`);
  }

  const response = await fetch(
    `${API_URL}/wp-json/wp/v2/posts?${params.toString()}`,
    {
      next: { revalidate: 60 }, // ISR — revalidate every 60 seconds
    }
  );

  if (!response.ok) {
    return { posts: [], total: 0, totalPages: 0 };
  }

  // MIGRATED: Reads X-WP-Total and X-WP-TotalPages response headers for pagination
  const total = Number(response.headers.get('X-WP-Total') || '0');
  const totalPages = Number(response.headers.get('X-WP-TotalPages') || '1');
  const posts: WPPost[] = await response.json();

  return { posts, total, totalPages };
}

// MIGRATED: Fetch category by slug — replaces get_queried_object() for category archives
async function fetchCategoryBySlug(slug: string): Promise<WPCategory | null> {
  const response = await fetch(
    `${API_URL}/wp-json/wp/v2/categories?slug=${encodeURIComponent(slug)}&_fields=id,name,slug,description,count`,
    { next: { revalidate: 3600 } }
  );
  if (!response.ok) return null;
  const categories: WPCategory[] = await response.json();
  return categories[0] || null;
}

// MIGRATED: Fetch tag by slug — replaces get_queried_object() for tag archives
async function fetchTagBySlug(slug: string): Promise<WPTag | null> {
  const response = await fetch(
    `${API_URL}/wp-json/wp/v2/tags?slug=${encodeURIComponent(slug)}&_fields=id,name,slug,description,count`,
    { next: { revalidate: 3600 } }
  );
  if (!response.ok) return null;
  const tags: WPTag[] = await response.json();
  return tags[0] || null;
}

// MIGRATED: Fetch author by slug — replaces get_queried_object() for author archives
async function fetchAuthorBySlug(slug: string): Promise<WPAuthor | null> {
  const response = await fetch(
    `${API_URL}/wp-json/wp/v2/users?slug=${encodeURIComponent(slug)}&_fields=id,name,slug,description,avatar_urls`,
    { next: { revalidate: 3600 } }
  );
  if (!response.ok) return null;
  const authors: WPAuthor[] = await response.json();
  return authors[0] || null;
}

// MIGRATED: Individual post card — replaces get_template_part('template-parts/content', get_post_type())
function PostCard({ post }: { post: WPPost }) {
  const featuredMedia = post._embedded?.['wp:featuredmedia']?.[0];
  const author = post._embedded?.author?.[0];
  // MIGRATED: Categories from embedded terms — replaces the_category()
  const categories = post._embedded?.['wp:term']?.[0]?.filter(
    (term) => term.taxonomy === 'category'
  ) || [];

  // MIGRATED: Date formatting — replaces the_date('F j, Y')
  const formattedDate = new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }).format(new Date(post.date));

  return (
    <article
      id={`post-${post.id}`}
      className={`post-${post.id} post type-post status-publish format-standard hentry`}
    >
      {/* MIGRATED: Featured image — replaces the_post_thumbnail() */}
      {featuredMedia && (
        <div className="post-thumbnail">
          {/* TODO: Replace with next/image Image component and add WordPress domain to next.config.js images.remotePatterns */}
          <Link href={`/posts/${post.slug}`}>
            <img
              src={featuredMedia.source_url}
              alt={featuredMedia.alt_text || post.title.rendered}
              width={featuredMedia.media_details?.width}
              height={featuredMedia.media_details?.height}
              loading="lazy"
            />
          </Link>
        </div>
      )}

      <header className="entry-header">
        {/* MIGRATED: Post title — replaces the_title() wrapped in permalink */}
        <h2 className="entry-title">
          <Link href={`/posts/${post.slug}`}>
            {/* MIGRATED: Title rendered — replaces esc_html(get_the_title()) */}
            <span dangerouslySetInnerHTML={{ __html: post.title.rendered }} />
          </Link>
        </h2>

        <div className="entry-meta">
          {/* MIGRATED: Post date — replaces the_date() / the_time() */}
          <span className="posted-on">
            <time className="entry-date published" dateTime={post.date}>
              {formattedDate}
            </time>
          </span>

          {/* MIGRATED: Author — replaces the_author_posts_link() */}
          {author && (
            <span className="byline">
              {' '}
              by{' '}
              <Link href={`/author/${author.slug}`} className="author vcard">
                {author.name}
              </Link>
            </span>
          )}

          {/* MIGRATED: Categories — replaces the_category() */}
          {categories.length > 0 && (
            <span className="cat-links">
              {' '}
              in{' '}
              {categories.map((cat, index) => (
                <span key={cat.id}>
                  <Link href={`/category/${cat.slug}`}>{cat.name}</Link>
                  {index < categories.length - 1 ? ', ' : ''}
                </span>
              ))}
            </span>
          )}
        </div>
      </header>

      <div className="entry-summary">
        {/* MIGRATED: Excerpt — replaces the_excerpt(); dangerouslySetInnerHTML needed for rendered HTML */}
        <div dangerouslySetInnerHTML={{ __html: post.excerpt.rendered }} />
      </div>

      <footer className="entry-footer">
        <Link href={`/posts/${post.slug}`} className="more-link">
          Read more<span className="screen-reader-text"> about {post.title.rendered}</span>
        </Link>
      </footer>
    </article>
  );
}

// MIGRATED: Empty state — replaces get_template_part('template-parts/content', 'none')
function NoPostsFound() {
  return (
    <section className="no-results not-found">
      <header className="page-header">
        <h1 className="page-title">Nothing Found</h1>
      </header>
      <div className="page-content">
        <p>
          It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.
        </p>
        {/* TODO: Wire up search form to /search route */}
        <form role="search" method="get" className="search-form" action="/search">
          <label htmlFor="search-field" className="screen-reader-text">
            Search for:
          </label>
          <input
            type="search"
            id="search-field"
            className="search-field"
            placeholder="Search …"
            name="s"
          />
          <button type="submit" className="search-submit">
            Search
          </button>
        </form>
      </div>
    </section>
  );
}

// MIGRATED: Archive header — replaces the_archive_title() and the_archive_description()
function ArchiveHeader({
  title,
  description,
}: {
  title: string;
  description?: string;
}) {
  return (
    <header className="page-header">
      {/* MIGRATED: Archive title — replaces the_archive_title('<h1 class="page-title">', '</h1>') */}
      <h1
        className="page-title"
        dangerouslySetInnerHTML={{ __html: title }}
      />
      {/* MIGRATED: Archive description — replaces the_archive_description('<div class="archive-description">', '</div>') */}
      {description && (
        <div
          className="archive-description"
          dangerouslySetInnerHTML={{ __html: description }}
        />
      )}
    </header>
  );
}

// MIGRATED: Main archive page component — replaces archive.php
// Handles category, tag, author, date, and generic post type archives via searchParams
// Each archive type in WordPress becomes a separate route directory in Next.js:
//   category archives -> app/category/[slug]/page.tsx
//   tag archives      -> app/tag/[slug]/page.tsx
//   author archives   -> app/author/[slug]/page.tsx
//   date archives     -> app/[year]/[month]/page.tsx (optional)
// This component handles the generic post archive (app/posts/page.tsx)
// For category/tag/author specifics, see the respective route files.
export default async function ArchivePage({ searchParams }: ArchivePageProps) {
  const currentPage = Number(searchParams?.page || '1');
  const categorySlug = searchParams?.category;
  const tagSlug = searchParams?.tag;
  const authorSlug = searchParams?.author;
  const year = searchParams?.year;
  const month = searchParams?.month;

  // MIGRATED: Resolve taxonomy/author filters before fetching posts
  let category: WPCategory | null = null;
  let tag: WPTag | null = null;
  let author: WPAuthor | null = null;

  if (categorySlug) {
    category = await fetchCategoryBySlug(categorySlug);
    if (!category) notFound();
  }
  if (tagSlug) {
    tag = await fetchTagBySlug(tagSlug);
    if (!tag) notFound();
  }
  if (authorSlug) {
    author = await fetchAuthorBySlug(authorSlug);
    if (!author) notFound();
  }

  const { posts, totalPages } = await fetchArchivePosts(currentPage, {
    categoryId: category?.id,
    tagId: tag?.id,
    authorId: author?.id,
    year,
    month,
  });

  // MIGRATED: Build archive title and description — replaces the_archive_title() / the_archive_description()
  let archiveTitle = 'Archives';
  let archiveDescription: string | undefined;

  if (category) {
    archiveTitle = `Category: ${category.name}`;
    archiveDescription = category.description || undefined;
  } else if (tag) {
    archiveTitle = `Tag: ${tag.name}`;
    archiveDescription = tag.description || undefined;
  } else if (author) {
    archiveTitle = `Author: ${author.name}`;
    archiveDescription = author.description || undefined;
  } else if (year && month) {
    const date = new Date(Number(year), Number(month) - 1);
    archiveTitle = new Intl.DateTimeFormat('en-US', {
      year: 'numeric',
      month: 'long',
    }).format(date);
  } else if (year) {
    archiveTitle = `Year: ${year}`;
  }

  return (
    <>
      {/* MIGRATED: get_header() replaced by importing Header component in layout.tsx */}
      <Header />

      <div id="primary" className="content-area">
        <main id="main" className="site-main">

          {posts.length > 0 ? (
            <>
              {/* MIGRATED: Archive page header — replaces the_archive_title() / the_archive_description() */}
              <ArchiveHeader title={archiveTitle} description={archiveDescription} />

              {/* MIGRATED: The Loop — replaces while (have_posts()) : the_post(); endwhile; */}
              {posts.map((post) => (
                <PostCard key={post.id} post={post} />
              ))}

              {/* MIGRATED: Pagination — replaces the_posts_navigation() / paginate_links()
                  Reads X-WP-TotalPages from REST API response headers */}
              {totalPages > 1 && (
                <Pagination
                  currentPage={currentPage}
                  totalPages={totalPages}
                />
              )}
            </>
          ) : (
            // MIGRATED: No posts found — replaces get_template_part('template-parts/content', 'none')
            <NoPostsFound />
          )}

        </main>{/* #main */}
      </div>{/* #primary */}

      {/* MIGRATED: get_sidebar() replaced by importing Sidebar component */}
      <Sidebar />

      {/* MIGRATED: get_footer() replaced by importing Footer component in layout.tsx */}
      <Footer />
    </>
  );
}