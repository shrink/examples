<?php

declare(strict_types=1);

namespace Shrink\Examples;

use InvalidArgumentException;
use function array_filter;
use function array_key_exists;
use function array_map;
use function array_merge;

final class Examples
{
    /**
     * List of builders keyed by the type they are capable of building.
     *
     * @var array<\Shrink\Examples\BuildsExampleInstances>
     */
    private array $builders = [];

    /**
     * Default values to be used when building an instance.
     *
     * @var array<array<mixed>>
     */
    private array $defaults = [];

    /**
     * Register a new builder for type.
     *
     * @param array<mixed> $defaults
     */
    public function register(
        string $type,
        BuildsExampleInstances $builder,
        array $defaults = []
    ): void {
        $this->builders[$type] = $builder;
        $this->defaults[$type] = $defaults;
    }

    /**
     * Make an example instance from the Example configuration.
     */
    public function make(ConfiguresExample $configuration): object
    {
        $type = $configuration->type();

        if (! array_key_exists($type, $this->builders)) {
            throw new InvalidArgumentException(
                "{$type} is not registered, an example cannot be built."
            );
        }

        $parameters = $this->fillParameters(array_merge(
            $this->defaults[$type],
            $configuration->parameters()
        ));

        return $this->builders[$type]->build($parameters);
    }

    /**
     * Fill parameters with any nested Examples.
     *
     * @param array<mixed> $parameters
     *
     * @return array<mixed>
     */
    private function fillParameters(array $parameters): array
    {
        $filterNestedExamples =
            /** @param mixed $parameter */
            static function ($parameter): bool {
                return $parameter instanceof ConfiguresExample;
            };

        $makeNestedExamples = function (ConfiguresExample $parameter): object {
            return $this->make($parameter);
        };

        $nestedExamples = array_map(
            $makeNestedExamples,
            array_filter($parameters, $filterNestedExamples)
        );

        return array_merge($parameters, $nestedExamples);
    }
}
