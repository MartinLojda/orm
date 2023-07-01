<?php declare(strict_types = 1);

/**
 * @testCase
 * @dataProvider ../../../databases.ini
 */

namespace NextrasTests\Orm\Integration\Relationships;


use Nextras\Orm\Collection\ICollection;
use NextrasTests\Orm\Author;
use NextrasTests\Orm\DataTestCase;
use NextrasTests\Orm\TagFollower;
use Tester\Assert;


require_once __DIR__ . '/../../../bootstrap.php';


class RelationshipOneHasManyCompositePkTest extends DataTestCase
{
	public function testHasMany(): void
	{
		/** @var Author $author */
		$author = $this->orm->authors->getByIdChecked(1);
		Assert::same(2, $author->tagFollowers->count());
		Assert::same(2, $author->tagFollowers->countStored());
	}


	public function testLimit(): void
	{
		$tagFollower = new TagFollower();
		$tagFollower->tag = 2;
		$tagFollower->author = 1;
		$this->orm->tagFollowers->persistAndFlush($tagFollower);

		$tagFollowers = [];
		$authors = $this->orm->authors->findAll()->orderBy('id');

		foreach ($authors as $author) {
			foreach ($author->tagFollowers->toCollection()->limitBy(2)
				         ->orderBy('tag', ICollection::DESC) as $innerTagFollower) {
				$tagFollowers[] = $innerTagFollower->getRawValue('tag');
			}
		}

		Assert::same([3, 2, 2], $tagFollowers);
	}


	public function testRemoveHasMany(): void
	{
		$tagFollower = $this->orm->tagFollowers->getByChecked(['tag' => 3, 'author' => 1]);
		$this->orm->tagFollowers->removeAndFlush($tagFollower);

		Assert::same(1, $this->orm->authors->getByIdChecked(1)->tagFollowers->count());
		Assert::same(1, $this->orm->authors->getByIdChecked(1)->tagFollowers->countStored());
	}

}


$test = new RelationshipOneHasManyCompositePkTest();
$test->run();
