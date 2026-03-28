.. include:: /Includes.rst.txt

.. _command-controller:

==================
Command Controller
==================

The "tea" extension comes with a CommandController that can be used for the
automatic creation of test data. It also serves to illustrate how data can be
created in the database using a command controller.

You must set a page id as argument. Therefore it's necessary to create an
sysfolder before.

You can add option `-d` to delete already existing data.


.. code-block:: bash

   vendor/bin/typo3 tea:create-test-data 3


.. seealso::

   For further details to Console Commands read the
   :ref:`Creating a basic command <t3coreapi:console-command-tutorial-create>`
   tutorial.
