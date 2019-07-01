<?php

namespace Hartmann\Enum;


use BadMethodCallException;
use InvalidArgumentException;
use ReflectionClass;
use function array_key_exists;

/**
 * Class Enum
 *
 * @package Hartmann\Enum
 * @author  Mark Hartmann <contact@mark-hartmann.com>
 */
abstract class Enum implements EnumInterface
{
    protected static $cache = [];
    protected $value;

    /**
     * Enum constructor.
     *
     * @param mixed $value
     *
     * @throws \InvalidArgumentException If unknown value is given
     */
    public function __construct($value)
    {
        if ($value instanceof static) {
            $this->value = $value->getValue();

            return;
        }

        if (!self::isDefined($value)) {
            throw new InvalidArgumentException(sprintf('Value %s is not part of %s', $value, static::class));
        }

        $this->value = $value;
    }

    /**
     * Retrieves an array of the values of the constants
     *
     * @return mixed[]
     */
    public static function getValues(): array
    {
        return array_values(static::toArray());
    }

    /**
     * Retrieves an array of the names of the constants
     *
     * @return string[]
     */
    public static function getNames(): array
    {
        return array_keys(static::toArray());
    }

    /**
     * Retrieves an array containing the names of the constants and their corresponding values
     *
     * @return mixed[]
     */
    public static function toArray(): array
    {
        $class = static::class;

        if (!isset(static::$cache[$class])) {
            try {
                $reflection = new ReflectionClass($class);
            } catch (\ReflectionException $e) {
                return [];
            }
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * Returns a Boolean telling whether a given value exists
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isDefined($value): bool
    {
        return in_array($value, static::toArray(), true);
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return static
     */
    public static function __callStatic(string $name, array $arguments = null): EnumInterface
    {
        $array = static::toArray();
        if (array_key_exists($name, $array) || isset($array[$name])) {
            return new static($array[$name]);
        }

        throw new BadMethodCallException(sprintf('No enum constant "%s" in class %s', $name, static::class));
    }

    /**
     * Retrieves the value of the constant
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Retrieves the name of the constant
     *
     * @return string
     */
    public function getName(): string
    {
        return array_search($this->value, static::toArray(), true);
    }

    /**
     * Returns a Boolean indicating whether this instance is equal to a specified enum object
     *
     * @param \Hartmann\Enum\EnumInterface $enum
     *
     * @return bool
     */
    public function equals(EnumInterface $enum): bool
    {
        return $this->getValue() === $enum->getValue() && static::class === get_class($enum);
    }

    /**
     * Converts the value of this instance to its equivalent string representation
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }
}