"use client";

import { signIn, signOut, useSession } from "next-auth/react"; // MIGRATED: NextAuth v5 — import source unchanged, next-auth/react still valid in v5
import React from "react";

// TODO: Manual review needed — if SessionProvider is used in a parent layout and passes
// a `session` prop obtained from `getServerSession(authOptions)`, replace that call with
// `auth()` from "@/auth" (v5 server-side session access). The SessionProvider itself
// still lives in next-auth/react but the prop should come from the new `auth()` helper.

const Dashboard = () => {
  // MIGRATED: NextAuth v5 — useSession() hook is unchanged in v5, works as before
  const { data: session } = useSession();

  return (
    <>
      {session ? (
        <>
          <img
            src={session.user?.image as string}
            className="rounded-full h-20 w-20"
          ></img>
          <h1 className="text-3xl text-green-500 font-bold">
            Welcome back, {session.user?.name}
          </h1>
          <p className="text-2xl font-semibold">{session.user?.email}</p>
          <button
            onClick={() => signOut()} // MIGRATED: NextAuth v5 — client-side signOut() from next-auth/react still works unchanged
            className="border border-black rounded-lg bg-red-400 px-5 py-1"
          >
            Sign Out
          </button>
        </>
      ) : (
        <>
          <h1 className="text-3xl text-red-500 font-bold">
            You're not logged in
          </h1>
          <div className="flex space-x-5">
            <button
              onClick={() => signIn("google")} // MIGRATED: NextAuth v5 — client-side signIn() from next-auth/react still works unchanged
              className="border border-black rounded-lg px-5 py-1"
            >
              Sign in with Google
            </button>
            <button
              onClick={() => signIn("github")} // MIGRATED: NextAuth v5 — client-side signIn() from next-auth/react still works unchanged
              className="border border-black rounded-lg bg-green-500 px-5 py-1"
            >
              Sign in with GitHub
            </button>
          </div>
        </>
      )}
    </>
  );
};

export default Dashboard;