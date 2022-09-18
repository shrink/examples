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
  ) {
  }
}

$examplePersonDefinition = E::define(Person::class, name: "Alice", age: 30);

($examples = new Examples())->register($examplePersonDefinition);

$person = $examples->make(E::g(Person::class, name: "Bob"));

self::assertSame(
  "Hello, Bob (age 30).",
  "Hello, {$person->name} (age {$person->age})."
);
```

## Usage

1. [**Install**](#install) the library with composer
2. [**Define**](#define-example) examples
3. [**Make**](#make-an-object) objects

### Install

```console
dev:~$ composer require shrink/examples --dev
```

:yarn: The latest version of Examples requires PHP 8.1, use
[Examples v1][examples/v1] for PHP 7.4 and PHP 8.0 support.

### Instantiate Examples

An `Examples::class` instance holds your example definitions and creates your
objects from these definitions.

```php
use Shrink\Examples\Examples;

$examples = new Examples();
```

### Define Examples

The `E::define()` method accepts a class `type` and zero or more named
arguments, which map to the `type`'s constructor arguments.
`E::define()` returns a definition to register with your instance of Examples.

```php
use Shrink\Examples\E;

$examples->register(E::define(Person::class, name: "Alice", age: 30));
```

:sparkles: Since v2, named arguments are used instead of a parameters array.

:dna: `E::define()` is a shortcut to create a simple example definition, see
[Internals -> Definition](#definition) for building your own implementation.

### Make An Object

The `E::g()` method accepts a class `type` (referring to a registered example)
and zero or more named arguments to overwrite the example defaults. `E::g()`
returns an example configuration, which your instance of Examples will `make()`.

```php
use Shrink\Examples\E;

$example = $examples->make(E::g(Person::class, name: "Bob"));

echo "Hello, {$example->name} (age {$example->age}).";
// Hello, Bob (age 30).
```

:dna: `E::g()` is a shortcut to create a simple example configuration, see
[Internals -> Creation](#creation) for building your own implementation.

### Features

#### Nested Examples

[Examples::class] will resolve any example definitions it encounters when making
an example, allowing for many levels of nested example definitions and
configuration.

```php
final class Person
{
  public function __construct(
    public readonly string $name,
    public readonly int $age,
    public readonly ?Location $location
  ) {
  }
}

final class Location
{
  public function __construct(
    public readonly string $streetAddress,
    public readonly string $country
  ) {
  }
}

$examples = new Examples();

$examples->register(
  E::define(
    Location::class,
    streetAddress: "123 Default Street",
    country: "England"
  )
);

$examples->register(
  E::define(
    Person::class,
    name: "Alice",
    age: 30,
    location: E::g(Location::class, country: "United States")
  )
);

$person = $examples->make(
  E::g(
    Person::class,
    name: "Bob",
    location: E::g(Location::class, country: "The Netherlands")
  )
);

self::assertSame(
  "Hello, {$person->name} (age {$person->age}) from {$person->location->country}.",
  "Hello, Bob (age 30) from The Netherlands."
);
```

## Internals

### Definition

Examples are registered using an example definition (`DefinesExample`) which in
turn uses a builder (`BuildsExampleInstances`) to create an object using
optional configuration.

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

$examples->register(
  new Definition(Person::class, new ReflectionBuilder(Person::class), [
    "name" => "Alice",
    "age" => 30,
  ])
);
```

`E::define()` is a shortcut for creating an example definition with implicit
building. Explicit instance building is handled by providing a callable which is
called with the Example parameters as method parameters.

```php
use Shrink\Examples\CallableBuilder;
use Shrink\Examples\Definition;

$examples->register(
  new Definition(
    Person::class,
    new CallableBuilder(
      fn(string $name, int $age): Person => new Person($name, $age)
    ),
    [
      "name" => "Alice",
      "age" => 30,
    ]
  )
);
```

#### Creation

Objects are made, from an example, with an optional configuration of
`parameters`. Ask the `Examples` instance to
`make(ConfiguresExample $configuration)`. A default implementation of
`ConfiguresExample` is included which is constructed with the type and
parameters.

```php
use Shrink\Examples\Example;

$person = $examples->make(new Example(Person::class));
```

Parameters may be provided to overwrite any defaults.

```php
use Shrink\Examples\Example;

$person = $examples->make(
  new Example(Person::class, [
    "name" => "Alice",
  ])
);
```

## License

Examples is open-sourced software licensed under the [MIT license][mit-license].

[mit-license]: https://choosealicense.com/licenses/mit/
[packagist]: https://packagist.org/packages/shrink/examples
[examples/v1]: https://github.com/shrink/examples/tree/v1
[examples::class]: src/Examples/Examples.php
