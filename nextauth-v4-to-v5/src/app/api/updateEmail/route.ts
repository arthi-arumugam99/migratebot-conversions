import clientPromise from "@/lib/MongodbClient";
import { auth } from "@/auth"; // MIGRATED: NextAuth v5 — replaced getServerSession with auth() from auth.ts
import { NextResponse } from "next/server";

export async function POST(request: Request) {
  const { email } = await request.json();

  try {
    const session = await auth(); // MIGRATED: NextAuth v5 — replaced getServerSession(authOptions) with auth()

    if (!session) {
      return NextResponse.json({ error: "Not authorized" }, { status: 400 });
    }

    const client = await clientPromise;
    const db = client.db();

    const doesUserExist = await db
      .collection("users")
      .findOne({ email: session?.user?.email });

    if (!doesUserExist) {
      return NextResponse.json(
        { error: "User doesn't exist" },
        { status: 400 }
      );
    }

    const updateEmail = await db
      .collection("users")
      .updateOne({ email: session?.user?.email }, {
        $set: {
            email
        }
      });

    return NextResponse.json({ success: "Email changed" }, { status: 200 });
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}