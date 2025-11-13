<?php

declare(strict_types=1);

namespace TTN\Tea\Controller;

use Psr\Http\Message\ResponseInterface;
use TTN\Tea\Domain\Model\Tea;
use TTN\Tea\Domain\Repository\TeaRepository;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\ErrorController;

/**
 * Controller for a CRUD FE editor for teas.
 */
class FrontEndEditorController extends ActionController
{
    public function __construct(
        private readonly Context $context,
        private readonly ErrorController $errorController,
        private readonly TeaRepository $teaRepository,
    ) {}

    public function indexAction(): ResponseInterface
    {
        $userUid = $this->getUidOfLoggedInUser();
        if ($userUid > 0) {
            $this->view->assign('teas', $this->teaRepository->findByOwnerUid($userUid));
        }

        return $this->htmlResponse();
    }

    /**
     * @return int<0, max>
     */
    private function getUidOfLoggedInUser(): int
    {
        $userUid = $this->context->getPropertyFromAspect('frontend.user', 'id');
        \assert(\is_int($userUid) && $userUid >= 0);

        return $userUid;
    }

    #[Extbase\IgnoreValidation(['argumentName' => 'tea'])]
    public function editAction(Tea $tea): ResponseInterface
    {
        $this->checkIfUserIsOwner($tea);

        $this->view->assign('tea', $tea);

        return $this->htmlResponse();
    }

    private function checkIfUserIsOwner(Tea $tea): void
    {
        if ($tea->getOwnerUid() !== $this->getUidOfLoggedInUser()) {
            $this->trigger403('You do not have the permissions to edit this tea.');
        }
    }

    public function updateAction(Tea $tea): ResponseInterface
    {
        $this->checkIfUserIsOwner($tea);

        $this->teaRepository->update($tea);

        return $this->redirect('index');
    }

    public function newAction(): ResponseInterface
    {
        // Note: We are using `makeInstance` here instead of `new` to allow for XCLASSing.
        $this->view->assign('tea', GeneralUtility::makeInstance(Tea::class));

        return $this->htmlResponse();
    }

    public function createAction(Tea $tea): ResponseInterface
    {
        $tea->setOwnerUid($this->getUidOfLoggedInUser());

        $this->teaRepository->add($tea);

        return $this->redirect('index');
    }

    #[Extbase\IgnoreValidation(['argumentName' => 'tea'])]
    public function deleteAction(Tea $tea): ResponseInterface
    {
        $this->checkIfUserIsOwner($tea);

        $this->teaRepository->remove($tea);

        return $this->redirect('index');
    }

    /**
     * @return never
     *
     * @throws PropagateResponseException
     */
    private function trigger403(string $message): void
    {
        throw new PropagateResponseException(
            $this->errorController->accessDeniedAction($this->request, $message),
            1687363749,
        );
    }
}
