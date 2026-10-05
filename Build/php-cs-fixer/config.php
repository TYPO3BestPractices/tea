<?php

declare(strict_types=1);

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use TYPO3\CodingStandards\CsFixerConfig;

$config = CsFixerConfig::create();
$config->setCacheFile('Build/.cache/php-cs-fixer/.php-cs-fixer.cache');

// @TODO 4.0 no need to call this manually
$config->setParallelConfig(ParallelConfigFactory::detect());

$config->getFinder()->in('Classes')->in('Configuration')->in('Tests');
return $config;
