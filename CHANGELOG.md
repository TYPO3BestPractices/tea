# Change log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/).

## x.y.z

### Added

### Changed

### Cleanup

### Removed

### Fixed

### Documentation

## 4.0.0

### Added

- Add support for PHP 8.4 (#1536)
- Add support for PHP 8.5 (#1814)
- Add support for PostgreSQL 16 (#1271)
- Add support for installations with Symfony ^7.2 (#1121, #1468, #1537)
- Add PHPStan extension for analyse cognitive load (#1156)
- Add PHPStan extension for disallowed calls (#1159)
- Add Rector (#1031)
- Include composer-unused to find unused packages (#1382, #1390)
- Add PHP mess detector (#1391)
- Add shellcheck to Github pipeline (#1400)
- Add support for PHP 8.4 in runTests.sh (#1413)
- One command to fix them all - run all automated code style fixes (#1409,#1980)
- Add command controller to create test data (#1297)
- Upgrade to PHPUnit 10.x (#1645)
- Add V13.4 to runTests.sh (#1763)
- Allow installations with TYPO3 13 (#2026)
- Add CType Update Wizard (#2010)
- Add backend module to demonstrate testing of them (#2022)

### Changed

- !!! Migrate plugin to CType (#1960)
- Require the latest TYPO3 12LTS security release (#1940)
- Move php-cs-fixer configuration to Build dir (#1353)
- Move php sniff configuration to Build dir (#1357)
- Move xliff configuration to Build xliff dir (#1356)
- Move tests configuration to Build dir (#1352)
- Stop storing development tool PHARs in the repository (#1277)
- !!! Require a storage PID for the tea list (#1223)
- Drop additional namespace segment for the Tea model (#1025)
- Prevent generation of composer.lock file (#1161)
- Raise PHPStan to level 4, 6, 8, 9 (#1254, #1258, #1267, #1269)
- Allow assertInstanceOf checks for PHPStan (#1256)
- Add ci:xliff:lint to ci:static (#1273)
- Activate workspace support for tea model (#1282)
- Migrate from Services.yaml to Services.php (#1286)
- Use PHP 8.3 by default in runTests.sh (#1287)
- Use TYPO3 core-testing images directly (#1293)
- Switch from yarn to npm (#1289)
- Allow installation of typo3/coding-standards 0.8.x (#1374)
- Include composer-unused to find unused packages (#1382)
- Rename npm commands to fit general naming scheme (#1404)
- Rename the CSS lint npm command (#1406)
- Allow running npm based quality checks via runTests.sh (#1396)
- Rename composerInstall* scripts (#1417)
- Update node to version 22 (#1420)
- Add missing commands to the runTest.sh documentation (#1423)
- Run composer-unused as part of ci:static (#1440)
- Add two new ISSUE_TEMPLATES, feature and task (#1446)
- Update the testing framework to 8.2.7 (#1453, #1649)
- Used fixed versions for most development dependencies (#1475)
- Add Composer scripts and a CI job for Rector (#1459)
- Add functional tests for showAction (#1419)
- Add rector/type-perfect PHPStan rules (#1599)
- Test the prepare-release Composer script on CI (#1630)
- Add proper 404 handling for tea detail action (#1643)
- Switch to using runTests.sh for PHP linting in GitHub Actions (#1672)
- Simplify the unit/functional tests CI matrix (#1686)
- Configure test suites for unit and functional tests (#1690)
- Migrate controller tests to functional tests (#1729, #1734, #1738, #1739,
  #1778, #1779)
- Also run PSR4-verify on ci:static (#1769)
- Add functional tests to update properties of teas (#1794)
- Unblock argument passing in Build/Scripts/runTests.sh (#1689)
- Use PHP 8.4 as default in runTests.sh (#1808)
- Add Flexforms configuration for the FE editor (#1893)
- Migrate composer normalize command to runTests.sh (#1901)
- Add new job check-fixers (#1978)
- Use TYPO3 13.4 as default version (#2042)

### Cleanup

- Drop obsolete options from ext_emconf.php (#1298)
- Import classes instead of FQCN (#1299)
- Clean up the unit tests configuration (#1389)
- Remove unavailable PHPUnit configuration option (#1395)
- Remove redundancies in the prepare-release Composer script (#1458)
- Do not quote workflow/job names for GitHub actions (#1671)
- Remove no longer needed options from runTest.sh (#1842)
- Make Rector calls explicit (#1967)

### Removed

- Drop DDEV configuration in favor of `runTests.sh` (#1063)
- Stop using the predefined GitHub Actions (#1211)
- Add more development documentation (#1399)
- Remove none existing cglFix (#1407)
- Remove undocumented composerInstall (#1416)
- Drop .phpstorm.meta.php (#1448)
- Drop obsolete entries from .gitattributes (#1632)
- Remove unused .prettierrc.js file from project root (#1795)
- Drop the setup for GitLab CI (#1869)
- Remove usage of Alpine image in runTests.sh (#1994)
- Remove leading slashes from global functions (#2013)
- Add rector to runTests.sh (#2015)

### Fixed

- Add `resname` to all language labels (#1221)
- Raise the minimal TYPO3 V12 version (#1212)
- Use our own PHPUnit configuration files in runTests.sh (#1134)
- Avoid warnings in PHP 8.2/8.3 with lowest dependencies (#1208)
- Display dbms version for functional test execution (#1276)

### Documentation

- Switch the documentation to PHP-based rendering (#1190)
- Document why EXT:tea uses a Services.php, while YAML is fine as well (#1286)
- Document philosophy of alignment with TYPO3 CMS Core (#1284)
- Document that we follow semantic versioning (#1288)
- Add feedback section to readme (#1292)
- Document to use WSL for tests on Windows (#1295)
- Document our approach to assertions and type checks (#1339)
- Add missing switch, hint to run composer install at beginning (#1377)
- Rework the testing framework section in the docs (#1480)
- Rework how we solicate feedback (#1501)
- Improve docs for run tests (#1588)
- Document in runTest.sh that Podman now is the default (#1714)
- Remove code coverage commands from docs (#1732)
- Improve documentation on how to run tests (#1843)
- Update repository URLs in the README to the new location (#1870)
- Document our policy for pull requests and commits (#1928)
- Update URLs of the moved team page (#1931)
- Add more tests for FrontEndEditorController (#1854)
- Add new runTests.sh commands to the build-in help (#1986)
- Improve documentation with findings from onsite sprint (#2008)

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
