<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Unit\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TTN\Tea\Controller\FrontEndEditorController;
use TTN\Tea\Domain\Model\Tea;
use TTN\Tea\Domain\Repository\TeaRepository;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Note: Unit tests for controllers are not considered best practice anymore. Instead, functional tests should be used.
 * We're currently in the process of migrating all controller tests to functional tests.
 */
#[CoversClass(FrontEndEditorController::class)]
final class FrontEndEditorControllerTest extends UnitTestCase
{
    /**
     * @var FrontEndEditorController&MockObject&AccessibleObjectInterface
     */
    private FrontEndEditorController $subject;

    /**
     * @var TemplateView&MockObject
     */
    private TemplateView $viewMock;

    private Context $context;

    /**
     * @var TeaRepository&MockObject
     */
    private TeaRepository $teaRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->context = new Context();
        $this->teaRepositoryMock = $this->createMock(TeaRepository::class);

        // We need to create an accessible mock in order to be able to set the protected `view`.
        $methodsToMock = ['htmlResponse', 'redirect', 'redirectToUri'];
        $this->subject = $this->getAccessibleMock(
            FrontEndEditorController::class,
            $methodsToMock,
            [$this->context, $this->teaRepositoryMock]
        );

        $this->viewMock = $this->createMock(TemplateView::class);
        $this->subject->_set('view', $this->viewMock);

        $responseStub = self::createStub(HtmlResponse::class);
        $this->subject->method('htmlResponse')->willReturn($responseStub);
    }

    protected function tearDown(): void
    {
        // empty FIFO queue
        GeneralUtility::makeInstance(Tea::class);

        parent::tearDown();
    }

    /**
     * @param int<0, max> $userUid
     */
    private function setUidOfLoggedInUser(int $userUid): void
    {
        $userAspectMock = $this->createMock(UserAspect::class);
        $userAspectMock->method('get')->with('id')->willReturn($userUid);
        $this->context->setAspect('frontend.user', $userAspectMock);
    }

    private function mockRedirect(string $actionName): void
    {
        $redirectResponse = self::createStub(RedirectResponse::class);
        $this->subject->expects(self::once())->method('redirect')->with($actionName)
            ->willReturn($redirectResponse);
    }

    #[Test]
    /**
     * Not possible to test with functionals due to hmac security.
     */
    public function updateActionWithTeaFromOtherUserThrowsException(): void
    {
        $this->setUidOfLoggedInUser(1);
        $tea = new Tea();
        $tea->setOwnerUid(2);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->subject->updateAction($tea);
    }

    #[Test]
    /**
     * Not possible to test with functionals due to hmac security.
     */
    public function updateActionWithTeaWithoutOwnerThrowsException(): void
    {
        $this->setUidOfLoggedInUser(1);
        $tea = new Tea();
        $tea->setOwnerUid(0);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->subject->updateAction($tea);
    }

    #[Test]
    /**
     * This feature is not implemented in Templates yet, therefore we can't test it with functional tests due to hmac.
     */
    public function newActionWithTeaAssignsProvidedTeaToView(): void
    {
        $tea = new Tea();

        $this->viewMock->expects(self::once())->method('assign')->with('tea', $tea);

        $this->subject->newAction($tea);
    }

    #[Test]
    /**
     * Extbase calls `header()` functions instead of using PSR.
     */
    public function createActionRedirectsToIndexAction(): void
    {
        $tea = new Tea();

        $this->mockRedirect('index');

        $this->subject->updateAction($tea);
    }

    #[Test]
    /**
     * Extbase calls `header()` functions instead of using PSR.
     */
    public function deleteActionWithOwnTeaRedirectsToIndexAction(): void
    {
        $userUid = 5;
        $this->setUidOfLoggedInUser($userUid);
        $tea = new Tea();
        $tea->setOwnerUid($userUid);

        $this->mockRedirect('index');

        $this->subject->deleteAction($tea);
    }

    #[Test]
    /**
     * Not possible to test with functionals due to hmac security.
     */
    public function deleteActionWithTeaFromOtherUserThrowsException(): void
    {
        $this->setUidOfLoggedInUser(1);
        $tea = new Tea();
        $tea->setOwnerUid(2);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->subject->deleteAction($tea);
    }

    #[Test]
    /**
     * Not possible to test with functionals due to hmac security.
     */
    public function deleteActionWithTeaWithoutOwnerThrowsException(): void
    {
        $this->setUidOfLoggedInUser(1);
        $tea = new Tea();
        $tea->setOwnerUid(0);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You do not have the permissions to edit this tea.');
        $this->expectExceptionCode(1687363749);

        $this->subject->deleteAction($tea);
    }
}
