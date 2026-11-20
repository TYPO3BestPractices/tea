<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use TTN\Tea\Command\CreateTestDataCommand;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(CreateTestDataCommand::class)]
final class CreateTestDataCommandTest extends FunctionalTestCase
{
    private const FIXTURES_PATH = __DIR__ . '/Fixtures';

    private const COMMAND_NAME = 'tea:create-test-data';

    protected array $testExtensionsToLoad = ['ttn/tea'];

    private CreateTestDataCommand $subject;

    private CommandTester $commandTester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(self::FIXTURES_PATH . '/Database/Pages.csv');
        $this->subject = new CreateTestDataCommand(self::COMMAND_NAME);
        $application = new Application();
        $application->add($this->subject);

        $command = $application->find('tea:create-test-data');
        $this->commandTester = new CommandTester($command);
    }

    #[Test]
    public function isConsoleCommand(): void
    {
        self::assertInstanceOf(Command::class, $this->subject);
    }

    #[Test]
    public function hasDescription(): void
    {
        $expected = 'Create test data for the tea extension in an already existing page (sysfolder).';
        self::assertSame($expected, $this->subject->getHelp());
    }

    #[Test]
    public function hasHelpText(): void
    {
        $expected = 'Create test data for the tea extension in an already existing page (sysfolder).';
        self::assertSame($expected, $this->subject->getHelp());
    }

    #[Test]
    public function runReturnsSuccessStatus(): void
    {
        $result = $this->commandTester->execute(
            [
                'pageUid' => 1,
            ],
        );

        self::assertSame(Command::SUCCESS, $result);
    }

    #[Test]
    public function createsTestData(): void
    {
        $this->commandTester->execute([
            'pageUid' => 1,
        ]);

        $this->assertCSVDataSet(self::FIXTURES_PATH . '/Database/Teas.csv');
    }

    #[Test]
    public function deletesExistingDataOnGivenPidBeforeCreatingNewData(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/Database/ExistingTeas.csv');
        $this->commandTester->execute(
            [
                'pageUid' => 1,
                '--delete-data-before' => true,
            ],
        );

        $this->assertCSVDataSet(self::FIXTURES_PATH . '/Database/TeasAfterDelete.csv');
    }

    #[Test]
    public function doesNotDeleteDataOnOtherPid(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/Database/OtherExistingTeas.csv');
        $this->commandTester->execute(
            [
                'pageUid' => 1,
                '--delete-data-before' => true,
            ],
        );

        $this->assertCSVDataSet(self::FIXTURES_PATH . '/Database/TeasAfterDeleteOtherExistingTeas.csv');
    }
}
