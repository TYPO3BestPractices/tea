<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Tea example',
    'description' => 'Example extension for unit testing and best practices',
    'version' => '3.1.0',
    'category' => 'example',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.4.99',
            'typo3' => '12.4.31-12.4.99',
            'extbase' => '12.4.31-12.4.99',
            'fluid' => '12.4.31-12.4.99',
            'frontend' => '12.4.31-12.4.99',
        ],
    ],
    'state' => 'stable',
    'author' => 'Oliver Klee, Daniel Siepmann, Łukasz Uznański',
    'author_email' => 'typo3-coding@oliverklee.de, coding@daniel-siepmann.de, lukaszuznanski94@gmail.com',
    'author_company' => 'TYPO3 Best Practices Team',
    'autoload' => [
        'psr-4' => [
            'TTN\\Tea\\' => 'Classes/',
        ],
    ],
    'autoload-dev' => [
        'psr-4' => [
            'TTN\\Tea\\Tests\\' => 'Tests/',
        ],
    ],
];
