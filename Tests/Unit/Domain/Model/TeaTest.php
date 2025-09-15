<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Unit\Domain\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Domain\Model\Tea;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(Tea::class)]
final class TeaTest extends UnitTestCase
{
    private Tea $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new Tea();
    }

    #[Test]
    public function isAbstractEntity(): void
    {
        self::assertInstanceOf(AbstractEntity::class, $this->subject);
    }

    #[Test]
    public function getTitleInitiallyReturnsEmptyString(): void
    {
        self::assertSame('', $this->subject->getTitle());
    }

    #[Test]
    public function setTitleSetsTitle(): void
    {
        $value = 'Earl Grey';
        $this->subject->setTitle($value);

        self::assertSame($value, $this->subject->getTitle());
    }

    #[Test]
    public function getDescriptionInitiallyReturnsEmptyString(): void
    {
        self::assertSame('', $this->subject->getDescription());
    }

    #[Test]
    public function setDescriptionSetsDescription(): void
    {
        $value = 'Very refreshing and amoratic.';
        $this->subject->setDescription($value);

        self::assertSame($value, $this->subject->getDescription());
    }

    #[Test]
    public function getImageInitiallyReturnsNull(): void
    {
        self::assertNull($this->subject->getImage());
    }

    #[Test]
    public function setImageSetsImage(): void
    {
        $model = new FileReference();
        $this->subject->setImage($model);

        self::assertSame($model, $this->subject->getImage());
    }

    #[Test]
    public function getOwnerUidInitiallyReturnsZero(): void
    {
        self::assertSame(0, $this->subject->getOwnerUid());
    }

    #[Test]
    public function setOwnerUidSetsOwnerUid(): void
    {
        $value = 123456;
        $this->subject->setOwnerUid($value);

        self::assertSame($value, $this->subject->getOwnerUid());
    }
}
