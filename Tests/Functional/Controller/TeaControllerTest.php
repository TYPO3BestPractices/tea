<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Controller;

use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \TTN\Tea\Controller\TeaController
 */
final class TeaControllerTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['ttn/tea'];

    protected array $coreExtensionsToLoad = ['typo3/cms-fluid-styled-content'];

    protected array $pathsToLinkInTestInstance = [
        'typo3conf/ext/tea/Tests/Functional/Controller/Fixtures/Sites/' => 'typo3conf/sites',
    ];

    protected array $configurationToUseInTestInstance = [
        'FE' => [
            'cacheHash' => [
                'enforceValidation' => false,
            ],
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/SiteStructure.csv');
        $this->setUpFrontendRootPage(1, [
            'constants' => [
                'EXT:fluid_styled_content/Configuration/TypoScript/constants.typoscript',
                'EXT:tea/Configuration/TypoScript/constants.typoscript',
                'EXT:tea/Tests/Functional/Controller/Fixtures/TypoScript/Constants/PluginConfiguration.typoscript',
            ],
            'setup' => [
                'EXT:fluid_styled_content/Configuration/TypoScript/setup.typoscript',
                'EXT:tea/Configuration/TypoScript/setup.typoscript',
                'EXT:tea/Tests/Functional/Controller/Fixtures/TypoScript/Setup/Rendering.typoscript',
            ],
        ]);
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/ContentElementTeaIndex.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/Teas.csv');
    }

    /**
     * @test
     */
    public function indexActionRendersAllAvailableTeas(): void
    {
        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Godesberger Burgtee', $html);
        self::assertStringContainsString('Oolong', $html);
    }

    /**
     * @test
     */
    public function showActionRendersTheGivenTeas(): void
    {
        $request = (new InternalRequest())->withPageId(3)->withQueryParameters(['tx_tea_teashow[tea]' => 1]);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Godesberger Burgtee', $html);
        self::assertStringNotContainsString('Oolong', $html);
    }
}
