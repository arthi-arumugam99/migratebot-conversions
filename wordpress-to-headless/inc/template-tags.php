// MIGRATED: inc/template-tags.php -> components/PostMeta.tsx
// This file converts WordPress template tag functions (_s_posted_on, _s_posted_by,
// _s_entry_footer, _s_post_thumbnail) into composable React components.
// PHP template tags that echo HTML are replaced with TSX components returning JSX.
// esc_html(), esc_attr(), esc_url(), wp_kses_post() removed -- JSX auto-escapes text,
// dangerouslySetInnerHTML is not used here so no sanitization wrapper needed.

import Link from 'next/link';
import Image from 'next/image';

// TODO: Manual review needed -- ensure WordPress REST API responses include _embedded data
// by appending ?_embed to all post fetch requests so featured media and author data are available.

// ---------------------------------------------------------------------------
// Shared TypeScript types for WP REST API response shapes
// ---------------------------------------------------------------------------

export interface WPTerm {
  id: number;
  name: string;
  slug: string;
  link: string;
}

export interface WPAuthorEmbedded {
  id: number;
  name: string;
  link: string;
  avatar_urls?: Record<string, string>;
}

export interface WPFeaturedMediaEmbedded {
  id: number;
  source_url: string;
  alt_text: string;
  media_details?: {
    width: number;
    height: number;
    sizes?: Record<
      string,
      { source_url: string; width: number; height: number }
    >;
  };
}

export interface WPPostEmbedded {
  author?: WPAuthorEmbedded[];
  'wp:featuredmedia'?: WPFeaturedMediaEmbedded[];
  'wp:term'?: WPTerm[][];
}

export interface WPPost {
  id: number;
  slug: string;
  type: string;
  date: string;           // ISO 8601
  modified: string;       // ISO 8601
  link: string;
  title: { rendered: string };
  content: { rendered: string; protected: boolean };
  excerpt: { rendered: string; protected: boolean };
  author: number;
  featured_media: number;
  comment_status: 'open' | 'closed';
  password?: string;
  comments_number?: number; // not in REST by default; included here for typing
  _embedded?: WPPostEmbedded;
}

// ---------------------------------------------------------------------------
// Utility helpers
// ---------------------------------------------------------------------------

/**
 * Format a date string using the same default locale WordPress uses (site locale).
 * Mirrors get_the_date() with no format arg (uses WordPress date format option).
 * MIGRATED: get_the_date() / get_the_modified_date() -> Intl.DateTimeFormat
 */
function formatDate(isoString: string): string {
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }).format(new Date(isoString));
}

/**
 * Return the W3C (ISO 8601) representation used in datetime attributes.
 * MIGRATED: get_the_date( DATE_W3C ) -> post.date (already ISO 8601 from REST API)
 */
function w3cDate(isoString: string): string {
  return new Date(isoString).toISOString();
}

// ---------------------------------------------------------------------------
// PostedOn component
// MIGRATED: _s_posted_on() PHP function -> <PostedOn /> React component
// PHP used get_the_date(), get_the_modified_date(), get_permalink().
// React reads post.date, post.modified from REST API response.
// The "published updated" class logic is preserved:
//   if date !== modified, render both <time> elements (published + updated).
// ---------------------------------------------------------------------------

interface PostedOnProps {
  post: Pick<WPPost, 'date' | 'modified' | 'slug'>;
}

export function PostedOn({ post }: PostedOnProps) {
  const publishedW3C = w3cDate(post.date);
  const publishedHuman = formatDate(post.date);
  const modifiedW3C = w3cDate(post.modified);
  const modifiedHuman = formatDate(post.modified);

  // MIGRATED: PHP compared get_the_time('U') !== get_the_modified_time('U')
  // Here we compare ISO strings; timestamps differ when the post was updated after publish.
  const wasUpdated = post.date !== post.modified;

  // MIGRATED: get_permalink() -> Next.js href built from slug
  const permalink = `/posts/${post.slug}`;

  return (
    <span className="posted-on">
      {/* translators: %s: post date — "Posted on <date>" */}
      {'Posted on '}
      <a href={permalink} rel="bookmark">
        {/* MIGRATED: esc_attr() on datetime -> JSX attribute auto-escaped */}
        <time
          className={`entry-date published${wasUpdated ? '' : ' updated'}`}
          dateTime={publishedW3C}
        >
          {/* MIGRATED: esc_html( get_the_date() ) -> {publishedHuman} */}
          {publishedHuman}
        </time>
        {wasUpdated && (
          <time className="updated" dateTime={modifiedW3C}>
            {modifiedHuman}
          </time>
        )}
      </a>
    </span>
  );
}

// ---------------------------------------------------------------------------
// PostedBy component
// MIGRATED: _s_posted_by() PHP function -> <PostedBy /> React component
// PHP used get_the_author(), get_author_posts_url(), get_the_author_meta('ID').
// React reads author data from post._embedded.author[0] (requires ?_embed on fetch).
// ---------------------------------------------------------------------------

interface PostedByProps {
  post: Pick<WPPost, '_embedded'>;
}

export function PostedBy({ post }: PostedByProps) {
  // MIGRATED: get_the_author() -> post._embedded?.author?.[0]?.name
  const author = post._embedded?.author?.[0];

  if (!author) {
    // TODO: Manual review needed -- if author is not embedded, fetch from
    // /wp-json/wp/v2/users/{id} using post.author as the ID.
    return null;
  }

  // MIGRATED: get_author_posts_url() -> /author/{slug} route
  // WordPress REST API embeds the author's `link` field which is the author archive URL
  // on the WP domain. We rewrite it to the headless frontend author archive route.
  const authorArchiveHref = `/author/${author.id}`;

  return (
    <span className="byline">
      {/* translators: %s: post author — "by <author>" */}
      {' by '}
      <span className="author vcard">
        {/* MIGRATED: esc_url( get_author_posts_url() ) -> href={authorArchiveHref} */}
        {/* MIGRATED: esc_html( get_the_author() ) -> {author.name} */}
        <a className="url fn n" href={authorArchiveHref}>
          {author.name}
        </a>
      </span>
    </span>
  );
}

// ---------------------------------------------------------------------------
// EntryFooter component
// MIGRATED: _s_entry_footer() PHP function -> <EntryFooter /> React component
// PHP used get_the_category_list(), get_the_tag_list(), comments_popup_link(),
// edit_post_link(), is_single(), post_password_required(), comments_open(),
// get_comments_number(), get_post_type().
//
// React:
//  - Categories / tags come from post._embedded['wp:term'] (requires ?_embed)
//  - is_single() -> isSingle prop (caller knows from routing context)
//  - comments_open() -> post.comment_status === 'open'
//  - post_password_required() -> post.password presence check
//  - get_post_type() -> post.type
//  - edit_post_link() -> omitted for public frontend (admin link not applicable headlessly)
//    // TODO: Manual review needed -- edit_post_link() was for logged-in admins.
//    // Implement an Edit button conditionally shown to authenticated users via session check.
// ---------------------------------------------------------------------------

interface EntryFooterProps {
  post: Pick<WPPost, 'id' | 'slug' | 'type' | 'comment_status' | '_embedded'> & {
    password?: string;
    comments_number?: number;
  };
  /** MIGRATED: is_single() -> pass true when rendering a single post view */
  isSingle?: boolean;
}

export function EntryFooter({ post, isSingle = false }: EntryFooterProps) {
  // MIGRATED: get_post_type() === 'post' check -> post.type === 'post'
  const isPost = post.type === 'post';

  // MIGRATED: get_the_category_list() -> categories from _embedded wp:term[0]
  // WordPress REST API puts categories in wp:term[0] and tags in wp:term[1]
  const categories: WPTerm[] = post._embedded?.['wp:term']?.[0] ?? [];
  const tags: WPTerm[] = post._embedded?.['wp:term']?.[1] ?? [];

  // MIGRATED: post_password_required() -> check post.password field
  const isPasswordProtected = Boolean(post.password);

  // MIGRATED: comments_open() -> post.comment_status === 'open'
  const commentsOpen = post.comment_status === 'open';

  // MIGRATED: get_comments_number() -> post.comments_number (not in default REST response)
  // TODO: Manual review needed -- comments_number is not included in the default REST API
  // response. Either register it via register_rest_field() in WordPress or fetch comment
  // count separately from /wp-json/wp/v2/comments?post={id}&per_page=1 and read X-WP-Total.
  const hasComments =
    typeof post.comments_number === 'number' && post.comments_number > 0;

  return (
    <div className="entry-footer">
      {/* MIGRATED: categories and tags only shown for 'post' type, not pages */}
      {isPost && categories.length > 0 && (
        <span className="cat-links">
          {/* translators: 1: list of categories — "Posted in <cats>" */}
          {'Posted in '}
          {categories.map((cat, index) => (
            <span key={cat.id}>
              {/* MIGRATED: get_the_category_list() links -> <Link> to category archive */}
              <Link href={`/category/${cat.slug}`}>{cat.name}</Link>
              {/* MIGRATED: esc_html__(', ', '_s') separator */}
              {index < categories.length - 1 ? ', ' : ''}
            </span>
          ))}
        </span>
      )}

      {isPost && tags.length > 0 && (
        <span className="tags-links">
          {/* translators: 1: list of tags — "Tagged <tags>" */}
          {'Tagged '}
          {tags.map((tag, index) => (
            <span key={tag.id}>
              {/* MIGRATED: get_the_tag_list() links -> <Link> to tag archive */}
              <Link href={`/tag/${tag.slug}`}>{tag.name}</Link>
              {index < tags.length - 1 ? ', ' : ''}
            </span>
          ))}
        </span>
      )}

      {/* MIGRATED: comments_popup_link() -> simple link to #{post.slug}-comments anchor */}
      {/* PHP condition: !is_single() && !post_password_required() && (comments_open() || get_comments_number()) */}
      {!isSingle && !isPasswordProtected && (commentsOpen || hasComments) && (
        <span className="comments-link">
          {/* MIGRATED: comments_popup_link() label -> static link with screen-reader text */}
          <a href={`/posts/${post.slug}#comments`}>
            Leave a Comment
            <span className="screen-reader-text"> on {post.id}</span>
          </a>
        </span>
      )}

      {/* MIGRATED: edit_post_link() -> omitted; admin editing handled via WP dashboard */}
      {/* TODO: Manual review needed -- add an Edit link for authenticated admin users.
           Use session/JWT role check: if (session?.user?.roles?.includes('administrator'))
           render <a href={`${WP_ADMIN_URL}/post.php?post=${post.id}&action=edit`}>Edit</a> */}
    </div>
  );
}

// ---------------------------------------------------------------------------
// PostThumbnail component
// MIGRATED: _s_post_thumbnail() PHP function -> <PostThumbnail /> React component
// PHP used has_post_thumbnail(), is_singular(), the_post_thumbnail(), the_permalink(),
// the_title_attribute(), post_password_required(), is_attachment().
//
// React:
//  - has_post_thumbnail() -> post.featured_media !== 0 && _embedded['wp:featuredmedia'] exists
//  - is_singular() -> isSingular prop
//  - is_attachment() -> post.type === 'attachment' check
//  - post_password_required() -> post.password presence
//  - the_post_thumbnail() -> <Image> with URL from _embedded['wp:featuredmedia'][0]
//  - the_title_attribute() -> alt text from media alt_text or post title
//  - the_permalink() -> /posts/{slug}
//
// TODO: Manual review needed -- add WordPress media domain to next.config.js:
//   images: { remotePatterns: [{ protocol: 'https', hostname: 'your-wp-domain.com' }] }
// ---------------------------------------------------------------------------

interface PostThumbnailProps {
  post: Pick<WPPost, 'id' | 'slug' | 'type' | 'featured_media' | '_embedded'> & {
    password?: string;
    title: { rendered: string };
  };
  /** MIGRATED: is_singular() -> pass true when rendering a single/singular view */
  isSingular?: boolean;
}

export function PostThumbnail({ post, isSingular = false }: PostThumbnailProps) {
  // MIGRATED: post_password_required() -> check post.password
  const isPasswordProtected = Boolean(post.password);

  // MIGRATED: is_attachment() -> post.type === 'attachment'
  const isAttachment = post.type === 'attachment';

  // MIGRATED: has_post_thumbnail() -> post.featured_media !== 0
  const hasThumbnail =
    post.featured_media !== 0 &&
    Boolean(post._embedded?.['wp:featuredmedia']?.[0]);

  // Early return mirrors PHP: if password required, attachment, or no thumbnail -> return
  if (isPasswordProtected || isAttachment || !hasThumbnail) {
    return null;
  }

  const media = post._embedded!['wp:featuredmedia']![0];

  // MIGRATED: width/height from media_details for Next.js Image component
  const width = media.media_details?.width ?? 800;
  const height = media.media_details?.height ?? 600;

  // MIGRATED: the_title_attribute( ['echo' => false] ) -> alt text from media or post title
  // Use media alt_text first; fall back to post title stripped of HTML tags
  const altText =
    media.alt_text ||
    post.title.rendered.replace(/<[^>]*>/g, '');

  // MIGRATED: the_permalink() -> /posts/{slug}
  const permalink = `/posts/${post.slug}`;

  if (isSingular) {
    // MIGRATED: is_singular() branch -- wrapped in a div, no link
    return (
      <div className="post-thumbnail">
        {/* MIGRATED: the_post_thumbnail() -> <Image> with WP media URL */}
        <Image
          src={media.source_url}
          alt={altText}
          width={width}
          height={height}
        />
      </div>
    );
  }

  // MIGRATED: non-singular branch -- wrapped in an anchor linking to the post
  return (
    <a
      className="post-thumbnail"
      href={permalink}
      aria-hidden="true"
      tabIndex={-1}
    >
      {/* MIGRATED: the_post_thumbnail('post-thumbnail', ['alt' => ...]) -> <Image> */}
      {/* Use the 'post-thumbnail' size if available; fall back to full source_url */}
      <Image
        src={
          media.media_details?.sizes?.['post-thumbnail']?.source_url ??
          media.source_url
        }
        alt={altText}
        width={
          media.media_details?.sizes?.['post-thumbnail']?.width ?? width
        }
        height={
          media.media_details?.sizes?.['post-thumbnail']?.height ?? height
        }
      />
    </a>
  );
}