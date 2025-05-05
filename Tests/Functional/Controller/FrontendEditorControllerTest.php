<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Controller\FrontEndEditorController;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;
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
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontendUsers.csv');
    }

    #[Test]
    public function indexActionForNoLoggedInUser(): void
    {
        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Please configure this plugin to be only visible if a website user is logged in.', $html);
    }

    #[Test]
    public function indexActionForLoggedInUserRendersTeasOwnedByTheLoggedInUser(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeasAssignedToUser.csv');

        $request = (new InternalRequest())->withPageId(1);
        $context = (new InternalRequestContext())->withFrontendUserId(1);

        $html = (string)$this->executeFrontendSubRequest($request, $context)->getBody();

        self::assertStringContainsString('Godesberger Burgtee', $html);
        self::assertStringNotContainsString('Oolong', $html);
    }

    #[Test]
    public function editActionWithOwnTeaAssignsProvidedTeaToView(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeasAssignedToUser.csv');

        $request = (new InternalRequest())->withPageId(1)->withQueryParameters([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => '1',
        ]);
        $context = (new InternalRequestContext())->withFrontendUserId(1);

        $html = (string)$this->executeFrontendSubRequest($request, $context)->getBody();

        self::assertStringContainsString('<input type="hidden" name="tx_tea_teafrontendeditor[tea][__identity]" value="1" />', $html);
        self::assertStringContainsString('Godesberger Burgtee', $html);
        self::assertStringNotContainsString('Oolong', $html);
    }

    #[Test]
    public function editActionWithTeaFromOtherUserThrowsException(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeasAssignedToUser.csv');

        $request = (new InternalRequest())->withPageId(1)->withQueryParameters([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => '2',
        ]);
        $context = (new InternalRequestContext())->withFrontendUserId(1);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->executeFrontendSubRequest($request, $context);
    }

    #[Test]
    public function editActionWithTeaWithoutOwnerThrowsException(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeasAssignedToUser.csv');

        $request = (new InternalRequest())->withPageId(1)->withQueryParameters([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => '2',
        ]);
        $context = (new InternalRequestContext())->withFrontendUserId(1);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->executeFrontendSubRequest($request, $context);
    }

}
