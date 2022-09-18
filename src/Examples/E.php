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
    public static function g(string $type, ...$parameters): Example
    {
        return new Example($type, $parameters);
    }

    /**
     * Create an Example definition for a type using the Reflection
     * Builder.
     *
     * @param class-string $type
     * @param array<mixed> $defaults
     */
    public static function define(string $type, ...$defaults): Definition
    {
        return new Definition($type, new ReflectionBuilder($type), $defaults);
    }
}
