<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

function checkTca(mixed $tca): mixed
{
    if (
        !is_array($tca)
        || !isset($tca['TCA'])
        || !is_array($tca['TCA'])
        || !isset($tca['TCA']['tt_content'])
        || !is_array($tca['TCA']['tt_content'])
        || !isset($tca['TCA']['tt_content']['types'])
        || !is_array($tca['TCA']['tt_content']['types'])
        || !isset($tca['TCA']['tt_content']['types']['list'])
        || !is_array($tca['TCA']['tt_content']['types']['list'])
        || !isset($tca['TCA']['tt_content']['types']['list']['subtypes_addlist'])
    ) {
        throw new \RuntimeException(
            'TCA global for tt_content not found',
            1666048882
        );
    }
    return $tca['TCA']['tt_content']['types']['list']['subtypes_addlist'];
}

function setSignature(mixed $tca, mixed $signature, string $controlsToRemove): void
{
    if (
        !is_array($tca)
        || !isset($tca[$signature])
    ) {
        throw new \Exception('Bad Signature in TCA', 1666048883);
    }

    $tca[$signature] = $controlsToRemove;
}

call_user_func(
    static function (): void {
        // This makes the plugin selectable in the BE.
        $indexPluginSignature = ExtensionUtility::registerPlugin(
            // extension name, matching the PHP namespaces (but without the vendor)
            'Tea',
            // arbitrary, but unique plugin name (not visible in the BE)
            'TeaIndex',
            // plugin title, as visible in the drop-down in the BE
            'LLL:EXT:tea/Resources/Private/Language/locallang.xlf:plugin.tea_index',
            // the icon visible in the drop-down in the BE
            'EXT:tea/Resources/Public/Icons/Extension.svg'
        );

        ExtensionManagementUtility::addPiFlexFormValue(
            $indexPluginSignature,
            'FILE:EXT:tea/Configuration/FlexForms/TeaIndex.xml'
        );

        $showPluginSignature = ExtensionUtility::registerPlugin(
            'Tea',
            'TeaShow',
            'LLL:EXT:tea/Resources/Private/Language/locallang.xlf:plugin.tea_show',
            'EXT:tea/Resources/Public/Icons/Extension.svg'
        );

        $editorPluginSignature = ExtensionUtility::registerPlugin(
            'Tea',
            'TeaFrontEndEditor',
            'LLL:EXT:tea/Resources/Private/Language/locallang.xlf:plugin.tea_frontend_editor',
            'EXT:tea/Resources/Public/Icons/Extension.svg'
        );

        // check the tca to get rid of offsetAccess.nonOffsetAccessible errors
        $tca = checkTca($GLOBALS);

        // This removes the default controls from the plugins.
        $controlsToRemove = 'recursive,pages';
        setSignature($tca, $indexPluginSignature, $controlsToRemove);
        setSignature($tca, $editorPluginSignature, $controlsToRemove);
        setSignature($tca, $showPluginSignature, $controlsToRemove);
    }
);
