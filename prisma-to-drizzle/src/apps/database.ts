// MIGRATED: Replaced PrismaClient import with Drizzle ORM and node-postgres imports
import { drizzle } from "drizzle-orm/node-postgres";
import { Pool } from "pg";
import * as schema from "./schema";
import { logger } from "./logging";

// MIGRATED: Created a node-postgres connection pool to replace PrismaClient connection management
const pool = new Pool({
  connectionString: process.env.DATABASE_URL,
});

// MIGRATED: Replaced 'new PrismaClient()' with 'drizzle(pool)'; schema passed for relational query support
// MIGRATED: Drizzle logger: true logs queries via built-in mechanism; event-based logging replaced below
export const prismaClient = drizzle(pool, {
  schema,
  logger: {
    // MIGRATED: Replaced prismaClient.$on('query', ...) event handler with Drizzle's custom logger
    logQuery(query: string, params: unknown[]) {
      logger.info({ query, params });
    },
  },
});

// TODO: Manual review needed -- Prisma's $on('error', ...) and $on('warn', ...) and $on('info', ...) event hooks
// have no direct Drizzle equivalent. Error and warning logging should be handled at the pg Pool level or
// via application-level error handling middleware.

// MIGRATED: Replaced prismaClient.$on('error', ...) with pool-level error handling
pool.on("error", (e) => {
  logger.error(e);
});

// TODO: Manual review needed -- Prisma $on('warn', ...) and $on('info', ...) have no pg Pool equivalents.
// Consider wrapping query calls or using application-level interceptors for warn/info level logging.