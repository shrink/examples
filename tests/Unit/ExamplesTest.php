<?php

declare(strict_types=1);

namespace Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Shrink\Examples\BuildsExampleInstances;
use Shrink\Examples\ConfiguresExample;
use Shrink\Examples\DefinesExample;
use Shrink\Examples\Examples;
use StdClass;

final class ExamplesTest extends TestCase
{
    /**
     * @test
     */
    public function ExampleOfTypeIsMadeByRegisteredBuilder(): void
    {
        $expectedExample = new StdClass();

        ($builder = $this->createMock(BuildsExampleInstances::class))
            ->method('build')
            ->willReturn($expectedExample);

        $examples = new Examples();

        $definition = $this->createMock(DefinesExample::class);
        $definition->method('type')->willReturn(StdClass::class);
        $definition->method('build')->with([])->willReturn($expectedExample);

        $examples->register($definition);

        $configuration = $this->createMock(ConfiguresExample::class);
        $configuration->method('type')->willReturn(StdClass::class);

        $example = $examples->make($configuration);

        $this->assertSame($expectedExample, $example);
    }

    /**
     * @test
     */
    public function ExceptionIsThrownWhenTypeIsNotRegistered(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Examples())->make(
            $this->createMock(ConfiguresExample::class)
        );
    }

    /**
     * @test
     */
    public function ExampleIsMadeWithNestedExamples(): void
    {
        $expectedParent = (object) [
            'child' => $expectedChild = (object) ['x' => 'y'],
        ];

        $examples = new Examples();

        $parentDefinition = $this->createMock(DefinesExample::class);
        $parentDefinition->method('type')->willReturn('parents');
        $parentDefinition->method('build')->with(['child' => $expectedChild])->willReturn($expectedParent);

        $childDefinition = $this->createMock(DefinesExample::class);
        $childDefinition->method('type')->willReturn('children');
        $childDefinition->method('build')->with(['x' => 'y'])->willReturn($expectedChild);

        $examples->register($parentDefinition);
        $examples->register($childDefinition);

        $childConfiguration = $this->createMock(ConfiguresExample::class);
        $childConfiguration->method('type')->willReturn('children');
        $childConfiguration->method('parameters')->willReturn(['x' => 'y']);

        $parentConfiguration = $this->createMock(ConfiguresExample::class);
        $parentConfiguration->method('type')->willReturn('parents');
        $parentConfiguration->method('parameters')->willReturn(['child' => $childConfiguration]);

        $parent = $examples->make($parentConfiguration);

        $this->assertSame($expectedParent, $parent);
    }

    /**
     * @test
     */
    public function ExampleIsMadeWithDefaults(): void
    {
        $expectedExample = (object) [
            'a' => 'b',
            'x' => 'y',
        ];

        $definition = $this->createMock(DefinesExample::class);
        $definition->method('type')->willReturn(StdClass::class);
        $definition->method('defaults')->willReturn(['a' => 'b']);
        $definition->method('build')->with(['a' => 'b', 'x' => 'y'])->willReturn($expectedExample);

        $examples = new Examples();
        $examples->register($definition);

        $configuration = $this->createMock(ConfiguresExample::class);
        $configuration->method('type')->willReturn(StdClass::class);
        $configuration->method('parameters')->willReturn(['x' => 'y']);

        $example = $examples->make($configuration);

        $this->assertSame($expectedExample, $example);
    }
}
