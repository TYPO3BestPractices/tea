<?php

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use TYPO3\CodingStandards\CsFixerConfig;

$config = CsFixerConfig::create();

// @TODO 4.0 no need to call this manually
$config->setParallelConfig(ParallelConfigFactory::detect());

$config->getFinder()->in('Classes')->in('Configuration')->in('Tests');
return $config;
