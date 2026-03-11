import Link from 'next/link';
import Image from 'next/image';

// MIGRATED: WordPress REST API post type definitions
interface WPPost {
  id: number;
  slug: string;
  type: string;
  link: string;
  title: {
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
        sizes?: Record<string, {
          source_url: string;
          width: number;
          height: number;
        }>;
      };
    }>;
    'wp:term'?: Array<Array<{
      id: number;
      name: string;
      slug: string;
      taxonomy: string;
    }>>;
  };
}

interface SearchResultProps {
  post: WPPost;
}

// MIGRATED: _s_posted_on() template tag -> PostedOn React component
function PostedOn({ date }: { date: string }) {
  const formatted = new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }).format(new Date(date));

  return (
    <span className="posted-on">
      {/* MIGRATED: the_time() / get_the_date() -> Intl.DateTimeFormat */}
      <time className="entry-date published" dateTime={date}>
        {formatted}
      </time>
    </span>
  );
}

// MIGRATED: _s_posted_by() template tag -> PostedBy React component
function PostedBy({ author }: { author?: WPPost['_embedded'] extends undefined ? never : NonNullable<WPPost['_embedded']>['author'] extends undefined ? never : NonNullable<NonNullable<WPPost['_embedded']>['author']>[number] }) {
  if (!author) return null;

  return (
    <span className="byline">
      {/* MIGRATED: get_the_author() / get_author_posts_url() -> author from _embedded */}
      <span className="author vcard">
        <Link href={`/author/${author.slug}`} className="url fn n">
          {author.name}
        </Link>
      </span>
    </span>
  );
}

// MIGRATED: _s_post_thumbnail() template tag -> PostThumbnail React component
function PostThumbnail({ post }: { post: WPPost }) {
  // MIGRATED: has_post_thumbnail() -> check featured_media !== 0 and _embedded data
  const featuredMedia = post._embedded?.['wp:featuredmedia']?.[0];

  if (!featuredMedia || post.featured_media === 0) return null;

  const width =
    featuredMedia.media_details?.sizes?.['post-thumbnail']
      ? featuredMedia.media_details.sizes['post-thumbnail'].width
      : featuredMedia.media_details?.width ?? 800;

  const height =
    featuredMedia.media_details?.sizes?.['post-thumbnail']
      ? featuredMedia.media_details.sizes['post-thumbnail'].height
      : featuredMedia.media_details?.height ?? 600;

  const src =
    featuredMedia.media_details?.sizes?.['post-thumbnail']?.source_url ??
    featuredMedia.source_url;

  return (
    // MIGRATED: the_post_thumbnail() / wp_get_attachment_image() -> Next.js Image component
    // TODO: Add WordPress media domain to next.config.js images.remotePatterns configuration
    <div className="post-thumbnail">
      <Link href={`/posts/${post.slug}`} tabIndex={-1} aria-hidden="true">
        <Image
          src={src}
          alt={featuredMedia.alt_text ?? ''}
          width={width}
          height={height}
          className="attachment-post-thumbnail size-post-thumbnail wp-post-image"
        />
      </Link>
    </div>
  );
}

// MIGRATED: _s_entry_footer() template tag -> EntryFooter React component
function EntryFooter({ post }: { post: WPPost }) {
  // MIGRATED: get_the_category_list() / get_the_tag_list() -> _embedded wp:term data
  const categories =
    post._embedded?.['wp:term']
      ?.flat()
      .filter((term) => term.taxonomy === 'category') ?? [];

  const tags =
    post._embedded?.['wp:term']
      ?.flat()
      .filter((term) => term.taxonomy === 'post_tag') ?? [];

  const hasMeta = categories.length > 0 || tags.length > 0;

  if (!hasMeta) return null;

  return (
    <>
      {categories.length > 0 && (
        // MIGRATED: the_category() -> categories from _embedded terms
        <span className="cat-links">
          {categories.map((cat, index) => (
            <span key={cat.id}>
              <Link href={`/category/${cat.slug}`}>{cat.name}</Link>
              {index < categories.length - 1 && ', '}
            </span>
          ))}
        </span>
      )}
      {tags.length > 0 && (
        // MIGRATED: the_tags() -> tags from _embedded terms
        <span className="tags-links">
          {tags.map((tag, index) => (
            <span key={tag.id}>
              <Link href={`/tag/${tag.slug}`}>{tag.name}</Link>
              {index < tags.length - 1 && ', '}
            </span>
          ))}
        </span>
      )}
    </>
  );
}

// MIGRATED: template-parts/content-search.php -> SearchResult React component
// Replaces: <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
// Replaces: the_title(), the_excerpt(), _s_posted_on(), _s_posted_by(),
//           _s_post_thumbnail(), _s_entry_footer()
export default function SearchResult({ post }: SearchResultProps) {
  const author = post._embedded?.author?.[0];

  // MIGRATED: is_post_type('post') check -> post.type from REST API response
  const isPost = post.type === 'post';

  // MIGRATED: post_class() -> static class string with post type and id
  const articleClasses = [
    'post',
    `post-${post.id}`,
    post.type,
    'type-' + post.type,
    'status-publish',
    post.featured_media ? 'has-post-thumbnail' : '',
    'hentry',
  ]
    .filter(Boolean)
    .join(' ');

  return (
    // MIGRATED: <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    // -> <article> with computed id and className
    <article id={`post-${post.id}`} className={articleClasses}>
      <header className="entry-header">
        {/* MIGRATED: the_title( '<h2 ...><a href="' . get_permalink() . '">', '</a></h2>' )
            -> Next.js Link wrapping title.rendered */}
        <h2 className="entry-title">
          <Link href={`/posts/${post.slug}`} rel="bookmark">
            {/* MIGRATED: esc_html( get_the_title() ) -> JSX auto-escapes text content */}
            {post.title.rendered}
          </Link>
        </h2>

        {/* MIGRATED: if ( 'post' === get_post_type() ) -> post.type === 'post' */}
        {isPost && (
          <div className="entry-meta">
            {/* MIGRATED: _s_posted_on() -> <PostedOn> component */}
            <PostedOn date={post.date} />
            {/* MIGRATED: _s_posted_by() -> <PostedBy> component using _embedded author */}
            {author && <PostedBy author={author} />}
          </div>
        )}
      </header>

      {/* MIGRATED: _s_post_thumbnail() -> <PostThumbnail> using Next.js Image */}
      <PostThumbnail post={post} />

      <div className="entry-summary">
        {/* MIGRATED: the_excerpt() -> dangerouslySetInnerHTML with excerpt.rendered
            WordPress REST API returns pre-rendered excerpt HTML including <p> tags */}
        <div
          dangerouslySetInnerHTML={{ __html: post.excerpt.rendered }}
        />
      </div>

      <footer className="entry-footer">
        {/* MIGRATED: _s_entry_footer() -> <EntryFooter> component */}
        <EntryFooter post={post} />
      </footer>
    </article>
  );
}