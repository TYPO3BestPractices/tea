.. include:: /Includes.rst.txt

.. _introduction:

============
Introduction
============

.. _what-it-does:

What does it do?
================

This TYPO3 extension, based on Extbase and Fluid, is an example of best
practices in building an extension and securing its quality by automated code
checks, unit/functional/acceptance testing and continuous integration (CI).

.. note::

   **Never run this extension in production!**

   This extension has educational and documentational purpose only. It is never
   meant to run in production.

   This is also not a kickstarter extension.
   It should not be used to kickstart other extensions.
   Instead, it should serve as an example for best practices.


.. _why-is-this-extension-called-tea:

Why is this extension called "tea"?
===================================

This extension aims to cover all fundamental aspects of developing an Extbase
extension. The fictional use case is the management of tea varieties. The
required models, classes, controllers and other components represent all
typical parts of an Extbase extension. Additional examples demonstrate how to
test these components.

Since well-chosen extension names should reflect their domain, this extension is
named “tea”. While we could have added `_example` to indicate that it is a
sample extension, this would contradict our goal of demonstrating best practices.

This is not related to the `tea package manager <https://tea.xyz/>`__.

.. _target-group:

Target group
============

The target group for this extension is **TYPO3 extension developers**.

Typical use cases include:

- Gaining inspiration by exploring the code :ref:`("see the code") <ForCodeExplorers>`
- Installing the tea-extension without tests to have a simple but comprehensive example at hand ("install the tea extension")
- Using the tests provided by tea extension to improve the code of your own extension :ref:`("use the tests") <ForLiveExplorers>`
- Contributing to the collection of best practices by improving the tea-extension :ref:`("contribute to tea-extension") <ForContributors>`


.. _presentation-online-days-2021:

Presentation at the TYPO3 Online Days 2021
==========================================

At the TYPO3 Online Days 2021, `Oliver Klee <https://www.oliverklee.de/>`__
held a session presenting our approach to automate extension code quality.
Have a look at the slides and the video of the presentation!

.. container:: row m-0 p-0

   .. container:: col-md-6 pl-0 pr-3 py-3 m-0

      .. container:: card px-0 h-100

         .. rst-class:: card-header h3

            .. rubric:: Slides

         .. container:: card-body

            .. image:: /Images/SlidesCover.jpg
               :target: https://speakerdeck.com/oliverklee/automating-the-code-quality-of-your-extensions

   .. container:: col-md-6 pl-0 pr-3 py-3 m-0

      .. container:: card px-0 h-100

         .. rst-class:: card-header h3

            .. rubric:: Video

         .. container:: card-body

            .. image:: /Images/VideoCover.jpg
               :target: https://youtu.be/_oe8ku2GM84?t=6983
