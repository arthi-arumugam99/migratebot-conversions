import type { InferSelectModel } from "drizzle-orm"; // MIGRATED: replaced '@prisma/client' import with Drizzle type inference
import { contacts, users } from "../db/schema"; // MIGRATED: import table definitions for type inference
import {
  ContactResponse,
  CreateContactRequest,
  SearchContactRequest,
  toContactResponse,
  UpdateContactRequest,
} from "../models/contact-model";
import { ContactValidation } from "../validations/contact-validation";
import { Validation } from "../validations/validation";
import { db } from "../apps/database"; // MIGRATED: replaced prismaClient with Drizzle db instance
import { ResponseError } from "../errors/response-error";
import { Pageable } from "../models/page";
import { eq, and, or, like, count } from "drizzle-orm"; // MIGRATED: import Drizzle query helpers

type User = InferSelectModel<typeof users>; // MIGRATED: replaced Prisma User type with Drizzle inferred type
type Contact = InferSelectModel<typeof contacts>; // MIGRATED: replaced Prisma Contact type with Drizzle inferred type

export class ContactService {
  static async create(
    user: User,
    request: CreateContactRequest
  ): Promise<ContactResponse> {
    const createRequest = Validation.validate(
      ContactValidation.CREATE,
      request
    );

    const record = {
      ...createRequest,
      ...{ username: user.username },
    };

    const [contact] = await db.insert(contacts).values(record).returning(); // MIGRATED: prismaClient.contact.create -> db.insert().values().returning()
    return toContactResponse(contact);
  }

  static async checkContactMustExists(
    username: string,
    contactId: number
  ): Promise<Contact> {
    const [contact] = await db // MIGRATED: prismaClient.contact.findFirst -> db.select().from().where().limit(1)
      .select()
      .from(contacts)
      .where(and(eq(contacts.id, contactId), eq(contacts.username, username)))
      .limit(1);

    if (!contact) {
      throw new ResponseError(404, "Contact not found");
    }

    return contact;
  }

  static async get(user: User, id: number): Promise<ContactResponse> {
    const contact = await this.checkContactMustExists(user.username, id);
    return toContactResponse(contact);
  }

  static async update(
    user: User,
    request: UpdateContactRequest
  ): Promise<ContactResponse> {
    const updateRequest = Validation.validate(
      ContactValidation.UPDATE,
      request
    );
    await this.checkContactMustExists(user.username, updateRequest.id);

    const [contact] = await db // MIGRATED: prismaClient.contact.update -> db.update().set().where().returning()
      .update(contacts)
      .set(updateRequest)
      .where(
        and(eq(contacts.id, updateRequest.id), eq(contacts.username, user.username))
      )
      .returning();

    return toContactResponse(contact);
  }

  static async remove(user: User, id: number): Promise<ContactResponse> {
    await this.checkContactMustExists(user.username, id);

    const [contact] = await db // MIGRATED: prismaClient.contact.delete -> db.delete().where().returning()
      .delete(contacts)
      .where(and(eq(contacts.id, id), eq(contacts.username, user.username)))
      .returning();

    return toContactResponse(contact);
  }

  static async search(
    user: User,
    request: SearchContactRequest
  ): Promise<Pageable<ContactResponse>> {
    const searchRequest = Validation.validate(
      ContactValidation.SEARCH,
      request
    );
    const skip = (searchRequest.page - 1) * searchRequest.size;

    const filters = [eq(contacts.username, user.username)]; // MIGRATED: build Drizzle-compatible filter conditions
    // check if name exists
    if (searchRequest.name) {
      filters.push(
        or( // MIGRATED: Prisma OR with contains -> Drizzle or() with like()
          like(contacts.first_name, `%${searchRequest.name}%`),
          like(contacts.last_name, `%${searchRequest.name}%`)
        ) as any // TODO: Manual review needed -- or() return type may need adjustment depending on Drizzle version
      );
    }
    // check if email exists
    if (searchRequest.email) {
      filters.push(like(contacts.email, `%${searchRequest.email}%`)); // MIGRATED: Prisma contains -> Drizzle like()
    }
    // check if phone exists
    if (searchRequest.phone) {
      filters.push(like(contacts.phone, `%${searchRequest.phone}%`)); // MIGRATED: Prisma contains -> Drizzle like()
    }

    const results = await db // MIGRATED: prismaClient.contact.findMany -> db.select().from().where().limit().offset()
      .select()
      .from(contacts)
      .where(and(...filters))
      .limit(searchRequest.size)
      .offset(skip);

    const [totalResult] = await db // MIGRATED: prismaClient.contact.count -> db.select({ count }).from().where()
      .select({ count: count() })
      .from(contacts)
      .where(and(...filters));

    const total = Number(totalResult.count);

    return {
      data: results.map((contact) => toContactResponse(contact)),
      paging: {
        current_page: searchRequest.page,
        total_page: Math.ceil(total / searchRequest.size),
        size: searchRequest.size,
      },
    };
  }
}