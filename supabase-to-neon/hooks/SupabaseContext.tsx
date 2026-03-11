'use client';

import React, { useState, useEffect, createContext, useContext, useMemo } from 'react';
import { useAuth } from '@clerk/nextjs';
// MIGRATED: Removed SupabaseDBClient type — Supabase client replaced by Neon/Drizzle DB connection
// TODO: Replace with your chosen auth provider (NextAuth, Clerk, Lucia, Better Auth) session/user types
// TODO: Replace with your chosen database client type (e.g., Drizzle db instance)
// import { SupabaseDBClient } from '@/utils/types/helper.types';
// MIGRATED: Removed supabaseClient import — Supabase client no longer used
// TODO: Import your Neon/Drizzle db instance instead, e.g.:
// import { db } from '@/utils/db';

// TODO: Manual review needed — This entire context was tightly coupled to Supabase client initialization
// and Supabase Auth session lifecycle. It needs to be redesigned around your chosen auth provider
// (NextAuth, Clerk, Lucia, Better Auth) and your Neon/Drizzle db client.
//
// Since this project already uses @clerk/nextjs, consider:
// - Removing this context entirely and using Clerk's built-in hooks (useAuth, useUser, useSession)
// - Replacing the db client context with a simple Neon/Drizzle db import (no context needed)
//
// Suggested replacement context shape if a DB context is still needed:
// type DBContextType = {
//   db: typeof db | null;
// };

// TODO: Replace SupabaseDBClient with your Neon/Drizzle db instance type
type DBContextType = {
    db: unknown | null;
};

// MIGRATED: Renamed from SupabaseContext to DBContext — Supabase client replaced by Neon/Drizzle db
const DBContext = createContext<DBContextType>(null);

export const DBProvider = ({ children }: {
    children: React.ReactNode;
}) => {
    // TODO: Replace with your Neon/Drizzle db instance — db does not require auth-gated initialization
    // import { db } from '@/utils/db'; and use it directly without state
    const [db, setDb] = useState<unknown>(null);

    const { isSignedIn } = useAuth();
    // TODO: Manual review needed — window.Clerk.session was used to gate Supabase client init with a Clerk JWT
    // With Neon/Drizzle, the db client does not need a user JWT for initialization
    // Authorization should be handled at the query/middleware level, not at the client level
    const container = typeof window !== 'undefined' ? window?.Clerk?.session : null;

    const initializeDb = () => {
        // TODO: Replace with your Neon/Drizzle db initialization, e.g.:
        // import { db } from '@/utils/db';
        // setDb(db);
        // NOTE: Unlike Supabase, Neon/Drizzle db does not embed user auth tokens
        // Authorization must be enforced in your API routes or query helpers
        setDb(null); // placeholder — replace with actual db instance
    };

    const handleDbSignOut = async () => {
        if (!isSignedIn) {
            // MIGRATED: Removed supabase.auth.signOut() — Supabase Auth replaced by Clerk
            // TODO: Clerk handles sign-out via its own methods — no DB client sign-out needed
            // If using Clerk: import { useClerk } from '@clerk/nextjs'; then call clerk.signOut()
            // TODO: Replace with your chosen auth provider (NextAuth, Clerk, Lucia, Better Auth) sign-out:
            // Clerk:    const { signOut } = useClerk(); await signOut();
            // NextAuth: import { signOut } from 'next-auth/react'; await signOut();
            // Lucia:    await lucia.invalidateSession(sessionId);
            // Better Auth: await authClient.signOut();
            setDb(null);
        }
    };

    useEffect(() => {
        handleDbSignOut();
    }, [isSignedIn]);

    useEffect(() => {
        if (isSignedIn && container) {
            initializeDb();
        }
    }, [container]);

    const value = useMemo(() => ({ db }), [db]);

    return (
        <DBContext.Provider value={value}>
            {children}
        </DBContext.Provider>
    );
};

// MIGRATED: Renamed from useSupabase to useDb — Supabase client replaced by Neon/Drizzle db
// TODO: Manual review needed — consider whether a db context is necessary at all;
// Neon/Drizzle db instances are typically imported directly rather than provided via context
const useDb = () => {
    const { db } = useContext(DBContext);

    if (db === undefined) {
        // TODO error handling
        throw new Error('Database connection failed.');
    }

    return db;
};

export default useDb;