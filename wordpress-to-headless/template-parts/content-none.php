'use client';

// MIGRATED: Converted from template-parts/content-none.php to React component
// MIGRATED: is_home() + current_user_can() -> prop-based conditional rendering
// MIGRATED: is_search() -> prop-based conditional rendering
// MIGRATED: get_search_form() -> inline search form JSX
// MIGRATED: esc_html_e() / wp_kses() -> plain JSX text content (JSX auto-escapes)
// MIGRATED: admin_url('post-new.php') -> hardcoded WP admin URL via env variable

import Link from 'next/link';
import { useSearchParams } from 'next/navigation';

// TODO: Manual review needed -- current_user_can('publish_posts') requires auth session check;
// implement with NextAuth.js session or JWT to conditionally show the admin link

interface ContentNoneProps {
  isSearch?: boolean;
  isHome?: boolean;
  isAdminUser?: boolean;
}

export default function ContentNone({
  isSearch = false,
  isHome = false,
  isAdminUser = false,
}: ContentNoneProps) {
  // MIGRATED: get_search_form() -> inline controlled search form
  const searchParams = useSearchParams();
  const currentQuery = searchParams.get('q') ?? '';

  // TODO: Manual review needed -- set NEXT_PUBLIC_WP_ADMIN_URL in your .env.local
  // pointing to your WordPress admin URL (e.g. https://your-wp-site.com/wp-admin)
  const wpAdminNewPostUrl =
    process.env.NEXT_PUBLIC_WP_ADMIN_URL
      ? `${process.env.NEXT_PUBLIC_WP_ADMIN_URL}/post-new.php`
      : '/wp-admin/post-new.php';

  return (
    <section className="no-results not-found">
      <header className="page-header">
        {/* MIGRATED: esc_html_e('Nothing Found') -> plain JSX text */}
        <h1 className="page-title">Nothing Found</h1>
      </header>
      {/* MIGRATED: .page-header closing comment preserved for parity */}

      <div className="page-content">
        {/* MIGRATED: is_home() && current_user_can('publish_posts') -> isHome && isAdminUser props */}
        {isHome && isAdminUser ? (
          <p>
            Ready to publish your first post?{' '}
            {/* MIGRATED: admin_url('post-new.php') -> env-variable-backed WP admin URL */}
            <a href={wpAdminNewPostUrl}>Get started here</a>.
          </p>
        ) : isSearch ? (
          // MIGRATED: is_search() branch -> isSearch prop
          <>
            <p>
              Sorry, but nothing matched your search terms. Please try again
              with some different keywords.
            </p>
            {/* MIGRATED: get_search_form() -> inline search form */}
            <SearchForm defaultValue={currentQuery} />
          </>
        ) : (
          // MIGRATED: else branch
          <>
            <p>
              It seems we can&rsquo;t find what you&rsquo;re looking for.
              Perhaps searching can help.
            </p>
            {/* MIGRATED: get_search_form() -> inline search form */}
            <SearchForm defaultValue={currentQuery} />
          </>
        )}
      </div>
      {/* MIGRATED: .page-content closing comment preserved for parity */}
    </section>
    // MIGRATED: .no-results closing comment preserved for parity
  );
}

// MIGRATED: get_search_form() -> standalone SearchForm sub-component
// MIGRATED: form action points to Next.js /search route instead of WordPress search
interface SearchFormProps {
  defaultValue?: string;
}

function SearchForm({ defaultValue = '' }: SearchFormProps) {
  return (
    <form role="search" method="get" className="search-form" action="/search">
      <label>
        <span className="screen-reader-text">Search for:</span>
        <input
          type="search"
          className="search-field"
          placeholder="Search &hellip;"
          defaultValue={defaultValue}
          name="q"
        />
      </label>
      <button type="submit" className="search-submit">
        Search
      </button>
    </form>
  );
}