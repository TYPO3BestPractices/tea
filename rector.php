<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\CodeQuality\General\ConvertImplicitVariablesToExplicitGlobalsRector;
use Ssch\TYPO3Rector\CodeQuality\General\ExtEmConfRector;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Ssch\Typo3RectorTestingFramework\Set\TYPO3TestingFrameworkSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Classes/',
        __DIR__ . '/Configuration/',
        __DIR__ . '/Tests/',
        __DIR__ . '/ext_emconf.php',
        __DIR__ . '/ext_localconf.php',
    ])
    ->withPhpVersion(PhpVersion::PHP_81)
    ->withPhpSets(
        true
    )
    // Note: We're only enabling a single set by default to improve performance. (Rector needs at least a single set to
    // run.)
    //
    // You can temporarily enable more sets as needed.
    ->withSets([
        // Rector sets

        // LevelSetList::UP_TO_PHP_53,
        // LevelSetList::UP_TO_PHP_54,
        // LevelSetList::UP_TO_PHP_55,
        // LevelSetList::UP_TO_PHP_56,
        // LevelSetList::UP_TO_PHP_70,
        // LevelSetList::UP_TO_PHP_71,
        // LevelSetList::UP_TO_PHP_72,
        // LevelSetList::UP_TO_PHP_73,
        // LevelSetList::UP_TO_PHP_74,
        // LevelSetList::UP_TO_PHP_80,
        // LevelSetList::UP_TO_PHP_81,
        // LevelSetList::UP_TO_PHP_82,
        // LevelSetList::UP_TO_PHP_83,

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

        // PHPUnitSetList::PHPUNIT80_DMS,
        PHPUnitSetList::PHPUNIT_100,
        // PHPUnitSetList::PHPUNIT_CODE_QUALITY,

        // TYPO3 Sets
        // https://github.com/sabbelasichon/typo3-rector/blob/main/src/Set/Typo3LevelSetList.php
        // https://github.com/sabbelasichon/typo3-rector/blob/main/src/Set/Typo3SetList.php

        Typo3SetList::CODE_QUALITY,
        Typo3SetList::GENERAL,

        Typo3LevelSetList::UP_TO_TYPO3_12,

        // TYPO3TestingFrameworkSetList::TYPO3_TESTING_FRAMEWORK_7,
    ])
    // To have a better analysis from PHPStan, we teach it here some more things
    ->withPHPStanConfigs([
        Typo3Option::PHPSTAN_FOR_RECTOR_PATH,
    ])
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
        ConvertImplicitVariablesToExplicitGlobalsRector::class,
    ])
    ->withImportNames(true, true, false)
    ->withConfiguredRule(ExtEmConfRector::class, [
        ExtEmConfRector::PHP_VERSION_CONSTRAINT => '8.1.0-8.4.99',
        ExtEmConfRector::TYPO3_VERSION_CONSTRAINT => '12.4.26-12.4.99',
        ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => [],
    ])
    ->withSkip([
    ]);
