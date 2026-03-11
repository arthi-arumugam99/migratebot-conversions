/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  // MIGRATED: Next.js 14 — `appDir` is now stable and no longer requires experimental flag; `experimental` block removed as it is now empty
  // NOTE: Partial Prerendering (PPR) is available as an experimental feature in Next.js 14 if desired: experimental: { ppr: true }
}

export default nextConfig