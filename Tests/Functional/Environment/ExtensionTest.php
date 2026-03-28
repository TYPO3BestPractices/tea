<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Environment;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversNothing]
final class ExtensionTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['ttn/tea'];
    protected bool $initializeDatabase = false;

    #[Test]
    public function isLoadedByExensionkey(): void
    {
        $isLoaded = ExtensionManagementUtility::isLoaded('tea');
        self::assertTrue($isLoaded);
    }
}
