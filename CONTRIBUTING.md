# How to contribute

## Code of Conduct

This project uses the
[TYPO3 Code of Conduct](https://typo3.org/community/values/code-of-conduct).

When you contribute to this project or interact with community members,
you agree to adhere to this code of conduct.

## Open an issue

Feel free to open an issue - no matter whether you found a bug, would like to
request a feature or have questions.

1. Please check existing open and closed issues first and feel free to comment
   and re-open them.
   Existing issues are available at
   https://github.com/TYPO3BestPractices/tea/issues.
2. Create a new issue at https://github.com/TYPO3BestPractices/tea/issues/new.
3. Use a meaningful title.
4. Provide enough context, a concrete use case and steps to reproduce bugs.

## Contributing via pull requests

1. Create a fork of the repository.
   You need a [GitHub account](https://github.com/join).
2. Create a new branch holding your changes.
3. Apply your changes.
4. Commit your changes following
   the [TYPO3 git commit conventions](https://docs.typo3.org/m/typo3/guide-contributionworkflow/main/en-us/Appendix/CommitMessage.html#commitmessage).
   The only relevant difference is that we do not use the `Releases` or
   `Change-Id` metadata.
5. Push your changes.
6. Open a pull request.

Please also have a look at the documented
[contribution workflow for GitHub](https://docs.github.com/en/get-started/quickstart/contributing-to-projects).

## Keeping pull requests up to date

We consider keeping pull requests up to date to be the responsibility of the
author of the pull request.

So please keep your pull request up to date by **rebasing** it regularly on top
of the `main` branch. (Please don't merge the `main` branch into your PR
branch. Unfortunately, this is the default option in the GitHub UI, and we
have found no way to change this.)

## Working with commits in a pull request

When you create a pull request, please squash additional commits into your
initial commit so that your pull request starts with a single, clean commit
when you submit it for review.

When you receive review comments that ask for changes in your pull request,
please add a single commit to address those comments. This will make it easier
for the reviewers to see what has changed since their review.

(We'll squash the commits when we merge a pull request in order to keep a
clean history.)
