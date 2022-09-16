<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shrink\Examples\Definition;
use Shrink\Examples\E;
use Shrink\Examples\Example;
use Shrink\Examples\ReflectionBuilder;
use StdClass;

final class ETest extends TestCase
{
    /**
     * @test
     */
    public function ExampleIsBuiltForType(): void
    {
        $example = E::g(type: 'type');

        $expectedExample = new Example(type: 'type');

        $this->assertEquals($expectedExample, $example);
    }

    /**
     * @test
     */
    public function ExampleIsBuiltForTypeWithParameters(): void
    {
        $example = E::g(type: 'type', x: "y");

        $expectedExample = new Example(type: 'type', parameters: ["x" => "y"]);

        $this->assertEquals($expectedExample, $example);
    }

    /**
     * @test
     */
    public function DefinitionIsBuiltWithReflectionBuilderForType(): void
    {
        $expectedDefinition = new Definition(
            StdClass::class,
            new ReflectionBuilder(StdClass::class),
            ['x' => 'y']
        );

        $definition = E::define(StdClass::class, x: "y");

        $this->assertEquals($expectedDefinition, $definition);
    }
}
