SELECT "books".* FROM "books" AS "books" WHERE (("books"."id" = 1));
START TRANSACTION;
INSERT INTO "eans" ("code", "type") VALUES ('1234', 2);
SELECT CURRVAL('public.eans_id_seq');
UPDATE "books" SET "ean_id" = 1 WHERE "id" = 1;
COMMIT;
SELECT "eans".* FROM "eans" AS "eans" WHERE (("eans"."id" = 1));
SELECT "books".* FROM "books" AS "books" WHERE "books"."ean_id" IN (1);
SELECT "authors".* FROM "public"."authors" AS "authors" WHERE "authors"."id" IN (1);
SELECT "authors".* FROM "public"."authors" AS "authors" WHERE "authors"."id" IN (1);
SELECT
  "books_x_tags"."tag_id",
  "books_x_tags"."book_id"
FROM
  "tags" AS "tags"
  LEFT JOIN "books_x_tags" AS "books_x_tags" ON (
    "books_x_tags"."tag_id" = "tags"."id"
  )
WHERE
  "books_x_tags"."book_id" IN (1);

SELECT "tags".* FROM "tags" AS "tags" WHERE (("tags"."id" IN (1, 2)));
SELECT "books".* FROM "books" AS "books" WHERE "books"."next_part" IN (1);
SELECT "publishers".* FROM "publishers" AS "publishers" WHERE "publishers"."publisher_id" IN (1);
START TRANSACTION;
DELETE FROM "books_x_tags" WHERE ("book_id", "tag_id") IN ((1, 1), (1, 2));
DELETE FROM "books" WHERE "id" = 1;
DELETE FROM "eans" WHERE "id" = 1;
COMMIT;
