# Change Log

## v2.3.0 - 2024-06-24

### Added
* Added recipes for Drupal 10.3+ to DrupalInstaller #534
* Added BotbleInstaller #533
* Added new Moodle types (communication, forumreport, h5plib, mlbackend, qbank, tiny) #535
* Added support for Fork CMS ^6 #529
* Added ConcreteCMS installers and types mimicking concrete5 ones #522

## v2.2.0 - 2022-08-20
### Added
* Added matomo-plugin by @codemorgan (#514)

## v2.1.1 - 2022-04-13
### Fixed
* Fixed TastyIgniter parsing of extra data to use the package being installed instead of the root package (#507)

## v2.1.0 - 2022-03-18
### Added
* Added new types (antivirus, customfield, contenttype, dataformat, media, paygw) for Moodle (#497)
* Added new `tastyigniter-module` type to TastyIgniter (#503)
* Added new `extra.plugin-modifies-install-path` to composer/installers for Composer 2.3 support, see [docs](https://getcomposer.org/doc/articles/plugins.md#plugin-modifies-install-path) if you are writing your own custom installer plugin.

## v2.0.1 - 2021-09-13
### Fixed
* Fixed installer for Pantheon Quicksilver modules/scripts [#494](https://github.com/composer/installers/pull/494).

## v2.0.0 - 2021-08-04
### Added
* Added installer for Pantheon Quicksilver modules/scripts [#492](https://github.com/composer/installers/pull/492).
* Added installer for Akaunting modules [#489](https://github.com/composer/installers/pull/489).

### Removed
* Removed support for TYPO3 Flow

## v2.0.0-alpha1 - 2021-07-23
### Added
* Type declarations have been added everywhere to support PHPStan level 8

### Changed
* Minimum supported PHP version is now 7.2
* Installer::getInstallPath() now returns absolute paths instead of relative ones previously (#307)

### Removed
* Removed support for Kirby
* Removed support for Symfony1
* Removed support for Joomla
* Removed support for TYPO3 CMS
* Removed support for Craft
* Removed support for Pimcore

## v1.12.0 - 2021-09-13
### Added
* Added installer for Pantheon Quicksilver modules/scripts #495.

## v1.11.0 - 2021-04-28
### Added
* Added installer for TastyIgniter extensions and themes [#478](https://github.com/composer/installers/pull/478).
* Added installers for Winter CMS [#480](https://github.com/composer/installers/pull/480), [#481](https://github.com/composer/installers/pull/481).
* Added installer for Miaoxing plugin [#482](https://github.com/composer/installers/pull/482).

## Changed
* Now using fully qualified theme names in the October CMS [#486](https://github.com/composer/installers/pull/486).
* Updated MauticInstaller to allow plugins & themes [#484](https://github.com/composer/installers/pull/484).

## v1.10.0 - 2021-01-14
### Added
* Added handling for `LibraryInstaller` returning `PromiseInterface`.
* Added Moodle document converter, [#464](https://github.com/composer/installers/pull/464).
* Added installer for Starbug packages, [#475](https://github.com/composer/installers/pull/475).
* Added installer for Processwire module, [#472](https://github.com/composer/installers/pull/472).

### Fixed
* Fixed TAO Installer for proper work, [#437](https://github.com/composer/installers/pull/437).

## v1.9.0 - 2020-04-07
## Added
* Added support for Composer 2.0.
* Added drupal-database-driver type to Drupal Framework, [#452](https://github.com/composer/installers/pull/452).
* Added support for declaring installer-paths values as strings [#449](https://github.com/composer/installers/pull/449).

## v1.8.0 - 2020-02-07
## Added
* Added support for MantisBT plugins, [#442](https://github.com/composer/installers/pull/442).
* Added drupal-config type to Drupal Framework, [#440](https://github.com/composer/installers/pull/440).
* Added support for Sylius themes, [#445](https://github.com/composer/installers/pull/445).

## v1.7.0 - 2019-08-12
## Added
* Added support for Redaxo v5, [#410](https://github.com/composer/installers/pull/410).
* Added TAO extensions installer, [#424](https://github.com/composer/installers/pull/424).
* Added Know installer, [#425](https://github.com/composer/installers/pull/425).
* Added support for Drupal Console custom packages and languages, [#311](https://github.com/composer/installers/pull/311).
* Added Drupal custom profile installation path, [#416](https://github.com/composer/installers/pull/416).
* Add support drupal-site type, [#417](https://github.com/composer/installers/pull/417).
* Added `customcertelement` for Moodle, [#408](https://github.com/composer/installers/pull/408).
* Added Dframe installer, [#404](https://github.com/composer/installers/pull/404).
* Added WHMCS installer, [#401](https://github.com/composer/installers/pull/401).

## Fixed
* Get `target-dir` from package attributes, [#432](https://github.com/composer/installers/pull/432).

## Deprecated
* Deprecated Pimcore installer, [#400](https://github.com/composer/installers/pull/400).

## v1.6.0 - 2018-09-12
## Added
* Added ability to disable all or certain installers, [#376](https://github.com/composer/installers/pull/376).
* Added MediaWiki Core, [#391](https://github.com/composer/installers/pull/391).
* Added CiviCrm installer, [#385](https://github.com/composer/installers/pull/385).

## Fixed
* Normalise vendor directory containing hyphen, [#397](https://github.com/composer/installers/pull/397).

## v1.5.0 - 2017-12-29
### Added
* Added WordPress dropin support.
* Added new types supported for Eliasis.
* Added support for Phoenix CMS.
* Added MODX installer.
* Added Majima instaler.
* Added SiteDirect installer.
* Added support optional prefix in OctoberCMS installers.
* Added PHP 7.2 support.

### Changed
* Changed remove packages, see [#348](https://github.com/composer/installers/pull/348).

### Fixed
* Fixed code style, removed unused imports.

## v1.4.0 - 2017-08-09
### Added
* Installer for eZ Platform.
* Installer for UserFrosting.
* Installer for Osclass.
* Installer for Lan Management System.

### Changed
* Added vendor name to package path for Lavalite.

## v1.3.0 - 2017-04-24
### Added
* Kanboard plugins installer.
* Porto-SAP installer.
* Add `core` to concrete5 installer.
* Support Moodle "search" plugin type.
* SyDES installer.
* iTop installer.
* Lavalite installer.
* Module type for Eliasis.
* Vgmcp installer.
* OntoWiki installer.
* The requirements for contributing (CONTRIBUTING.md).

## v1.2.0 - 2016-08-13
### Added
* Installer for Attogram.
* Installer for Cockpit.
* Installer for Plentymarkets.
* Installer for ReIndex.
* Installer for Vanilla.
* Installer for YAWIK.
* Added missing environments for new Shopware (5.2) Plugin System.

## v1.1.0 - 2016-07-05
### Added
* Installer for ReIndex.
* Installer for RadPHP.
* Installer for Decibel.
* Installer for Phifty.
* Installer for ExpressionEngine.

### Changed
* New paths for new Bitrix CMS. Old paths is deprecated.

### Deprecated
* Old paths in Bitrix CMS Installer is deprecated.

## v1.0.25 - 2016-04-13
### Removed
* Revert TYPO3 installer deletion.

## v1.0.24 - 2016-04-05
### Added
* Installer for ImageCMS.
* Installer for Mautic.
* New types in the Kirby installer: `kirby-plugin` and `kirby-field`.
* New types in the Drupal installer: `custom-theme` and `custom-module`.

### Changed
* Switch to PSR-4.
* Update Bitrix Installer: configuration for setting custom path to directory with kernel.

### Removed
* Remove TYPO3 Extension installers.
