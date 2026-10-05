<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Tea example',
    'description' => 'Example extension for unit testing and best practices',
    'version' => '4.1.0',
    'category' => 'example',
    'constraints' => [
        'depends' => [
            'php' => '8.2.0-8.5.99',
            'typo3' => '13.4.31-14.3.99',
            'extbase' => '13.4.31-14.3.99',
            'fluid' => '13.4.31-14.3.99',
            'frontend' => '13.4.31-14.3.99',
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
