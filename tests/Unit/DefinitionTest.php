<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shrink\Examples\BuildsExampleInstances;
use Shrink\Examples\Definition;
use StdClass;

final class DefinitionTest extends TestCase
{
    /**
     * @test
     */
    public function InstanceIsBuiltWithParameters(): void
    {
        $parameters = [
            'x' => 'y',
            'a' => 'b',
        ];

        $expectedInstance = (object) $parameters;

        ($builder = $this->createMock(BuildsExampleInstances::class))
            ->method('build')
            ->with($parameters)
            ->willReturn($expectedInstance);

        $definition = new Definition('type', $builder);

        $instance = $definition->build($parameters);

        $this->assertEquals($expectedInstance, $instance);
    }

    /**
     * @test
     */
    public function DefinitionProvidesDefaults(): void
    {
        $defaults = [
            'x' => 'y',
            'a' => 'b',
        ];

        $definition = new Definition(
            'type',
            $this->createMock(BuildsExampleInstances::class),
            $defaults
        );

        $this->assertSame($defaults, $definition->defaults());
    }

    /**
     * @test
     */
    public function DefinitionIsForExampleOfType(): void
    {
        $definition = new Definition(
            StdClass::class,
            $this->createMock(BuildsExampleInstances::class)
        );

        $this->assertSame(StdClass::class, $definition->type());
    }
}
