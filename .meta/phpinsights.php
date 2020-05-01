<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;

return [
    'config' => [
        OrderedImportsFixer::class => [
            'imports_order' => ['class', 'const', 'function'],
            'sort_algorithm' => 'alpha',
        ],
    ],
    'remove' => [
        // Mixed typehints are explicitly allowed due to the nature of the
        // library -- example parameters are unknown.
        DisallowMixedTypeHintSniff::class,
    ],
    'requirements' => [
        'min-architecture' => 95,
        'min-complexity' => 95,
        'min-quality' => 95,
        'min-style' => 100,
    ],
];
