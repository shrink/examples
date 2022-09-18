<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shrink\Examples\Example;

final class ExampleTest extends TestCase
{
    /**
     * @test
     */
    public function ExampleIsConfiguredByParameters(): void
    {
        $example = new Example("example", [
            "x" => "y",
            "a" => "b",
        ]);

        $this->assertSame("example", $example->type());
        $this->assertSame(["x" => "y", "a" => "b"], $example->parameters());
    }
}
