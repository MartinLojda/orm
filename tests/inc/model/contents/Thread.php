<?php declare(strict_types = 1);

namespace NextrasTests\Orm;


use Nextras\Orm\Relationships\OneHasMany;


/**
 * @property-read string              $type     {default thread}
 * @property      OneHasMany<Comment> $comments {1:m Comment::$thread, cascade=[persist, remove]}
 * @property Book|null                $book     {1:1 Book::$thread}
 */
class Thread extends ThreadCommentCommon
{
}
