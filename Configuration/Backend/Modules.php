<?php

declare(strict_types=1);

use TTN\Tea\Controller\BackendModuleController;

return [
    'tea_index' => [
        'parent' => 'web',
        'position' => ['after' => 'web_list'],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/tea/index',
        'labels' => 'LLL:EXT:tea/Resources/Private/Language/locallang_index_mod.xlf',
        'extensionName' => 'Tea',
        'iconIdentifier' => 'tx-tea-backend-module',
        'inheritNavigationComponentFromMainModule' => false,
        'controllerActions' => [
            BackendModuleController::class => [
                'index',
            ],
        ],
    ],
];
