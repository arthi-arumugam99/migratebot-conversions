// MIGRATED: sidebar.php -> components/Sidebar.tsx
// Converts dynamic_sidebar('sidebar-1') to API-fetched React component
// is_active_sidebar() check replaced by conditional rendering based on widget data

import Link from 'next/link';
import Image from 'next/image';

// TypeScript types for WordPress REST API widget responses
interface WPCategory {
  id: number;
  name: string;
  slug: string;
  count: number;
  link: string;
}

interface WPTag {
  id: number;
  name: string;
  slug: string;
  count: number;
  link: string;
}

interface WPPost {
  id: number;
  title: {
    rendered: string;
  };
  slug: string;
  date: string;
  excerpt: {
    rendered: string;
  };
  featured_media: number;
  _embedded?: {
    'wp:featuredmedia'?: Array<{
      source_url: string;
      alt_text: string;
      media_details: {
        width: number;
        height: number;
        sizes: {
          thumbnail?: {
            source_url: string;
            width: number;
            height: number;
          };
        };
      };
    }>;
  };
}

interface WPSiteInfo {
  name: string;
  description: string;
}

interface SidebarData {
  recentPosts: WPPost[];
  categories: WPCategory[];
  tags: WPTag[];
  siteInfo: WPSiteInfo;
}

const API_URL = process.env.NEXT_PUBLIC_WORDPRESS_API_URL || '';

// MIGRATED: Replaces dynamic_sidebar() fetch logic — each widget area maps to a fetch call
async function getSidebarData(): Promise<SidebarData> {
  const headers = { 'Content-Type': 'application/json' };

  const [recentPostsRes, categoriesRes, tagsRes, siteInfoRes] = await Promise.all([
    // MIGRATED: Replaces WP_Widget_Recent_Posts widget
    fetch(
      `${API_URL}/wp-json/wp/v2/posts?per_page=5&orderby=date&order=desc&_fields=id,title,slug,date,excerpt,featured_media&_embed`,
      {
        headers,
        next: { revalidate: 3600 }, // ISR: revalidate every hour
      }
    ),
    // MIGRATED: Replaces WP_Widget_Categories widget
    fetch(
      `${API_URL}/wp-json/wp/v2/categories?per_page=20&orderby=count&order=desc&_fields=id,name,slug,count`,
      {
        headers,
        next: { revalidate: 3600 },
      }
    ),
    // MIGRATED: Replaces WP_Widget_Tag_Cloud widget
    fetch(
      `${API_URL}/wp-json/wp/v2/tags?per_page=30&orderby=count&order=desc&_fields=id,name,slug,count`,
      {
        headers,
        next: { revalidate: 3600 },
      }
    ),
    // MIGRATED: Replaces bloginfo() calls for site name/description in search widget
    fetch(`${API_URL}/wp-json/wp/v2/settings`, {
      headers,
      next: { revalidate: 86400 }, // revalidate once per day
    }),
  ]);

  const recentPosts: WPPost[] = recentPostsRes.ok ? await recentPostsRes.json() : [];
  const categories: WPCategory[] = categoriesRes.ok ? await categoriesRes.json() : [];
  const tags: WPTag[] = tagsRes.ok ? await tagsRes.json() : [];
  const siteInfo: WPSiteInfo = siteInfoRes.ok
    ? await siteInfoRes.json()
    : { name: '', description: '' };

  // Filter out uncategorized (id: 1 is typically "Uncategorized") and empty categories
  const filteredCategories = categories.filter(
    (cat) => cat.count > 0 && cat.slug !== 'uncategorized'
  );

  // Filter out tags with no posts
  const filteredTags = tags.filter((tag) => tag.count > 0);

  return {
    recentPosts,
    categories: filteredCategories,
    tags: filteredTags,
    siteInfo,
  };
}

// MIGRATED: Replaces WP_Widget_Search — search widget submits to /search route
function SearchWidget() {
  return (
    <div className="widget widget_search">
      <form role="search" method="get" className="search-form" action="/search">
        {/* MIGRATED: Replaces <label> with screen_reader_text from WP search widget */}
        <label>
          <span className="screen-reader-text">Search for:</span>
          <input
            type="search"
            className="search-field"
            placeholder="Search &hellip;"
            name="q"
            // TODO: Manual review needed — WP uses 's' as query param; Next.js search page should read 'q' or 's' consistently
          />
        </label>
        <input type="submit" className="search-submit" value="Search" />
      </form>
    </div>
  );
}

// MIGRATED: Replaces WP_Widget_Recent_Posts
function RecentPostsWidget({ posts }: { posts: WPPost[] }) {
  if (!posts || posts.length === 0) return null;

  return (
    <div className="widget widget_recent_entries">
      <h2 className="widget-title">Recent Posts</h2>
      <ul>
        {posts.map((post) => {
          // MIGRATED: Replaces get_the_post_thumbnail() inside widget
          const thumbnail =
            post._embedded?.['wp:featuredmedia']?.[0]?.media_details?.sizes?.thumbnail;

          return (
            <li key={post.id}>
              {thumbnail && (
                <Image
                  src={thumbnail.source_url}
                  alt={post._embedded?.['wp:featuredmedia']?.[0]?.alt_text || ''}
                  width={thumbnail.width}
                  height={thumbnail.height}
                  // TODO: Manual review needed — Add WordPress media domain to next.config.js images.remotePatterns
                />
              )}
              {/* MIGRATED: Replaces the_title() with {post.title.rendered} */}
              <Link
                href={`/posts/${post.slug}`}
                dangerouslySetInnerHTML={{ __html: post.title.rendered }}
              />
              {/* MIGRATED: Replaces the_time() / the_date() */}
              <span className="post-date">
                {new Intl.DateTimeFormat('en-US', {
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric',
                }).format(new Date(post.date))}
              </span>
            </li>
          );
        })}
      </ul>
    </div>
  );
}

// MIGRATED: Replaces WP_Widget_Categories
function CategoriesWidget({ categories }: { categories: WPCategory[] }) {
  if (!categories || categories.length === 0) return null;

  return (
    <div className="widget widget_categories">
      <h2 className="widget-title">Categories</h2>
      <ul>
        {categories.map((category) => (
          <li key={category.id} className="cat-item">
            {/* MIGRATED: Replaces wp_list_categories() — links to category archive route */}
            <Link href={`/category/${category.slug}`}>
              {/* MIGRATED: Replaces esc_html($category->name) — JSX auto-escapes text */}
              {category.name}
            </Link>
            {/* MIGRATED: Replaces category post count display */}
            <span className="cat-count"> ({category.count})</span>
          </li>
        ))}
      </ul>
    </div>
  );
}

// MIGRATED: Replaces WP_Widget_Tag_Cloud
function TagCloudWidget({ tags }: { tags: WPTag[] }) {
  if (!tags || tags.length === 0) return null;

  // Calculate tag sizes based on count (font-size scaling like WP tag cloud)
  const maxCount = Math.max(...tags.map((t) => t.count));
  const minCount = Math.min(...tags.map((t) => t.count));
  const minFontSize = 0.75; // rem
  const maxFontSize = 1.75; // rem

  const getFontSize = (count: number): string => {
    if (maxCount === minCount) return `${(minFontSize + maxFontSize) / 2}rem`;
    const ratio = (count - minCount) / (maxCount - minCount);
    const fontSize = minFontSize + ratio * (maxFontSize - minFontSize);
    return `${fontSize.toFixed(2)}rem`;
  };

  return (
    <div className="widget widget_tag_cloud">
      <h2 className="widget-title">Tags</h2>
      <div className="tagcloud">
        {tags.map((tag) => (
          // MIGRATED: Replaces wp_tag_cloud() — links to tag archive route
          <Link
            key={tag.id}
            href={`/tag/${tag.slug}`}
            className="tag-cloud-link"
            style={{ fontSize: getFontSize(tag.count) }}
            aria-label={`${tag.name} (${tag.count} ${tag.count === 1 ? 'item' : 'items'})`}
          >
            {/* MIGRATED: Replaces esc_html($tag->name) — JSX auto-escapes text */}
            {tag.name}
          </Link>
        ))}
      </div>
    </div>
  );
}

// MIGRATED: Replaces WP_Widget_Meta (login/admin links)
function MetaWidget() {
  // TODO: Manual review needed — WP_Widget_Meta shows login/logout links;
  // implement authentication check with NextAuth.js useSession() if login links are needed
  return (
    <div className="widget widget_meta">
      <h2 className="widget-title">Meta</h2>
      <ul>
        <li>
          <Link href="/wp-admin">Site Admin</Link>
          {/* TODO: Manual review needed — Replace with NextAuth signIn/signOut if auth is implemented */}
        </li>
        <li>
          <a href={`${API_URL}/wp-login.php`}>Log in</a>
        </li>
        <li>
          <a href={`${API_URL}/wp-json/`}>
            <abbr title="Really Simple Syndication">RSS</abbr>
          </a>
        </li>
        <li>
          <a href="https://wordpress.org/">WordPress.org</a>
        </li>
      </ul>
    </div>
  );
}

// MIGRATED: Main Sidebar component
// Replaces: <aside id="secondary" class="widget-area"><?php dynamic_sidebar('sidebar-1'); ?></aside>
// is_active_sidebar('sidebar-1') check replaced by data availability check
export default async function Sidebar() {
  const { recentPosts, categories, tags } = await getSidebarData();

  // MIGRATED: Replaces is_active_sidebar('sidebar-1') — return null if no widget data available
  const hasWidgetContent =
    recentPosts.length > 0 || categories.length > 0 || tags.length > 0;

  if (!hasWidgetContent) {
    // MIGRATED: Replaces `if (!is_active_sidebar('sidebar-1')) { return; }`
    return null;
  }

  return (
    // MIGRATED: Preserves original <aside id="secondary" class="widget-area"> markup
    <aside id="secondary" className="widget-area">
      {/* MIGRATED: Replaces dynamic_sidebar('sidebar-1') — renders individual widget components */}

      {/* MIGRATED: Replaces WP_Widget_Search */}
      <SearchWidget />

      {/* MIGRATED: Replaces WP_Widget_Recent_Posts */}
      <RecentPostsWidget posts={recentPosts} />

      {/* MIGRATED: Replaces WP_Widget_Categories */}
      <CategoriesWidget categories={categories} />

      {/* MIGRATED: Replaces WP_Widget_Tag_Cloud */}
      <TagCloudWidget tags={tags} />

      {/* MIGRATED: Replaces WP_Widget_Meta */}
      <MetaWidget />

      {/*
        TODO: Manual review needed — dynamic_sidebar() renders whatever widgets
        were configured in WP Admin > Appearance > Widgets. Audit the actual
        widgets in sidebar-1 and add/remove widget components above accordingly.
        Additional common widgets to implement:
        - WP_Widget_Text -> <TextWidget content="..." />
        - WP_Widget_Pages -> fetch /wp-json/wp/v2/pages and render list
        - WP_Widget_Archives -> fetch /wp-json/wp/v2/posts grouped by month
        - WP_Widget_Calendar -> custom calendar component fetching posts by date
        - WP_Widget_RSS -> fetch external RSS feed in an API route
        - Custom/plugin widgets -> audit and create individual React components
      */}
    </aside>
  );
}