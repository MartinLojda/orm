<?php declare(strict_types = 1);

namespace Nextras\Orm\Entity\PropertyWrapper;


use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Entity\ImmutableValuePropertyWrapper;
use Nextras\Orm\Entity\PropertyComparator;
use Nextras\Orm\Exception\InvalidPropertyValueException;
use Nextras\Orm\Exception\NullValueException;


/**
 * DateTimeImmutable property wrapper. Handles auto-conversion from string, int (unix timestamp) and DateTimeInterface
 * instances to DateTimeImmutable (sub-)type.
 */
class DateTimeWrapper extends ImmutableValuePropertyWrapper implements PropertyComparator
{
	public function convertToRawValue(mixed $value): mixed
	{
		/** @var class-string<covariant DateTimeImmutable> $rawType */
		$rawType = array_key_first($this->propertyMetadata->types);

		if ($value instanceof $rawType) {
			return $value;

		} elseif ($value instanceof \DateTimeInterface) {
			return new $rawType($value->format('c'));

		} elseif ($value === null) {
			return null;

		} elseif (is_string($value)) {
			if ($value === '') throw new InvalidPropertyValueException($this->propertyMetadata);

			$tmp = new $rawType($value);
			return $tmp->setTimezone(new \DateTimeZone(date_default_timezone_get()));

		} elseif (ctype_digit((string) $value)) {
			return new $rawType("@{$value}");

		} else {
			throw new InvalidPropertyValueException($this->propertyMetadata);
		}
	}


	public function convertFromRawValue($value)
	{
		if ($value === null && !$this->propertyMetadata->isNullable) {
			throw new NullValueException($this->propertyMetadata);
		}

		// The string conversion from raw values is used when using {default} modifier in property definition.
		// This string value is considered to be a raw value.
		if (is_string($value)) {
			if ($value === '') throw new InvalidPropertyValueException($this->propertyMetadata);

			/** @var class-string<covariant DateTimeImmutable> $rawType */
			$rawType = array_key_first($this->propertyMetadata->types);
			$tmp = new $rawType($value);
			return $tmp->setTimezone(new \DateTimeZone(date_default_timezone_get()));
		}

		return $value;
	}


	public function setInjectedValue($value): bool
	{
		if ($value === null && !$this->propertyMetadata->isNullable) {
			throw new NullValueException($this->propertyMetadata);
		}
		return parent::setInjectedValue($this->convertToRawValue($value));
	}


	public function equals(mixed $a, mixed $b): bool
	{
		assert($a === null || $a instanceof \DateTimeImmutable);
		assert($b === null || $b instanceof \DateTimeImmutable);
		return $a?->getTimestamp() === $b?->getTimestamp();
	}


	public function compare(mixed $a, mixed $b): int
	{
		assert($a === null || $a instanceof \DateTimeImmutable);
		assert($b === null || $b instanceof \DateTimeImmutable);
		return $a?->getTimestamp() <=> $b?->getTimestamp();
	}
}
