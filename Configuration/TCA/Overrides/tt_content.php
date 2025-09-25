<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(
    static function (): void {
        /**
         * Add new select group for content element
         */
        ExtensionManagementUtility::addTcaSelectItemGroup(
            'tt_content',
            'CType',
            'tea',
            'LLL:EXT:tea/Resources/Private/Language/locallang_db.xlf:tx_tea_domain_model_tea.pluginGroup',
            'after:default'
        );

        /**
         * Register all plugins
         */
        $plugins = ['tea_index', 'tea_show', 'tea_front_end_editor'];

        foreach ($plugins as $contentType) {
            // This makes the plugin selectable in the BE.
            ExtensionUtility::registerPlugin(
                // extension name, matching the PHP namespaces (but without the vendor)
                'Tea',
                // arbitrary, but unique plugin name (not visible in the BE)
                GeneralUtility::underscoredToUpperCamelCase($contentType),
                // plugin title, as visible in the drop-down in the BE
                'LLL:EXT:tea/Resources/Private/Language/locallang.xlf:plugin.' . $contentType,
                // the icon visible in the drop-down in the BE
                'EXT:tea/Resources/Public/Icons/Extension.svg',
                'tea'
            );

        }

        // Add the FlexForm to the show item list
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
            'tea_teaindex',
            'after:palette:headers'
        );
        // Add the flexform configuration for the TeaIndex plugin.
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:tea/Configuration/FlexForms/TeaIndex.xml',
            'tea_teaindex'
        );
        // Add the flexform configuration for the FE editor plugin.
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:tea/Configuration/FlexForms/FrontEndEditor.xml',
            'tea_front_end_editor'
        );

        /**
         * Register TeaIndex as "Insert Record"
         */
        ExtensionManagementUtility::addToInsertRecords('tea_index');
    }
);
