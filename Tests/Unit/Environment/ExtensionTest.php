<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Unit\Environment;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversNothing]
final class ExtensionTest extends UnitTestCase
{
    #[Test]
    public function checkIfCurrentVersionIsSupported(): void
    {
        // Define the supported TYPO3 versions
        $supportedVersions = [12, 13];
        // Get the current TYPO3 version
        $currentVersion = (new \TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion();
        // Check if the current TYPO3 version is in the array
        self::assertContains(
            $currentVersion,
            $supportedVersions
        );
    }
}
