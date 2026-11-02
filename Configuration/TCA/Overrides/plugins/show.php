<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(
    static function (): void {
        $pluginKey = 'tea_show';
        // This makes the plugin selectable in the BE.
        ExtensionUtility::registerPlugin(
            // extension name, matching the PHP namespaces (but without the vendor)
            'Tea',
            // arbitrary, but unique plugin name (not visible in the BE)
            'TeaShow',
            // plugin title, as visible in the drop-down in the BE
            'LLL:EXT:tea/Resources/Private/Language/locallang.xlf:plugin.' . $pluginKey,
            // the icon visible in the drop-down in the BE
            'EXT:tea/Resources/Public/Icons/Extension.svg',
            'tea',
        );

        ExtensionManagementUtility::addToInsertRecords($pluginKey);
    },
);
