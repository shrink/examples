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
     * List of example definitions keyed by the example type.
     *
     * @var array<\Shrink\Examples\DefinesExample>
     */
    private array $definitions = [];

    /**
     * Register a new example definition.
     */
    public function register(DefinesExample $definition): void
    {
        $this->definitions[$definition->type()] = $definition;
    }

    /**
     * Make an example instance from the Example configuration.
     */
    public function make(ConfiguresExample $configuration): object
    {
        $type = $configuration->type();

        if (! array_key_exists($type, $this->definitions)) {
            throw new InvalidArgumentException(
                "{$type} is not registered, an example cannot be built."
            );
        }

        $definition = $this->definitions[$configuration->type()];

        $parameters = $this->fillParameters(array_merge(
            $definition->defaults(),
            $configuration->parameters()
        ));

        return $definition->build($parameters);
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
