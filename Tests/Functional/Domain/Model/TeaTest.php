<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Domain\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Domain\Model\Tea;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(Tea::class)]
final class TeaTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['ttn/tea'];

    private Tea $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new Tea();

    }

    #[Test]
    public function validTitleLengthReturnsNoError(): void
    {
        $this->subject->setTitle(str_repeat('p', 255));

        $validatorResolver = $this->getContainer()->get(ValidatorResolver::class);
        $validator = $validatorResolver->getBaseValidatorConjunction(Tea::class);
        $result = $validator->validate($this->subject);
        self::assertFalse($result->forProperty('title')->hasErrors());

    }

    #[Test]
    public function invalidTitleLengthReturnsError(): void
    {
        $this->subject->setTitle(str_repeat('p', 256));

        $validatorResolver = $this->getContainer()->get(ValidatorResolver::class);
        $validator = $validatorResolver->getBaseValidatorConjunction(Tea::class);
        $result = $validator->validate($this->subject);
        self::assertTrue($result->forProperty('title')->hasErrors());

    }

    #[Test]
    public function validateDescriptionLengthReturnsNoErrors(): void
    {
        $this->subject->setDescription(str_repeat('d', 2000));

        $validatorResolver = $this->getContainer()->get(ValidatorResolver::class);
        $validator = $validatorResolver->getBaseValidatorConjunction(Tea::class);
        $result = $validator->validate($this->subject);
        self::assertFalse($result->forProperty('description')->hasErrors());

    }

    #[Test]
    public function invalidDescriptionLengthReturnsError(): void
    {
        $this->subject->setDescription(str_repeat('d', 2001));

        $validatorResolver = $this->getContainer()->get(ValidatorResolver::class);
        $validator = $validatorResolver->getBaseValidatorConjunction(Tea::class);
        $result = $validator->validate($this->subject);
        self::assertTrue($result->forProperty('description')->hasErrors());
    }

}
