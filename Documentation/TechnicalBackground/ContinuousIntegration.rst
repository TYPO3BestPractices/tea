.. include:: /Includes.rst.txt

.. _continuous-integration:

======================
Continuous integration
======================

As an example, this extension provides several ways to perform the code quality
checks and tests in a CI pipeline. You can copy the
appropriate configuration depending on which Git hosting service and CI
platform you want to use.

.. contents:: Table of Contents:
   :backlinks: top
   :class: compact-list
   :depth: 2
   :local:

.. _github-actions:

GitHub Actions
==============

This extension has a code-checking workflow for
`GitHub Actions <https://github.com/TYPO3BestPractices/tea/actions>`__ using
`local tools <https://github.com/TYPO3BestPractices/tea/blob/main/.github/workflows/ci.yml>`__:
This workflow uses the development tools installed via Composer and PHIVE and
calls them using the provided Composer scripts. This allows running the code
quality checks locally as well as in GitHub Actions.
