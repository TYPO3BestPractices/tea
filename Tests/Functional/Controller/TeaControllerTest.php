<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Controller\TeaController;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(TeaController::class)]
final class TeaControllerTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['ttn/tea'];

    protected array $coreExtensionsToLoad = ['typo3/cms-fluid-styled-content'];

    protected array $pathsToLinkInTestInstance = [
        'typo3conf/ext/tea/Tests/Functional/Controller/Fixtures/Sites/' => 'typo3conf/sites',
    ];

    protected array $pathsToProvideInTestInstance = [
        'typo3conf/ext/tea/Tests/Functional/Controller/Fixtures/Database/TeaController/ImageOfTea.jpeg' => 'fileadmin/user_upload/ImageOfTea.jpeg',
    ];

    protected array $configurationToUseInTestInstance = [
        'FE' => [
            'cacheHash' => [
                'enforceValidation' => false,
            ],
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/RootPage.csv');
        $this->setUpFrontendRootPage(1, [
            'constants' => [
                'EXT:fluid_styled_content/Configuration/TypoScript/constants.typoscript',
                'EXT:tea/Configuration/TypoScript/constants.typoscript',
            ],
            'setup' => [
                'EXT:fluid_styled_content/Configuration/TypoScript/setup.typoscript',
                'EXT:tea/Configuration/TypoScript/setup.typoscript',
                'EXT:tea/Tests/Functional/Controller/Fixtures/TypoScript/Setup/Rendering.typoscript',
            ],
        ]);
    }

    #[Test]
    #[IgnoreDeprecations]
    public function indexActionShowsMessageWhenNoTeasAreAvailable(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/indexAction/IndexWithoutTeas.csv');

        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('No teas available.', $html);
    }

    #[Test]
    public function indexActionShowsNoTableMarkupWhenNoTeasAreAvailable(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/indexAction/IndexWithoutTeas.csv');

        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringNotContainsString('table', $html);
    }

    #[Test]
    #[IgnoreDeprecations]
    public function indexActionRendersAllAvailableTeasOnStoragePage(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/indexAction/IndexWithTeasOnStoragePage.csv');

        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Godesberger Burgtee', $html);
        self::assertStringContainsString('Oolong', $html);
    }

    #[Test]
    public function indexActionWithRecursionCanRenderTeaInStoragePageSubfolder(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/indexAction/IndexWithTeaInStoragePageSubfolder.csv');

        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Tea in subfolder', $html);
    }

    #[Test]
    public function indexActionWithoutRecursionDoesNotRenderTeaInStoragePageSubfolder(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/indexAction/IndexWithoutRecursionAndTeaInStoragePageSubfolder.csv');

        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('No teas available.', $html);
        self::assertStringNotContainsString('Tea in subfolder', $html);
    }

    #[Test]
    public function indexActionWithRecursionDepthOneRendersTeaInDirectSubfolder(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/indexAction/IndexWithRecursionDepthOneAndTeasInNestedSubfolders.csv');

        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Tea in subfolder', $html);
    }

    #[Test]
    public function indexActionWithRecursionDepthOneDoesNotRenderTeaInNestedSubfolder(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/indexAction/IndexWithRecursionDepthOneAndTeasInNestedSubfolders.csv');

        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Tea in subfolder', $html);
        self::assertStringNotContainsString('Tea in nested subfolder', $html);
    }

    #[Test]
    public function showActionRendersTheGivenTeas(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/showAction/ShowWithTwoTeas.csv');

        $request = (new InternalRequest())->withPageId(3)->withQueryParameters(['tx_tea_teashow[tea]' => 1]);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Godesberger Burgtee', $html);
        self::assertStringNotContainsString('Oolong', $html);
    }

    #[Test]
    public function showActionRendersImageOfTea(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/showAction/ShowWithTeaWithImage.csv');

        $request = (new InternalRequest())->withPageId(3)->withQueryParameters(['tx_tea_teashow[tea]' => 1]);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('<figure>', $html);
        self::assertStringContainsString('<img', $html);
        self::assertStringContainsString('ImageOfTea', $html);
    }

    #[Test]
    public function showActionRendersAltTextFromImageOfTea(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/showAction/ShowWithTeaWithAltTextOfImage.csv');

        $request = (new InternalRequest())->withPageId(3)->withQueryParameters(['tx_tea_teashow[tea]' => 1]);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('alt="Alt-text"', $html);
    }

    #[Test]
    public function showActionRendersMaxWidthFromImageOfTea(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/showAction/ShowWithTeaWithImage.csv');

        $request = (new InternalRequest())->withPageId(3)->withQueryParameters(['tx_tea_teashow[tea]' => 1]);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('width="600"', $html);
    }

    #[Test]
    public function showActionForTeaWithoutImageDoesNotRenderFigure(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/showAction/ShowWithTwoTeas.csv');

        $request = (new InternalRequest())->withPageId(3)->withQueryParameters(['tx_tea_teashow[tea]' => 1]);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringNotContainsString('<figure>', $html);
    }

    #[Test]
    public function showActionTriggers404ForMissingTeaArgument(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/showAction/ShowWithoutTeas.csv');

        $request = (new InternalRequest())->withPageId(3);

        $response = $this->executeFrontendSubRequest($request);

        self::assertSame(404, $response->getStatusCode());
    }

    #[Test]
    public function showActionTriggers404ForUnavailableTea(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/showAction/ShowWithoutTeas.csv');

        $request = (new InternalRequest())->withPageId(3)->withQueryParameters(['tx_tea_teashow[tea]' => 1]);

        $response = $this->executeFrontendSubRequest($request);

        self::assertSame(404, $response->getStatusCode());
    }

    #[Test]
    public function showActionFor404RendersReasonFor404(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/showAction/ShowWithoutTeas.csv');

        $request = (new InternalRequest())->withPageId(3);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Reason: No tea given.', $html);
    }
}
