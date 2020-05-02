<?php

declare(strict_types=1);

namespace Shrink\Examples;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use function array_map;
use function array_merge;
use function array_values;
use function is_null;

final class ReflectionBuilder implements BuildsExampleInstances
{
    /**
     * @var class-string $target
     */
    private string $target;

    /**
     * @param class-string $target
     */
    public function __construct(string $target)
    {
        $this->target = $target;
    }

    /**
     * Build an instance of $target using the provided parameters as the values
     * for constructor arguments.
     *
     * @param array<mixed> $parameters
     */
    public function build(array $parameters): object
    {
        $target = new ReflectionClass($this->target);

        $constructor = $target->getConstructor();

        if (is_null($constructor)) {
            return $target->newInstance();
        }

        /** @var array<int,mixed> $constructorParameters */
        $constructorParameters = $this->createOrderedConstructorParameters(
            $constructor,
            $parameters
        );

        return $target->newInstanceArgs($constructorParameters);
    }

    /**
     * Assemble parameters for a constructor using values from a key/value list
     * containing values keyed by the (case-sensitive) parameter name.
     *
     * @param array<mixed> $parameters
     *
     * @return array<mixed>
     */
    private function createOrderedConstructorParameters(
        ReflectionMethod $constructor,
        array $parameters
    ): array {
        $constructorParameters = $this->keyMethodParametersWithDefaults(
            $constructor
        );

        return array_merge($constructorParameters, $parameters);
    }

    /**
     * Create a key/value list of method parameters keyed by the parameter name
     * with the parameter default as the value.
     *
     * @return array<string,mixed>
     */
    private function keyMethodParametersWithDefaults(
        ReflectionMethod $method
    ): array {
        $parametersWithDefaultValues = static function (
            ReflectionParameter $parameter
        ): array {
            $hasDefault = $parameter->isDefaultValueAvailable();
            /** @var mixed $default */
            $default = $hasDefault ? $parameter->getDefaultValue() : null;

            return [$parameter->getName() => $default];
        };

        $methodParameters = array_map(
            $parametersWithDefaultValues,
            $method->getParameters()
        );

        return array_merge(...array_values($methodParameters));
    }
}
