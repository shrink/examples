# Examples

Compose example Value Objects and Entities for testing.

## Usage

1. **Register** one or more examples by type
2. **Make** examples with parameters

### Register

Examples for a `type` are registered to be built by a `BuildsExampleInstances`
using a set of `default` parameters.

```php
use Shrink\Examples\Examples;

$examples = new Examples();

$examples->register(
    $type = Person::class,
    BuildsExampleInstances $builder,
    $defaults = []
);
```

Implicit instance building is handled through Reflection by `ReflectionBuilder`
which accepts a class name to build.

```php
use Shrink\Examples\ReflectionBuilder;

$examples->register(
    Person::class,
    new ReflectionBuilder(Person::class),
    [
        'name' => 'Jane',
        'age' => 30,
    ]
);
```

Explicit instance building is handled by providing a callable which is called
with the Example parameters as method parameters.

```php
use Shrink\Examples\CallableBuilder;

$examples->register(
    Person::class,
    new CallableBuilder(
        fn(string $name, int $age): Person => new Person($name, $age)
    ),
    [
        'name' => 'Jane',
        'age' => 30
    ]
);
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
    'name' => 'Jane Doe',
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
