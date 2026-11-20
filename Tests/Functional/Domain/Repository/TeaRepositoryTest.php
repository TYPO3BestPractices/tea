<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Domain\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Domain\Model\Tea;
use TTN\Tea\Domain\Repository\TeaRepository;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(TeaRepository::class)]
final class TeaRepositoryTest extends FunctionalTestCase
{
    private const FIXTURES_PATH = __DIR__ . '/Fixtures';

    protected array $testExtensionsToLoad = ['ttn/tea'];

    private TeaRepository $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->get(TeaRepository::class);
    }

    #[Test]
    public function isRepository(): void
    {
        self::assertInstanceOf(Repository::class, $this->subject);
    }

    #[Test]
    // @todo The "Validate" attributes of the model trigger an Extbase
    //   deprecation on TYPO3 14.3. This needs a dedicated investigation and is
    //   unrelated to this change.
    #[IgnoreDeprecations]
    public function findByUidForExistingRecordReturnsModel(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/findByUid/Tea.csv');

        $model = $this->subject->findByUid(1);

        self::assertInstanceOf(Tea::class, $model);
    }

    #[Test]
    #[IgnoreDeprecations]
    public function findAllForNoRecordsReturnsEmptyQueryResult(): void
    {
        $result = $this->subject->findAll();

        self::assertCount(0, $result);
    }

    #[Test]
    // @todo The "Validate" attributes of the model trigger an Extbase
    //   deprecation on TYPO3 14.3. This needs a dedicated investigation and is
    //   unrelated to this change.
    #[IgnoreDeprecations]
    public function findAllSortsByTitleInAscendingOrder(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/findAll/TwoUnsortedTeas.csv');

        $result = $this->subject->findAll();

        $model = $result->getFirst();
        self::assertInstanceOf(Tea::class, $model);
        self::assertSame('Assam', $model->getTitle());
    }

    #[Test]
    public function findByOwnerUidFindsTeaWithTheGivenOwnerUid(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/findByOwnerUid/TeaWithOwner.csv');

        $result = $this->subject->findByOwnerUid(1);

        self::assertCount(1, $result);
    }

    #[Test]
    public function findByOwnerUidFindsTeaWithTheGivenOwnerUidOnPage(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/findByOwnerUid/TeaWithOwnerOnPage.csv');

        $result = $this->subject->findByOwnerUid(1);

        self::assertCount(1, $result);
    }

    #[Test]
    public function findByOwnerUidIgnoresTeaWithNonMatchingOwnerUid(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/findByOwnerUid/TeaWithOwner.csv');

        $result = $this->subject->findByOwnerUid(2);

        self::assertCount(0, $result);
    }

    #[Test]
    public function findByOwnerUidIgnoresTeaWithZeroOwnerUid(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/findByOwnerUid/TeaWithoutOwner.csv');

        $result = $this->subject->findByOwnerUid(1);

        self::assertCount(0, $result);
    }

    #[Test]
    public function findByOwnerUidSortsByTitleInAscendingOrder(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/findByOwnerUid/TwoTeasWithOwner.csv');

        $result = $this->subject->findByOwnerUid(1);

        $model = $result->getFirst();
        self::assertInstanceOf(Tea::class, $model);
        self::assertSame('Assam', $model->getTitle());
    }

    #[Test]
    public function findAllFromAllPagesCanFindRecordsFromMultiplePages(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/findsAllFromAllPages/TwoTeasWithDifferentPids.csv');

        $result = $this->subject->findAllFromAllPages();

        self::assertCount(2, $result);
    }

    #[Test]
    public function findsAllFromAllPagesSortsByUidInDescendingOrder(): void
    {
        $this->importCSVDataSet(self::FIXTURES_PATH . '/findsAllFromAllPages/TwoTeasWithUidsForSorting.csv');

        $result = $this->subject->findAllFromAllPages();

        $model = $result->getFirst();
        self::assertInstanceOf(Tea::class, $model);
        self::assertSame(2, $model->getUid());
    }
}
