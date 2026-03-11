// MIGRATED: NextAuth v5 — API route becomes a thin re-export of handlers from auth.ts
// TODO: Manual review needed — ensure `src/auth.ts` has been created with the NextAuth config extracted from `src/lib/authOptions.ts`
export { handlers as GET, handlers as POST } from "@/auth";