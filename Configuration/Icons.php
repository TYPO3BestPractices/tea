<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'tx-tea-backend-module' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:tea/Resources/Public/Icons/Module.svg',
    ],
    'tx-tea' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:tea/Resources/Public/Icons/Extension.svg',
    ],
];
