.. include:: /Includes.rst.txt

.. _dependency-manager:

==================
Dependency manager
==================

To keep things simple, most development tools, for example PHP_CodeSniffer, are
installed by Composer as development requirements and in some cases where
installation via Composer is not possible, we use `PHIVE <https://phar.io/>`__.

PHIVE packages each tool with all its dependencies as
a separate PHAR. This helps avoid dependency hell (which means that you cannot
install or upgrade some tool as the tool's dependencies conflict with the
dependencies on another library). It also allows running versions of tools
that require a PHP version that is higher than the lowest allowed PHP version
for this project.

.. _using-phive-to-install-phpcov:

Using PHIVE to install `phpunit/phpcov`
=======================================

To support php version 7.4 and 8.2 in the `tea` extension, we are using PHIVE
to install `phpunit/phpcov`.
We need `phpunit/phpcov` in version 10 to support php 8.2.
Our minimum php version 7.4 would prevent the installation with composer.

.. note::

   To find more information about install and usage,
   please check out the documentation of `PHIVE <https://phar.io>`__.
