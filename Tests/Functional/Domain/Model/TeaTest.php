<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Domain\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Domain\Model\Tea;
use TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(Tea::class)]
final class TeaTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['ttn/tea'];

    private Tea $subject;
    private ConjunctionValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Tea();
        $validatorResolver = $this->getContainer()->get(ValidatorResolver::class);
        $this->validator = $validatorResolver->getBaseValidatorConjunction(Tea::class);
    }

    #[Test]
    public function titleWithMaximumLengthPassesValidation(): void
    {
        $this->subject->setTitle(str_repeat('p', 255));
        $result = $this->validator->validate($this->subject);
        self::assertFalse($result->forProperty('title')->hasErrors());
    }

    #[Test]
    public function titleLongerThanMaximumLengthDoesNotPassValidation(): void
    {
        $this->subject->setTitle(str_repeat('p', 256));
        $result = $this->validator->validate($this->subject);
        self::assertTrue($result->forProperty('title')->hasErrors());
    }

    #[Test]
    public function emptyTitleDoesNotPassValidation(): void
    {
        $this->subject->setTitle('');
        $result = $this->validator->validate($this->subject);
        self::assertTrue($result->forProperty('title')->hasErrors());
    }

    #[Test]
    public function descriptionWithMaximumLengthPassesValidation(): void
    {
        $this->subject->setDescription(str_repeat('d', 2000));
        $result = $this->validator->validate($this->subject);
        self::assertFalse($result->forProperty('description')->hasErrors());
    }

    #[Test]
    public function descriptionLongerThanMaximumLengthDoesNotPassValidation(): void
    {
        $this->subject->setDescription(str_repeat('d', 2001));
        $result = $this->validator->validate($this->subject);
        self::assertTrue($result->forProperty('description')->hasErrors());
    }

}
