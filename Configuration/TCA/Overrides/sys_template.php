<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

ExtensionManagementUtility::addStaticFile('tea', 'Configuration/TypoScript', 'Tea');
ExtensionManagementUtility::addStaticFile(
    'tea',
    'Configuration/TypoScript/Frontend/',
    'Tea frontend (optional)',
);
