<?php

declare(strict_types=1);

namespace TTN\Tea\Tests\Functional\Domain\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TTN\Tea\Domain\Model\Tea;
use TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(Tea::class)]
final class TeaTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['ttn/tea'];

    protected bool $initializeDatabase = false;

    private Tea $subject;

    private ConjunctionValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $validatorResolver = $this->get(ValidatorResolver::class);
        $this->validator = $validatorResolver->getBaseValidatorConjunction(Tea::class);

        $this->subject = new Tea();
    }

    #[Test]
    #[IgnoreDeprecations]
    #[DataProvider('validTitles')]
    public function validTitlePassesValidation(string $title): void
    {
        $this->subject->setTitle($title);

        $result = $this->validator->validate($this->subject);

        self::assertFalse($result->forProperty('title')->hasErrors());
    }

    /**
     * @return \Generator<non-empty-string, array{0: non-empty-string}>
     */
    public static function validTitles(): \Generator
    {
        yield 'one character' => ['p'];
        yield 'maximum length' => [str_repeat('p', 255)];
    }

    #[Test]
    #[IgnoreDeprecations]
    #[DataProvider('invalidTitles')]
    public function invalidTitleDoesNotPassValidation(string $title): void
    {
        $this->subject->setTitle($title);

        $result = $this->validator->validate($this->subject);

        self::assertTrue($result->forProperty('title')->hasErrors());
    }

    /**
     * @return \Generator<non-empty-string, array{0: string}>
     */
    public static function invalidTitles(): \Generator
    {
        yield 'empty' => [''];
        yield 'longer than maximum length' => [str_repeat('p', 256)];
    }

    #[Test]
    #[IgnoreDeprecations]
    #[DataProvider('validDescriptions')]
    public function validDescriptionPassesValidation(string $description): void
    {
        $this->subject->setDescription($description);

        $result = $this->validator->validate($this->subject);

        self::assertFalse($result->forProperty('description')->hasErrors());
    }

    /**
     * @return \Generator<non-empty-string, array{0: string}>
     */
    public static function validDescriptions(): \Generator
    {
        yield 'empty' => [''];
        yield 'maximum length' => [str_repeat('d', 2000)];
    }

    #[Test]
    #[IgnoreDeprecations]
    public function descriptionLongerThanMaximumLengthDoesNotPassValidation(): void
    {
        $this->subject->setDescription(str_repeat('d', 2001));

        $result = $this->validator->validate($this->subject);

        self::assertTrue($result->forProperty('description')->hasErrors());
    }
}
