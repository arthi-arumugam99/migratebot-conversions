// THIS FILE IS NO LONGER NEEDED AFTER VITE MIGRATION.
// All configuration below has been translated to vite.config.ts at the project root.
// This file (config/webpack.common.js) can be safely deleted once vite.config.ts is in place.
//
// ─── MIGRATION NOTES ────────────────────────────────────────────────────────
//
// ENTRY POINT:
//   Was: entry: [paths.src + '/index.js']
//   Now: Vite uses index.html at the project root as the entry point.
//        Add <script type="module" src="/src/index.js"></script> inside index.html.
//
// OUTPUT:
//   Was: output: { path: paths.build, filename: '[name].bundle.js', publicPath: '/' }
//   Now: Vite defaults to dist/ for output. Controlled via build.outDir in vite.config.ts.
//        Chunk naming is handled by Rollup automatically.
//
// PLUGINS:
//   CleanWebpackPlugin  → NOT NEEDED. Vite cleans the output directory by default.
//   CopyWebpackPlugin   → Files in public/ are served and copied automatically by Vite.
//                         For copying from a custom path (paths.public → 'assets'),
//                         use vite-plugin-static-copy in vite.config.ts.
//   HtmlWebpackPlugin   → NOT NEEDED. Vite uses the root index.html directly.
//                         Move src/template.html to the project root as index.html
//                         and embed the <script type="module"> tag manually.
//                         The favicon can be placed in public/ or referenced in index.html.
//
// LOADERS (all removed — Vite handles these natively or via lighter alternatives):
//   babel-loader        → NOT NEEDED. Vite uses esbuild for JS transforms.
//                         If you need advanced Babel plugins (@babel/plugin-proposal-class-properties),
//                         install @vitejs/plugin-react or vite-plugin-babel.
//   asset/resource      → NOT NEEDED. Vite handles images natively via ES module imports.
//   asset/inline        → NOT NEEDED. Vite inlines small assets automatically (<4KB default).
//                         SVGs can be imported as raw strings using the ?raw query suffix.
//
// RESOLVE:
//   resolve.modules: [paths.src, 'node_modules'] → Use resolve.alias in vite.config.ts instead.
//   resolve.extensions: ['.js', '.jsx', '.json'] → NOT NEEDED. Vite resolves these by default.
//   resolve.alias: { '@': paths.src, assets: paths.public }
//                                               → Translated directly to resolve.alias in vite.config.ts.
//
// ─── GENERATED vite.config.ts ───────────────────────────────────────────────
//
// The following vite.config.ts should be created at the project root:
//
// ─────────────────────────────────────────────────────────────────────────────
//  import { defineConfig } from 'vite'
//  import path from 'path'
//  import { viteStaticCopy } from 'vite-plugin-static-copy' // MIGRATED: replaces CopyWebpackPlugin
//
//  const src = path.resolve(__dirname, '../src')
//  const publicDir = path.resolve(__dirname, '../public')
//
//  export default defineConfig({
//    // MIGRATED: entry point is now index.html at project root; no entry field needed
//
//    resolve: {
//      alias: {
//        '@': src,              // MIGRATED: from resolve.alias['@']
//        assets: publicDir,     // MIGRATED: from resolve.alias['assets']
//      },
//      // MIGRATED: resolve.extensions not needed — Vite resolves .js, .jsx, .json by default
//      // MIGRATED: resolve.modules replaced by alias above
//    },
//
//    build: {
//      outDir: 'dist',          // MIGRATED: equivalent of output.path (paths.build)
//      emptyOutDir: true,       // MIGRATED: replaces CleanWebpackPlugin (Vite default, explicit here)
//      rollupOptions: {
//        output: {
//          entryFileNames: '[name].bundle.js', // MIGRATED: mirrors output.filename '[name].bundle.js'
//        },
//      },
//    },
//
//    plugins: [
//      // MIGRATED: HtmlWebpackPlugin removed — Vite uses index.html at project root directly
//      // MIGRATED: CleanWebpackPlugin removed — Vite cleans outDir by default
//
//      // MIGRATED: replaces CopyWebpackPlugin — copies public/ contents to dist/assets/
//      // TODO: Manual review needed — confirm paths.public resolves correctly relative to vite.config.ts
//      viteStaticCopy({
//        targets: [
//          {
//            src: publicDir + '/!(*.DS_Store)', // MIGRATED: globOptions.ignore ['*.DS_Store']
//            dest: 'assets',                    // MIGRATED: CopyWebpackPlugin 'to' field
//          },
//        ],
//      }),
//    ],
//
//    // MIGRATED: babel-loader removed — esbuild handles JS/JSX transforms natively
//    // TODO: Manual review needed — if @babel/plugin-proposal-class-properties is required,
//    //        install vite-plugin-babel and configure it in the plugins array above.
//
//    // MIGRATED: image/font/SVG loaders removed — Vite handles static assets natively.
//    //           Import assets using ES module syntax: import logo from './images/logo.png'
//    //           For SVG as raw text: import icon from './icon.svg?raw'
//    //           Assets < 4KB are inlined automatically; adjust with build.assetsInlineLimit.
//  })
// ─────────────────────────────────────────────────────────────────────────────
//
// ─── REQUIRED DEPENDENCY CHANGES (package.json) ─────────────────────────────
//
// Remove (no longer needed):
//   webpack, webpack-cli, webpack-dev-server, webpack-merge
//   babel-loader, css-loader, style-loader, sass-loader, postcss-loader
//   html-webpack-plugin, mini-css-extract-plugin, css-minimizer-webpack-plugin
//   clean-webpack-plugin, copy-webpack-plugin
//
// Add:
//   vite                        (core build tool)
//   vite-plugin-static-copy     (replaces CopyWebpackPlugin)
//
// Keep (still relevant):
//   sass                        (Vite auto-detects .scss/.sass files — no loader needed)
//   postcss-preset-env          (Vite auto-detects postcss.config.js)
//   eslint, prettier            (unchanged)
//   cross-env                   (can be removed if only used in webpack scripts)
//
// Update scripts in package.json:
//   "start": "vite"             (replaces webpack serve)
//   "build": "vite build"       (replaces webpack --config)
//   "preview": "vite preview"   (new — preview production build locally)
// ─────────────────────────────────────────────────────────────────────────────

const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const HtmlWebpackPlugin = require('html-webpack-plugin')

const paths = require('./paths')

module.exports = {
  // Where webpack looks to start building the bundle
  entry: [paths.src + '/index.js'],

  // Where webpack outputs the assets and bundles
  output: {
    path: paths.build,
    filename: '[name].bundle.js',
    publicPath: '/',
  },

  // Customize the webpack build process
  plugins: [
    // Removes/cleans build folders and unused assets when rebuilding
    new CleanWebpackPlugin(),

    // Copies files from target to destination folder
    new CopyWebpackPlugin({
      patterns: [
        {
          from: paths.public,
          to: 'assets',
          globOptions: {
            ignore: ['*.DS_Store'],
          },
          noErrorOnMissing: true,
        },
      ],
    }),

    // Generates an HTML file from a template
    // Generates deprecation warning: https://github.com/jantimon/html-webpack-plugin/issues/1501
    new HtmlWebpackPlugin({
      title: 'webpack Boilerplate',
      favicon: paths.src + '/images/favicon.png',
      template: paths.src + '/template.html', // template file
      filename: 'index.html', // output file
    }),
  ],

  // Determine how modules within the project are treated
  module: {
    rules: [
      // JavaScript: Use Babel to transpile JavaScript files
      { test: /\.js$/, use: ['babel-loader'] },

      // Images: Copy image files to build folder
      { test: /\.(?:ico|gif|png|jpg|jpeg)$/i, type: 'asset/resource' },

      // Fonts and SVGs: Inline files
      { test: /\.(woff(2)?|eot|ttf|otf|svg|)$/, type: 'asset/inline' },
    ],
  },

  resolve: {
    modules: [paths.src, 'node_modules'],
    extensions: ['.js', '.jsx', '.json'],
    alias: {
      '@': paths.src,
      assets: paths.public,
    },
  },
}