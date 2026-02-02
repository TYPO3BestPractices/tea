<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use TTN\Tea\Controller\FrontEndEditorController;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;

#[CoversClass(FrontEndEditorController::class)]
final class FrontEndEditorControllerTest extends AbstractFrontendControllerTestCase
{
    private const UID_OF_PAGE = 1;
    private const UID_OF_TEA = 1;
    private const TRUSTED_PROPERTIES = [
        'title' => 1,
        'description' => 1,
    ];

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
    public function indexActionHasHeadline(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $html = $this->getHtmlWithLoggedInUser();

        $expected = LocalizationUtility::translate('plugin.frontEndEditor.index.heading', 'tea');
        self::assertIsString($expected);
        self::assertStringContainsString($expected, $html);
    }

    #[Test]
    public function indexActionByDefaultHasLinkToNewAction(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $html = $this->getHtmlWithLoggedInUser();

        $expected = '?tx_tea_teafrontendeditor%5Baction%5D=new'
            . '&amp;tx_tea_teafrontendeditor%5Bcontroller%5D=FrontEndEditor';
        self::assertStringContainsString($expected, $html);
    }

    #[Test]
    public function indexActionRendersTeaUid(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUserWithHigherUid.csv');

        $html = $this->getHtmlWithLoggedInUser();

        self::assertStringContainsString('1337', $html);
    }

    #[Test]
    public function indexActionWithHasEditTeaLink(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $html = $this->getHtmlWithLoggedInUser();

        $expected = '?tx_tea_teafrontendeditor%5Baction%5D=edit'
            . '&amp;tx_tea_teafrontendeditor%5Bcontroller%5D=FrontEndEditor'
            . '&amp;tx_tea_teafrontendeditor%5Btea%5D=1';
        self::assertStringContainsString($expected, $html);
    }

    #[Test]
    public function indexActionWithHasDeleteTeaForm(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $html = $this->getHtmlWithLoggedInUser();

        $expected = 'action="/'
            . '?tx_tea_teafrontendeditor%5Baction%5D=delete'
            . '&amp;tx_tea_teafrontendeditor%5Bcontroller%5D=FrontEndEditor';
        self::assertStringContainsString($expected, $html);
    }

    #[Test]
    public function deleteActionWithOwnTeaRemovesProvidedTea(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'delete',
            'tx_tea_teafrontendeditor[tea][__identity]' => (string)self::UID_OF_TEA,
        ]);

        $this->assertCSVDataSet(__DIR__ . '/Assertions/Database/FrontEndEditorController/Delete/SoftDeletedTea.csv');
    }

    #[Test]
    public function deleteActionWithTeaFromOtherUserReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToOtherUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'delete',
            'tx_tea_teafrontendeditor[tea][__identity]' => (string)self::UID_OF_TEA,
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function deleteActionWithTeaWithoutOwnerReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToNoUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'delete',
            'tx_tea_teafrontendeditor[tea][__identity]' => (string)self::UID_OF_TEA,
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function editActionWithOwnTeaAssignsProvidedTeaToView(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $html = $this->getHtmlWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => (string)self::UID_OF_TEA,
        ]);

        self::assertStringContainsString(
            '<input type="hidden" name="tx_tea_teafrontendeditor[tea][__identity]" value="1" />',
            $html,
        );
        self::assertStringContainsString('Godesberger Burgtee', $html);
    }

    #[Test]
    #[DataProvider('possibleEditFormFieldNames')]
    public function editActionWithOwnTeaHasAllFormFields(string $fieldName): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $html = $this->getHtmlWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => (string)self::UID_OF_TEA,
        ]);

        self::assertStringContainsString('name="tx_tea_teafrontendeditor[tea][' . $fieldName . ']"', $html);
    }

    /**
     * @return \Generator<non-empty-string, array{0: non-empty-string}>
     */
    public static function possibleEditFormFieldNames(): \Generator
    {
        yield 'title' => ['title'];
    }

    #[Test]
    public function editActionWithOwnTeaHasUpdateFormAction(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $html = $this->getHtmlWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => (string)self::UID_OF_TEA,
        ]);

        $expected = 'action="/'
            . '?tx_tea_teafrontendeditor%5Baction%5D=update'
            . '&amp;tx_tea_teafrontendeditor%5Bcontroller%5D=FrontEndEditor';
        self::assertStringContainsString($expected, $html);
    }

    #[Test]
    public function editActionWithTeaFromOtherUserReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToOtherUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => (string)self::UID_OF_TEA,
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function editActionWithTeaWithoutOwnerReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToNoUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[action]' => 'edit',
            'tx_tea_teafrontendeditor[tea]' => (string)self::UID_OF_TEA,
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function updateActionWithOwnTeaPersistsNewTitle(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesForEditForm(),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => (string)self::UID_OF_TEA,
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(
            __DIR__ . '/Assertions/Database/FrontEndEditorController/Update/UpdatedTeaWithTitle.csv',
        );
    }

    #[Test]
    public function updateActionWithOwnTeaKeepsPidUnchanged(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUserWithDifferentPid.csv');

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesForEditForm(),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => (string)self::UID_OF_TEA,
            'tx_tea_teafrontendeditor[tea][title]' => 'Godesberger Burgtee',
        ]);

        $this->assertCSVDataSet(
            __DIR__ . '/Assertions/Database/FrontEndEditorController/Update/UpdatedTeaWithDifferentPid.csv'
        );
    }

    #[Test]
    public function updateActionWithOwnTeaPersistsNewDescription(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToLoggedInUser.csv');

        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesForEditForm(),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => (string)self::UID_OF_TEA,
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
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesForEditForm(),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => (string)self::UID_OF_TEA,
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        self::assertForbiddenResponse($response);
    }

    #[Test]
    public function updateActionWithTeaWithoutOwnerReturnsForbiddenResponse(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/FrontEndEditorController/TeaAssignedToNoUser.csv');

        $response = $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesForEditForm(),
            'tx_tea_teafrontendeditor[action]' => 'update',
            'tx_tea_teafrontendeditor[tea][__identity]' => (string)self::UID_OF_TEA,
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
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesForNewForm(),
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
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesForNewForm(),
            'tx_tea_teafrontendeditor[action]' => 'create',
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(
            __DIR__ . '/Assertions/Database/FrontEndEditorController/Create/CreatedTeaWithOwner.csv',
        );
    }

    #[Test]
    public function createActionSetsPidOfProvidedTeaToStoragePidSetInFlexforms(): void
    {
        $this->executeRequestWithLoggedInUser([
            'tx_tea_teafrontendeditor[__trustedProperties]' => $this->getTrustedPropertiesForNewForm(),
            'tx_tea_teafrontendeditor[action]' => 'create',
            'tx_tea_teafrontendeditor[tea][title]' => 'Darjeeling',
        ]);

        $this->assertCSVDataSet(
            __DIR__ . '/Assertions/Database/FrontEndEditorController/Create/CreatedTeaWithPid.csv',
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

    /**
     * @return non-empty-string
     */
    private function getTrustedPropertiesForEditForm(): string
    {
        $properties = self::TRUSTED_PROPERTIES;
        $properties['__identity'] = self::UID_OF_TEA;

        return $this->getTrustedPropertiesForFormInput(['tea' => $properties]);
    }

    /**
     * @return non-empty-string
     */
    private function getTrustedPropertiesForNewForm(): string
    {
        return $this->getTrustedPropertiesForFormInput(['tea' => self::TRUSTED_PROPERTIES]);
    }

    /**
     * @param array<non-empty-string, array<non-empty-string, int<1, max>>> $trustedProperties
     *
     * @return non-empty-string
     */
    private function getTrustedPropertiesForFormInput(array $trustedProperties): string
    {
        $result = $this
            ->get(HashService::class)
            ->appendHmac(\json_encode($trustedProperties, JSON_THROW_ON_ERROR));
        self::assertNotSame('', $result);

        return $result;
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
