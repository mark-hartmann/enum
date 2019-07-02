<?php

namespace Hartmann\Enum\Tests;


use BadMethodCallException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class EnumTest extends TestCase
{
    public function testGetValues(): void
    {
        $this->assertEquals([0, 2, 4, 'blubb'], AnimalEnum::getValues());
    }

    public function testGetNames(): void
    {
        $this->assertEquals(['Dog', 'Cat', 'Horse', 'Fish'], AnimalEnum::getNames());
    }

    public function testToArray(): void
    {
        $this->assertEquals([
            'Dog' => 0,
            'Cat' => 2,
            'Horse' => 4,
            'Fish' => 'blubb',
        ], AnimalEnum::toArray());
    }

    public function testIsDefined(): void
    {
        $this->assertTrue(AnimalEnum::isDefined(0));
        $this->assertFalse(AnimalEnum::isDefined(1));
        $this->assertTrue(AnimalEnum::isDefined(2));
        $this->assertTrue(AnimalEnum::isDefined('blubb'));
        $this->assertFalse(AnimalEnum::isDefined('meow'));
    }

    public function testInstantiatingWithWrongValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new AnimalEnum('meow');
        new AnimalEnum(new stdClass());
    }

    public function testGetValue(): void
    {
        $cat = AnimalEnum::Cat();
        $horse = AnimalEnum::Horse();

        $this->assertEquals(2, $cat->getValue());
        $this->assertEquals(4, $horse->getValue());
    }

    public function testGetName(): void
    {
        $cat = AnimalEnum::Cat();
        $horse = AnimalEnum::Horse();

        $this->assertEquals('Cat', $cat->getName());
        $this->assertEquals('Horse', $horse->getName());
    }

    public function testEquals(): void
    {
        $cat = AnimalEnum::Cat();
        $horse = AnimalEnum::Horse();

        $this->assertTrue($cat->equals(AnimalEnum::Cat()));
        $this->assertFalse($cat->equals(AnimalEnum::Horse()));
        $this->assertTrue($horse->equals(AnimalEnum::Horse()));
        $this->assertFalse($horse->equals(AnimalEnum::Dog()));
    }

    public function testInstantiatingWorksIfOtherEnumIsPassed(): void
    {
        $animal = new AnimalEnum(AnimalEnum::Fish());

        $this->assertEquals('blubb', $animal->getValue());
        $this->assertEquals('Fish', $animal->getName());
        $this->assertEquals($animal, AnimalEnum::Fish());
        $this->assertNotEquals($animal, AnimalEnum::Cat());
    }

    public function testToString(): void
    {
        $this->assertEquals('0', (string)AnimalEnum::Dog());
        $this->assertEquals('2', (string)AnimalEnum::Cat());
        $this->assertEquals('4', (string)AnimalEnum::Horse());
        $this->assertEquals('blubb', (string)AnimalEnum::Fish());
    }

    public function testCallStaticMethod(): void
    {
        $this->expectException(BadMethodCallException::class);

        AnimalEnum::Ant();
    }
}