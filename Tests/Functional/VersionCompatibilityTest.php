<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;
use TTN\Tea\Tests\VersionCompatibilityTestsTrait;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversNothing]
final class VersionCompatibilityTest extends FunctionalTestCase
{
    use VersionCompatibilityTestsTrait;

    protected array $testExtensionsToLoad = ['ttn/tea'];

    protected bool $initializeDatabase = false;
}
