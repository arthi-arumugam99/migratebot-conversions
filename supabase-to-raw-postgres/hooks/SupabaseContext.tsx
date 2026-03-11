'use client';

import React, { useState, useEffect, createContext, useContext, useMemo } from 'react';
import { useAuth } from '@clerk/nextjs';
// MIGRATED: Removed SupabaseDBClient and supabaseClient imports — Supabase client replaced with pg Pool
// TODO: Manual review needed — import your pg Pool instance or chosen auth provider's db client here
// import { Pool } from 'pg';
// const pool = new Pool({ connectionString: process.env.DATABASE_URL });

// TODO: Manual review needed — SupabaseDBClient type should be replaced with your db client type (e.g., pg.Pool)
// Remove or replace SupabaseDBClient with the appropriate type from your chosen db/auth solution
type DBClientType = null; // TODO: Replace with actual db client type (e.g., import { Pool } from 'pg')

type SupabaseContextType = {
    // MIGRATED: supabase field replaced — was SupabaseDBClient, now represents a plain db client or null
    // TODO: Replace DBClientType with your actual pg Pool or ORM client type
    supabase: DBClientType,
};

const SupabaseContext = createContext<SupabaseContextType>(null);

export const SupabaseProvider = ({ children }: {
    children: React.ReactNode;
}) => {
    // MIGRATED: Removed SupabaseDBClient state — was initialized with supabaseClient
    // TODO: Replace with your pg Pool instance or ORM client if context-based db access is needed
    const [supabase, setSupabase] = useState<DBClientType>(null);

    const { isSignedIn } = useAuth();
    const container = typeof window !== 'undefined' ? window?.Clerk?.session : null;

    const initializeSupabase = () => {
        // MIGRATED: Removed supabaseClient initialization
        // TODO: If a db client needs to be stored in context, initialize your pg Pool here:
        // setSupabase(pool);
        // Or remove this pattern entirely if db access is handled outside of React context
    };

    const handleSupabaseSignOut = async () => {
        if (!isSignedIn) {
            // MIGRATED: Removed supabase.auth.signOut() call
            // TODO: Replace with your chosen auth provider's sign-out logic:
            //   - NextAuth.js: import { signOut } from 'next-auth/react'; await signOut();
            //   - Lucia: call your session invalidation endpoint
            //   - Better Auth: call authClient.signOut()
            //   - Clerk (already in use): useClerk().signOut() — consider if this is redundant with Clerk
            setSupabase(null);
        }
    };

    useEffect(() => {
        handleSupabaseSignOut();
    }, [isSignedIn]);

    useEffect(() => {
        if (isSignedIn && container) {
            initializeSupabase();
        }
    }, [container]);
    // MIGRATED: Removed supabaseClient from dependency array — was a Supabase client import, no longer needed

    const value = useMemo(() => ({ supabase }), [supabase]);

    return (
        <SupabaseContext.Provider value={value}>
            {children}
        </SupabaseContext.Provider>
    );
};

const useSupabase = () => {
    const { supabase } = useContext(SupabaseContext);

    if (supabase === undefined) {
        // TODO error handling
        throw new Error('Supabase connection failed.');
    }

    return supabase;
};

export default useSupabase;