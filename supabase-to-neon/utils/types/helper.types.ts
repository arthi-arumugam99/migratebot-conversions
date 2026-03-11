// MIGRATED: Removed SupabaseClient import from @supabase/supabase-js — replaced with Drizzle ORM types
import { drizzle } from 'drizzle-orm/neon-http';
import { Database } from './database.types';

export type TodosType = Database['public']['Tables']['todos']['Row'];

// MIGRATED: SupabaseDBClient replaced with Drizzle database client type
// TODO: Define Drizzle schema for table definitions (see drizzle-orm docs) and replace the Database generic with your Drizzle schema
export type DBClient = ReturnType<typeof drizzle>;