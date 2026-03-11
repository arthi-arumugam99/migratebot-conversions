# MigrateBot — Automatic Code Migration Tool | 28 Real-World Examples

**Automatically migrate Next.js, React, Vue, Angular, Django, WordPress, and 22 more frameworks. Powered by Claude AI.**

[**Try MigrateBot Free →**](https://migratebot-lac.vercel.app)

This repository contains **28 real-world migration examples** — each one is a real open-source GitHub repo that was automatically converted by [MigrateBot](https://migratebot-lac.vercel.app). Every folder includes the fully migrated codebase and a README linking back to the original source repository.

---

## Why MigrateBot?

Framework migrations are the most dreaded task in software engineering. Upgrading Next.js 14 to 15, converting Vue 2 to Vue 3, switching from Firebase to Supabase — these projects take weeks of tedious, error-prone manual work.

**MigrateBot does it in minutes.** Upload your repo, get a migration plan, and download the fully converted codebase.

- **28 supported migrations** — framework upgrades, architecture changes, and full platform switches
- **AI-powered transforms** — Claude analyzes each file and applies context-aware migrations
- **Deterministic + AI hybrid** — mechanical changes (package.json bumps, config renames) are instant; complex code rewrites use AI
- **Migration plan first** — see exactly what will change before committing
- **Inline documentation** — every migrated file includes `// MIGRATED:` comments explaining changes
- **1,007 integration tests** — every rule set tested with realistic code patterns

---

## Migration Examples

### Tier 1 — Framework Upgrades

Upgrade your framework to the latest version without rewriting your codebase by hand.

| Migration | Original Repo | Files Migrated | Folder |
|-----------|--------------|----------------|--------|
| **Migrate Next.js 14 to 15** — async request APIs, React 19, caching defaults | [BashirMohamedAli/nextjs14-starter](https://github.com/BashirMohamedAli/nextjs14-starter) | 8 | [nextjs-14-to-15/](nextjs-14-to-15/) |
| **Migrate Next.js 13 to 14** — Server Actions, metadata viewport, `next/font` | [shadcn-ui/next-template](https://github.com/shadcn-ui/next-template) | 3 | [nextjs-13-to-14/](nextjs-13-to-14/) |
| **Migrate React 18 to 19** — `forwardRef` removal, `use()` hook, form actions | [joaopaulomoraes/reactjs-vite-tailwindcss-boilerplate](https://github.com/joaopaulomoraes/reactjs-vite-tailwindcss-boilerplate) | 1 | [react-18-to-19/](react-18-to-19/) |
| **Migrate Tailwind CSS 3 to 4** — CSS-first config, `@theme` directive, utility renames | [joaopaulomoraes/reactjs-vite-tailwindcss-boilerplate](https://github.com/joaopaulomoraes/reactjs-vite-tailwindcss-boilerplate) | 4 | [tailwind-3-to-4/](tailwind-3-to-4/) |
| **Migrate Supabase v1 to v2** — Auth API, Realtime channels, query builder | [salmandotweb/nextjs-supabase-boilerplate](https://github.com/salmandotweb/nextjs-supabase-boilerplate) | 1 | [supabase-v1-to-v2/](supabase-v1-to-v2/) |
| **Migrate Django 4 to 5** — STORAGES, async views, generated fields | [BobsProgrammingAcademy/student-management-system](https://github.com/BobsProgrammingAcademy/student-management-system) | 11 | [django-4-to-5/](django-4-to-5/) |
| **Upgrade FastAPI + Pydantic v2** — `model_dump`, `field_validator`, `ConfigDict` | [testdrivenio/fastapi-crud-async](https://github.com/testdrivenio/fastapi-crud-async) | 5 | [fastapi-upgrade/](fastapi-upgrade/) |
| **Migrate Create React App to Vite** — build config, env variables, imports | [machadop1407/firebase-react-crud](https://github.com/machadop1407/firebase-react-crud) | 2 | [cra-to-vite/](cra-to-vite/) |
| **Convert JavaScript to TypeScript** — type annotations, interfaces, tsconfig | [fraigo/node-express-rest-api-example](https://github.com/fraigo/node-express-rest-api-example) | 2 | [js-to-typescript/](js-to-typescript/) |
| **Convert React Class Components to Hooks** — useState, useEffect, useRef | [devat-youtuber/shopping-cart-react](https://github.com/devat-youtuber/shopping-cart-react) | 9 | [react-class-to-hooks/](react-class-to-hooks/) |

### Tier 2 — Architecture Migrations

Switch frameworks, ORMs, bundlers, and backend providers without starting from scratch.

| Migration | Original Repo | Files Migrated | Folder |
|-----------|--------------|----------------|--------|
| **Migrate Vue 2 to Vue 3** — Composition API, `createApp()`, v-model, Pinia | [lstoeferle/vite-vue2-starter](https://github.com/lstoeferle/vite-vue2-starter) | 3 | [vue-2-to-3/](vue-2-to-3/) |
| **Migrate Angular 16 to 17** — standalone components, new control flow, signals | [bezkoder/angular-16-crud-example](https://github.com/bezkoder/angular-16-crud-example) | 18 | [angular-16-to-17/](angular-16-to-17/) |
| **Migrate Nuxt 2 to Nuxt 3** — Nitro server, composables, auto-imports | [nuxt-community/typescript-template](https://github.com/nuxt-community/typescript-template) | 9 | [nuxt-2-to-3/](nuxt-2-to-3/) |
| **Migrate Next.js Pages Router to App Router** — Server Components, layouts, metadata | [prisma/blogr-nextjs-prisma](https://github.com/prisma/blogr-nextjs-prisma) | 6 | [pages-to-app-router/](pages-to-app-router/) |
| **Migrate Webpack to Vite** — config, loaders to plugins, HMR, ESM | [taniarascia/webpack-boilerplate](https://github.com/taniarascia/webpack-boilerplate) | 6 | [webpack-to-vite/](webpack-to-vite/) |
| **Convert CommonJS to ESM** — `require()` to `import`, `module.exports` to `export` | [fraigo/node-express-rest-api-example](https://github.com/fraigo/node-express-rest-api-example) | 3 | [commonjs-to-esm/](commonjs-to-esm/) |
| **Migrate Prisma to Drizzle ORM** — schema, queries, relations, types | [berthutapea/typescript-express-prisma-starter](https://github.com/berthutapea/typescript-express-prisma-starter) | 11 | [prisma-to-drizzle/](prisma-to-drizzle/) |
| **Migrate Firebase to Supabase** — Auth, Firestore to Postgres, Storage, Realtime | [machadop1407/firebase-react-crud](https://github.com/machadop1407/firebase-react-crud) | 3 | [firebase-to-supabase/](firebase-to-supabase/) |
| **Migrate Supabase to Neon** — serverless driver, Drizzle ORM, connection pooling | [Ali-Onar/nextjs-supabase-todo-app](https://github.com/Ali-Onar/nextjs-supabase-todo-app) | 10 | [supabase-to-neon/](supabase-to-neon/) |
| **Migrate Supabase to Raw Postgres** — `pg` client, standalone auth, connection pools | [Ali-Onar/nextjs-supabase-todo-app](https://github.com/Ali-Onar/nextjs-supabase-todo-app) | 8 | [supabase-to-raw-postgres/](supabase-to-raw-postgres/) |
| **Migrate NextAuth v4 to Auth.js v5** — `auth()` function, Edge middleware, config | [janhbnr/nextauth-nextjs14-starter](https://github.com/janhbnr/nextauth-nextjs14-starter) | 12 | [nextauth-v4-to-v5/](nextauth-v4-to-v5/) |

### Tier 3 — Platform Migrations

Move between CMS platforms, e-commerce systems, and hosting providers.

| Migration | Original Repo | Files Migrated | Folder |
|-----------|--------------|----------------|--------|
| **Migrate WordPress 5.x to 6.x** — block editor, `theme.json`, block patterns | [gregsullivan/_tw](https://github.com/gregsullivan/_tw) | 13 | [wordpress-5-to-6/](wordpress-5-to-6/) |
| **Migrate WordPress to Headless Next.js** — PHP to React, WP REST API, decoupled | [psorensen/tailwind_s](https://github.com/psorensen/tailwind_s) | 20 | [wordpress-to-headless/](wordpress-to-headless/) |
| **Migrate WooCommerce to Shopify** — products, Liquid themes, payment gateways | [tutsplus/developing-a-woocommerce-theme](https://github.com/tutsplus/developing-a-woocommerce-theme) | 3 | [woocommerce-to-shopify/](woocommerce-to-shopify/) |
| **Migrate PHP Themes to Block Themes** — `template-parts` to blocks, `theme.json` | [psorensen/tailwind_s](https://github.com/psorensen/tailwind_s) | 19 | [php-to-block-themes/](php-to-block-themes/) |
| **Migrate Elementor to Native Blocks** — widget to block conversion, CSS cleanup | [bmarshall511/elementor-awesomesauce](https://github.com/bmarshall511/elementor-awesomesauce) | 2 | [elementor-to-blocks/](elementor-to-blocks/) |
| **Migrate ACF to Native Meta Fields** — `get_field()` to `get_post_meta()` | [steam0r/acf-wp-theme-skeleton](https://github.com/steam0r/acf-wp-theme-skeleton) | 2 | [acf-to-native-fields/](acf-to-native-fields/) |
| **Migrate WordPress to Webflow** — content export, URL mapping, SEO redirects | [psorensen/tailwind_s](https://github.com/psorensen/tailwind_s) | 12 | [wordpress-to-webflow/](wordpress-to-webflow/) |

**Total: 28/28 migrations passed. 195 files migrated across 20 real open-source repositories.**

---

## How MigrateBot Works

MigrateBot runs every project through a 5-stage pipeline:

```
Upload → Detect → Classify → Transform → Validate → Download
```

| Stage | What Happens |
|-------|-------------|
| **Detect** | Verifies the project matches the selected migration type (checks package.json, config files, framework versions) |
| **Classify** | Categorizes each source file by what needs to change (async APIs, caching, config, dependencies, etc.) |
| **Transform** | Applies **deterministic transforms** for mechanical changes (version bumps, config renames) + **AI transforms** (Claude Sonnet) for complex code pattern rewrites |
| **Validate** | Checks every transformed file for syntax issues (balanced brackets, unresolved markers) |
| **Package** | Bundles the migrated codebase with migration reports and inline documentation |

### What makes MigrateBot different from codemods?

Traditional codemods handle simple find-and-replace patterns. MigrateBot combines deterministic transforms with AI-powered code analysis:

- **Deterministic transforms** handle the predictable stuff — bumping `"next": "^14.2.0"` to `"^15.0.0"` in package.json, renaming `serverComponentsExternalPackages` to `serverExternalPackages` in next.config.js
- **AI transforms** handle the hard stuff — converting Vue 2 Options API to Composition API, rewriting Prisma queries to Drizzle query builder syntax, converting Firebase Firestore calls to Supabase client calls
- **Context-aware** — Claude receives the full file content, package.json, config files, and migration-specific system prompts with comprehensive rules and examples
- **Documented output** — every change is annotated with `// MIGRATED:` comments, and ambiguous transforms are flagged with `// TODO: Manual review`

---

## Supported Migrations

MigrateBot supports **28 migration types** across three tiers:

### Framework Upgrades
- Migrate Next.js 14 to 15 | Migrate Next.js 13 to 14
- Migrate React 18 to 19 | Migrate Tailwind CSS 3 to 4
- Migrate Supabase v1 to v2 | Migrate Django 4 to 5
- Upgrade FastAPI + Pydantic v2 | Migrate CRA to Vite
- Convert JavaScript to TypeScript | Convert React Class Components to Hooks

### Architecture Migrations
- Migrate Vue 2 to 3 | Migrate Angular 16 to 17 | Migrate Nuxt 2 to 3
- Migrate Next.js Pages Router to App Router | Migrate Webpack to Vite
- Convert CommonJS to ESM | Migrate Prisma to Drizzle ORM
- Migrate Firebase to Supabase | Migrate Supabase to Neon | Migrate Supabase to Raw Postgres
- Migrate NextAuth v4 to Auth.js v5

### Platform Migrations
- Migrate WordPress 5.x to 6.x | Migrate WordPress to Headless Next.js
- Migrate WooCommerce to Shopify | Migrate PHP Themes to Block Themes
- Migrate Elementor to Native Blocks | Migrate ACF to Native Meta Fields
- Migrate WordPress to Webflow

---

## Try It Yourself

MigrateBot works with any public GitHub repository or ZIP upload.

1. Go to [migratebot-lac.vercel.app](https://migratebot-lac.vercel.app)
2. Paste a GitHub URL or upload a ZIP
3. Select your migration type
4. Review the migration plan
5. Download your migrated codebase

**Free tier includes 1 migration.** Paid plans start at $9/month for 5 migrations.

[**Try MigrateBot Free →**](https://migratebot-lac.vercel.app)

---

## Tech Stack

MigrateBot is built with:

- **Frontend** — Next.js, React 19, Tailwind CSS 4, Framer Motion
- **AI Engine** — Anthropic Claude Sonnet with prompt caching
- **Backend** — Supabase (PostgreSQL, Auth, Storage, Realtime), Inngest (durable background jobs)
- **Testing** — Vitest with 1,007 integration tests across all 28 rule sets

---

## Keywords

migrate nextjs 14 to 15, migrate nextjs 13 to 14, upgrade next.js, migrate react 18 to 19, upgrade react, migrate tailwind css 3 to 4, upgrade tailwindcss, migrate vue 2 to vue 3, vue 3 migration tool, migrate angular 16 to 17, angular migration, migrate nuxt 2 to nuxt 3, nuxt 3 migration, next.js pages router to app router, app router migration, migrate webpack to vite, vite migration tool, convert commonjs to esm, esm migration, migrate prisma to drizzle, drizzle orm migration, migrate firebase to supabase, firebase migration tool, migrate supabase to neon, neon migration, migrate nextauth v4 to auth.js v5, authjs migration, migrate django 4 to 5, django upgrade, upgrade fastapi pydantic v2, migrate cra to vite, create react app to vite, convert javascript to typescript, typescript migration, react class components to hooks, hooks migration, migrate wordpress 5 to 6, wordpress upgrade, wordpress to headless nextjs, headless wordpress, migrate woocommerce to shopify, shopify migration, php themes to block themes, block theme migration, elementor to gutenberg blocks, migrate acf to native fields, wordpress to webflow, webflow migration, ai code migration tool, automatic framework migration, code migration tool, ai-powered code upgrade

---

*All 28 examples generated by [MigrateBot](https://migratebot-lac.vercel.app) on March 11, 2026. Built by [@arthi-arumugam99](https://github.com/arthi-arumugam99).*
