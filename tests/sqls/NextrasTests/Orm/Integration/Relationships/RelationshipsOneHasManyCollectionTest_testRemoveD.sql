SELECT "publishers".* FROM "publishers" AS "publishers" WHERE "publishers"."publisher_id" = 1;
SELECT "authors".* FROM "public"."authors" AS "authors" WHERE "authors"."id" = 1;
SELECT "authors".* FROM "public"."authors" AS "authors" WHERE "authors"."id" = 2;
SELECT "books".* FROM "books" AS "books" WHERE "books"."id" = 1;
SELECT "books".* FROM "books" AS "books" WHERE "books"."translator_id" IN (1);
