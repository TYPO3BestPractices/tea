# Change log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/).

## x.y.z

### Added
- Add support for PostgreSQL 16 (#1271)

### Changed
- Move php-cs-fixer configuration to Build dir (#1353)
- Move php sniff configuration to Build dir (#1357)
- Move xliff configuration to Build xliff dir (#1356)
- Move tests configuration to Build dir (#1352)
- Stop storing development tool PHARs in the repository (#1277)
- !!! Require a storage PID for the tea list (#1223)
- Drop additional namespace segment for the Tea model (#1025)

### Deprecated

### Removed
- Drop DDEV configuration in favor of `runTests.sh` (#1063)
- Stop using the predefined GitHub Actions (#1211)

### Fixed
- Add `resname` to all language labels (#1221)
- Raise the minimal TYPO3 V12 version (#1212)

## 3.1.0

### Added
- Add Composer script for XLIFF linting (#1071)
- Add polish translation for tea (#1020)
- Make records timeable and hideable (#989)
- Add support for PHP 8.3 (#965)
- Add an FE editor (#864, #872, #874, #876)
- Add automerging of green Dependabot PRs (#756)
- Add type coverage calculation (#830)

### Changed
- Rename TsConfig directory to `TSconfig` (#923)
- Set the minimal 12LTS version to 12.1 (#702)

### Removed
- Stop using Prophecy (#676)

### Fixed
- Get the functional tests to work with TYPO3 >= 12.1 (#704)
- Avoid race condition on case-insensitive filesystems (#657)

## 3.0.0

### Added
- Harden the GitHub Actions workflows (#649)
- Add support for TYPO3 12 (#615, #652, #653)
- Add `.gitignore` entry for JetBrains Fleet editor (#642)

### Changed
- Switch the coverage on CI from Xdebug to PCOV (#648)
- Upgrade to `helmich/typo3-typoscript-lint` V3 (#645)
- Upgrade to the testing framework v7 (#629)
- Make the TCA ready for TYPO3 v12 (#625)
- Upgrade to PHPUnit 9 and PHPCOV 8 (#610)
- Convert functional test fixtures to CSV (#601)
- Use typed properties instead of `@var` annotations (#599, #612, #628)
- Return `ResponseInterface` in controller actions (#597)
- Replace switchable controller actions with separate plugins (#575)

### Removed
- Drop support for Symfony 4.4 (#622)
- Drop support for TYPO3 10LTS (#594)
- Drop support for PHP 7.2 and 7.3 (#581)

### Fixed
- Stop injecting QuerySettings (#650)
- Do not check `composer.lock` during `composer normalize` (#641)
- Require TYPO3 >= 11.5.4 (#643)
- Stop relying on transitive dependencies for `psr/http-message` (#613)

## 2.0.1

### Changed
- Use CamelCase for the TsConfig folder (#522)
- Stop using the `typo3/minimal` package on CI (#520, #531)
- Update to Composer 2.4 (#513)
- Change the default indentation for rst files to 4 spaces (#194)

### Removed
- Remove the ancient acceptance tests (#512)

### Fixed
- Have all extension dependencies in the `ext_emconf.php` as well (#515)
- Bump the minimal 10.4 Extbase requirement (#514)
- Explicitly require Prophecy (#511)

## 2.0.0

### Added
- Add the PHPStan strict rules (#471)
- Add a Dependabot action for updating dependencies (#452, #461, #469, #481)
- Use Coveralls for the code coverage (#425)

### Changed
- Move npm tools and config to default locations (#444)
- Use the TYPO3 Code of Conduct (#430)

### Removed
- Drop support for TYPO3 9LTS (#363, #372)

## 1.1.0

### Added
- Also run the unit tests with V11 in the CI pipeline (#336)
- Allow installations on TYPO3 11LTS

### Changed
- Require at least TYPO3 11.5.2 for 11LTS (#335)
- Upgrade to PHPUnit 8.5 (#328)

### Fixed
- Only publish to the TER if the tag is a valid version number (#329)

## 1.0.0

### Added
- Also run the CI build once a week (#160)
- Run the functional tests via GitHub Actions (#55)
- Cache Composer dependencies in build (#31)
- Add a status badge for GitHub actions (#32)
- Composer script for PHP code sniffer fixing (#21)
- Run the functional tests in parallel
- Add PHP-CS-Fixer
- Add support for PHP 7.3 and 7.4
- Add support for TYPO3 9.5 and 10.x (#27)
- Add PHP_CodeSniffer to the Travis CI build
- Auto-release to the TER
- Composer script for PHP linting

### Changed
- Disable running with lower dependencies on GitHub actions (#54)
- Move the project to the TYPO3 Documentation Team (#47)
- Run unit tests with GitHub actions (#37)
- Switch from PSR-2 to PSR-12 (#3, #35)
- Move TypoScript linting to GitHub actions (#14)
- Move PHP code sniffing to GitHub actions (#13)
- Move PHP linting to GitHub actions (#12)
- Use `.typoscript` as file extension for TS files (#19)
- Convert the PHP namespaces to "TTN" (#8)
- Update the contact email in the CoC document (#6)
- Switch to the `TTN` PHP vendor namespace (#1, #5)
- Sort the Composer dependencies
- Upgrade PHPUnit to 7.5.14
- Change from GPL V3+ to GPL V2+
- Streamline `ext_emconf.php`
- Complete rewrite. Usable with TYPO3 7.6 and 8.7.

### Removed
- Drop the Travis CI builds (#56)
- Drop obsolete `dividers2tabs` from the TCA (#44)
- Drop obsolete parts from the README (#34)
- Drop unneeded Travis CI configuration settings
- Drop `ext_icon.svg`
- Stop building with the lowest Composer dependencies
- Drop support for TYPO3 < 9.5
- Drop support for PHP < 7.2
- Drop support for TYPO3 7.6 and require TYPO3 >= 8.7
- Drop the TYPO3 package repository from `composer.json`
- Drop the dependency of `roave/security-advisories`

### Fixed
- Always use the Composer-installed tools (#49)
- Avoid unwanted higher PHP versions (#50)
- Stop caching `vendor/` on Travis CI (#51)
- Use the PHP version from the matrix in the CI (#48)
- Re-add the static TypoScript registration (#41)
- Keep the global namespace clean in `ext_localconf.php` (#40)
- Update the badge URLs in the `README` (#29, #22)
- Fix code inspection warnings
- Use the new annotations for lazy loading
- Update and pin the dev dependencies
- Drop an obsolete "replace" entry from `composer.json`
- Explicitly require MySQL on Travis CI
- Add `.php_cs.cache` to the `.gitignore`
