<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use TTN\Tea\Controller\FrontEndEditorController;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;

#[CoversClass(FrontEndEditorController::class)]
final class FrontEndEditorControllerTest extends AbstractFrontendControllerTestCase
{
    /** @var positive-int */
    private const UID_OF_PAGE = 1;
    /** @var numeric-string */
    private const UID_OF_TEA = '1';

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/ContentElement.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/FrontendUser.csv');
    }

    #[Test]
    public function indexActionForNoLoggedInUserRendersErrorMessage(): void
    {
        $request = (new InternalRequest())->withPageId(self::UID_OF_PAGE);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString(
            'Please configure this plugin to be only visible if a website user is logged in.',
            $html,
        );
    }

    #[Test]
    public function indexActionForLoggedInUserRendersTeaOwnedByTheLoggedInUser(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $html = $this->getHtmlWithLoggedInUser();

        self::assertStringContainsString('Godesberger Burgtee', $html);
    }

    #[Test]
    public function indexActionForLoggedInUserDoesNotRenderTeaOwnedByOtherUser(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToOtherUser.csv');

        $html = $this->getHtmlWithLoggedInUser();

        self::assertStringNotContainsString('Oolong', $html);
    }

    #[Test]
    public function indexActionForLoggedInUserDoesNotRenderTeaWithoutOwner(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToNoUser.csv');

        $html = $this->getHtmlWithLoggedInUser();

        self::assertStringNotContainsString('Sencha', $html);
    }

    #[Test]
    public function deleteActionWithOwnTeaRemovesProvidedTea(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'delete',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA,
        ]);

        $this->assertCSVDataSet(__DIR__ . '/Assertions/Database/FrontEndEditorController/Delete/SoftDeletedTea.csv');
    }

    #[Test]
    public function deleteActionWithTeaFromOtherUserReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToOtherUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'delete',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA,
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function deleteActionWithTeaWithoutOwnerReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToNoUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'delete',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA,
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function editActionWithOwnTeaAssignsProvidedTeaToView(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $html = $this->getHtmlWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => self::UID_OF_TEA,
        ]);

        self::assertStringContainsString(
            '<input type="hidden" name="tx_tea_teafrontendeditor[tea][__identity]" value="1" />',
            $html,
        );
        self::assertStringContainsString('Godesberger Burgtee', $html);
    }

    #[Test]
    public function editActionWithTeaFromOtherUserReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToOtherUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => self::UID_OF_TEA,
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function editActionWithTeaWithoutOwnerReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToNoUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => self::UID_OF_TEA,
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function updateActionWithOwnTeaPersistsNewTitle(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromEditForm(),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA,
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(
            __DIR__ . '/Assertions/Database/FrontEndEditorController/Update/UpdatedTeaWithTitle.csv',
        );
    }

    #[Test]
    public function updateActionWithOwnTeaPersistsNewDescription(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromEditForm(),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA,
            'tx_tea_teafrontendeditor[tea][description]' => 'The new description.',
        ]);

        $this->assertCSVDataSet(
            __DIR__ . '/Assertions/Database/FrontEndEditorController/Update/UpdatedTeaWithDescription.csv',
        );
    }

    #[Test]
    public function updateActionWithTeaFromOtherUserReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToOtherUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromEditForm(),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA,
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function updateActionWithTeaWithoutOwnerReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToNoUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromEditForm(),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => self::UID_OF_TEA,
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function newActionHasLabelForNewAction(): void
    {
        $html = $this->getHtmlWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'new',
        ]);

        self::assertStringContainsString('Create new tea', $html);
    }

    #[Test]
    public function createActionStoresNewTeaWithProvidedTitle(): void
    {
        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromNewForm(),
            'tx_tea_teafrontendeditor[action]' => 'create',
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(
            __DIR__ . '/Assertions/Database/FrontEndEditorController/Create/CreatedTeaWithProvidedTitle.csv',
        );
    }

    #[Test]
    public function createActionSetsLoggedInUserAsOwnerOfProvidedTea(): void
    {
        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromNewForm(),
            'tx_tea_teafrontendeditor[action]' => 'create',
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(
            __DIR__ . '/Assertions/Database/FrontEndEditorController/Create/CreatedTeaWithOwner.csv',
        );
    }

    #[Test]
    public function createActionSetsDefaultStoragePidOfProvidedTea(): void
    {
        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesFromNewForm(),
            'tx_tea_teafrontendeditor[action]' => 'create',
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(
            __DIR__ . '/Assertions/Database/FrontEndEditorController/Create/CreatedTeaWithDefaultStoragePid.csv',
        );
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
            ->withQueryParameters($queryParameters);

        $context = (new InternalRequestContext())->withFrontendUserId(1);

        return $this->executeFrontendSubRequest($request, $context);
    }

    private function getTrustedPropertiesFromEditForm(): string
    {
        return $this->getTrustedPropertiesForFormInput([
            'tea' => [
                'title' => 1,
                'description' => 1,
                '__identity' => (int)self::UID_OF_TEA,
            ],
        ]);
    }

    private function getTrustedPropertiesFromNewForm(): string
    {
        return $this->getTrustedPropertiesForFormInput([
            'tea' => [
                'title' => 1,
                'description' => 1,
            ],
        ]);
    }

    /**
     * @param array<non-empty-string, array<non-empty-string, int>> $trustedProperties
     */
    private function getTrustedPropertiesForFormInput(array $trustedProperties): string
    {
        return $this
            ->get(HashService::class)
            ->appendHmac(
                \json_encode($trustedProperties, JSON_THROW_ON_ERROR),
            );
    }

    private static function assertForbiddenResponse(ResponseInterface $response): void
    {
        self::assertSame(403, $response->getStatusCode());
        self::assertSame('Forbidden', $response->getReasonPhrase());
        self::assertStringContainsString(
            'You do not have the permissions to edit this tea.',
            $response->getBody()->__toString(),
        );
    }
}
