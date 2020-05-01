<?php

declare(strict_types=1);

namespace Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Shrink\Examples\BuildsExampleInstances;
use Shrink\Examples\ConfiguresExample;
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
            ->with(['x' => 'y'])
            ->willReturn($expectedExample);

        $examples = new Examples();
        $examples->register(StdClass::class, $builder);

        $configuration = $this->createMock(ConfiguresExample::class);
        $configuration->method('type')->willReturn(StdClass::class);
        $configuration->method('parameters')->willReturn(['x' => 'y']);

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
            'child' => $child = (object) ['x' => 'y'],
        ];

        $childConfiguration = $this->createMock(ConfiguresExample::class);
        $childConfiguration->method('type')->willReturn('child-objects');
        $childConfiguration->method('parameters')->willReturn(['x' => 'y']);

        ($childBuilder = $this->createMock(BuildsExampleInstances::class))
            ->method('build')
            ->with(['x' => 'y'])
            ->willReturn((object) ['x' => 'y']);

        $parentConfiguration = $this->createMock(ConfiguresExample::class);
        $parentConfiguration->method('type')->willReturn('parent-objects');
        $parentConfiguration->method('parameters')->willReturn(['child' => $childConfiguration]);

        ($parentBuilder = $this->createMock(BuildsExampleInstances::class))
            ->method('build')
            ->with(['child' => $child])
            ->willReturn($expectedParent);

        $examples = new Examples();
        $examples->register('child-objects', $childBuilder);
        $examples->register('parent-objects', $parentBuilder);

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

        ($builder = $this->createMock(BuildsExampleInstances::class))
            ->method('build')
            ->with(['a' => 'b', 'x' => 'y'])
            ->willReturn($expectedExample);

        $examples = new Examples();
        $examples->register(StdClass::class, $builder, [
            'a' => 'b',
        ]);

        $configuration = $this->createMock(ConfiguresExample::class);
        $configuration->method('type')->willReturn(StdClass::class);
        $configuration->method('parameters')->willReturn(['x' => 'y']);

        $example = $examples->make($configuration);

        $this->assertSame($expectedExample, $example);
    }
}
