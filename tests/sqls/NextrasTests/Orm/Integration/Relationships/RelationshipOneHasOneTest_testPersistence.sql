SELECT "authors".* FROM "public"."authors" AS "authors" WHERE (("authors"."id" = 1));
SELECT "publishers".* FROM "publishers" AS "publishers" WHERE (("publishers"."publisher_id" = 1));
SELECT "authors".* FROM "public"."authors" AS "authors" WHERE (("authors"."id" = 2));
SELECT "publishers".* FROM "publishers" AS "publishers" WHERE (("publishers"."publisher_id" = 2));
START TRANSACTION;
INSERT INTO "books" ("title", "author_id", "translator_id", "next_part", "ean_id", "publisher_id", "genre", "published_at", "printed_at", "price", "price_currency", "orig_price_cents", "orig_price_currency") VALUES ('Games of Thrones II', 2, NULL, NULL, NULL, 2, 'fantasy', '2021-12-31 23:59:59.000000'::timestamp, NULL, NULL, NULL, NULL, NULL);
SELECT CURRVAL('public.books_id_seq');
INSERT INTO "books" ("title", "author_id", "translator_id", "next_part", "ean_id", "publisher_id", "genre", "published_at", "printed_at", "price", "price_currency", "orig_price_cents", "orig_price_currency") VALUES ('Games of Thrones I', 1, NULL, 5, NULL, 1, 'fantasy', '2021-12-31 23:59:59.000000'::timestamp, NULL, NULL, NULL, NULL, NULL);
SELECT CURRVAL('public.books_id_seq');
COMMIT;
