import { auth } from "@/auth"; // MIGRATED: NextAuth v5 — replaced getServerSession(authOptions) with auth()
import { NextResponse } from "next/server";

export async function GET() {
    const session = await auth(); // MIGRATED: NextAuth v5 — replaced getServerSession(authOptions) with auth()

    if (!session) {
        return NextResponse.json({ error: "Not authorized" }, { status: 400 })
    }

    return NextResponse.json({ success: session }, { status: 200 })
}