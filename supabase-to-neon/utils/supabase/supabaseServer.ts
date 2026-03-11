/* eslint-disable import/prefer-default-export */

import { auth } from '@clerk/nextjs/server';
// MIGRATED: Supabase env var removed — replaced by DATABASE_URL (Neon)
// MIGRATED: @supabase/ssr createServerClient removed — Supabase client replaced by Neon + Drizzle
// TODO: Replace with your chosen auth provider (NextAuth, Clerk, Lucia, Better Auth) session/token handling
// TODO: Replace with your chosen storage provider env vars if needed (e.g., AWS_ACCESS_KEY_ID, BLOB_READ_WRITE_TOKEN)
// TODO: Add DATABASE_URL (Neon connection string) to your .env.local:
//   DATABASE_URL=postgresql://<user>:<password>@<host>.neon.tech/<dbname>?sslmode=require
// TODO: NEXT_PUBLIC_SUPABASE_URL — remove from .env.local (no longer needed)
// TODO: NEXT_PUBLIC_SUPABASE_ANON_KEY — remove from .env.local (no longer needed)
import { neon } from '@neondatabase/serverless'; // MIGRATED: Supabase client → Neon serverless driver
import { drizzle } from 'drizzle-orm/neon-http'; // MIGRATED: Supabase client → Drizzle ORM over Neon
// TODO: Define Drizzle schema for table definitions (see drizzle-orm docs)

// MIGRATED: NEXT_PUBLIC_SUPABASE_URL and NEXT_PUBLIC_SUPABASE_ANON_KEY removed — use DATABASE_URL for Neon
const databaseUrl = process.env.DATABASE_URL!;

export async function createClerkSupabaseClient() {
    // TODO: Replace with your chosen auth provider (NextAuth, Clerk, Lucia, Better Auth)
    // The Clerk token previously used for Supabase JWT auth is no longer needed for DB access.
    // Clerk is still used here for auth state — wire up your own authorization middleware
    // to enforce access control at the application level (replacing Supabase RLS).
    const { getToken } = auth();

    // MIGRATED: Supabase JWT token template no longer needed for DB connection
    // TODO: If you still need the Clerk token for your own API authorization, keep this line;
    // otherwise remove it. Authorization must now be enforced in application-level middleware.
    const token = await getToken({ template: 'supabase' }); // TODO: Manual review needed — 'supabase' JWT template no longer applicable; update or remove token usage

    // MIGRATED: createServerClient (Supabase SSR) → Drizzle ORM over Neon HTTP driver
    // TODO: Manual review needed — cookie-based session management was handled by Supabase SSR;
    // with Clerk already in use, session/cookie handling is managed by Clerk directly.
    // Remove cookie get/set/remove logic below if Clerk handles sessions independently.
    const sql = neon(databaseUrl);
    const db = drizzle(sql);
    // TODO: Define Drizzle schema for table definitions (see drizzle-orm docs)

    return db;
}