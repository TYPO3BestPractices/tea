<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Controller\FrontEndEditorController;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(FrontEndEditorController::class)]
final class FrontendEditorControllerTest extends FunctionalTestCase
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
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/ContentElementTeaFrontEndEditor.csv');
    }

    #[Test]
    public function indexActionForNoLoggedInUser(): void
    {
        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Please configure this plugin to be only visible if a website user is logged in.', $html);
    }

}
