.. include:: /Includes.rst.txt

.. _php-version-support:

===================================
Our approach to PHP version support
===================================

1.  For the TYPO3 versions we currently support, this extension will support all
    PHP versions officially supported by those TYPO3 versions.
2.  In addition, we are doing our best to support newer PHP versions as soon as
    they are available (even release candidates, alphas and betas).
3.  Before we mark a PHP version as supported via :file:`composer.json` and
    :file:`ext_emconf.php`, we ensure that all tests pass on that PHP
    version. (That also means that if you want to install the extension on that
    PHP version nonetheless, you will need to tweak Composer's platform settings
    to bypass this.)
