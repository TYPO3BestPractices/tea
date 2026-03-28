.. include:: /Includes.rst.txt

.. _assertions-and-type-checks:

==========================================
Our approach to assertions and type checks
==========================================

In general, we use assertions and type checks to catch bad input, to help debug
problems, and to help static type analysis tools like PHPStan or Psalm to
determine the type and range of a variable where it cannot detect it
automatically.

In production code
------------------

To catch bad input that actually is possible, we throw an exception. This allows
developers and users to see what is wrong and to fix it.

..  code-block:: php

    if (!$model instanceof Tea) {
        throw new \RuntimeException('No model found.', 1687363745);
    }


To help PHPStan and Psalm with variables where we (as developers) know the type
and range for sure, but the tool cannot determine it automatically, we use
assertions.

This makes the type and range obvious both to PHPStan as well as the human
reader.

..  code-block:: php

    /**
     * @return int<0, max>
     */
    private function getUidOfLoggedInUser(): int
    {
        $userUid = $this->context->getPropertyFromAspect('frontend.user', 'id');
        \assert(\is_int($userUid) && $userUid >= 0);

        return $userUid;
    }

In tests
--------

In tests, we use PHPUnit's assertions to check the expected outcome of a test,
and also to test preconditions.

This allows us to have test failure messages that help debugging as well as
allow PHPStan to determine types.

..  code-block:: php

    /**
     * @test
     */
    public function findByUidForExistingRecordMapsAllScalarData(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/TeaWithAllScalarData.csv');

        $model = $this->subject->findByUid(1);
        self::assertInstanceOf(Tea::class, $model);

        self::assertSame('Earl Grey', $model->getTitle());
        self::assertSame('Fresh and hot.', $model->getDescription());
        self::assertSame(2, $model->getOwnerUid());
    }

