# Examples

[![Packagist](https://img.shields.io/packagist/v/shrink/examples.svg)][packagist]

Compose example objects.

```php
use Shrink\Examples\E;
use Shrink\Examples\Examples;

final class Person
{
    public function __construct(
        public readonly string $name,
        public readonly int $age
    ) {}
};

$examplePersonDefinition = E::define(
    Person::class,
    name: "Alice",
    age: 30
);

($examples = new Examples())->register($examplePersonDefinition);

$person = $examples->make(E::g(Person::class, name: "Bob"));

self::assertSame(
    "Hello, Bob (age 30).",
    "Hello, {$person->name} (age {$person->age})."
);
```

- [Usage](#usage)
- [Contributing](#contributing)

## Usage

1. [**Install**](#install) the library with composer
2. [**Define**](#define-example-template) example templates
3. [**Make**](#make-an-example) examples

### Install

```console
dev:~$ composer require shrink/examples --dev
```

### Instantiate Examples

An `Examples::class` instance holds your example template definitions and
creates your examples from these definitions.

```php
use Shrink\Examples\Examples;

$examples = new Examples();
```

### Define Example Template

The `E::define()` method accepts a class `type` and zero or more named
arguments containing the example's default values.

```php
use Shrink\Examples\E;

$examples->register(
    E::define(Person::class, name: "Alice", age: 30)
);
```

:sparkles: Since v2, named arguments are used instead of a parameters array.

### Make An Example

The `E::g()` method accepts a class `type` and zero or more named arguments
containing the values that will replace any defaults.

```php
use Shrink\Examples\E;

$example = $examples->make(E::g(Person::class, name: "Bob"));

echo "Hello, {$example->name} (age {$example->age}).";
// Hello, Bob (age 30).
```

### Advanced Usage

#### Register an Example

Examples are registered using an example definition (`DefinesExample`) which in
turn uses a builder (`BuildsExampleInstances`) to create instances from a set
of parameters.

```php
use Shrink\Examples\Definition;
use Shrink\Examples\Examples;
use Shrink\Examples\Example;

$examples = new Examples();

$examples->register(new Definition(
    Person::class,
    BuildsExampleInstances $builder,
    array $defaults
));

$person = $examples->make(new Example(
    Person::class,
    array $parameters
));
```

Implicit instance building is handled through Reflection by `ReflectionBuilder`
which accepts a class name to build.

```php
use Shrink\Examples\Definition;
use Shrink\Examples\ReflectionBuilder;

$examples->register(new Definition(
    Person::class,
    new ReflectionBuilder(Person::class),
    [
        'name' => 'Alice',
        'age' => 30,
    ]
));
```

`E::define()` is a shortcut for creating an example definition with implicit
building.

Explicit instance building is handled by providing a callable which is called
with the Example parameters as method parameters.

```php
use Shrink\Examples\CallableBuilder;
use Shrink\Examples\Definition;

$examples->register(new Definition(
    Person::class,
    new CallableBuilder(
        fn(string $name, int $age): Person => new Person($name, $age)
    ),
    [
        'name' => 'Alice',
        'age' => 30
    ]
));
```

#### Make an Example

Examples are configured with a `type` and `parameters`. Ask the `Examples`
instance to `make(ConfiguresExample $configuration)`. A default implementation
of `ConfiguresExample` is included which is constructed with the type and
parameters.

```php
use Shrink\Examples\Example;

$person = $examples->make(new Example(Person::class));
```

Parameters may be provided to overwrite any defaults.

```php
use Shrink\Examples\Example;

$person = $examples->make(new Example(Person::class, [
    'name' => 'Alice',
]));
```

## Contributing

### Hooks

A set of Git Hooks are included for ensuring compliance with code requirements,
enable them by running the following command:

```console
dev:~$ git config core.hooksPath .github/hooks
```

## License

Examples is open-sourced software licensed under the [MIT license][mit-license].

[mit-license]: https://choosealicense.com/licenses/mit/
[packagist]: https://packagist.org/packages/shrink/examples
[examples/v1]: https://github.com/shrink/examples/releases/tag/v1
