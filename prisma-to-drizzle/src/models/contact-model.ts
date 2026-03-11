import type { InferSelectModel } from "drizzle-orm"; // MIGRATED: replaced '@prisma/client' import with Drizzle's InferSelectModel
import type { contacts } from "../db/schema"; // MIGRATED: import contacts table schema for type inference

// MIGRATED: replaced `Contact` from '@prisma/client' with Drizzle's inferred select model type
type Contact = InferSelectModel<typeof contacts>;

export type ContactResponse = {
  id: number;
  first_name: string;
  last_name?: string | null;
  email?: string | null;
  phone?: string | null;
};

export type CreateContactRequest = {
  first_name: string;
  last_name?: string;
  email?: string;
  phone?: string;
};

export type UpdateContactRequest = {
  id: number;
  first_name: string;
  last_name?: string;
  email?: string;
  phone?: string;
};

export type SearchContactRequest = {
  name?: string;
  phone?: string;
  email?: string;
  page: number;
  size: number;
};

export function toContactResponse(contact: Contact): ContactResponse {
  return {
    id: contact.id,
    first_name: contact.first_name,
    last_name: contact.last_name,
    email: contact.email,
    phone: contact.phone,
  };
}