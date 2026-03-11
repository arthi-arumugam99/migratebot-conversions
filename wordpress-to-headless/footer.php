// MIGRATED: footer.php -> components/Footer.tsx
// Converted WordPress PHP footer template to React Server Component
// Removed: wp_footer(), esc_url(), esc_html__(), printf() template tags
// Removed: closing #content div (now handled by app/layout.tsx)
// Removed: closing </body></html> (now handled by app/layout.tsx)

export default function Footer() {
  return (
    <footer id="colophon" className="site-footer">
      <div className="site-info">
        {/* MIGRATED: esc_url(__('https://wordpress.org/', '_s')) -> plain href */}
        <a href="https://wordpress.org/">
          {/* MIGRATED: printf(esc_html__('Proudly powered by %s', '_s'), 'WordPress') -> JSX */}
          Proudly powered by WordPress
        </a>
        <span className="sep"> | </span>
        {/* MIGRATED: printf(esc_html__('Theme: %1$s by %2$s.', '_s'), '_s', '<a href="...">Automattic</a>') -> JSX */}
        Theme: _s by{" "}
        <a href="https://automattic.com/">Automattic</a>.
      </div>
      {/* MIGRATED: .site-info */}
    </footer>
    // MIGRATED: #colophon
    // MIGRATED: wp_footer() removed — Next.js handles script injection automatically
    // MIGRATED: </div>#page closing tag moved to app/layout.tsx
    // MIGRATED: </body></html> moved to app/layout.tsx
  );
}