"use client";
import { SessionProvider } from "next-auth/react"; // MIGRATED: NextAuth v5 — SessionProvider import remains from next-auth/react

import React from "react";

// TODO: Manual review needed — In v5, if you were passing a `session` prop to SessionProvider
// (e.g., from getServerSession), replace that server-side call with `auth()` from your auth.ts
// and pass the result accordingly. This component currently uses no props, so no change required.
const SessionWrapper = ({ children }: { children: React.ReactNode }) => {
  return <SessionProvider>{children}</SessionProvider>;
};

export default SessionWrapper