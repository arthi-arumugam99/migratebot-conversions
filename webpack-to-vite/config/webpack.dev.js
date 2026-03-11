// MIGRATED: This file (config/webpack.dev.js) is no longer needed after migration to Vite.
// Vite handles development mode, source maps, dev server, CSS/SASS/PostCSS natively.
// This file can be safely deleted once vite.config.ts is in place.
//
// ─── EQUIVALENT VITE CONFIGURATION (vite.config.ts) ───────────────────────────
//
// import { defineConfig } from 'vite'
//
// export default defineConfig({
//   // MIGRATED: mode: 'development' — Vite automatically uses development mode
//   // when running `vite` (dev server) and production mode when running `vite build`.
//   // No explicit mode configuration is needed here.
//
//   // MIGRATED: devtool: 'inline-source-map' — Vite uses 'cheap-module-source-map'
//   // by default in development. Override if needed:
//   build: {
//     sourcemap: true, // MIGRATED: enables source maps for production builds
//   },
//
//   // MIGRATED: css-loader + style-loader + postcss-loader + sass-loader rules
//   // → Vite handles CSS, PostCSS, and SASS natively. No loader configuration needed.
//   // Just ensure `sass` is installed as a devDependency (it already is).
//   // PostCSS config is auto-detected from postcss.config.js at the project root.
//   // CSS source maps in dev are enabled by default.
//   css: {
//     devSourcemap: true, // MIGRATED: sourceMap: true on css-loader/sass-loader/postcss-loader
//   },
//
//   // MIGRATED: devServer → server
//   server: {
//     // MIGRATED: historyApiFallback: true — Vite handles SPA routing by default; not needed
//     open: true,    // MIGRATED: devServer.open: true
//     compress: true, // MIGRATED: devServer.compress: true
//     // MIGRATED: hot: true — Vite HMR is enabled by default via native ESM
//     port: 8080,    // MIGRATED: devServer.port: 8080
//   },
// })
//
// ──────────────────────────────────────────────────────────────────────────────
// NOTE: The actual vite.config.ts file should be placed at the project root.
// See webpack.common.js migration for additional settings (entry, resolve, plugins).
// TODO: Manual review needed — merge settings from webpack.common.js and
// webpack.prod.js into a single vite.config.ts at the project root, using
// Vite's mode-based conditional config where dev/prod behaviour differs.