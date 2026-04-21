.. include:: /Includes.rst.txt

.. _forLiveExplorers:

==================
For Live-Explorers
==================

Your use case: You are developing your own extension and want to add tests to improve your code quality.

.. _preparation:

Preparation
===========

Checkout your own extension.

In the composer.json of your extension add the following parts of the composer.json of the tea-extension:

* require-dev
* scripts
* scripts-descriptions

From the config-section of tea-extension, copy these two lines:

* "vendor-dir": ".Build/vendor"
* "bin-dir": ".Build/bin",

Copy the folder "Build" (not: ".Build"!) from tea-extension into your extension.

Merge .gitignore from tea-extension into your extension.

.. _run-tests:

Run Tests
=========

After preparation run:

.. code-block:: bash

  ./Build/Scripts/runTests.sh -s composer up -W

Composer should load a long list of dev-dependencies.

Then use the testing commands. E.g.

.. code-block:: bash

  ./Build/Scripts/runTests.sh -s composer check:php:stan


