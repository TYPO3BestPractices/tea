<?php

declare(strict_types=1);

namespace TTN\Tea\Controller;

use Psr\Http\Message\ResponseInterface;
use TTN\Tea\Domain\Model\Tea;
use TTN\Tea\Domain\Repository\TeaRepository;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\ErrorController;

/**
 * Controller for the main "Tea" FE plugin.
 */
class TeaController extends ActionController
{
    public function __construct(
        private readonly TeaRepository $teaRepository,
        private readonly ErrorController $errorController,
    ) {}

    public function indexAction(): ResponseInterface
    {
        $this->view->assign('teas', $this->teaRepository->findAll());
        return $this->htmlResponse();
    }

    /**
     * @throws PropagateResponseException
     */
    public function showAction(?Tea $tea = null): ResponseInterface
    {
        if ($tea === null) {
            $this->trigger404('No tea given.');
        }

        $this->view->assign('tea', $tea);
        return $this->htmlResponse();
    }

    /**
     * @throws PropagateResponseException
     *
     * @return never
     */
    protected function trigger404(string $message): void
    {
        throw new PropagateResponseException(
            $this->errorController->pageNotFoundAction($this->request, $message),
            1744021673
        );
    }
}
