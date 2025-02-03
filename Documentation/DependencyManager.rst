.. include:: /Includes.rst.txt

.. _dependency-manager:

==================
Dependency manager
==================

To keep things simple, most development tools, for example php-cs-fixer, are
installed by Composer as development requirements and in some cases where
installation via Composer is not possible, we use `PHIVE <https://phar.io/>`__.

PHIVE packages each tool with all its dependencies as
a separate PHAR. This helps avoid dependency hell (which means that you cannot
install or upgrade some tool as the tool's dependencies conflict with the
dependencies on another library). It also allows running versions of tools
that require a PHP version that is higher than the lowest allowed PHP version
for this project.
