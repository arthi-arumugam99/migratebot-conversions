import { Pool } from 'pg'; // MIGRATED: Supabase client initialization → pg Pool

// MIGRATED: Removed @supabase/supabase-js import
// MIGRATED: Removed Database type import (no longer needed for pg Pool)
// TODO: Manual review needed — if you need typed query results, consider using Drizzle ORM or Prisma with your existing schema

// MIGRATED: Removed Window.Clerk declaration used for Supabase auth token injection
// TODO: Manual review needed — Clerk token was previously injected into Supabase requests for RLS auth.uid() resolution
// Without this, RLS policies using auth.uid() will not resolve — implement app-level authorization or pass Clerk user ID explicitly

// MIGRATED: Removed NEXT_PUBLIC_SUPABASE_URL and NEXT_PUBLIC_SUPABASE_ANON_KEY env vars
// Add DATABASE_URL to your .env: DATABASE_URL=postgresql://user:password@host:5432/dbname

const pool = new Pool({ // MIGRATED: createClient<Database>() → new Pool()
    connectionString: process.env.DATABASE_URL,
});

export default pool;