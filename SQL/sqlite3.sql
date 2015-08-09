PRAGMA foreign_keys = OFF;

DROP TABLE IF EXISTS "main"."events";
CREATE TABLE "events" (
"event_key"  TEXT NOT NULL,
"user_id"  INTEGER NOT NULL,
"event_type"  INTEGER NOT NULL,
"event_value"  TEXT,
"event_expire"  INTEGER NOT NULL,
"event_complete"  INTEGER NOT NULL DEFAULT 0,
PRIMARY KEY ("event_key" ASC),
CONSTRAINT "fkey0" FOREIGN KEY ("user_id") REFERENCES "users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS "main"."lists";
CREATE TABLE "lists" (
"list_id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"user_id"  INTEGER NOT NULL,
"name"  TEXT NOT NULL,
FOREIGN KEY ("user_id") REFERENCES "users" ("user_id") ON UPDATE CASCADE,
UNIQUE ("user_id", "name")
);

DROP TABLE IF EXISTS "main"."list_users";
CREATE TABLE "list_users" (
"list_id"  INTEGER NOT NULL,
"user_id"  INTEGER NOT NULL,
"added_by"  INTEGER,
"member_from"  INTEGER NOT NULL,
"list_admin_from"  INTEGER,
PRIMARY KEY ("list_id", "user_id"),
FOREIGN KEY ("list_id") REFERENCES "lists" ("list_id") ON UPDATE CASCADE,
FOREIGN KEY ("user_id") REFERENCES "users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("added_by") REFERENCES "users" ("user_id") ON DELETE SET NULL ON UPDATE CASCADE
);

DROP TABLE IF EXISTS "main"."replies";
CREATE TABLE "replies" (
"reply_id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"task_id"  INTEGER NOT NULL,
"user_id"  INTEGER NOT NULL,
"posted"  INTEGER NOT NULL,
"text"  TEXT NOT NULL,
FOREIGN KEY ("task_id") REFERENCES "tasks" ("task_id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("user_id") REFERENCES "users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS "main"."reply_edits";
CREATE TABLE "reply_edits" (
"user_id"  INTEGER NOT NULL,
"reply_id"  INTEGER NOT NULL,
"reply_edit_date"  INTEGER NOT NULL,
PRIMARY KEY ("user_id", "reply_id", "reply_edit_date"),
FOREIGN KEY ("user_id") REFERENCES "users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("reply_id") REFERENCES "replies" ("reply_id") ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS "main"."sqlite_sequence";
CREATE TABLE sqlite_sequence(name,seq);

DROP TABLE IF EXISTS "main"."tags";
CREATE TABLE "tags" (
"tag_id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"list_id"  INTEGER NOT NULL,
"tag"  TEXT NOT NULL,
FOREIGN KEY ("list_id") REFERENCES "lists" ("list_id") ON DELETE CASCADE ON UPDATE CASCADE,
UNIQUE ("list_id", "tag")
);

DROP TABLE IF EXISTS "main"."tasks";
CREATE TABLE "tasks" (
"task_id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"list_id"  INTEGER NOT NULL,
"user_id"  INTEGER NOT NULL,
"title"  TEXT NOT NULL,
"description"  TEXT NOT NULL,
"priority"  INTEGER NOT NULL,
"create_date"  INTEGER NOT NULL,
"due_date"  INTEGER,
"done_date"  INTEGER,
FOREIGN KEY ("list_id") REFERENCES "lists" ("list_id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("user_id") REFERENCES "users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE,
UNIQUE ("list_id", "title")
);

DROP TABLE IF EXISTS "main"."tasks_assignment";
CREATE TABLE "tasks_assignment" (
"task_id"  INTEGER NOT NULL,
"user_id"  INTEGER NOT NULL,
"assigned_by"  INTEGER,
"assign_date"  INTEGER NOT NULL,
PRIMARY KEY ("task_id", "user_id"),
FOREIGN KEY ("task_id") REFERENCES "tasks" ("task_id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("user_id") REFERENCES "users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("assigned_by") REFERENCES "users" ("user_id") ON DELETE SET NULL ON UPDATE CASCADE
);

DROP TABLE IF EXISTS "main"."task_edits";
CREATE TABLE "task_edits" (
"user_id"  INTEGER NOT NULL,
"task_id"  INTEGER NOT NULL,
"task_edit_date"  INTEGER NOT NULL,
PRIMARY KEY ("user_id", "task_id", "task_edit_date"),
FOREIGN KEY ("user_id") REFERENCES "users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("task_id") REFERENCES "tasks" ("task_id") ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS "main"."task_tags";
CREATE TABLE "task_tags" (
"task_id"  INTEGER NOT NULL,
"tag_id"  INTEGER NOT NULL,
PRIMARY KEY ("task_id", "tag_id"),
FOREIGN KEY ("task_id") REFERENCES "tasks" ("task_id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("tag_id") REFERENCES "tags" ("tag_id") ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS "main"."users";
CREATE TABLE "users" (
"user_id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"nick"  TEXT NOT NULL,
"password"  TEXT NOT NULL,
"email"  TEXT NOT NULL,
"last_login"  INTEGER,
"app_admin_from"  INTEGER,
UNIQUE ("email"),
UNIQUE ("nick")
);