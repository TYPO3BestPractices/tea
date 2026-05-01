<?php

declare(strict_types=1);

namespace TTN\Tea\Tests;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Information\Typo3Version;

/**
 * Asserts that the tests run against a supported TYPO3 version, and against the
 * version they have been started for.
 *
 * The version to run the tests for is selected with the option `-t` of
 * `runTests.sh`, while the dependencies are installed separately. A run against
 * the dependencies of another version therefore looks like a regular one, and
 * fails somewhere in the middle of the suite where the cause is hard to
 * recognize.
 *
 * `runTests.sh` passes `--exclude-group not-core-<version>` for the version
 * given with `-t`, so exactly the pinned test of that version remains, and it
 * fails right away if another version is installed.
 *
 * The group names are written out rather than composed from constants, so that
 * they can be found together with the `--exclude-group` arguments which use
 * them.
 *
 * Both test suites use this trait, so they cannot drift apart.
 *
 * @see \TTN\Tea\Tests\Unit\VersionCompatibilityTest
 * @see \TTN\Tea\Tests\Functional\VersionCompatibilityTest
 */
trait VersionCompatibilityTestsTrait
{
    private const SUPPORTED_MAJOR_VERSIONS = [13, 14];

    private const VERSION_MISMATCH_MESSAGE = 'The tests are run for TYPO3 %s, but the dependencies are installed'
        . ' for another version. Install them for the same version, using the "-t" option.';

    #[Test]
    public function currentVersionIsSupported(): void
    {
        self::assertContains(
            (new Typo3Version())->getMajorVersion(),
            self::SUPPORTED_MAJOR_VERSIONS,
        );
    }

    #[Group('not-core-14.3')]
    #[Test]
    public function installedCoreIsVersion13(): void
    {
        self::assertSame(
            13,
            (new Typo3Version())->getMajorVersion(),
            sprintf(self::VERSION_MISMATCH_MESSAGE, '13.4'),
        );
    }

    #[Group('not-core-13.4')]
    #[Test]
    public function installedCoreIsVersion14(): void
    {
        self::assertSame(
            14,
            (new Typo3Version())->getMajorVersion(),
            sprintf(self::VERSION_MISMATCH_MESSAGE, '14.3'),
        );
    }
}
