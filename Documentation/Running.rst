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

.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer check:php:lint

This makes your life easier because you
don't have to worry about local dependencies.

.. note::

    It's always a good idea to look into the `runTests.sh` "Examples" section to
    get an idea how it works. In the "Options" section you can find all
    available options.

.. _running-code-checks:

Running code checks
===================

You can currently run these code checks on the command line:

.. index:: Commands; composer check:composer:normalize
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer check:composer:normalize

Checks the composer.json.

.. index:: Commands; composer check:json:lint
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer check:json:lint

Lints the JSON files.

.. index:: Commands; composer check:php
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer check:php

Runs all static checks for the PHP files.

.. index:: Commands; composer check:php:cs-fixer
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer check:php:cs-fixer

Checks the code style with the PHP Coding Standards Fixer (PHP-CS-Fixer).

.. index:: Commands; composer check:php:lint
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer check:php:lint

Lints the PHP files for syntax errors.

.. index:: Commands; composer check:php:stan
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer check:php:stan

Checks the PHP types using PHPStan.

.. index:: Commands; composer check:static
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer check:static

Runs all static code checks (syntax, style, types).

.. index:: Commands; composer check:typoscript:lint
.. code-block:: bash

    ./Build/Scripts/runTests.sh -scomposer check:typoscript:lint

Lints the TypoScript files.

.. index:: Commands; composer check:yaml:lint
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer check:yaml:lint

Lints the YAML files.

.. index:: Commands; composer fix
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer fix

Runs all fixers (except for the ones that need JavaScript).

.. index:: Commands; composer fix:php
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer fix:php

Runs all fixers for the PHP code.

.. index:: Commands; composer fix:php:cs-fixer
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer fix:php:cs-fixer

Fixes the code style with PHP-CS-Fixer.

.. index:: Commands; composer phpstan:baseline
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer phpstan:baseline

Updates the PHPStan baseline file to match the code.

.. _running-unit-and-functional-tests:

Running unit and functional tests
=================================

.. note::

   Executing tests on Windows via `runTests.sh` should be done within the
   [Windows Subsystem for Linux (WSL 2)](https://en.wikipedia.org/wiki/Windows_Subsystem_for_Linux).

.. important::

    Before running any commands you need to install all dependencies with

.. code-block:: bash

    ./Build/Scripts/runTests.sh -s composer update

You can currently run these tests on the command line:

.. index:: Commands; composer check:tests:functional
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s functional

Runs the functional tests using a database populated from the CSV files in
`Tests/Functional/Controller/Fixtures/Database` folder.

.. note::

    For executing functional tests, a database connection is needed. Therefore,
    it is recommended to run the functional tests using :code:`runTests.sh`.

.. index:: Commands; composer check:tests:unit
.. code-block:: bash

    ./Build/Scripts/runTests.sh -s unit

Runs the unit tests.

.. tip::

    With the option :code:`-p`, you can specify which PHP version will be used to
    execute the tests. If you don't specify a PHP version, the default PHP
    version will be used.

    .. code-block:: bash

        ./Build/Scripts/runTests.sh -p 8.2 -s unit

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
