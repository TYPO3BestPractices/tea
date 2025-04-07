<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(
    static function (): void {
        // This makes the plugin selectable in the BE.
        ExtensionUtility::registerPlugin(
            // extension name, matching the PHP namespaces (but without the vendor)
            'Tea',
            // arbitrary, but unique plugin name (not visible in the BE)
            'TeaIndex',
            // plugin title, as visible in the drop-down in the BE
            'LLL:EXT:tea/Resources/Private/Language/locallang.xlf:plugin.tea_index',
            // the icon visible in the drop-down in the BE
            'EXT:tea/Resources/Public/Icons/Extension.svg'
        );

        ExtensionUtility::registerPlugin(
            'Tea',
            'TeaShow',
            'LLL:EXT:tea/Resources/Private/Language/locallang.xlf:plugin.tea_show',
            'EXT:tea/Resources/Public/Icons/Extension.svg'
        );

        ExtensionUtility::registerPlugin(
            'Tea',
            'TeaFrontEndEditor',
            'LLL:EXT:tea/Resources/Private/Language/locallang.xlf:plugin.tea_frontend_editor',
            'EXT:tea/Resources/Public/Icons/Extension.svg'
        );

        // This removes the default controls from the plugin.
        $controlsToRemove = 'recursive,pages';
    }
);
