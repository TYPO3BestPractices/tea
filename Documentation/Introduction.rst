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

   This is not a kickstarter extension.

   This extension should not be used to kickstart other extensions.
   Instead, this extension should serve as an example for best practices.

.. _why-is-this-extension-called-tea:

Why is this extension called "tea"?
===================================

This extension aims to cover all fundamental aspects of developing an Extbase
extension. The fictional use case is the management of tea varieties. The
required models, classes, controllers, and other components represent all
typical parts of an Extbase extension. Additional examples demonstrate how to
test these components.

Since well-chosen extension names should reflect their domain, this extension is
named “tea”. While we could have added `_example` to indicate that it is a
sample extension, this would contradict our goal of demonstrating best practices.

This is not related to the `tea package manager <https://tea.xyz/>`__.

.. _target-group:

Target group
============

The target group for this extension is **TYPO3 extension developers**. Typical use
cases include:

- Gaining inspiration by exploring the code
- Cloning the tea extension locally to access a working example of tests, which can then be adapted for your own extension


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
