<?php

declare(strict_types=1);

namespace Shrink\Examples;

interface ConfiguresExample
{
    /**
     * Type of the instance to be created.
     */
    public function type(): string;

    /**
     * Parameters which define how the instance is configured.
     *
     * @return array<mixed>
     */
    public function parameters(): array;
}
