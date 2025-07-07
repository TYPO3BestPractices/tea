<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Controller;

use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

abstract class AbstractFrontendControllerTestCase extends FunctionalTestCase
{
    protected function setUp(): void
    {
        $this->testExtensionsToLoad = [
            'ttn/tea',
        ];

        $this->coreExtensionsToLoad = [
            'typo3/cms-fluid-styled-content',
        ];

        $this->pathsToLinkInTestInstance = [
            'typo3conf/ext/tea/Tests/Functional/Controller/Fixtures/Sites/' => 'typo3conf/sites',
        ];

        $this->configurationToUseInTestInstance = [
            'FE' => [
                'cacheHash' => [
                    'enforceValidation' => false,
                ],
            ],
        ];

        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/SiteStructure.csv');
        $this->setUpFrontendRootPage(1, [
            'constants' => [
                'EXT:fluid_styled_content/Configuration/TypoScript/constants.typoscript',
                'EXT:tea/Configuration/TypoScript/constants.typoscript',
            ],
            'setup' => [
                'EXT:fluid_styled_content/Configuration/TypoScript/setup.typoscript',
                'EXT:tea/Configuration/TypoScript/setup.typoscript',
                'EXT:tea/Tests/Functional/Controller/Fixtures/TypoScript/Setup/Rendering.typoscript',
                'EXT:tea/Tests/Functional/Controller/Fixtures/TypoScript/Setup/Storage.typoscript',
            ],
        ]);
    }
}
