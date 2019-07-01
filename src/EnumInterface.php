<?php

namespace Hartmann\Enum;

/**
 * Interface EnumInterface
 *
 * @package Hartmann\Enum
 */
interface EnumInterface
{
    /**
     * Retrieves an array of the values of the constants
     *
     * @return mixed[]
     */
    public static function getValues(): array;

    /**
     * Retrieves an array of the names of the constants
     *
     * @return string[]
     */
    public static function getNames(): array;

    /**
     * Retrieves an array containing the names of the constants and their corresponding values
     *
     * @return mixed[]
     */
    public static function toArray(): array;

    /**
     * Returns a Boolean telling whether a given value exists
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isDefined($value): bool;

    /**
     * Retrieves the value of the constant
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Retrieves the name of the constant
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns a Boolean indicating whether this instance is equal to a specified enum object
     *
     * @param \Hartmann\Enum\EnumInterface $enum
     *
     * @return bool
     */
    public function equals(EnumInterface $enum): bool;

    /**
     * Converts the value of this instance to its equivalent string representation
     *
     * @return string
     */
    public function __toString(): string;
}