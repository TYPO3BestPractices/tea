.. include:: /Includes.rst.txt

.. _forContributors:

================
For Contributors
================

Your use case: You might want to contribute to the tea-extensions.

You want to run the code quality checks and automated tests locally (using a
local PHP, Composer, and database) or using runTests.sh.

To kickstart the project, we suggest the usage of the
`TYPO3-testing-distribution <https://github.com/oliverklee/TYPO3-testing-distribution/>`__
by Oliver Klee as development environment. The distribution comes with a frontend,
example data and predefined plugins.

The following commands expect that your SSH key is known by GitHub. If not, you need to use `https://`.

.. index:: Clone TYPO3 Testing Distribution
.. code-block:: bash

   git clone git@github.com:oliverklee/TYPO3-testing-distribution.git

Please checkout the branch for the TYPO3 version you need. e.g. `12.x`.
For more information about the TYPO3-testing-distribution (e.g. backend user credentials), use the documentation:
https://github.com/oliverklee-de/TYPO3-testing-distribution/blob/13.x/README.md (TYPO3 13)
Adjust link to your needed version.



.. index:: Clone Tea Extension
.. code-block:: bash

   git clone git@github.com:TYPO3BestPractices/tea.git

You can organize the folder structure as you wish, but lets say your folder
structure looks like this:

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

The file needs to mount the tea extension into the testing distribution. Keep in mind
that you use the correct paths here.


.. index:: Mount extension into testing distribution
.. code-block:: yaml

    services:
      web:
        volumes:
          - "$HOME/git/tea:/var/www/html/src/extensions/tea:cached,ro"

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
