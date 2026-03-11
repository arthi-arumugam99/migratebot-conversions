import Link from 'next/link';
import Image from 'next/image';

// MIGRATED: WordPress REST API post type definitions
export interface WPPost {
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
  featured_media: number;
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
      link: string;
    }>>;
  };
}

// MIGRATED: Props replacing PHP template part context — isSingular mirrors is_singular()
export interface PostCardProps {
  post: WPPost;
  isSingular?: boolean;
}

// MIGRATED: _s_posted_on() helper — formats post date like the_date() / the_time()
function PostedOn({ date, modified }: { date: string; modified: string }) {
  const publishedDate = new Date(date);
  const modifiedDate = new Date(modified);

  const formattedPublished = new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }).format(publishedDate);

  const formattedModified = new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }).format(modifiedDate);

  // MIGRATED: Replaces _s_posted_on() output with semantic <time> elements
  return (
    <span className="posted-on">
      {date !== modified && (
        <span className="updated">
          <time className="entry-date updated" dateTime={modifiedDate.toISOString()}>
            {formattedModified}
          </time>
        </span>
      )}
      <time
        className={`entry-date published${date !== modified ? ' screen-reader-text' : ''}`}
        dateTime={publishedDate.toISOString()}
      >
        {formattedPublished}
      </time>
    </span>
  );
}

// MIGRATED: _s_posted_by() helper — renders author byline using _embedded author data
function PostedBy({ post }: { post: WPPost }) {
  const author = post._embedded?.author?.[0];

  if (!author) return null;

  // MIGRATED: author link uses Next.js routing pattern for author archives
  return (
    <span className="byline">
      {' '}
      <span className="author vcard">
        <Link className="url fn n" href={`/author/${author.slug}`}>
          {author.name}
        </Link>
      </span>
    </span>
  );
}

// MIGRATED: _s_post_thumbnail() helper — replaces the_post_thumbnail() with Next.js Image
function PostThumbnail({ post, isSingular }: { post: WPPost; isSingular: boolean }) {
  const featuredMedia = post._embedded?.['wp:featuredmedia']?.[0];

  if (!featuredMedia || !featuredMedia.source_url) return null;

  const width = featuredMedia.media_details?.width ?? 1200;
  const height = featuredMedia.media_details?.height ?? 628;
  const altText = featuredMedia.alt_text || post.title.rendered;

  // MIGRATED: Wraps thumbnail in Link for archive/listing views, plain image for singular
  // TODO: Add WordPress media domain to next.config.js images.remotePatterns configuration
  return (
    <div className="post-thumbnail">
      {isSingular ? (
        <Image
          src={featuredMedia.source_url}
          alt={altText}
          width={width}
          height={height}
          priority
        />
      ) : (
        <Link href={`/posts/${post.slug}`} tabIndex={-1}>
          <Image
            src={featuredMedia.source_url}
            alt={altText}
            width={width}
            height={height}
          />
        </Link>
      )}
    </div>
  );
}

// MIGRATED: _s_entry_footer() helper — renders categories and tags from _embedded terms
function EntryFooter({ post }: { post: WPPost }) {
  // MIGRATED: _embedded['wp:term'][0] = categories, [1] = tags (mirrors get_the_category / get_the_tags)
  const categories = post._embedded?.['wp:term']?.[0]?.filter(
    (term) => term.taxonomy === 'category'
  ) ?? [];
  const tags = post._embedded?.['wp:term']?.[1]?.filter(
    (term) => term.taxonomy === 'post_tag'
  ) ?? [];

  const hasCategories = categories.length > 0;
  const hasTags = tags.length > 0;

  if (!hasCategories && !hasTags) return null;

  return (
    <>
      {hasCategories && (
        // MIGRATED: Replaces the_category() output
        <span className="cat-links">
          {'Posted in '}
          {categories.map((cat, index) => (
            <span key={cat.id}>
              <Link href={`/category/${cat.slug}`} rel="category tag">
                {cat.name}
              </Link>
              {index < categories.length - 1 && ', '}
            </span>
          ))}
        </span>
      )}
      {hasTags && (
        // MIGRATED: Replaces the_tags() output
        <span className="tags-links">
          {hasCategories && ' '}
          {'Tagged '}
          {tags.map((tag, index) => (
            <span key={tag.id}>
              <Link href={`/tag/${tag.slug}`} rel="tag">
                {tag.name}
              </Link>
              {index < tags.length - 1 && ', '}
            </span>
          ))}
        </span>
      )}
    </>
  );
}

// MIGRATED: Main template part component — replaces template-parts/content.php
// get_template_part('template-parts/content') -> import PostCard from '@/components/PostCard'
// isSingular prop replaces is_singular() conditional tag; file-based routing handles most cases
export default function PostCard({ post, isSingular = false }: PostCardProps) {
  // MIGRATED: post_class() dynamic classes — simplified; extend as needed for full parity
  const postClasses = [
    'post',
    `post-${post.id}`,
    post.type,
    'type-' + post.type,
    'status-publish',
    post.featured_media ? 'has-post-thumbnail' : 'no-post-thumbnail',
    'hentry',
  ].join(' ');

  return (
    // MIGRATED: id="post-<?php the_ID(); ?>" -> id={`post-${post.id}`}
    // post_class() -> dynamic className string above
    <article id={`post-${post.id}`} className={postClasses}>
      <header className="entry-header">
        {/* MIGRATED: is_singular() ? h1 : h2 with link — isSingular prop replaces PHP conditional tag */}
        {isSingular ? (
          <h1
            className="entry-title"
            // MIGRATED: esc_html() not needed — JSX auto-escapes text content
            dangerouslySetInnerHTML={{ __html: post.title.rendered }}
          />
        ) : (
          <h2 className="entry-title">
            {/* MIGRATED: esc_url(get_permalink()) -> Next.js Link with slug-based href */}
            <Link href={`/posts/${post.slug}`} rel="bookmark">
              {/* MIGRATED: esc_html() not needed — JSX auto-escapes text content */}
              <span dangerouslySetInnerHTML={{ __html: post.title.rendered }} />
            </Link>
          </h2>
        )}

        {/* MIGRATED: if ('post' === get_post_type()) — check post.type from REST response */}
        {post.type === 'post' && (
          <div className="entry-meta">
            {/* MIGRATED: _s_posted_on() -> <PostedOn /> component */}
            <PostedOn date={post.date} modified={post.modified} />
            {/* MIGRATED: _s_posted_by() -> <PostedBy /> component */}
            <PostedBy post={post} />
          </div>
        )}
      </header>

      {/* MIGRATED: _s_post_thumbnail() -> <PostThumbnail /> component using Next.js Image */}
      <PostThumbnail post={post} isSingular={isSingular} />

      <div className="entry-content">
        {/* MIGRATED: the_content() with "Continue reading" more link ->
            dangerouslySetInnerHTML for singular; excerpt + read more link for archive.
            WordPress REST API renders the <!--more--> tag server-side when isSingular is true.
            For archive/listing views the excerpt is used instead with an explicit read-more link. */}
        {isSingular ? (
          <div
            // MIGRATED: wp_kses_post() not needed here — content comes from trusted WP backend.
            // TODO: Manual review needed — if user-generated content is possible, add DOMPurify sanitization
            dangerouslySetInnerHTML={{ __html: post.content.rendered }}
          />
        ) : (
          <>
            <div dangerouslySetInnerHTML={{ __html: post.excerpt.rendered }} />
            {/* MIGRATED: the_content('Continue reading...') more link for archive/listing */}
            <Link href={`/posts/${post.slug}`} className="more-link">
              Continue reading
              {/* MIGRATED: screen-reader-text span preserved for accessibility parity */}
              <span className="screen-reader-text"> &ldquo;
                <span dangerouslySetInnerHTML={{ __html: post.title.rendered }} />
              &rdquo;</span>
            </Link>
          </>
        )}
        {/* TODO: Manual review needed — wp_link_pages() multi-page post pagination
            requires fetching paginated content; not supported by default REST API response.
            Consider using WPGraphQL or a custom endpoint if paginated posts are used. */}
      </div>

      <footer className="entry-footer">
        {/* MIGRATED: _s_entry_footer() -> <EntryFooter /> component */}
        <EntryFooter post={post} />
      </footer>
    </article>
  );
}