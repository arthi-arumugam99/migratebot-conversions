// MIGRATED: Removed PostgrestError import from @supabase/supabase-js — replaced with a generic error type for Neon/Drizzle compatibility
import { NextResponse } from 'next/server';

// MIGRATED: PostgrestError replaced with a compatible interface for Neon/Drizzle error handling
// TODO: Manual review needed — Drizzle ORM and Neon driver throw standard JS errors, not PostgrestError objects.
// Update callers of handleErrorResponse to pass Drizzle/Neon errors shaped to this interface, or refactor to use handleJSErrorResponse directly.
interface DbError {
    message?: string;
    details?: string;
    code?: string;
}

function handleErrorResponse(error: DbError | null) {
    return NextResponse.json({ message: error?.message, details: error?.details, errorCode: error?.code });
}

function handleJSErrorResponse(error: any) {
    return new NextResponse(JSON.stringify(error), {
        status: 500,
        headers: { 'Content-Type': 'application/json' },
    });
}

export { handleErrorResponse, handleJSErrorResponse };