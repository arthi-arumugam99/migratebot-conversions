import { InferSelectModel } from "drizzle-orm"; // MIGRATED: replaced '@prisma/client' User import with Drizzle InferSelectModel
import { users } from "../db/schema"; // MIGRATED: import users table schema for type inference

type User = InferSelectModel<typeof users>; // MIGRATED: replaced Prisma-generated User type with Drizzle inferred type

export type UserResponse = {
  username: string;
  name: string;
  token?: string;
};

export type CreateUserRequest = {
  username: string;
  name: string;
  password: string;
};

export type LoginUserRequest = {
  username: string;
  password: string;
};

export type UpdateUserRequest = {
  name?: string;
  password?: string;
};

export function toUserResponse(user: User): UserResponse {
  return {
    name: user.name,
    username: user.username,
  };
}