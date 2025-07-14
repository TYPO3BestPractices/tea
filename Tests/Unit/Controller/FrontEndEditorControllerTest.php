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
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
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

    #[Test]
    public function isActionController(): void
    {
        self::assertInstanceOf(ActionController::class, $this->subject);
    }

    private function mockRedirect(string $actionName): void
    {
        $redirectResponse = self::createStub(RedirectResponse::class);
        $this->subject->expects(self::once())->method('redirect')->with($actionName)
            ->willReturn($redirectResponse);
    }

    private function stubRedirect(string $actionName): void
    {
        $redirectResponse = self::createStub(RedirectResponse::class);
        $this->subject->method('redirect')->willReturn($redirectResponse);
    }

    #[Test]
    public function newActionWithTeaAssignsProvidedTeaToView(): void
    {
        $tea = new Tea();

        $this->viewMock->expects(self::once())->method('assign')->with('tea', $tea);

        $this->subject->newAction($tea);
    }

    #[Test]
    public function newActionWithNullTeaAssignsProvidedNewTeaToView(): void
    {
        $tea = new Tea();
        GeneralUtility::addInstance(Tea::class, $tea);

        $this->viewMock->expects(self::once())->method('assign')->with('tea', $tea);

        $this->subject->newAction(null);
    }

    #[Test]
    public function newActionWithoutTeaAssignsProvidedNewTeaToView(): void
    {
        $tea = new Tea();
        GeneralUtility::addInstance(Tea::class, $tea);

        $this->viewMock->expects(self::once())->method('assign')->with('tea', $tea);

        $this->subject->newAction();
    }

    #[Test]
    public function newActionReturnsHtmlResponse(): void
    {
        $result = $this->subject->newAction();

        self::assertInstanceOf(HtmlResponse::class, $result);
    }

    #[Test]
    public function createActionSetsLoggedInUserAsOwnerOfProvidedTea(): void
    {
        $userUid = 5;
        $this->setUidOfLoggedInUser($userUid);
        $tea = new Tea();
        $this->stubRedirect('index');

        $this->subject->createAction($tea);

        self::assertSame($userUid, $tea->getOwnerUid());
    }

    #[Test]
    public function createActionPersistsProvidedTea(): void
    {
        $tea = new Tea();
        $this->stubRedirect('index');

        $this->teaRepositoryMock->expects(self::once())->method('add')->with($tea);

        $this->subject->createAction($tea);
    }

    #[Test]
    public function createActionRedirectsToIndexAction(): void
    {
        $tea = new Tea();

        $this->mockRedirect('index');

        $this->subject->updateAction($tea);
    }
}
