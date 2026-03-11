// MIGRATED: layout — was _app.tsx/_document.tsx, now app/layout.tsx
// MIGRATED: This simple _app.tsx had no global CSS imports, no providers, no router events,
// and no getInitialProps — it only rendered <Component {...pageProps} />, so the root layout
// is straightforward. The <html> and <body> tags are added here as required by App Router.

import type { Metadata } from "next";

export const metadata: Metadata = {
  // TODO: Manual review needed — add site-wide title, description, and other metadata here
  // that was previously handled via next/head in individual pages or _document.tsx
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en">
      {/* MIGRATED: <body> replaces the implicit body wrapper from Pages Router */}
      <body>{children}</body>
    </html>
  );
}