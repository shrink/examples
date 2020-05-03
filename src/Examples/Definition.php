<?php

declare(strict_types=1);

namespace Shrink\Examples;

final class Definition implements DefinesExample
{
    /**
     * Type of the instance created by this definition.
     *
     * @var class-string $type
     */
    private string $type;

    /**
     * Builds instances of the type.
     */
    private BuildsExampleInstances $builder;

    /**
     * @var array<mixed>
     */
    private array $defaults;

    /**
     * @param class-string $type
     * @param array<mixed> $defaults
     */
    public function __construct(
        string $type,
        BuildsExampleInstances $builder,
        array $defaults = []
    ) {
        $this->type = $type;
        $this->builder = $builder;
        $this->defaults = $defaults;
    }

    /**
     * Type of instance created.
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Default values to be used for parameters.
     *
     * @return array<mixed>
     */
    public function defaults(): array
    {
        return $this->defaults;
    }

    /**
     * Build an instance with the provided parameters.
     *
     * @param array<mixed> $parameters
     */
    public function build(array $parameters): object
    {
        return $this->builder->build($parameters);
    }
}
