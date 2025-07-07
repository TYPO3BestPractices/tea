<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use TTN\Tea\Controller\FrontEndEditorController;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;

#[CoversClass(FrontEndEditorController::class)]
final class FrontEndEditorControllerTest extends AbstractFrontendControllerTestCase
{
    private const UID_OF_PAGE = 1;
    private const UID_OF_TEA_OWNED_BY_LOGGED_IN_USER = '1';
    private const UID_OF_TEA_OWNED_BY_FOREIGN_USER = '2';
    private const UID_OF_TEA_WITHOUT_OWNER = '3';

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/ContentElement.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/Teas.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/FrontendUser.csv');
    }

    #[Test]
    public function indexActionForNoLoggedInUserRendersErrorMessage(): void
    {
        $request = (new InternalRequest())->withPageId(self::UID_OF_PAGE);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Please configure this plugin to be only visible if a website user is logged in.', $html);
    }

    #[Test]
    public function indexActionForLoggedInUserRendersTeaOwnedByTheLoggedInUser(): void
    {
        $html = $this->getHtmlWithLoggedInUser();

        self::assertStringContainsString('Godesberger Burgtee', $html);
    }

    #[Test]
    public function indexActionForLoggedInUserDoesNotRenderTeaOwnedByOtherUser(): void
    {
        $html = $this->getHtmlWithLoggedInUser();

        self::assertStringNotContainsString('Oolong', $html);
    }

    #[Test]
    public function indexActionForLoggedInUserDoesNotRenderTeaWithoutOwner(): void
    {
        $html = $this->getHtmlWithLoggedInUser();

        self::assertStringNotContainsString('Sencha', $html);
    }

    #[Test]
    public function deleteActionWithOwnTeaRemovesProvidedTea(): void
    {
        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'delete',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA_OWNED_BY_LOGGED_IN_USER,
        ]);

        $this->assertCSVDataSet(__DIR__ . '/Assertions/Database/FrontEndEditorController/Delete/Deleted.csv');
    }

    #[Test]
    public function deleteActionWithTeaFromOtherUserThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'delete',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA_OWNED_BY_FOREIGN_USER,
        ]);
    }

    #[Test]
    public function deleteActionWithTeaWithoutOwnerThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'delete',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA_WITHOUT_OWNER,
        ]);
    }

    #[Test]
    public function editActionWithOwnTeaAssignsProvidedTeaToView(): void
    {
        $html = $this->getHtmlWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => self::UID_OF_TEA_OWNED_BY_LOGGED_IN_USER,
        ]);

        self::assertStringContainsString('<input type="hidden" name="tx_tea_teafrontendeditor[tea][__identity]" value="1" />', $html);
        self::assertStringContainsString('Godesberger Burgtee', $html);
    }

    #[Test]
    public function editActionWithTeaFromOtherUserThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => self::UID_OF_TEA_OWNED_BY_FOREIGN_USER,
        ]);
    }

    #[Test]
    public function editActionWithTeaWithoutOwnerThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => self::UID_OF_TEA_WITHOUT_OWNER,
        ]);
    }

    #[Test]
    public function updateActionWithOwnTeaPersistsProvidedTea(): void
    {
        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromEditForm(self::UID_OF_TEA_OWNED_BY_LOGGED_IN_USER),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA_OWNED_BY_LOGGED_IN_USER,
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(__DIR__ . '/Assertions/Database/FrontEndEditorController/Update/OwnTea.csv');
    }

    #[Test]
    public function updateActionWithTeaFromOtherUserThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromEditForm(self::UID_OF_TEA_OWNED_BY_LOGGED_IN_USER),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA_OWNED_BY_FOREIGN_USER,
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);
    }

    #[Test]
    public function updateActionWithTeaWithoutOwnerThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromEditForm(self::UID_OF_TEA_OWNED_BY_LOGGED_IN_USER),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA_WITHOUT_OWNER,
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);
    }

    #[Test]
    public function newActionCanBeCalledByUser(): void
    {
        $html = $this->getHtmlWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'new',
        ]);

        self::assertStringContainsString('Create new tea', $html);
    }

    #[Test]
    public function createActionStoresNewTeaWithProvidedTitle(): void
    {
        $this->truncateExistingTeas();

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromNewForm(),
            'tx_tea_teafrontendeditor[action]' => 'create',
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(__DIR__ . '/Assertions/Database/FrontEndEditorController/Create/WithProvidedTitle.csv');
    }

    #[Test]
    public function createActionSetsLoggedInUserAsOwnerOfProvidedTea(): void
    {
        $this->truncateExistingTeas();

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromNewForm(),
            'tx_tea_teafrontendeditor[action]' => 'create',
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(__DIR__ . '/Assertions/Database/FrontEndEditorController/Create/WithOwner.csv');
    }

    #[Test]
    public function createActionSetsDefaultStoragePidOfProvidedTea(): void
    {
        $this->truncateExistingTeas();

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromNewForm(),
            'tx_tea_teafrontendeditor[action]' => 'create',
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(__DIR__ . '/Assertions/Database/FrontEndEditorController/Create/WithDefaultStoragePid.csv');
    }

    /**
     * @param array<string, string> $queryParameters
     */
    private function getHtmlWithLoggedInUser(array $queryParameters = []): string
    {
        return (string)$this->executeRequestWithLoggedInUser($queryParameters)->getBody();
    }

    /**
     * @param array<string, string> $queryParameters
     */
    private function executeRequestWithLoggedInUser(array $queryParameters = []): ResponseInterface
    {
        $request = (new InternalRequest())
            ->withPageId(self::UID_OF_PAGE)
            ->withQueryParameters($queryParameters)
        ;

        $context = (new InternalRequestContext())->withFrontendUserId(1);

        return $this->executeFrontendSubRequest($request, $context);
    }

    private function getTrustedPropertiesFromEditForm(string $teaUid): string
    {
        $html = $this->getHtmlWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => $teaUid,
        ]);

        return $this->getTrustedPropertiesFromHtml($html);
    }

    private function getTrustedPropertiesFromNewForm(): string
    {
        $html = $this->getHtmlWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'new',
        ]);

        return $this->getTrustedPropertiesFromHtml($html);
    }

    private function getTrustedPropertiesFromHtml(string $html): string
    {
        $matches = [];
        preg_match('/__trustedProperties]" value="([a-zA-Z0-9&{};:,_\\[\\]]+)"/', $html, $matches);
        if (!isset($matches[1])) {
            throw new \RuntimeException('Could not fetch trustedProperties from returned HTML.', 1744028933);
        }

        return html_entity_decode($matches[1]);
    }

    private function truncateExistingTeas(): void
    {
        $this->getConnectionPool()
            ->getConnectionForTable('tx_tea_domain_model_tea')
            ->truncate('tx_tea_domain_model_tea');
    }
}
