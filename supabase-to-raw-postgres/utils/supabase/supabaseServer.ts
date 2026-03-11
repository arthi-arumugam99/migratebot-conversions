/* eslint-disable import/prefer-default-export */

import { Pool } from 'pg';

// MIGRATED: Supabase client initialization → pg Pool connection
// TODO: Manual review needed — Clerk JWT auth token was previously passed to Supabase via Authorization header.
// With raw Postgres, app-level authorization must be handled separately (e.g., middleware or RLS via SET LOCAL).
// TODO: Implement app-level authorization — RLS auth.uid() no longer resolves without Supabase

export const pool = new Pool({ connectionString: process.env.DATABASE_URL });

// MIGRATED: Replaced createClerkSupabaseClient() with a pg Pool export.
// The Clerk token/session integration previously handled by Supabase SSR cookies is no longer applicable here.
// TODO: Manual review needed — implement session/auth checks using Clerk's auth() helper directly in your route handlers or middleware.