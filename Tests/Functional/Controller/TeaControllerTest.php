<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Controller\TeaController;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

#[CoversClass(TeaController::class)]
final class TeaControllerTest extends AbstractFrontendControllerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/ContentElements.csv');
    }

    #[Test]
    public function indexActionShowsMessageWhenNoTeasAreAvailable(): void
    {
        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('No teas available.', $html);
    }

    #[Test]
    public function indexActionShowsNoTableMarkupWhenNoTeasAreAvailable(): void
    {
        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringNotContainsString('table', $html);
    }

    #[Test]
    public function indexActionRendersAllAvailableTeasOnStoragePage(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/Teas.csv');

        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Godesberger Burgtee', $html);
        self::assertStringContainsString('Oolong', $html);
    }

    #[Test]
    public function indexActionWithRecursionCanRenderTeaInStoragePageSubfolder(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/TeaInSubfolder.csv');

        $request = (new InternalRequest())->withPageId(1);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Tea in subfolder', $html);
    }

    #[Test]
    public function showActionRendersTheGivenTeas(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Database/TeaController/Teas.csv');

        $request = (new InternalRequest())->withPageId(3)->withQueryParameters(['tx_tea_teashow[tea]' => 1]);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Godesberger Burgtee', $html);
        self::assertStringNotContainsString('Oolong', $html);
    }

    #[Test]
    public function showActionTriggers404ForMissingTeaArgument(): void
    {
        $request = (new InternalRequest())->withPageId(3);

        $response = $this->executeFrontendSubRequest($request);

        self::assertSame(404, $response->getStatusCode());
    }

    #[Test]
    public function showActionTriggers404ForUnavailableTea(): void
    {
        $request = (new InternalRequest())->withPageId(3)->withQueryParameters(['tx_tea_teashow[tea]' => 1]);

        $response = $this->executeFrontendSubRequest($request);

        self::assertSame(404, $response->getStatusCode());
    }

    #[Test]
    public function showActionFor404RendersReasonFor404(): void
    {
        $request = (new InternalRequest())->withPageId(3);

        $html = (string)$this->executeFrontendSubRequest($request)->getBody();

        self::assertStringContainsString('Reason: No tea given.', $html);
    }
}
