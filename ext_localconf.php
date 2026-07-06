<?php

declare(strict_types=1);

use TTN\Tea\Controller\FrontEndEditorController;
use TTN\Tea\Controller\TeaController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die('Access denied.');

// This makes the plugin available for front-end rendering.
ExtensionUtility::configurePlugin(
    // extension name, matching the PHP namespaces (but without the vendor)
    'Tea',
    // arbitrary, but unique plugin name (not visible in the BE)
    'TeaIndex',
    // all actions
    [
        TeaController::class => 'index',
    ],
    // non-cacheable actions
    [
        TeaController::class => '',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
ExtensionUtility::configurePlugin(
    'Tea',
    'TeaShow',
    [
        TeaController::class => 'show',
    ],
    [
        TeaController::class => '',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// This makes the plugin available for front-end rendering.
ExtensionUtility::configurePlugin(
    // extension name, matching the PHP namespaces (but without the vendor)
    'Tea',
    // arbitrary, but unique plugin name (not visible in the BE)
    'TeaFrontEndEditor',
    // all actions
    [
        FrontEndEditorController::class => 'index, edit, update, create, new, delete',
    ],
    // non-cacheable actions
    [
        // All actions need to be non-cacheable because they either contain dynamic data,
        // or because they are specific to the logged-in FE user (while FE content is cached by FE groups).
        FrontEndEditorController::class => 'index, edit, update, create, new, delete',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
