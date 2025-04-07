<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
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

        // These two commands add the flexform configuration for the plugin.
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['tea_teaindex'] = 'pi_flexform';
        ExtensionManagementUtility::addPiFlexFormValue(
            'tea_teaindex',
            'FILE:EXT:tea/Configuration/FlexForms/TeaIndex.xml'
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
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['tea_teaindex'] = $controlsToRemove;
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['tea_teashow'] = $controlsToRemove;
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['tea_teafrontendeditor'] = $controlsToRemove;
    }
);
