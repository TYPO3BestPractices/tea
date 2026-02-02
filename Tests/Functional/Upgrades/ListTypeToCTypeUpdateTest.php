<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Upgrades;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Upgrades\AbstractListTypeToCTypeUpdate;
use TTN\Tea\Upgrades\ListTypeToCTypeUpdate;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(ListTypeToCTypeUpdate::class)]
final class ListTypeToCTypeUpdateTest extends FunctionalTestCase
{
    /**
     * @var non-empty-string
     */
    private const FIXTURES_PREFIX = __DIR__ . '/Fixtures/';

    protected array $testExtensionsToLoad = ['ttn/tea'];

    private readonly ListTypeToCTypeUpdate $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->get(ListTypeToCTypeUpdate::class);
    }

    #[Test]
    public function isUpgradeWizard(): void
    {
        self::assertInstanceOf(AbstractListTypeToCTypeUpdate::class, $this->subject);
    }

    #[Test]
    public function hasDescription(): void
    {
        $expected = 'Migrates tea_index, tea_show, tea_front_end_editor from list_type to CType.';
        self::assertSame($expected, $this->subject->getDescription());
    }

    #[Test]
    public function hasTitle(): void
    {
        $expected = 'Migrates tea extension plugins';
        self::assertSame($expected, $this->subject->getTitle());
    }

    #[Test]
    public function hasListTypeToCTypeMapping(): void
    {
        $expected = [
            'tea_teaindex' => 'tea_teaindex',
            'tea_teashow' => 'tea_teashow',
            'tea_teafront_end_editor' => 'tea_teafront_end_editor',
        ];
        self::assertSame($expected, $this->subject->getListTypeToCTypeMapping());
    }

    #[Test]
    public function updateNecessary(): void
    {
        $this->importCSVDataSet(
            self::FIXTURES_PREFIX . 'PluginAsListType.csv'
        );
        self::assertTrue($this->subject->updateNecessary());
    }

    #[Test]
    public function updateNotNecessary(): void
    {
        $this->importCSVDataSet(
            self::FIXTURES_PREFIX . 'PluginAsCType.csv'
        );
        self::assertFalse($this->subject->updateNecessary());
    }

    #[Test]
    public function executeUpdateOnListType(): void
    {
        $this->importCSVDataSet(
            self::FIXTURES_PREFIX . 'PluginAsListType.csv'
        );
        self::assertTrue($this->subject->updateNecessary());
        $result = $this->subject->executeUpdate();
        self::assertTrue($result);
        self::assertFalse($this->subject->updateNecessary());
        $this->assertCSVDataSet(
            self::FIXTURES_PREFIX . 'PluginAsCType.csv'
        );
    }

    #[Test]
    public function executeUpdateOnCType(): void
    {
        $this->importCSVDataSet(
            self::FIXTURES_PREFIX . 'PluginAsCType.csv'
        );
        self::assertFalse($this->subject->updateNecessary());
        $result = $this->subject->executeUpdate();
        self::assertTrue($result);
        $this->assertCSVDataSet(
            self::FIXTURES_PREFIX . 'PluginAsCType.csv'
        );
    }

}
