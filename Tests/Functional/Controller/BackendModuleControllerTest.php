<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use TTN\Tea\Controller\BackendModuleController;
use TYPO3\CMS\Backend\Routing\Route;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(BackendModuleController::class)]
final class BackendModuleControllerTest extends FunctionalTestCase
{
    private const TRANSLATE_KEY_PREFIX = 'LLL:EXT:tea/Resources/Private/Language/locallang_index_mod.xlf:';

    protected function setUp(): void
    {
        $this->testExtensionsToLoad = [
            'ttn/tea',
        ];

        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/BackendModuleController/BackendUser.csv');
        $this->setUpBackendUser(1);
        $GLOBALS['LANG'] = $this->get(LanguageServiceFactory::class)->create('en');
    }

    #[Test]
    public function indexListsTeasFromMultiplePids(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/BackendModuleController/TeasForIndex.csv');

        $response = $this->executeRequest(
            '/module/tea/index/BackendModule/index',
            'tea_index',
            'index',
        );

        $html = $response->getBody()->__toString();

        self::assertStringContainsString('Tea 1', $html);
        self::assertStringContainsString('Tea 2', $html);
    }

    #[Test]
    public function indexProvidesCaptionForListing(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/BackendModuleController/TeaForIndex.csv');

        $response = $this->executeRequest(
            '/module/tea/index/BackendModule/index',
            'tea_index',
            'index',
        );

        $html = $response->getBody()->__toString();

        self::assertTranslationKeyIsRendered('listing.caption', $html);
    }

    #[Test]
    public function indexListsTeasFromSortedByUidInDescendingOrder(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/BackendModuleController/TeasForIndex.csv');

        $response = $this->executeRequest(
            '/module/tea/index/BackendModule/index',
            'tea_index',
            'index',
        );

        $html = $response->getBody()->__toString();

        self::assertLessThan(
            strpos($html, 'Tea 1'),
            strpos($html, 'Tea 2'),
            'Tea 1 is not sorted after Tea 2'
        );
    }

    #[Test]
    public function indexShowsFlashMessageIfNoTeaExists(): void
    {
        $response = $this->executeRequest(
            '/module/tea/index/BackendModule/index',
            'tea_index',
            'index',
        );

        $html = $response->getBody()->__toString();

        self::assertTranslationKeyIsRendered('flash_message.missing_teas.title', $html);
        self::assertTranslationKeyIsRendered('flash_message.missing_teas.message', $html);
        self::assertStringContainsString('alert-warning', $html);
    }

    #[Test]
    public function indexDoesNotShowFlashMessageIfTeaExists(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/BackendModuleController/TeaForIndex.csv');

        $response = $this->executeRequest(
            '/module/tea/index/BackendModule/index',
            'tea_index',
            'index',
        );

        $html = $response->getBody()->__toString();

        self::assertStringNotContainsString('alert-warning', $html);
    }

    #[Test]
    public function indexDoesNotShowTeaListingMarkupIfNoTeaExists(): void
    {
        $response = $this->executeRequest(
            '/module/tea/index/BackendModule/index',
            'tea_index',
            'index',
        );

        $html = $response->getBody()->__toString();

        self::assertTranslationKeyIsNotRendered('listing.caption', $html);
    }

    /**
     * @param non-empty-string $route
     * @param non-empty-string $pluginName
     * @param non-empty-string $action
     */
    private function executeRequest(
        string $route,
        string $pluginName,
        string $action
    ): ResponseInterface {
        $request = $this->createRequest(
            $route,
            'ttn/tea',
            $pluginName,
            $action,
            BackendModuleController::class,
        );
        $GLOBALS['TYPO3_REQUEST'] = $request;

        $isCli = Environment::isCli();

        Environment::initialize(
            Environment::getContext(),
            false,
            Environment::isComposerMode(),
            Environment::getProjectPath(),
            Environment::getPublicPath(),
            Environment::getVarPath(),
            Environment::getConfigPath(),
            Environment::getCurrentScript(),
            Environment::toArray()['os']
        );

        $response = $this->get(BackendModuleController::class)
            ->processRequest(new Request($request));

        Environment::initialize(
            Environment::getContext(),
            $isCli,
            Environment::isComposerMode(),
            Environment::getProjectPath(),
            Environment::getPublicPath(),
            Environment::getVarPath(),
            Environment::getConfigPath(),
            Environment::getCurrentScript(),
            Environment::toArray()['os']
        );

        return $response;
    }

    /**
     * @param non-empty-string $route
     * @param non-empty-string $packageName
     * @param non-empty-string $pluginName
     * @param non-empty-string $action
     * @param class-string<ActionController> $controllerClassName
     */
    private function createRequest(
        string $route,
        string $packageName,
        string $pluginName,
        string $action,
        string $controllerClassName,
    ): ServerRequest {
        $extbaseParameters = (new ExtbaseRequestParameters($controllerClassName))
            ->setControllerObjectName($controllerClassName)
            ->setControllerActionName($action)
            ->setPluginName($pluginName);

        return (new ServerRequest($route))
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE)
            ->withAttribute('site', new Site('test', 1, ['base' => 'localhost/']))
            ->withAttribute('route', new Route($route, ['packageName' => $packageName]))
            ->withAttribute('extbase', $extbaseParameters);
    }

    /**
     * @param non-empty-string $translationKey
     */
    private function assertTranslationKeyIsRendered(string $translationKey, string $html): void
    {
        $fullTranslationId = self::TRANSLATE_KEY_PREFIX . $translationKey;

        $translation = LocalizationUtility::translate($fullTranslationId);

        self::assertIsString($translation, 'Translation key "' . $fullTranslationId . '" does not exist.');
        self::assertStringContainsString($translation, $html);
    }

    /**
     * @param non-empty-string $translationKey
     */
    private function assertTranslationKeyIsNotRendered(string $translationKey, string $html): void
    {
        $fullTranslationId = self::TRANSLATE_KEY_PREFIX . $translationKey;

        $translation = LocalizationUtility::translate($fullTranslationId);

        self::assertIsString($translation, 'Translation key "' . $fullTranslationId . '" does not exist.');
        self::assertStringNotContainsString($translation, $html);
    }
}
