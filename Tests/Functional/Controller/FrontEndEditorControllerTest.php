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
}
