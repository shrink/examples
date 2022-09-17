<?php

declare(strict_types=1);

namespace Tests\Documentation;

use PHPUnit\Framework\TestCase;
use Shrink\Examples\E;
use Shrink\Examples\Examples;
use StdClass;

final class ReadmeTest extends TestCase
{
    public function testIntroductionExampleProducesDocumentedResult(): void
    {
        $examplePersonDefinition = E::define(
            Person::class,
            name: "Alice",
            age: 30
        );

        ($examples = new Examples())->register($examplePersonDefinition);

        $person = $examples->make(E::g(Person::class, name: "Bob"));
        
        self::assertSame(
            "Hello, Bob (age 30).", 
            "Hello, {$person->name} (age {$person->age})."
        );
    }

    public function testNestedExampleProducesDocumentedResult(): void
    {
        $examples = new Examples();

        $examples->register(E::define(
            Location::class,
            streetAddress: "123 Default Street", 
            country: "England"
        ));

        $examples->register(E::define(
            Person::class,
            name: "Alice",
            age: 30,
            location: E::g(Location::class, country: "United States")
        ));

        $person = $examples->make(E::g(
            Person::class, 
            name: "Bob",
            location: E::g(Location::class, country: "The Netherlands")
        ));

        self::assertSame(
            "Hello, {$person->name} (age {$person->age}) from {$person->location->country}.",
            "Hello, Bob (age 30) from The Netherlands."
        );
    }
}

final class Person
{
    public function __construct(
        public readonly string $name,
        public readonly int $age,
        public readonly ?Location $location
    ) {}
};

final class Location
{
    public function __construct(
        public readonly string $streetAddress,
        public readonly string $country
    ) {}
}