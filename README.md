# Examples

Compose example Value Objects and Entities for testing.

## Usage

Examples for a `type` are registered to be built by a `BuildsExampleInstances`
using a set of default parameters.

```php
$examples = new Examples();

$examples->register(
    Person::class,
    new CallableBuilder(
        fn(string $name, int $age): Person => new Person($name, $age)
    ),
    ['name' => 'Jane', 'age' => 30]
);

$person = $examples->make(new Example(Person::class));
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
