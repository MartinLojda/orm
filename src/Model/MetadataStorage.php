<?php

/**
 * This file is part of the Nextras\ORM library.
 *
 * @license    MIT
 * @link       https://github.com/nextras/orm
 * @author     Jan Skrasek
 */

namespace Nextras\Orm\Model;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Object;
use Nette\Reflection\ClassType;
use Nextras\Orm\Entity\Reflection\AnnotationParser;
use Nextras\Orm\Entity\Reflection\EntityMetadata;
use Nextras\Orm\InvalidArgumentException;


class MetadataStorage extends Object
{
	/** @var EntityMetadata[] */
	private static $metadata;


	public static function get($className)
	{
		if (!isset(static::$metadata[$className])) {
			throw new InvalidArgumentException("Entity metadata for '{$className}' does not exist.");
		}
		return static::$metadata[$className];
	}


	public function __construct(IStorage $cacheStorage, array $entityClasses)
	{
		$cache = new Cache($cacheStorage, 'Nextras.Orm.metadata');
		static::$metadata = $cache->load($entityClasses, function(& $dp) use ($entityClasses) {
			$entityList = $this->prepareEntityList($entityClasses);
			$dp[Cache::FILES] = array_values($entityList);
			return $this->parseMetadata($entityClasses);
		});
	}


	private function parseMetadata($entityList)
	{
		$cache = [];
		foreach ($entityList as $className) {
			$annotationParser = new AnnotationParser($className);
			$cache[$className] = $annotationParser->getMetaData();
		}

		return $cache;
	}


	private function prepareEntityList($classes)
	{
		$list = [];
		foreach ($classes as $class) {
			$ref = new ClassType($class);
			$list[$class] = $ref->getFileName();
		}

		return $list;
	}

}