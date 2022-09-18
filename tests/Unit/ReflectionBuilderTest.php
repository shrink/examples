<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shrink\Examples\ReflectionBuilder;
use StdClass;

final class ReflectionBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function BuildsObjectUsingConstructorParameters(): void
    {
        $reflectionBuilder = new ReflectionBuilder(Person::class);

        $expectedPerson = new Person("person-id", 30, "Alice");

        $person = $reflectionBuilder->build([
            "id" => "person-id",
            "name" => "Alice",
            "age" => 30,
        ]);

        $this->assertEquals($expectedPerson, $person);
    }

    /**
     * @test
     */
    public function BuildsObjectWithUnorderedParameters(): void
    {
        $reflectionBuilder = new ReflectionBuilder(Person::class);

        $expectedPerson = new Person("person-id", 30, "Alice");

        $person = $reflectionBuilder->build([
            "age" => 30,
            "id" => "person-id",
            "name" => "Alice",
        ]);

        $this->assertEquals($expectedPerson, $person);
    }

    /**
     * @test
     */
    public function IgnoresInvalidParametersWhenBuildingObject(): void
    {
        $reflectionBuilder = new ReflectionBuilder(Person::class);

        $expectedPerson = new Person("person-id", 30, "Alice");

        $person = $reflectionBuilder->build([
            "id" => "person-id",
            "name" => "Alice",
            "invalid-parameter" => "Hello, World!",
            "age" => 30,
        ]);

        $this->assertEquals($expectedPerson, $person);
    }

    /**
     * @test
     */
    public function BuildsObjectFromClassThatDoesNotHaveAConstructor(): void
    {
        $reflectionBuilder = new ReflectionBuilder(StdClass::class);

        $object = $reflectionBuilder->build([]);

        $this->assertEquals(new StdClass(), $object);
    }

    /**
     * @test
     */
    public function BuildsObjectWithDefaultParameterValues(): void
    {
        $reflectionBuilder = new ReflectionBuilder(Person::class);

        $expectedPerson = new Person("person-id", 30);

        $person = $reflectionBuilder->build([
            "id" => "person-id",
            "age" => 30,
        ]);

        $this->assertEquals($expectedPerson, $person);
    }

    /**
     * @test
     */
    public function BuildsObjectWithoutParameters(): void
    {
        $reflectionBuilder = new ReflectionBuilder(StdClass::class);

        $expectedObject = new StdClass();

        $object = $reflectionBuilder->build();

        $this->assertEquals($expectedObject, $object);
    }
}

final class Person
{
    private string $id;
    private string $name;
    private int $age;

    public function __construct(string $id, int $age, string $name = "Default")
    {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
    }
}
