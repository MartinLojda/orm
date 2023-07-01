<?php declare(strict_types = 1);

/**
 * @testCase
 */

namespace NextrasTests\Orm\Mapper\Dbal;


use Mockery;
use Nextras\Dbal\Result\Result;
use Nextras\Dbal\Result\Row;
use Nextras\Orm\Collection\ArrayCollection;
use Nextras\Orm\Exception\InvalidArgumentException;
use Nextras\Orm\Mapper\Dbal\Conventions\Conventions;
use Nextras\Orm\Mapper\Dbal\DbalMapper;
use Nextras\Orm\Repository\IRepository;
use NextrasTests\Orm\Author;
use NextrasTests\Orm\TestCase;
use ReflectionProperty;
use Tester\Assert;


require_once __DIR__ . '/../../../../bootstrap.php';


class DbalMapperTest extends TestCase
{

	public function testToCollectionArray(): void
	{
		$repository = Mockery::mock(IRepository::class);

		$mapper = Mockery::mock(DbalMapper::class)->makePartial();
		$mapper->shouldReceive('getRepository')->once()->andReturn($repository);
		$conventions = Mockery::mock(Conventions::class);
		$conventions->shouldReceive('convertStorageToEntity')->andReturnUsing(function ($value) {
			return $value;
		});

		$mapper->shouldReceive('getConventions')->andReturn($conventions);

		$repository->shouldReceive('hydrateEntity')->once()->with(['id' => 1])->andReturn($a = new Author());
		$repository->shouldReceive('hydrateEntity')->once()->with(['id' => 2])->andReturn($b = new Author());
		$repository->shouldReceive('hydrateEntity')->once()->with(['id' => 3])->andReturn($c = new Author());

		$collection = $mapper->toCollection([
			['id' => 1],
			['id' => 2],
			['id' => 3],
		]);

		Assert::type(ArrayCollection::class, $collection);

		$reflection = new ReflectionProperty(ArrayCollection::class, 'data');
		$reflection->setAccessible(true);
		$data = $reflection->getValue($collection);

		Assert::same(3, count($data));
		Assert::equal($a, $data[0]);
		Assert::equal($b, $data[1]);
		Assert::equal($c, $data[2]);
	}


	public function testToCollectionResult(): void
	{
		$repository = Mockery::mock(IRepository::class);

		$mapper = Mockery::mock(DbalMapper::class)->makePartial();
		$mapper->shouldReceive('getRepository')->twice()->andReturn($repository);
		$conventions = Mockery::mock(Conventions::class);
		$conventions->shouldReceive('convertStorageToEntity')->andReturnUsing(function ($value) {
			return $value;
		});

		$mapper->shouldReceive('getConventions')->andReturn($conventions);

		$repository->shouldReceive('hydrateEntity')->once()->with(['id' => 1])->andReturn($a = new Author());
		$repository->shouldReceive('hydrateEntity')->once()->with(['id' => 2])->andReturn($b = new Author());
		$repository->shouldReceive('hydrateEntity')->once()->with(['id' => 3])->andReturn($c = new Author());

		$row = Mockery::mock(Row::class);
		$row->shouldReceive('toArray')->once()->andReturn(['id' => 1]);
		$row->shouldReceive('toArray')->once()->andReturn(['id' => 2]);
		$row->shouldReceive('toArray')->once()->andReturn(['id' => 3]);

		$result = Mockery::mock(Result::class);
		$result->shouldReceive('rewind')->once();
		$result->shouldReceive('valid')->times(3)->andReturn(true);
		$result->shouldReceive('current')->times(3)->andReturn($row);
		$result->shouldReceive('next')->times(3);
		$result->shouldReceive('valid')->once()->andReturn(false);

		$collection = $mapper->toCollection($result);

		Assert::type(ArrayCollection::class, $collection);

		$reflection = new ReflectionProperty(ArrayCollection::class, 'data');
		$reflection->setAccessible(true);
		$data = $reflection->getValue($collection);

		Assert::same(3, count($data));
		Assert::equal($a, $data[0]);
		Assert::equal($b, $data[1]);
		Assert::equal($c, $data[2]);

		Assert::throws(function () use ($mapper): void {
			// @phpstan-ignore-next-line
			$mapper->toCollection(new ArrayCollection([], $this->orm->authors));
		}, InvalidArgumentException::class);
	}

}


$test = new DbalMapperTest();
$test->run();
