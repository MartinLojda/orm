SELECT
  "books".*
FROM
  "books" AS "books"
  LEFT JOIN "books_x_tags" AS "books_x_tags_any" ON (
    "books"."id" = "books_x_tags_any"."book_id"
  )
  LEFT JOIN "tags" AS "tags_any" ON (
    (
      "books_x_tags_any"."tag_id" = "tags_any"."id"
    )
    AND "tags_any"."id" = 1
  )
  LEFT JOIN "public"."authors" AS "author" ON (
    "books"."author_id" = "author"."id"
  )
  LEFT JOIN "publishers" AS "publisher" ON (
    "books"."publisher_id" = "publisher"."publisher_id"
  )
GROUP BY
  "books"."id",
  "author"."name",
  "publisher"."name"
HAVING
  (
    ("author"."name" = 'Writer 2')
    OR (
      "publisher"."name" = 'Nextras publisher C'
    )
    OR (
      COUNT("tags_any"."id") > 0
    )
  );
