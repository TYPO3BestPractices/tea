<?php

declare(strict_types=1);

namespace TTN\Tea\Controller;

use Psr\Http\Message\ResponseInterface;
use TTN\Tea\Domain\Model\Tea;
use TTN\Tea\Domain\Repository\TeaRepository;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller for a CRUD FE editor for teas.
 */
class FrontEndEditorController extends ActionController
{
    public function __construct(
        private readonly Context $context,
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

    /**
     * @throws \RuntimeException
     */
    private function checkIfUserIsOwner(Tea $tea): void
    {
        if ($tea->getOwnerUid() !== $this->getUidOfLoggedInUser()) {
            throw new \RuntimeException('You do not have the permissions to edit this tea.', 1687363749);
        }
    }

    public function updateAction(Tea $tea): ResponseInterface
    {
        $this->checkIfUserIsOwner($tea);

        $this->teaRepository->update($tea);

        return $this->redirect('index');
    }

    #[Extbase\IgnoreValidation(['argumentName' => 'tea'])]
    public function newAction(?Tea $tea = null): ResponseInterface
    {
        // Note: We are using `makeInstance` here instead of `new` to allow for XCLASSing.
        $teaToAssign = $tea ?? GeneralUtility::makeInstance(Tea::class);
        $this->view->assign('tea', $teaToAssign);

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
}
