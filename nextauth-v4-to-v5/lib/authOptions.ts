// MIGRATED: NextAuth v5 — transformed authOptions.ts into root-level auth.ts with new v5 config pattern
import NextAuth from "next-auth";

import GithubProvider from "next-auth/providers/github";
import GoogleProvider from "next-auth/providers/google";
import CredentialsProvider from "next-auth/providers/credentials";
import clientPromise from "@/lib/MongodbClient";

// TODO: Manual review needed — bcrypt is a Node.js-only dependency; v5 runs on Edge Runtime by default.
// If you encounter runtime errors, consider moving CredentialsProvider logic to a separate Node.js-only
// auth config or switching to a pure-JS bcrypt alternative (e.g., bcryptjs).

// TODO: Manual review needed — GITHUB_ID/GITHUB_SECRET and GOOGLE_CLIENT_ID/GOOGLE_CLIENT_SECRET
// are v4 env var names. v5 convention is AUTH_GITHUB_ID, AUTH_GITHUB_SECRET,
// AUTH_GOOGLE_ID, AUTH_GOOGLE_SECRET. Update your .env file accordingly (old names still work
// if explicitly passed as below, but migrating is recommended).

// TODO: Manual review needed — AUTH_SECRET should be set in your .env file (replaces NEXTAUTH_SECRET).
// NEXTAUTH_SECRET still works in v5 but AUTH_SECRET is the new convention.

export const { handlers, auth, signIn, signOut } = NextAuth({
  // MIGRATED: NextAuth v5 — session config moved directly into NextAuth() call
  session: {
    strategy: "jwt",
  },
  providers: [
    GithubProvider({
      clientId: process.env.GITHUB_ID as string,
      clientSecret: process.env.GITHUB_SECRET as string,
    }),
    GoogleProvider({
      clientId: process.env.GOOGLE_CLIENT_ID as string,
      clientSecret: process.env.GOOGLE_CLIENT_SECRET as string,
    }),
    CredentialsProvider({
      name: "Credentials",
      credentials: {
        email: {},
        password: {},
      },
      async authorize(credentials) {
        // MIGRATED: NextAuth v5 — removed unused `req` parameter from authorize signature
        const client = await clientPromise;
        const db = client.db() as any;

        const user = await db
          .collection("users")
          .findOne({ email: credentials?.email });

        const bcrypt = require("bcrypt");

        const passwordCorrect = await bcrypt.compare(
          credentials?.password,
          user?.password
        );

        if (passwordCorrect) {
          return {
            id: user?._id,
            email: user?.email,
          };
        }

        console.log("credentials", credentials);
        return null;
      },
    }),
  ],
  callbacks: {
    // TODO: Manual review needed — jwt callback signature is largely compatible in v5,
    // but the `session` parameter (used for trigger === "update") now comes from the
    // `session` event object. Verify the shape of `session.user` matches your use case.
    jwt: async ({ user, token, trigger, session }) => {
      if (trigger === "update") {
        return { ...token, ...session.user };
      }
      return { ...token, ...user };
    },
  },
});