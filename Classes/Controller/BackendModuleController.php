<?php

declare(strict_types=1);

namespace TTN\Tea\Controller;

use Psr\Http\Message\ResponseInterface;
use TTN\Tea\Domain\Repository\TeaRepository;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

#[AsController]
final class BackendModuleController extends ActionController
{
    public function __construct(
        private readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly TeaRepository $teaRepository,
    ) {}

    public function indexAction(): ResponseInterface
    {
        $teas = $this->teaRepository->findAllFromAllPages();

        if ($teas->count() === 0) {
            $this->addFlashMessage(
                LocalizationUtility::translate('LLL:EXT:tea/Resources/Private/Language/locallang_index_mod.xlf:flashmessage.missing_teas.message') ?? '',
                LocalizationUtility::translate('LLL:EXT:tea/Resources/Private/Language/locallang_index_mod.xlf:flashmessage.missing_teas.title') ?? '',
                ContextualFeedbackSeverity::WARNING
            );
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

        $moduleTemplate->assign('teas', $teas);

        return $moduleTemplate->renderResponse('BackendModule/Index');
    }
}
