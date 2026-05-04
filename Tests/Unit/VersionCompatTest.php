<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Basic unit tests.
 *
 * This can and should be used in every extension instead of dummy assertTrue(true) filler.
 */
#[CoversNothing]
final class VersionCompatTest extends UnitTestCase
{
    private const ALLOWED_MAJOR_VERSIONS = [12, 13];

    #[Test]
    public function allowedMajorTypo3Version(): void
    {
        self::assertContains((new Typo3Version())->getMajorVersion(), self::ALLOWED_MAJOR_VERSIONS);
    }

    #[Group('not-core-13.4')]
    #[Test]
    public function verifyCore12(): void
    {
        self::assertSame(12, (new Typo3Version())->getMajorVersion());
    }

    #[Group('not-core-12.4')]
    #[Test]
    public function verifyCore13(): void
    {
        self::assertSame(13, (new Typo3Version())->getMajorVersion());
    }
}
