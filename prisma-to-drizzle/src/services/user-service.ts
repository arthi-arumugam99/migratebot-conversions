import { InferSelectModel } from "drizzle-orm"; // MIGRATED: replaced '@prisma/client' User import with Drizzle InferSelectModel
import { users } from "../db/schema"; // MIGRATED: import users table schema for type inference
import { db } from "../apps/database"; // MIGRATED: replaced prismaClient with Drizzle db client
import { ResponseError } from "../errors/response-error";
import {
  CreateUserRequest,
  LoginUserRequest,
  toUserResponse,
  UpdateUserRequest,
  UserResponse,
} from "../models/user-model";
import { UserValidation } from "../validations/user-validation";
import { Validation } from "../validations/validation";
import bcrypt from "bcrypt";
import { v4 as uuid } from "uuid";
import { eq, count } from "drizzle-orm"; // MIGRATED: import Drizzle query helpers

type User = InferSelectModel<typeof users>; // MIGRATED: replaced Prisma User type with Drizzle inferred type

export class UserService {
  static async register(request: CreateUserRequest): Promise<UserResponse> {
    const registerRequest = Validation.validate(
      UserValidation.REGISTER,
      request
    );

    // MIGRATED: prismaClient.user.count() -> db.select({ count: count() }).from(users).where()
    const [{ totalUsers }] = await db
      .select({ totalUsers: count() })
      .from(users)
      .where(eq(users.username, registerRequest.username));

    if (totalUsers != 0) {
      throw new ResponseError(400, "Username already exists");
    }

    registerRequest.password = await bcrypt.hash(registerRequest.password, 10);

    // MIGRATED: prismaClient.user.create() -> db.insert(users).values().returning()
    const [user] = await db
      .insert(users)
      .values(registerRequest)
      .returning();

    return toUserResponse(user);
  }

  static async login(request: LoginUserRequest): Promise<UserResponse> {
    const loginRequest = Validation.validate(UserValidation.LOGIN, request);

    // MIGRATED: prismaClient.user.findUnique() -> db.select().from(users).where().limit(1)
    let [user] = await db
      .select()
      .from(users)
      .where(eq(users.username, loginRequest.username))
      .limit(1);

    if (!user) {
      throw new ResponseError(401, "Username or password is wrong");
    }

    const isPasswordValid = await bcrypt.compare(
      loginRequest.password,
      user.password
    );
    if (!isPasswordValid) {
      throw new ResponseError(401, "Username or password is wrong");
    }

    // MIGRATED: prismaClient.user.update() -> db.update(users).set().where().returning()
    [user] = await db
      .update(users)
      .set({ token: uuid() })
      .where(eq(users.username, loginRequest.username))
      .returning();

    const response = toUserResponse(user);
    response.token = user.token!;
    return response;
  }

  static async get(user: User): Promise<UserResponse> {
    return toUserResponse(user);
  }

  static async update(
    user: User,
    request: UpdateUserRequest
  ): Promise<UserResponse> {
    const updateRequest = Validation.validate(UserValidation.UPDATE, request);

    if (updateRequest.name) {
      user.name = updateRequest.name;
    }

    if (updateRequest.password) {
      user.password = await bcrypt.hash(updateRequest.password, 10);
    }

    // MIGRATED: prismaClient.user.update() -> db.update(users).set().where().returning()
    const [result] = await db
      .update(users)
      .set(user)
      .where(eq(users.username, user.username))
      .returning();

    return toUserResponse(result);
  }

  static async logout(user: User): Promise<UserResponse> {
    // MIGRATED: prismaClient.user.update() -> db.update(users).set().where().returning()
    const [result] = await db
      .update(users)
      .set({ token: null })
      .where(eq(users.username, user.username))
      .returning();
    return toUserResponse(result);
  }
}