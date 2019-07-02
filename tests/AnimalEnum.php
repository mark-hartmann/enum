<?php

namespace Hartmann\Enum\Tests;


use Hartmann\Enum\Enum;

/**
 * Class AnimalEnum
 *
 * @package Hartmann\Enum\Tests
 *
 * @method static AnimalEnum Dog()
 * @method static AnimalEnum Cat()
 * @method static AnimalEnum Horse()
 * @method static AnimalEnum Fish()
 */
class AnimalEnum extends Enum
{
    protected const Dog = 0;
    protected const Cat = 2;
    protected const Horse = 4;
    protected const Fish = 'blubb';
}