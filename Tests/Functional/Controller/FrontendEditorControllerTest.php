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

    #[Test]
    public function updateActionWithOwnTeaUpdatesTea(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeasAssignedToUser.csv');

        $request = (new InternalRequest())->withPageId(1)->withQueryParameters([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromEditForm(1, 1),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => '1',
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);
        $context = (new InternalRequestContext())->withFrontendUserId(1);

        $this->executeFrontendSubRequest($request, $context);

        self::assertSame('Darjeeling', $this->getAllRecords('tx_tea_domain_model_tea')[0]['title']);
    }

    #[Test]
    public function newActionWithNoProvidedTeaCanBeRendered(): void
    {
        $request = (new InternalRequest())->withPageId(1)->withQueryParameters([
            'tx_tea_teafrontendeditor[action]' => 'new',
        ]);
        $context = (new InternalRequestContext())->withFrontendUserId(1);

        $html = (string)$this->executeFrontendSubRequest($request, $context)->getBody();

        self::assertStringContainsString('Create new tea', $html);
    }

    #[Test]
    public function createActionSetsLoggedInUserAsOwnerOfProvidedTea(): void
    {
        $request = (new InternalRequest())->withPageId(1)->withQueryParameters([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromNewForm(1),
            'tx_tea_teafrontendeditor[action]' => 'create',
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);
        $context = (new InternalRequestContext())->withFrontendUserId(1);

        $this->executeFrontendSubRequest($request, $context);

        self::assertSame(1, $this->getAllRecords('tx_tea_domain_model_tea')[0]['owner']);
    }

    private function getTrustedPropertiesFromEditForm(int $tea, int $userId): string
    {
        $request = (new InternalRequest())->withPageId(1)->withQueryParameters([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => $tea,
        ]);
        $context = (new InternalRequestContext())->withFrontendUserId($userId);

        $html = (string)$this->executeFrontendSubRequest($request, $context)->getBody();

        return $this->getTrustedPropertiesFromHtml($html);
    }

    private function getTrustedPropertiesFromNewForm(int $userId): string
    {
        $request = (new InternalRequest())->withPageId(1)->withQueryParameters([
            'tx_tea_teafrontendeditor[action]' => 'new',
        ]);
        $context = (new InternalRequestContext())->withFrontendUserId($userId);

        $html = (string)$this->executeFrontendSubRequest($request, $context)->getBody();

        return $this->getTrustedPropertiesFromHtml($html);
    }

    private function getTrustedPropertiesFromHtml(string $html): string
    {
        $matches = [];
        preg_match('/__trustedProperties]" value="([a-zA-Z0-9&{};:,_]+)"/', $html, $matches);
        if (isset($matches[1]) === false) {
            throw new \RuntimeException('Could not fetch trustedProperties from returned HTML.', 1744028933);
        }

        return html_entity_decode($matches[1]);
    }
}
