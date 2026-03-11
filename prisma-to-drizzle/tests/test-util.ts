import { db } from "../src/apps/database"; // MIGRATED: replaced prismaClient with drizzle db instance
import bcrypt from "bcrypt";
import type { InferSelectModel } from "drizzle-orm"; // MIGRATED: replaced '@prisma/client' types with Drizzle InferSelectModel
import { users, contacts, addresses } from "../src/models/schema"; // MIGRATED: import table definitions for type inference
import { eq } from "drizzle-orm";

// MIGRATED: replaced Prisma generated types with Drizzle inferred types
type User = InferSelectModel<typeof users>;
type Contact = InferSelectModel<typeof contacts>;
type Address = InferSelectModel<typeof addresses>;

export class UserTest {
  static async delete() {
    await db.delete(users).where(eq(users.username, "test")); // MIGRATED: prismaClient.user.deleteMany -> db.delete
  }

  static async create() {
    await db.insert(users).values({ // MIGRATED: prismaClient.user.create -> db.insert
      username: "test",
      name: "test",
      password: await bcrypt.hash("test", 10),
      token: "test",
    });
  }

  static async get(): Promise<User> {
    const user = await db.select().from(users).where(eq(users.username, "test")).limit(1); // MIGRATED: prismaClient.user.findFirst -> db.select

    if (!user[0]) {
      throw new Error("User is not found");
    }

    return user[0];
  }
}

export class ContactTest {
  static async deleteAll() {
    await db.delete(contacts).where(eq(contacts.username, "test")); // MIGRATED: prismaClient.contact.deleteMany -> db.delete
  }

  static async create() {
    await db.insert(contacts).values({ // MIGRATED: prismaClient.contact.create -> db.insert
      first_name: "test",
      last_name: "test",
      email: "test@example.com",
      phone: "08999999",
      username: "test",
    });
  }

  static async get(): Promise<Contact> {
    const contact = await db.select().from(contacts).where(eq(contacts.username, "test")).limit(1); // MIGRATED: prismaClient.contact.findFirst -> db.select

    if (!contact[0]) {
      throw new Error("Contact is not found");
    }

    return contact[0];
  }
}

export class AddressTest {
  static async deleteAll() {
    // TODO: Manual review needed -- Prisma nested relation filter `contact: { username: 'test' }` has no direct Drizzle equivalent; using a subquery or join may be required
    const contactList = await db.select({ id: contacts.id }).from(contacts).where(eq(contacts.username, "test")); // MIGRATED: fetch contact ids to filter addresses
    const contactIds = contactList.map((c) => c.id);
    if (contactIds.length > 0) {
      const { inArray } = await import("drizzle-orm");
      await db.delete(addresses).where(inArray(addresses.contact_id, contactIds)); // MIGRATED: prismaClient.address.deleteMany -> db.delete with inArray
    }
  }

  static async create() {
    const contact = await ContactTest.get();
    await db.insert(addresses).values({ // MIGRATED: prismaClient.address.create -> db.insert
      contact_id: contact.id,
      street: "Jl. Central Jakarta test",
      city: "Jakarta test",
      province: "DKI Jakarta test",
      country: "Indonesia",
      postal_code: "11111",
    });
  }

  static async get(): Promise<Address> {
    // TODO: Manual review needed -- Prisma nested relation filter `contact: { username: 'test' }` has no direct Drizzle equivalent; using a join to filter by contact username
    const result = await db // MIGRATED: prismaClient.address.findFirst with nested where -> db.select with join
      .select({ address: addresses })
      .from(addresses)
      .innerJoin(contacts, eq(addresses.contact_id, contacts.id))
      .where(eq(contacts.username, "test"))
      .limit(1);

    if (!result[0]) {
      throw new Error("Address is not found");
    }
    return result[0].address;
  }
}