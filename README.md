# Examples

Compose example Value Objects and Entities for testing.

```php
use Shrink\Examples\E;
use Shrink\Examples\Examples;

$examples = new Examples();

$examples->register(E::define(
    Person::class,
    ['name' => 'Alice']
));

$bob = $examples->make(E::g(Person::class, [
    'name' => 'Bob',
]));
```

## Usage

1. **Register** one or more examples with an example definition.
2. **Make** examples using an example configuration.

### Register

Examples are registered using an example definition (`DefinesExample`) which in
turn uses a builder (`BuildsExampleInstances`) to create instances from a set
of parameters.

```php
use Shrink\Examples\Definition;
use Shrink\Examples\Examples;
use Shrink\Examples\Example;

$examples = new Examples();

$examples->register(new Definition(
    string Person::class,
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

A shortcut for creating an example definition with implicit building is
included.

```php
use Shrink\Examples\E;

$definition = E::define(
    Person::class,
    ['name' => 'Alice']
);
```

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

### Make

Examples are configured with a `type` and `parameters`. Ask the `Examples`
instance to `make(ConfiguresExample $configuration)`. A default implementation
of `ConfiguresExample` is included which is constructed with the type and
parameters.

```php
use Shrink\Examples\Example;

$person = $examples->make(new Example(Person::class));
```

Parameters may be provided which will overwrite any defaults.

```php
use Shrink\Examples\Example;

$person = $examples->make(new Example(Person::class, [
    'name' => 'Alice',
]));
```

An additional shortcut is available for creating an example configuration:

```php
use Shrink\Examples\E;

$person = $examples->make(E::g(Person::class));
```

## Hooks

A set of Git Hooks are included for ensuring compliance with code requirements,
enable them by running the following command:

```console
dev:~$ git config core.hooksPath .meta/githooks
```

## License

Examples is open-sourced software licensed under the [MIT license][mit-license].

[mit-license]: https://choosealicense.com/licenses/mit/
