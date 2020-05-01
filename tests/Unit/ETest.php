<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shrink\Examples\E;
use Shrink\Examples\Example;
use StdClass;

final class ETest extends TestCase
{
    /**
     * @test
     */
    public function ExampleIsBuiltForType(): void
    {
        $example = E::g('type');

        $expectedExample = new Example('type', []);

        $this->assertEquals($expectedExample, $example);
    }

    /**
     * @test
     */
    public function ExampleIsBuiltForTypeWithParameters(): void
    {
        $example = E::g('type', ['x' => 'y']);

        $expectedExample = new Example('type', ['x' => 'y']);

        $this->assertEquals($expectedExample, $example);
    }
}
