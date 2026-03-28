<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Unit\Environment;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversNothing]
final class ExtensionTest extends UnitTestCase
{
    #[Test]
    public function currentVersionIsSupported(): void
    {
        $supportedVersions = [12, 13];
        $currentVersion = (new Typo3Version())->getMajorVersion();
        self::assertContains(
            $currentVersion,
            $supportedVersions
        );
    }
}
