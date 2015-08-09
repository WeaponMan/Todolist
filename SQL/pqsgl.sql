
DROP SEQUENCE "public"."lists_list_id_seq";
CREATE SEQUENCE "public"."lists_list_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."lists_list_id_seq"', 1, true);

DROP SEQUENCE "public"."replies_reply_id_seq";
CREATE SEQUENCE "public"."replies_reply_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."replies_reply_id_seq"', 1, true);

DROP SEQUENCE "public"."tags_tag_id_seq";
CREATE SEQUENCE "public"."tags_tag_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 2
 CACHE 1;
SELECT setval('"public"."tags_tag_id_seq"', 2, true);

DROP SEQUENCE "public"."tasks_task_id_seq";
CREATE SEQUENCE "public"."tasks_task_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."tasks_task_id_seq"', 1, true);

DROP SEQUENCE "public"."users_user_id_seq";
CREATE SEQUENCE "public"."users_user_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."users_user_id_seq"', 1, true);

DROP TABLE IF EXISTS "public"."events";
CREATE TABLE "public"."events" (
"event_key" varchar(50) COLLATE "default" NOT NULL,
"user_id" int4 NOT NULL,
"event_type" int4 NOT NULL,
"event_value" varchar(100) COLLATE "default",
"event_expire" int4 NOT NULL,
"event_complete" int4 DEFAULT 0 NOT NULL
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS "public"."list_users";
CREATE TABLE "public"."list_users" (
"list_id" int4 NOT NULL,
"user_id" int4 NOT NULL,
"added_by" int4,
"member_from" int4 NOT NULL,
"list_admin_from" int4
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS "public"."lists";
CREATE TABLE "public"."lists" (
"list_id" int4 DEFAULT nextval('lists_list_id_seq'::regclass) NOT NULL,
"user_id" int4 NOT NULL,
"name" varchar(50) COLLATE "default" NOT NULL
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS "public"."replies";
CREATE TABLE "public"."replies" (
"reply_id" int4 DEFAULT nextval('replies_reply_id_seq'::regclass) NOT NULL,
"task_id" int4 NOT NULL,
"user_id" int4 NOT NULL,
"posted" int4 NOT NULL,
"text" text COLLATE "default" NOT NULL
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS "public"."reply_edits";
CREATE TABLE "public"."reply_edits" (
"user_id" int4 NOT NULL,
"reply_id" int4 NOT NULL,
"reply_edit_date" int4 NOT NULL
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS "public"."tags";
CREATE TABLE "public"."tags" (
"tag_id" int4 DEFAULT nextval('tags_tag_id_seq'::regclass) NOT NULL,
"list_id" int4 NOT NULL,
"tag" varchar(50) COLLATE "default" NOT NULL
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS "public"."task_edits";
CREATE TABLE "public"."task_edits" (
"user_id" int4 NOT NULL,
"task_id" int4 NOT NULL,
"task_edit_date" int4 NOT NULL
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS "public"."task_tags";
CREATE TABLE "public"."task_tags" (
"task_id" int4 NOT NULL,
"tag_id" int4 NOT NULL
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS "public"."tasks";
CREATE TABLE "public"."tasks" (
"task_id" int4 DEFAULT nextval('tasks_task_id_seq'::regclass) NOT NULL,
"list_id" int4 NOT NULL,
"user_id" int4 NOT NULL,
"title" varchar(50) COLLATE "default" NOT NULL,
"description" text COLLATE "default" NOT NULL,
"priority" int4 NOT NULL,
"create_date" int4 NOT NULL,
"due_date" int4,
"done_date" int4
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS "public"."tasks_assignment";
CREATE TABLE "public"."tasks_assignment" (
"task_id" int4 NOT NULL,
"user_id" int4 NOT NULL,
"assigned_by" int4,
"assign_date" int4 NOT NULL
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS "public"."users";
CREATE TABLE "public"."users" (
"user_id" int4 DEFAULT nextval('users_user_id_seq'::regclass) NOT NULL,
"nick" varchar(30) COLLATE "default" NOT NULL,
"password" varchar(50) COLLATE "default" NOT NULL,
"email" varchar(100) COLLATE "default" NOT NULL,
"last_login" int4,
"app_admin_from" int4
)
WITH (OIDS=FALSE);

ALTER SEQUENCE "public"."lists_list_id_seq" OWNED BY "lists"."list_id";
ALTER SEQUENCE "public"."replies_reply_id_seq" OWNED BY "replies"."reply_id";
ALTER SEQUENCE "public"."tags_tag_id_seq" OWNED BY "tags"."tag_id";
ALTER SEQUENCE "public"."tasks_task_id_seq" OWNED BY "tasks"."task_id";
ALTER SEQUENCE "public"."users_user_id_seq" OWNED BY "users"."user_id";

ALTER TABLE "public"."events" ADD PRIMARY KEY ("event_key");
ALTER TABLE "public"."list_users" ADD PRIMARY KEY ("list_id", "user_id");
ALTER TABLE "public"."lists" ADD UNIQUE ("user_id", "name");
ALTER TABLE "public"."lists" ADD PRIMARY KEY ("list_id");
ALTER TABLE "public"."replies" ADD PRIMARY KEY ("reply_id");
ALTER TABLE "public"."reply_edits" ADD PRIMARY KEY ("user_id", "reply_id", "reply_edit_date");
ALTER TABLE "public"."tags" ADD UNIQUE ("tag", "list_id");
ALTER TABLE "public"."tags" ADD PRIMARY KEY ("tag_id");
ALTER TABLE "public"."task_edits" ADD PRIMARY KEY ("user_id", "task_id", "task_edit_date");
ALTER TABLE "public"."task_tags" ADD PRIMARY KEY ("task_id", "tag_id");
ALTER TABLE "public"."tasks" ADD UNIQUE ("list_id", "title");
ALTER TABLE "public"."tasks" ADD PRIMARY KEY ("task_id");
ALTER TABLE "public"."tasks_assignment" ADD PRIMARY KEY ("task_id", "user_id");
ALTER TABLE "public"."users" ADD UNIQUE ("nick");
ALTER TABLE "public"."users" ADD UNIQUE ("email");
ALTER TABLE "public"."users" ADD PRIMARY KEY ("user_id");

ALTER TABLE "public"."events" ADD FOREIGN KEY ("user_id") REFERENCES "public"."users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."list_users" ADD FOREIGN KEY ("list_id") REFERENCES "public"."lists" ("list_id") ON DELETE NO ACTION ON UPDATE CASCADE;
ALTER TABLE "public"."list_users" ADD FOREIGN KEY ("added_by") REFERENCES "public"."users" ("user_id") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "public"."list_users" ADD FOREIGN KEY ("user_id") REFERENCES "public"."users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."lists" ADD FOREIGN KEY ("user_id") REFERENCES "public"."users" ("user_id") ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE "public"."replies" ADD FOREIGN KEY ("user_id") REFERENCES "public"."users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."replies" ADD FOREIGN KEY ("task_id") REFERENCES "public"."tasks" ("task_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."reply_edits" ADD FOREIGN KEY ("user_id") REFERENCES "public"."users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."reply_edits" ADD FOREIGN KEY ("reply_id") REFERENCES "public"."replies" ("reply_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."tags" ADD FOREIGN KEY ("list_id") REFERENCES "public"."lists" ("list_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."task_edits" ADD FOREIGN KEY ("task_id") REFERENCES "public"."tasks" ("task_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."task_edits" ADD FOREIGN KEY ("user_id") REFERENCES "public"."users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."task_tags" ADD FOREIGN KEY ("tag_id") REFERENCES "public"."tags" ("tag_id") ON DELETE NO ACTION ON UPDATE CASCADE;
ALTER TABLE "public"."task_tags" ADD FOREIGN KEY ("task_id") REFERENCES "public"."tasks" ("task_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."tasks" ADD FOREIGN KEY ("user_id") REFERENCES "public"."users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."tasks" ADD FOREIGN KEY ("list_id") REFERENCES "public"."lists" ("list_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."tasks_assignment" ADD FOREIGN KEY ("assigned_by") REFERENCES "public"."users" ("user_id") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "public"."tasks_assignment" ADD FOREIGN KEY ("task_id") REFERENCES "public"."tasks" ("task_id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "public"."tasks_assignment" ADD FOREIGN KEY ("user_id") REFERENCES "public"."users" ("user_id") ON DELETE CASCADE ON UPDATE CASCADE;
