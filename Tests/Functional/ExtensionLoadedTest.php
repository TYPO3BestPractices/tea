<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversNothing]
final class ExtensionLoadedTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['ttn/tea'];

    protected bool $initializeDatabase = false;

    #[Test]
    public function isLoaded(): void
    {
        self::assertTrue(
            ExtensionManagementUtility::isLoaded('tea'),
        );
    }
}
