<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Basic unit tests.
 *
 * This can and should be used in every extension instead of dummy assertTrue(true) filler.
 * This checks against supported version and additional ensures that the test execution is
 * using the same core version installed, which can help to rule out other test failures
 * early due to wrong TYPO3 version usage.
 */
#[CoversNothing]
final class ExtensionLoadedTest extends FunctionalTestCase
{
    private const ALLOWED_MAJOR_VERSIONS = [12, 13];

    protected array $testExtensionsToLoad = [
        'ttn/tea',
    ];

    #[Test]
    public function isLoadedExtensionKey(): void
    {
        self::assertTrue(ExtensionManagementUtility::isLoaded('tea'));
    }

    #[Test]
    public function isLoadedComposerPackageName(): void
    {
        self::assertTrue(ExtensionManagementUtility::isLoaded('ttn/tea'));
    }

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
