<?php

namespace Hartmann\Enum;


use BadMethodCallException;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
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
     * @param static|mixed $value Either another instance of static or a value
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
     * {@inheritDoc}
     */
    public static function getValues(): array
    {
        return array_values(static::toArray());
    }

    /**
     * {@inheritDoc}
     */
    public static function getNames(): array
    {
        return array_keys(static::toArray());
    }

    /**
     * {@inheritDoc}
     */
    public static function toArray(): array
    {
        $class = static::class;

        if (!isset(static::$cache[$class])) {
            try {
                $reflection = new ReflectionClass($class);
            } catch (ReflectionException $e) {
                return [];
            }
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * {@inheritDoc}
     */
    public static function isDefined($value): bool
    {
        return in_array($value, static::toArray(), true);
    }

    /**
     * This is a convenient way to access the enum constants. If the constant does not exist, an Exception gets thrown.
     * For a proper auto-completion the enum constants should be documented as static method
     *
     * @param string $name      The constants name, should be declared protected or private
     * @param array  $arguments Not required in this implementation
     *
     * @return static
     * @throws \BadMethodCallException When attempting to call an unknown constant
     * @example MyEnum::ConstantName()
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
     * {@inheritDoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return array_search($this->value, static::toArray(), true);
    }

    /**
     * {@inheritDoc}
     */
    public function equals(EnumInterface $enum): bool
    {
        return $this->getValue() === $enum->getValue() && static::class === get_class($enum);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }
}