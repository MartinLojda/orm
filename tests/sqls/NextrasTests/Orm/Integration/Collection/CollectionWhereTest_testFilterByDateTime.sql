SELECT COUNT(*) AS count FROM (SELECT "tag_followers"."tag_id", "tag_followers"."author_id" FROM "tag_followers" AS "tag_followers" WHERE (("tag_followers"."created_at" IN ('2014-01-01 00:10:00.000000'::timestamptz, '2014-01-02 00:10:00.000000'::timestamptz)))) temp;
SELECT "tag_followers".* FROM "tag_followers" AS "tag_followers" WHERE (("tag_followers"."created_at" IN ('2014-01-01 00:10:00.000000'::timestamptz, '2014-01-02 00:10:00.000000'::timestamptz)));