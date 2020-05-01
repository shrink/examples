<?php

declare(strict_types=1);

namespace Shrink\Examples;

final class Example implements ConfiguresExample
{
    /**
     * Type of the instance to be created.
     */
    private string $type;

    /**
     * Parameters which define how the instance is configured.
     *
     * @var array<mixed>
     */
    private array $parameters;

    /**
     * @param array<mixed> $parameters
     */
    public function __construct(string $type, array $parameters = [])
    {
        $this->type = $type;
        $this->parameters = $parameters;
    }

    /**
     * Type of the instance to be created.
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Parameters which define how the instance is configured.
     *
     * @return array<mixed>
     */
    public function parameters(): array
    {
        return $this->parameters;
    }
}
