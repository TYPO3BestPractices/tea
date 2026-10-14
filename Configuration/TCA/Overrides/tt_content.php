<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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
    },
);
