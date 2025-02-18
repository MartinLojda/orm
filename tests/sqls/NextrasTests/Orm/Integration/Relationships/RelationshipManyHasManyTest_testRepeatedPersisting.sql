SELECT "books".* FROM "books" AS "books" WHERE "books"."id" = 1;
START TRANSACTION;
INSERT INTO "tags" ("name", "is_global") VALUES ('A', 'y');
SELECT CURRVAL('public.tags_id_seq');
INSERT INTO "tags" ("name", "is_global") VALUES ('B', 'y');
SELECT CURRVAL('public.tags_id_seq');
INSERT INTO "books_x_tags" ("book_id", "tag_id") VALUES (1, 4), (1, 5);
COMMIT;
START TRANSACTION;
UPDATE "tags" SET "name" = 'X' WHERE "id" = 4;
COMMIT;
