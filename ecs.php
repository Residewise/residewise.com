<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareEqualNormalizeFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\IncrementStyleFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $containerConfigurator) {
    $containerConfigurator->parallel();
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/config',
        __DIR__ . '/tests',
    ]);

    $containerConfigurator->sets([SetList::COMMON, SetList::SYMPLIFY]);

    $ruleConfigurations = [
        [
            IncrementStyleFixer::class,
            ['style' => 'post'],
        ],
        [
            CastSpacesFixer::class,
            ['space' => 'none'],
        ],
        [
            YodaStyleFixer::class,
            [
                'equal' => false,
                'identical' => false,
                'less_and_greater' => false,
            ],
        ],
        [
            ConcatSpaceFixer::class,
            ['spacing' => 'one'],
        ],
        [
            CastSpacesFixer::class,
            ['space' => 'none'],
        ],
        [
            OrderedImportsFixer::class,
            ['imports_order' => ['class', 'function', 'const']],
        ],
        [
            NoSuperfluousPhpdocTagsFixer::class,
            [
                'remove_inheritdoc' => false,
                'allow_mixed' => true,
                'allow_unused_params' => false,
            ],
        ],
        [
            DeclareEqualNormalizeFixer::class,
            ['space' => 'single'],
        ],
        [
            BlankLineBeforeStatementFixer::class,
            ['statements' => ['continue', 'declare', 'return', 'throw', 'try']],
        ],
        [
            BinaryOperatorSpacesFixer::class,
            ['operators' => ['&' => 'align']],
        ],
    ];

    array_map(static fn($parameters) => $containerConfigurator->ruleWithConfiguration(...$parameters), $ruleConfigurations);


};
