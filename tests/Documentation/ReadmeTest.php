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
}

final class Person
{
    public function __construct(
        public readonly string $name,
        public readonly int $age
    ) {}
};
