<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Persistence;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Domain\Model\Tea;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(Tea::class)]
final class TeaTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['ttn/tea'];

    private PersistenceManagerInterface $persistenceManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->persistenceManager = $this->get(PersistenceManagerInterface::class);
    }

    #[Test]
    // @todo The "Validate" attributes of the model trigger an Extbase
    // deprecation on TYPO3 14.3. This needs a dedicated investigation and is
    // unrelated to this change.
    #[IgnoreDeprecations]
    public function mapsAllScalarData(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/propertyMapping/TeaWithAllScalarData.csv');

        $model = $this->persistenceManager->getObjectByIdentifier(1, Tea::class);
        self::assertInstanceOf(Tea::class, $model);

        self::assertSame('Earl Grey', $model->getTitle());
        self::assertSame('Fresh and hot.', $model->getDescription());
        self::assertSame(2, $model->getOwnerUid());
    }

    #[Test]
    // @todo The "Validate" attributes of the model trigger an Extbase
    // deprecation on TYPO3 14.3. This needs a dedicated investigation and is
    // unrelated to this change.
    #[IgnoreDeprecations]
    public function fillsImageRelation(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/propertyMapping/TeaWithImage.csv');

        $model = $this->persistenceManager->getObjectByIdentifier(1, Tea::class);
        self::assertInstanceOf(Tea::class, $model);

        $image = $model->getImage();
        self::assertInstanceOf(FileReference::class, $image);
        self::assertSame(1, $image->getUid());
    }

    #[Test]
    // @todo The "Validate" attributes of the model trigger an Extbase
    // deprecation on TYPO3 14.3. This needs a dedicated investigation and is
    // unrelated to this change.
    #[IgnoreDeprecations]
    public function mapsDeletedImageRelationToNull(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/propertyMapping/TeaWithDeletedImage.csv');

        $model = $this->persistenceManager->getObjectByIdentifier(1, Tea::class);
        self::assertInstanceOf(Tea::class, $model);

        self::assertNull($model->getImage());
    }

    #[Test]
    // @todo The "Validate" attributes of the model trigger an Extbase
    // deprecation on TYPO3 14.3. This needs a dedicated investigation and is
    // unrelated to this change.
    #[IgnoreDeprecations]
    public function canBePersisted(): void
    {
        $title = 'Godesberger Burgtee';
        $model = new Tea();
        $model->setTitle($title);

        $this->persistenceManager->add($model);
        $this->persistenceManager->persistAll();

        $this->assertCSVDataSet(__DIR__ . '/Fixtures/persistence/PersistedTea.csv');
    }
}
