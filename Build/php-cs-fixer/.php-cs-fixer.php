<?php

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use TYPO3\CodingStandards\CsFixerConfig;

$config = CsFixerConfig::create();

// This is required as long as we are on PHPUnit 9.x. It can be removed after the switch to PHPUnit 10.x.
// @see https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/issues/8337
$rules = $config->getRules();
$rules['php_unit_test_case_static_method_calls'] = ['call_type' => 'self', 'methods' => ['createStub' => 'this']];
$config->setRules($rules);

// @TODO 4.0 no need to call this manually
$config->setParallelConfig(ParallelConfigFactory::detect());

$config->getFinder()->in('Classes')->in('Configuration')->in('Tests');
return $config;
