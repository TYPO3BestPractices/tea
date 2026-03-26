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
            'after:default',
        );

        /**
         * Register all plugins their flexform configurations
         */
        $plugins = [
            'index' => ['flexformsConfiguration' => 'TeaIndex'],
            'show' => ['flexformsConfiguration' => null],
            'front_end_editor' => ['flexformsConfiguration' => 'TeaFrontEndEditor'],
        ];

        foreach ($plugins as $key => $value) {
            $plugin = sprintf('tea_%1$s', $key);
            // This makes the plugin selectable in the BE.
            $pluginSignature = ExtensionUtility::registerPlugin(
                // extension name, matching the PHP namespaces (but without the vendor)
                'Tea',
                // arbitrary, but unique plugin name (not visible in the BE)
                GeneralUtility::underscoredToUpperCamelCase($plugin),
                // plugin title, as visible in the drop-down in the BE
                'LLL:EXT:tea/Resources/Private/Language/locallang.xlf:plugin.' . $plugin,
                // the icon visible in the drop-down in the BE
                'EXT:tea/Resources/Public/Icons/Extension.svg',
                'tea',
            );

            ExtensionManagementUtility::addToInsertRecords($plugin);

            // If flexform is configured for current plugin
            if (is_string($value['flexformsConfiguration'])) {
                // Add the FlexForm to the show item list
                ExtensionManagementUtility::addToAllTCAtypes(
                    'tt_content',
                    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
                    $pluginSignature,
                    'after:palette:headers',
                );
                // Add the flexform configuration for the plugin.
                ExtensionManagementUtility::addPiFlexFormValue(
                    '*',
                    sprintf('FILE:EXT:tea/Configuration/FlexForms/%1$s.xml', $value['flexformsConfiguration']),
                    $pluginSignature,
                );
            }
        }
    },
);
