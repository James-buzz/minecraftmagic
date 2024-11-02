<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
    ])
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_110,
        LaravelSetList::LARAVEL_CODE_QUALITY,

    ])->withRules([
        \Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector::class,
        \Rector\CodingStyle\Rector\Stmt\RemoveUselessAliasInUseStatementRector::class,
        \Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector::class,
        \Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector::class,
    ]);
