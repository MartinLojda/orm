SELECT
  "authors".*
FROM
  "public"."authors" AS "authors"
  LEFT JOIN "books" AS "books__AVG" ON (
    "authors"."id" = "books__AVG"."author_id"
  )
GROUP BY
  "authors"."id"
HAVING
  AVG("books__AVG"."price") < 110
ORDER BY
  "authors"."id" ASC;

SELECT
  "authors".*
FROM
  "public"."authors" AS "authors"
  LEFT JOIN "books" AS "books__AVG" ON (
    "authors"."id" = "books__AVG"."author_id"
  )
GROUP BY
  "authors"."id"
HAVING
  AVG("books__AVG"."price") <= 120
ORDER BY
  "authors"."id" ASC;
