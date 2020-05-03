<?php

declare(strict_types=1);

namespace Shrink\Examples;

interface DefinesExample
{
    /**
     * Type of instance created.
     */
    public function type(): string;

    /**
     * Default values to be used for parameters.
     *
     * @return array<mixed>
     */
    public function defaults(): array;

    /**
     * Build an instance.
     *
     * @param array<mixed> $parameters
     */
    public function build(array $parameters): object;
}
