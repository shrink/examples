<?php

declare(strict_types=1);

use ObjectCalisthenics\Sniffs\NamingConventions\ElementNameMinimalLengthSniff;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;

return [
    'config' => [
        OrderedImportsFixer::class => [
            'imports_order' => ['class', 'const', 'function'],
            'sort_algorithm' => 'alpha',
        ],
        ElementNameMinimalLengthSniff::class => [
            'exclude' => [
                'src/Examples/E.php',
            ],
        ],
    ],
    'remove' => [
        // Mixed typehints are explicitly allowed due to the nature of the
        // library -- example parameters are unknown.
        DisallowMixedTypeHintSniff::class,
    ],
    'requirements' => [
        'min-architecture' => 100,
        'min-complexity' => 90,
        'min-quality' => 100,
        'min-style' => 100,
    ],
];