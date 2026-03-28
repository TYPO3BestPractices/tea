<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Ssch\TYPO3Rector\CodeQuality\General\ExtEmConfRector;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/../../Build/',
        __DIR__ . '/../../Classes/',
        __DIR__ . '/../../Configuration/',
        __DIR__ . '/../../Tests/',
        __DIR__ . '/../../ext_emconf.php',
        __DIR__ . '/../../ext_localconf.php',
    ])
    ->withPhpSets()
    ->withSets([
        // Rector sets

        // SetList::CODE_QUALITY,
        // SetList::CODING_STYLE,
        // SetList::DEAD_CODE,
        // SetList::EARLY_RETURN,
        // SetList::INSTANCEOF,
        // SetList::NAMING,
        // SetList::PRIVATIZATION,
        // SetList::STRICT_BOOLEANS,
        // SetList::TYPE_DECLARATION,

        // PHPUnit sets

        PHPUnitSetList::PHPUNIT_100,
        // PHPUnitSetList::PHPUNIT_110,
        // PHPUnitSetList::PHPUNIT_CODE_QUALITY,

        // TYPO3 Sets
        // https://github.com/sabbelasichon/typo3-rector/blob/main/src/Set/Typo3LevelSetList.php
        // https://github.com/sabbelasichon/typo3-rector/blob/main/src/Set/Typo3SetList.php

        Typo3SetList::CODE_QUALITY,
        Typo3SetList::GENERAL,

        Typo3LevelSetList::UP_TO_TYPO3_12,
        // Typo3LevelSetList::UP_TO_TYPO3_13,
    ])
    // To have a better analysis from PHPStan, we teach it here some more things
    ->withPHPStanConfigs([
        Typo3Option::PHPSTAN_FOR_RECTOR_PATH,
    ])
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ])
    ->withImportNames(true, true, false)
    ->withConfiguredRule(ExtEmConfRector::class, [
        ExtEmConfRector::PHP_VERSION_CONSTRAINT => '8.1.0-8.5.99',
        ExtEmConfRector::TYPO3_VERSION_CONSTRAINT => '12.4.41-13.4.99',
        ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => [],
    ])
    ->withSkip([
    ]);
