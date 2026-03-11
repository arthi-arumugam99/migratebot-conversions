import Link from 'next/link';
import { Metadata } from 'next';

// MIGRATED: WordPress 404.php -> Next.js not-found.tsx
// get_header() / get_footer() removed; layout.tsx wraps this component automatically
// esc_html_e() removed; JSX handles text rendering safely
// get_search_form() -> inline search form with GET action pointing to /search route
// the_widget('WP_Widget_Recent_Posts') -> RecentPosts component fetching from WP REST API
// wp_list_categories() -> CategoriesList component fetching from WP REST API
// the_widget('WP_Widget_Archives') -> MonthlyArchives component fetching from WP REST API
// the_widget('WP_Widget_Tag_Cloud') -> TagCloud component fetching from WP REST API

const WP_API_BASE = process.env.NEXT_PUBLIC_WORDPRESS_API_URL ?? '';

// TypeScript types for WP REST API responses
interface WPPost {
  id: number;
  slug: string;
  title: { rendered: string };
  date: string;
}

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
}

interface MonthArchive {
  year: number;
  month: number;
  label: string;
  count: number;
}

// MIGRATED: wp_list_categories() -> fetch from /wp-json/wp/v2/categories
async function getTopCategories(): Promise<WPCategory[]> {
  try {
    const res = await fetch(
      `${WP_API_BASE}/wp-json/wp/v2/categories?orderby=count&order=desc&per_page=10&hide_empty=true&_fields=id,name,slug,count`,
      { next: { revalidate: 3600 } }
    );
    if (!res.ok) return [];
    return res.json();
  } catch {
    return [];
  }
}

// MIGRATED: the_widget('WP_Widget_Recent_Posts') -> fetch from /wp-json/wp/v2/posts
async function getRecentPosts(): Promise<WPPost[]> {
  try {
    const res = await fetch(
      `${WP_API_BASE}/wp-json/wp/v2/posts?orderby=date&order=desc&per_page=5&_fields=id,slug,title,date`,
      { next: { revalidate: 3600 } }
    );
    if (!res.ok) return [];
    return res.json();
  } catch {
    return [];
  }
}

// MIGRATED: the_widget('WP_Widget_Tag_Cloud') -> fetch from /wp-json/wp/v2/tags
async function getTagCloud(): Promise<WPTag[]> {
  try {
    const res = await fetch(
      `${WP_API_BASE}/wp-json/wp/v2/tags?orderby=count&order=desc&per_page=45&hide_empty=true&_fields=id,name,slug,count`,
      { next: { revalidate: 3600 } }
    );
    if (!res.ok) return [];
    return res.json();
  } catch {
    return [];
  }
}

// MIGRATED: the_widget('WP_Widget_Archives', 'dropdown=1') -> fetch posts and group by month
async function getMonthlyArchives(): Promise<MonthArchive[]> {
  try {
    // Fetch enough posts to build a meaningful archive list
    const res = await fetch(
      `${WP_API_BASE}/wp-json/wp/v2/posts?orderby=date&order=desc&per_page=100&_fields=id,date`,
      { next: { revalidate: 3600 } }
    );
    if (!res.ok) return [];
    const posts: { id: number; date: string }[] = await res.json();

    // Group posts by year/month
    const archiveMap = new Map<string, MonthArchive>();
    for (const post of posts) {
      const date = new Date(post.date);
      const year = date.getFullYear();
      const month = date.getMonth() + 1; // 1-based
      const key = `${year}-${String(month).padStart(2, '0')}`;
      if (archiveMap.has(key)) {
        archiveMap.get(key)!.count += 1;
      } else {
        const label = new Intl.DateTimeFormat('en-US', {
          year: 'numeric',
          month: 'long',
        }).format(date);
        archiveMap.set(key, { year, month, label, count: 1 });
      }
    }
    return Array.from(archiveMap.values());
  } catch {
    return [];
  }
}

// MIGRATED: WordPress 404 page title / meta -> Next.js Metadata API
// esc_html_e() removed; static string used directly
export const metadata: Metadata = {
  title: 'Page Not Found',
  robots: { index: false, follow: false },
};

export default async function NotFound() {
  // MIGRATED: Widgets replaced by async data fetches in the server component
  const [categories, recentPosts, tags, archives] = await Promise.all([
    getTopCategories(),
    getRecentPosts(),
    getTagCloud(),
    getMonthlyArchives(),
  ]);

  return (
    // MIGRATED: get_header() / get_footer() removed — layout.tsx handles wrapping
    <div id="primary" className="content-area">
      <main id="main" className="site-main">

        <section className="error-404 not-found">
          {/* MIGRATED: esc_html_e() removed; JSX text nodes are safe */}
          <header className="page-header">
            <h1 className="page-title">Oops! That page can&rsquo;t be found.</h1>
          </header>

          <div className="page-content">
            <p>
              It looks like nothing was found at this location. Maybe try one of
              the links below or a search?
            </p>

            {/* MIGRATED: get_search_form() -> inline HTML search form targeting /search route */}
            <form role="search" method="get" className="search-form" action="/search">
              <label>
                <span className="screen-reader-text">Search for:</span>
                <input
                  type="search"
                  className="search-field"
                  placeholder="Search &hellip;"
                  name="q"
                  required
                />
              </label>
              <button type="submit" className="search-submit">Search</button>
            </form>

            {/* MIGRATED: the_widget('WP_Widget_Recent_Posts') -> React component with API data */}
            {recentPosts.length > 0 && (
              <div className="widget widget_recent_entries">
                <h2 className="widget-title">Recent Posts</h2>
                <ul>
                  {recentPosts.map((post) => (
                    <li key={post.id}>
                      {/* MIGRATED: get_permalink() -> Next.js <Link> */}
                      <Link href={`/posts/${post.slug}`}>
                        {/* MIGRATED: the_title() -> post.title.rendered */}
                        {post.title.rendered}
                      </Link>
                      <span className="post-date">
                        {/* MIGRATED: the_date('F j, Y') -> Intl.DateTimeFormat */}
                        {new Intl.DateTimeFormat('en-US', {
                          year: 'numeric',
                          month: 'long',
                          day: 'numeric',
                        }).format(new Date(post.date))}
                      </span>
                    </li>
                  ))}
                </ul>
              </div>
            )}

            {/* MIGRATED: wp_list_categories() -> React list with API data */}
            {categories.length > 0 && (
              <div className="widget widget_categories">
                <h2 className="widget-title">Most Used Categories</h2>
                <ul>
                  {categories.map((cat) => (
                    <li key={cat.id} className="cat-item">
                      {/* MIGRATED: wp_list_categories() link -> Next.js <Link> to category archive */}
                      <Link href={`/category/${cat.slug}`}>
                        {cat.name}
                      </Link>
                      {' '}
                      ({cat.count})
                    </li>
                  ))}
                </ul>
              </div>
            )}

            {/* MIGRATED: the_widget('WP_Widget_Archives', 'dropdown=1') -> <select> with grouped months */}
            {/* convert_smilies(':)') -> literal emoji */}
            {archives.length > 0 && (
              <div className="widget widget_archive">
                <h2 className="widget-title">Archives</h2>
                <p>Try looking in the monthly archives. 🙂</p>
                {/* MIGRATED: 'dropdown=1' option -> <select> element with client-side navigation */}
                {/* TODO: Manual review needed — dropdown navigation requires a Client Component
                    wrapper if you want onChange to trigger router.push(); currently renders
                    as a <select> linking to /[year]/[month] archive routes */}
                <label htmlFor="archive-dropdown" className="screen-reader-text">
                  Archives
                </label>
                <select
                  id="archive-dropdown"
                  name="archive-dropdown"
                  className="postform"
                  defaultValue=""
                  // MIGRATED: onChange navigation needs a Client Component wrapper; add if interactive
                  // navigation is required. See TODO above.
                >
                  <option value="" disabled>Select Month</option>
                  {archives.map((archive) => (
                    <option
                      key={`${archive.year}-${archive.month}`}
                      value={`/${archive.year}/${String(archive.month).padStart(2, '0')}`}
                    >
                      {archive.label} ({archive.count})
                    </option>
                  ))}
                </select>
              </div>
            )}

            {/* MIGRATED: the_widget('WP_Widget_Tag_Cloud') -> inline tag links */}
            {tags.length > 0 && (
              <div className="widget widget_tag_cloud">
                <h2 className="widget-title">Tags</h2>
                <div className="tagcloud">
                  {tags.map((tag) => (
                    // MIGRATED: tag cloud links -> Next.js <Link> to tag archive
                    <Link
                      key={tag.id}
                      href={`/tag/${tag.slug}`}
                      className="tag-cloud-link"
                      // MIGRATED: WP tag cloud font-size scaling removed; apply via CSS if needed
                    >
                      {tag.name}
                    </Link>
                  ))}
                </div>
              </div>
            )}

          </div>{/* .page-content */}
        </section>{/* .error-404 */}

      </main>{/* #main */}
    </div>
  );
}