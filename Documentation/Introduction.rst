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

This extension tries to cover all fundamental aspects of the development of an extbase-extension.
The fictional use case here is the management of tea varieties.
The needed models, classes, controllers, etc. cover all typical parts of an extbase-extension.
Further examples show how to test all these resources.

Since good extension names cover the domain of their purpose, this extension is named "tea".
We could also have added `_example` to the name in order to state that this extension is an example.
But that would already break with our goal to provide a best practice.

This is not related to the `tea package manager <https://tea.xyz/>`__.

.. _target-group:

Target group
============

The target group of this extension are TYPO3-extension developers. Typical usecases are:

- getting inspired by looking into the code
- installing the tea-extension alongside another extension that gets developed and have a running test environment available.

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
