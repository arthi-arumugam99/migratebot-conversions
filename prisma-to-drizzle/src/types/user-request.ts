import { InferSelectModel } from "drizzle-orm"; // MIGRATED: replaced '@prisma/client' import with Drizzle's InferSelectModel
import { users } from "../db/schema"; // MIGRATED: import users table schema for type inference
import { Request } from "express";

// MIGRATED: replaced Prisma-generated `User` type with Drizzle's InferSelectModel<typeof users>
type User = InferSelectModel<typeof users>;

export interface UserRequest extends Request {
  user?: User;
}