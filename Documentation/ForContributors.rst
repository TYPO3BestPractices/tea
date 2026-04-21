.. include:: /Includes.rst.txt

.. _forContributors:

================
For Contributors
================

Your use case: You might want to contribute to the tea-extensions.
You want to run the code quality checks and automated tests locally (using a
local PHP, Composer, and database).


.. _clone:

Cloning the distribution
========================

To use the full functionality of the tea-extension, you need a surrounding
TYPO3-installation. You might take any installation for that, but we suggest the usage of the
`TYPO3-testing-distribution <https://github.com/oliverklee/TYPO3-testing-distribution/>`__
by Oliver Klee as development environment. The distribution comes with a frontend,
example data and predefined plugins.


The following commands expects that you have a GitHub-account and added your SSH key.

.. index:: Clone TYPO3 Testing Distribution
.. code-block:: bash

   git clone git@github.com:oliverklee/TYPO3-testing-distribution.git

Without GitHub-account, clone via https:

.. code-block:: bash

   git clone https://github.com/oliverklee/TYPO3-testing-distribution.git


Please check out the branch corresponding to your required TYPO3 version, for example 12.x.

For more information about the TYPO3-testing-distribution (e.g. backend user credentials), use the documentation:
https://github.com/oliverklee-de/TYPO3-testing-distribution/blob/13.x/README.md (TYPO3 13)

Again, adjust the link to the required TYPO3 version.

.. _clone-tea:

Cloning the tea extension
=========================

In the same way clone the tea extension.

.. index:: Clone Tea Extension
.. code-block:: bash

   git clone git@github.com:TYPO3BestPractices/tea.git

You can organize the folder structure as you wish. It might look similar to this:

.. index:: Folder structure
.. code-block:: bash

    git\
        TYPO3-testing-distribution
        tea


Inside the testing distribution there is a file
:file:`docker-compose.extensions.yaml.template` which mounts the used extensions. This file need to be renamed and adjusted.

.. index:: Create docker-compose.extensions.yaml
.. code-block:: bash

    cp .ddev/docker-compose.extensions.yaml.template .ddev/docker-compose.extensions.yaml

The file mounts the tea extension into the testing distribution. Make sure to insert the correct paths here.

.. index:: Mount extension into testing distribution
.. code-block:: yaml

    services:
      web:
        volumes:
          - "<PATH/TO/TEA/EXTENSION>/tea:/var/www/html/src/extensions/tea:cached,ro"

Other volumes can safely be deleted.

.. _start-testing-distribution:

Start the testing distribution
==============================

After that you can start the testing distribution using ddev.

.. index:: Start the testing distribution
.. code-block:: bash

    ddev start
    ddev composer install
    ddev install-typo3
    ddev db-import

After that you should be able to access the frontend:

.. code-block:: bash

    ddev launch
