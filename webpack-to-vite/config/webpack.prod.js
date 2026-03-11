// MIGRATED: This file (config/webpack.prod.js) is no longer needed after migrating to Vite.
// Vite handles all production build concerns natively:
//   - CSS extraction is built-in (no MiniCssExtractPlugin needed)
//   - CSS minification is built-in via Lightning CSS / esbuild (no CssMinimizerPlugin needed)
//   - Content hashing for filenames is built-in via Rollup
//   - Runtime chunk splitting is handled automatically
//   - SASS/SCSS processing only requires the `sass` package (already in devDependencies)
//   - PostCSS is auto-detected from postcss.config.js
//   - webpack-merge is not needed (Vite uses a single unified config with mode-based conditions)
//
// This file can be safely DELETED after the vite.config.ts is in place.
//
// The equivalent production configuration is expressed in vite.config.ts (project root):
//
// import { defineConfig } from 'vite'
// import path from 'path'
//
// export default defineConfig({
//   // MIGRATED: mode: 'production' is set automatically when running `vite build`
//
//   build: {
//     // MIGRATED: output.path (paths.build) → build.outDir
//     outDir: 'build',
//
//     // MIGRATED: devtool: false → build.sourcemap: false
//     sourcemap: false,
//
//     // MIGRATED: output.filename 'js/[name].[contenthash].bundle.js' → Rollup handles hashing natively
//     // Customize asset filenames via rollupOptions if needed:
//     rollupOptions: {
//       output: {
//         // MIGRATED: js/[name].[contenthash].bundle.js equivalent
//         entryFileNames: 'js/[name].[hash].bundle.js',
//         chunkFileNames: 'js/[name].[hash].bundle.js',
//         // MIGRATED: MiniCssExtractPlugin filename: 'styles/[name].[contenthash].css' equivalent
//         assetFileNames: (assetInfo) => {
//           if (assetInfo.name && assetInfo.name.endsWith('.css')) {
//             return 'styles/[name].[hash][extname]'
//           }
//           return 'assets/[name].[hash][extname]'
//         },
//       },
//     },
//
//     // MIGRATED: optimization.minimize: true → build.minify is true by default ('esbuild')
//     minify: true,
//
//     // MIGRATED: CssMinimizerPlugin → Vite minifies CSS natively; no plugin needed
//     cssMinify: true,
//
//     // MIGRATED: optimization.runtimeChunk → Vite/Rollup handles module preloading automatically
//     // No direct equivalent needed; dynamic imports create automatic code splitting
//
//     // MIGRATED: performance hints disabled
//     // No direct equivalent in Vite; Vite warns on chunks > 500KB by default
//     // To suppress: set build.chunkSizeWarningLimit
//     chunkSizeWarningLimit: 512,
//   },
//
//   // MIGRATED: output.publicPath: '/' → base: '/'
//   base: '/',
// })
//
// -----------------------------------------------------------------------------
// DEPENDENCIES TO REMOVE from package.json (no longer needed after Vite migration):
//   - mini-css-extract-plugin
//   - css-minimizer-webpack-plugin
//   - css-loader
//   - style-loader
//   - sass-loader
//   - postcss-loader
//   - babel-loader
//   - html-webpack-plugin
//   - clean-webpack-plugin
//   - copy-webpack-plugin
//   - webpack-merge
//   - webpack
//   - webpack-cli
//   - webpack-dev-server
//   - cross-env (may no longer be needed)
//
// DEPENDENCIES TO ADD:
//   - vite
//   - (keep) sass        — Vite auto-detects .scss/.sass when `sass` is installed
//   - (keep) postcss     — Vite auto-detects postcss.config.js
//   - (keep) postcss-preset-env
//
// UPDATE package.json scripts:
//   "start": "vite"             (replaces webpack serve)
//   "build": "vite build"       (replaces webpack --config config/webpack.prod.js)
//   "preview": "vite preview"   (new: preview the production build locally)
// -----------------------------------------------------------------------------