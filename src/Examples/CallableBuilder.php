<?php

declare(strict_types=1);

namespace Shrink\Examples;

use Closure;
use function call_user_func_array;

final class CallableBuilder implements BuildsExampleInstances
{
    /**
     * Closure, created from a callable, which accepts a list of parameters
     * and builds an object.
     */
    private Closure $builder;

    public function __construct(callable $callable)
    {
        $this->builder = Closure::fromCallable($callable);
    }

    /**
     * Build an instance of an object using the provided parameters to define
     * how the object is constructed.
     *
     * @param array<mixed> $parameters
     */
    public function build(array $parameters): object
    {
        return (object) call_user_func_array(
            $this->builder,
            $parameters
        );
    }
}
