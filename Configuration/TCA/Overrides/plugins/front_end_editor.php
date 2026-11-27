<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(
    static function (): void {
        $pluginKey = 'tea_front_end_editor';
        // This makes the plugin selectable in the BE.
        $ctypeKey = ExtensionUtility::registerPlugin(
            // extension name, matching the PHP namespaces (but without the vendor)
            'Tea',
            // arbitrary, but unique plugin name (not visible in the BE)
            'TeaFrontEndEditor',
            // plugin title, as visible in the drop-down in the BE
            'LLL:EXT:tea/Resources/Private/Language/locallang.xlf:plugin.' . $pluginKey,
            // the icon visible in the drop-down in the BE
            'EXT:tea/Resources/Public/Icons/Extension.svg',
            'tea',
        );

        ExtensionManagementUtility::addToInsertRecords($pluginKey);

        // Add the FlexForm to the show item list
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
            $ctypeKey,
            'after:subheader',
        );
        // Add the flexform configuration for the plugin.
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:tea/Configuration/FlexForms/TeaFrontEndEditor.xml',
            $ctypeKey,
        );
    },
);
