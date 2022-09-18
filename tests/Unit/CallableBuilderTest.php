<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shrink\Examples\CallableBuilder;
use StdClass;

final class CallableBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function BuildsExampleWithParameters(): void
    {
        $callableBuilder = new CallableBuilder(function (
            string $a,
            string $x
        ): StdClass {
            return (object) [
                "a" => $a,
                "x" => $x,
            ];
        });

        $expectedObject = new StdClass();
        $expectedObject->a = "b";
        $expectedObject->x = "y";

        $object = $callableBuilder->build([
            "a" => "b",
            "x" => "y",
        ]);

        $this->assertEquals($expectedObject, $object);
    }

    /**
     * @test
     */
    public function BuildsExampleUsingInvokable(): void
    {
        $invokable = new class {
            public function __invoke(string $a, string $x): object
            {
                return (object) [
                    "a" => $a,
                    "x" => $x,
                ];
            }
        };

        $callableBuilder = new CallableBuilder($invokable);

        $expectedObject = new StdClass();
        $expectedObject->a = "b";
        $expectedObject->x = "y";

        $object = $callableBuilder->build([
            "a" => "b",
            "x" => "y",
        ]);

        $this->assertEquals($expectedObject, $object);
    }

    /**
     * @test
     */
    public function BuildsExampleWithoutParameters(): void
    {
        $callableBuilder = new CallableBuilder(fn(): StdClass => (object) []);

        $expectedObject = new StdClass();

        $object = $callableBuilder->build();

        $this->assertEquals($expectedObject, $object);
    }
}
