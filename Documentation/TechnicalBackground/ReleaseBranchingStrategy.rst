.. include:: /Includes.rst.txt

.. _release-branching-strategy:

==============================
Release and Branching Strategy
==============================

When maintaining TYPO3 extensions, developers often need to support multiple 
TYPO3 Long-Term Support (LTS) versions simultaneously. This typically requires 
a clear release and branching strategy to manage development and maintenance 
across different TYPO3 versions.

There are two common strategies for managing branches when supporting multiple 
TYPO3 LTS versions.

.. contents:: Table of Contents:
   :backlinks: top
   :class: compact-list
   :depth: 2
   :local:

.. _release-branching-strategy-one-branch:

Approach 1: One branch for multiple TYPO3 LTS versions
======================================================

In this approach, there is a single main branch that receives new features and 
updates, while supporting multiple TYPO3 LTS versions at the same time.

The downside of this approach is that it may require some version-dependent 
code switches, which can increase complexity. However, the major advantage is 
that there is only one branch to maintain, making it easier to implement new 
features and code changes across all supported TYPO3 versions.

This approach simplifies the maintenance of the extension and is preferred when 
minimizing maintenance overhead is the primary concern.

.. _release-branching-strategy-multiple-branches:

Approach 2: Separate branch per TYPO3 LTS version
=================================================

In this approach, there is one main branch for each TYPO3 LTS version. This 
means that each branch exclusively supports a single TYPO3 LTS version.

The advantage here is that version-specific code can be used without requiring 
version-dependent switches, reducing complexity in the codebase. However, this 
approach increases the maintenance burden, as any new features or updates must 
be applied to each branch individually.

This approach may be necessary when there are significant differences between 
TYPO3 LTS versions or when you want to avoid version-dependent code.

.. _release-strategy-conclusion:

Conclusion
==========

The appropriate release and branching strategy depends on the specific 
requirements of the extension and the TYPO3 versions you are supporting. If 
reducing maintenance complexity is a priority, using a single branch for 
multiple versions is often the better choice. However, if you need to tailor 
your extension for each version, a separate branch for each version may be more 
suitable.
