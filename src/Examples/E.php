<?php

declare(strict_types=1);

namespace Shrink\Examples;

final class E
{
    /**
     * Shortcut to create an Example configuration.
     *
     * @param array<mixed> $parameters
     */
    public static function g(string $type, array $parameters = []): Example
    {
        return new Example($type, $parameters);
    }
}
