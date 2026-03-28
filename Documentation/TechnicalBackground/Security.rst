.. include:: /Includes.rst.txt

.. _security:

========
Security
========

Libraries and extensions do not need the security check as they should not have
any restrictions concerning the other libraries they are installed alongside
with (unless those would create breakage). Libraries and extension also should
not have a version-controlled `composer.lock` (which usually is used for
security checks).

Instead, the projects and distributions (i.e., for TYPO3 installations) need to
have the security checks.
