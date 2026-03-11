// MIGRATED: Removed @supabase/supabase-js createClient — replaced with Neon serverless driver and Drizzle ORM
import { neon } from '@neondatabase/serverless';
import { drizzle } from 'drizzle-orm/neon-http';
// TODO: Define Drizzle schema for table definitions and import here (see drizzle-orm docs)
// Example: import * as schema from '../db/schema';

// MIGRATED: Supabase env var removed — replaced by DATABASE_URL (Neon)
// Remove NEXT_PUBLIC_SUPABASE_URL and NEXT_PUBLIC_SUPABASE_ANON_KEY from your .env files
// Add DATABASE_URL from your Neon dashboard instead

// TODO: Replace with your chosen auth provider (NextAuth, Clerk, Lucia, Better Auth)
// NOTE: The original client injected a Clerk-issued Supabase JWT via a custom fetch wrapper.
// With Neon, auth token injection into the DB driver is not required — authorization should
// be enforced at the application/middleware level instead of via Supabase RLS + JWT.
// TODO: These RLS policies must be reimplemented as application-level authorization middleware
// Since Neon does not use Supabase Auth JWTs, RLS policies referencing auth.uid() will not work

declare global {
    interface Window {
        Clerk: {
            session?: {
                getToken: (options: { template: string }) => Promise<string>;
            };
        };
    }
}

// MIGRATED: Supabase createClient replaced with Neon sql + Drizzle db instances
const sql = neon(process.env.DATABASE_URL!);

// TODO: Pass your Drizzle schema as the second argument once defined:
// const db = drizzle(sql, { schema });
const db = drizzle(sql);

export { sql };
export default db;