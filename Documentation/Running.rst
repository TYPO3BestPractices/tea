.. include:: /Includes.rst.txt

.. _running-checks-and-tests:

======================
Running checks & tests
======================

Most code checks and tests can be run via Composer commands.

.. contents:: Table of Contents:
   :backlinks: top
   :class: compact-list
   :depth: 1
   :local:

.. _composer-scripts:

Composer scripts
================

For most development-related tasks, this extension provides Composer scripts.
If you are working locally (Composer needs to be installed on your local
machine), you can run them using :bash:`composer <scriptname>`.

You can run :bash:`composer` or :bash:`./Build/Scripts/runTests.sh -s composer` to
display a list of all available Composer commands and scripts. For all custom
Composer scripts there are descriptions in the `script-description` section of
the `composer.json`.

If you have problems with missing dependencies on your local machine, it is
recommended to execute tests with the usage of the `runTests.sh` script.

Example: `./Build/Scripts/runTests.sh -s composer ci:php:lint`

It is not necessary to executing the tests only with the composer scripts.
You can also use the `runTests.sh`. This makes your life easier because you
don't have to worry about local dependencies.

.. note::

    It's always a good idea to look into the `runTests.sh` "Examples" section to
    get an idea how it works. In the "Options" section you can find all
    available options.

.. _running-code-checks:

Running code checks
===================

You can currently run these code checks on the command line:

.. index:: Commands; composer ci:composer:normalize
.. code-block:: bash

    composer ci:composer:normalize

Checks the composer.json.

.. index:: Commands; composer ci:json:lint
.. code-block:: bash

    composer ci:json:lint

Lints the JSON files.

.. index:: Commands; composer ci:php
.. code-block:: bash

    composer ci:php

Runs all static checks for the PHP files.

.. index:: Commands; composer ci:php:cs-fixer
.. code-block:: bash

    composer ci:php:cs-fixer

Checks the code style with the PHP Coding Standards Fixer (PHP-CS-Fixer).

.. index:: Commands; composer ci:php:lint
.. code-block:: bash

    composer ci:php:lint

Lints the PHP files for syntax errors.

.. index:: Commands; composer ci:php:stan
.. code-block:: bash

    composer ci:php:stan

Checks the PHP types using PHPStan.

.. index:: Commands; composer ci:static
.. code-block:: bash

    composer ci:static

Runs all static code checks (syntax, style, types).

.. index:: Commands; composer ci:typoscript:lint
.. code-block:: bash

    composer ci:typoscript:lint

Lints the TypoScript files.

.. index:: Commands; composer ci:yaml:lint
.. code-block:: bash

    composer ci:yaml:lint

Lints the YAML files.

.. index:: Commands; composer fix
.. code-block:: bash

    composer fix

Runs all fixers (except for the ones that need JavaScript).

.. index:: Commands; composer fix:php
.. code-block:: bash

    composer fix:php

Runs all fixers for the PHP code.

.. index:: Commands; composer fix:php:cs
.. code-block:: bash

    composer fix:php:cs

Fixes the code style with PHP-CS-Fixer.

.. index:: Commands; composer phpstan:baseline
.. code-block:: bash

    composer phpstan:baseline

Updates the PHPStan baseline file to match the code.

.. _running-unit-and-functional-tests:

Running unit and functional tests
=================================

.. note::

   Executing tests on Windows via `runTests.sh` should be done within the
   [Windows Subsystem for Linux (WSL 2)](https://en.wikipedia.org/wiki/Windows_Subsystem_for_Linux).

.. important::

    Before running any commands you need to install all dependencies with
    `runTests.sh -s composer update`

You can currently run these tests and coverages on the command line:

.. index:: Commands; composer ci:coverage
.. code-block:: bash

    composer ci:coverage

Runs the ci:coverage script as defined in composer.json.

.. index:: Commands; composer ci:coverage:functional
.. code-block:: bash

    composer ci:coverage:functional

Generates the code coverage report for functional tests.

.. index:: Commands; composer ci:coverage:merge
.. code-block:: bash

    composer ci:coverage:merge

Merges the code coverage reports for unit and functional tests.

.. index:: Commands; composer ci:coverage:unit
.. code-block:: bash

    composer ci:coverage:unit

Generates the code coverage report for unit tests.

.. index:: Commands; composer ci:tests:functional
.. code-block:: bash

    composer ci:tests:functional

Runs the functional tests.

On executing functional tests a database connection is needed. Therefore you
should execute functional tests directly with the command in the `runTests.sh`.
Otherwise you have to take care of the database connection by yourself.

Example: `./Build/Scripts/runTests.sh -s functional`

.. index:: Commands; composer ci:tests:unit
.. code-block:: bash

    composer ci:tests:unit

Runs the unit tests.

.. _running-unit-and-functional-tests-in-phpstorm:

Running unit and functional tests in PHPStorm
=============================================

General setup
-------------

-  Open :guilabel:`File > Settings > PHP > Test Frameworks`.
-  (*) Use Composer autoloader.
-  Path to script: select `.Build/vendor/autoload.php` in your project folder.

In the Run configurations, edit the PHPUnit configuration and use these
settings so this configuration can serve as a template:

-  Directory: use the `Tests/Unit` directory in your project.
-  (*) Use alternative configuration file.
-  Use `.Build/vendor/typo3/testing-framework/Resources/Core/Build/UnitTests.xml`
   in your project folder.
-  Add the following environment variables:

   -  typo3DatabaseUsername
   -  typo3DatabasePassword
   -  typo3DatabaseHost
   -  typo3DatabaseName

Unit tests configuration
------------------------

In the Run configurations, copy the PHPUnit configuration and use these
settings:

-  Directory: use the `Tests/Unit` directory in your project

Functional tests configuration
------------------------------

In the Run configurations, copy the PHPUnit configuration and use these
settings:

-  Directory: use the `Tests/Functional` directory in your project.
-  (*) Use alternative configuration file.
-  Use
   `.Build/vendor/typo3/testing-framework/Resources/Core/Build/FunctionalTests.xml`.
