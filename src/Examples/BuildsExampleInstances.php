<?php

declare(strict_types=1);

namespace Shrink\Examples;

interface BuildsExampleInstances
{
    /**
     * Build an instance of an object using the provided parameters to define
     * how the object is constructed.
     *
     * @param array<mixed> $parameters
     */
    public function build(array $parameters): object;
}
