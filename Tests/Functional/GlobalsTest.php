<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversNothing]
final class GlobalsTest extends FunctionalTestCase
{
    #[Test]
    public function globalVariablesAreNotSetWhenStartingTheTest1(): void
    {
        self::assertArrayNotHasKey('COFFEE', $GLOBALS);

        $GLOBALS['COFFEE'] = 'is great';
    }

    #[Test]
    public function globalVariablesAreNotSetWhenStartingTheTest2(): void
    {
        self::assertArrayNotHasKey('COFFEE', $GLOBALS);

        $GLOBALS['COFFEE'] = 'is even better';
    }
}
