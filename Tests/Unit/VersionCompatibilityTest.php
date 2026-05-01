<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversNothing;
use TTN\Tea\Tests\VersionCompatibilityTestsTrait;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversNothing]
final class VersionCompatibilityTest extends UnitTestCase
{
    use VersionCompatibilityTestsTrait;
}
