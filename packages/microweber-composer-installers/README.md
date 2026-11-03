# A Multi-Framework [Composer](http://getcomposer.org) Library Installer

[![Continuous Integration](https://github.com/composer/installers/workflows/Continuous%20Integration/badge.svg?branch=main)](https://github.com/composer/installers/actions)

This is for PHP package authors to require in their `composer.json`. It will
install their package to the correct location based on the specified package
type.

The goal of Installers is to be a simple package type to install path map.
Users can also customize the install path per package and package authors can
modify the package name upon installing.

Installers isn't intended on replacing all custom installers. If your
package requires special installation handling then by all means, [create a
custom installer to handle it](https://getcomposer.org/doc/articles/custom-installers.md).

**Natively Supported Frameworks**:

Most frameworks these days natively work with Composer and will be
installed to the default `vendor` directory. `composer/installers`
is **not needed** to install packages with these frameworks.

## Alternative to custom installers with Composer 2.1+

As of Composer 2.1, the `Composer\InstalledVersions` class has a
[`getInstalledPackagesByType`](https://getcomposer.org/doc/07-runtime.md#knowing-which-packages-of-a-given-type-are-installed)
method which can let you figure out at runtime which plugins/modules/extensions are installed.

It is highly recommended to use that instead of building new custom
installers if you are building a new application. This has the advantage of leaving
all vendor code in the vendor directory, and not requiring custom installer code.

## Current Supported Package Types

> Stable types are marked as **bold**, this means that installation paths
> for those type will not be changed. Any adjustment for those types would
> require creation of brand new type that will cover required changes.

| Framework    | Types
| ---------    | -----
| Akaunting    | `akaunting-module`
| Asgard       | `asgard-module`<br>`asgard-theme`
| Attogram     | `attogram-module`
| AGL          | `agl-module`
| Bonefish     | `bonefish-package`
| Botble       | `botble-plugin`<br>`botble-theme`
| AnnotateCms  | `annotatecms-module`<br>`annotatecms-component`<br>`annotatecms-service`
| Bitrix       | `bitrix-module` (deprecated) <br>`bitrix-component` (deprecated) <br>`bitrix-theme` (deprecated) <br><br> `bitrix-d7-module` <br> `bitrix-d7-component` <br> `bitrix-d7-template`
| CakePHP 2+   | **`cakephp-plugin`**
| Chef         | `chef-cookbook`<br>`chef-role`
| CiviCrm      | `civicrm-ext`
| CCFramework  | `ccframework-ship`<br>`ccframework-theme`
| Cockpit      | `cockpit-module`
| CodeIgniter  | `codeigniter-library`<br>`codeigniter-third-party`<br>`codeigniter-module`
| concrete5    | `concrete5-core`<br>`concrete5-package`<br>`concrete5-theme`<br>`concrete5-block`<br>`concrete5-update`
| ConcreteCMS  | `concretecms-core`<br>`concretecms-package`<br>`concretecms-theme`<br>`concretecms-block`<br>`concretecms-update`
| Croogo       | `croogo-plugin`<br>`croogo-theme`
| Decibel      | `decibel-app`
| Dframe       | `dframe-module`
| DokuWiki     | `dokuwiki-plugin`<br>`dokuwiki-template`
| Dolibarr     | `dolibarr-module`
| Drupal       | <b>`drupal-core`<br>`drupal-module`<br>`drupal-theme`</b><br>`drupal-library`<br>`drupal-profile`<br>`drupal-database-driver`<br>`drupal-drush`<br>`drupal-custom-theme`<br>`drupal-custom-module`<br>`drupal-custom-profile`<br>`drupal-drupal-multisite`<br>`drupal-console`<br>`drupal-console-language`<br>`drupal-config`<br>`drupal-recipe`
| Elgg         | `elgg-plugin`
| Eliasis      | `eliasis-component`<br>`eliasis-module`<br>`eliasis-plugin`<br>`eliasis-template`
| ExpressionEngine 3         | `ee3-addon`<br>`ee3-theme`
| eZ Platform  | `ezplatform-assets`<br>`ezplatform-meta-assets`
| ForkCMS ^v6.x| `fork-cms-module`<br>`fork-cms-theme`
| FuelPHP v1.x | `fuel-module`<br>`fuel-package`<br/>`fuel-theme`
| FuelPHP v2.x | `fuelphp-component`
| Grav         | `grav-plugin`<br>`grav-theme`
| Hurad        | `hurad-plugin`<br>`hurad-theme`
| ImageCMS     | `imagecms-template`<br>`imagecms-module`<br>`imagecms-library`
| iTop         | `itop-extension`
| Kanboard     | `kanboard-plugin`
| Known        | `known-plugin`<br>`known-theme`<br>`known-console`
| KodiCMS      | `kodicms-plugin`<br>`kodicms-media`
| Kohana       | **`kohana-module`**
| Lan Management System | `lms-plugin`<br>`lms-template`<br>`lms-document-template`<br>`lms-userpanel-module`
| Laravel      | `laravel-library`
| Lavalite     | `lavalite-theme`<br>`lavalite-package`
| Lithium      | **`lithium-library`<br>`lithium-source`**
| Magento      | `magento-library`<br>`magento-skin`<br>`magento-theme`
| majima       | `majima-plugin`
| Mako         | `mako-package`
| MantisBT     | `mantisbt-plugin`
| Matomo       | `matomo-plugin`
| Mautic       | `mautic-core`<br>`mautic-plugin`<br>`mautic-theme`
| Maya         | `maya-module`
| MODX         | `modx-extra`
| MODX Evo     | `modxevo-snippet`<br>`modxevo-plugin`<br>`modxevo-module`<br>`modxevo-template`<br>`modxevo-lib`
| MediaWiki    | `mediawiki-extension`
| Miaoxing     | `miaoxing-plugin`
| October      | **`october-module`<br>`october-plugin`<br>`october-theme`**
| OntoWiki     | `ontowiki-extension`<br>`ontowiki-theme`<br>`ontowiki-translation`
| OXID         | `oxid-module`<br>`oxid-theme`<br>`oxid-out`
| Osclass      | `osclass-plugin`<br>`osclass-theme`<br>`osclass-language`
| MODULEWork   | `modulework-module`
| Moodle       | `moodle-*` (Please [check source](https://github.com/composer/installers/blob/main/src/Composer/Installers/MoodleInstaller.php) for all supported types)
| Pantheon     | `quicksilver-script`<br>`quicksilver-module`
| Piwik        | `piwik-plugin`
| Phifty       | `phifty-bundle`<br>`phifty-framework`<br>`phifty-library`
| phpBB        | `phpbb-extension`<br>`phpbb-style`<br>`phpbb-language`
| Plentymarkets      | `plentymarkets-plugin`
| PPI          | **`ppi-module`**
| Prestashop   | `prestashop-module`<br>`prestashop-theme`
| Puppet       | `puppet-module`
| Porto        | `porto-container`
| ProcessWire  | `processwire-module`
| RadPHP       | `radphp-bundle`
| REDAXO       | `redaxo-addon`
| REDAXO bestyle-plugin | `redaxo-bestyle-plugin`
| REDAXO V5.*  | `redaxo5-addon`
| REDAXO V5.*  bestyle-plugin  | `redaxo5-bestyle-plugin`
| ReIndex      | **`reindex-plugin`** <br> **`reindex-theme`**
| Roundcube    | `roundcube-plugin`
| shopware     | `shopware-backend-plugin`<br/>`shopware-core-plugin`<br/>`shopware-frontend-plugin`<br/>`shopware-theme`<br/>`shopware-plugin`<br/>`shopware-frontend-theme`
| SilverStripe | `silverstripe-module`<br>`silverstripe-theme`
| SiteDirect   | `sitedirect-module`<br>`sitedirect-plugin`
| SMF          | `smf-module`<br>`smf-theme`
| Starbug      | `starbug-module`<br>`starbug-theme`<br>`starbug-custom-module`<br>`starbug-custom-theme`
| SyDES        | `sydes-module`<br>`sydes-theme`
| Sylius       | `sylius-theme`
| TAO          | `tao-extension`
| TastyIgniter | **`tastyigniter-module`<br>`tastyigniter-extension`<br>`tastyigniter-theme`**
| Tusk         | `tusk-task`<br>`tusk-command`<br>`tusk-asset`
| UserFrosting | `userfrosting-sprinkle`
| Vanilla      | `vanilla-plugin`<br>`vanilla-theme`
| Vgmcp        | `vgmcp-bundle`<br>`vgmcp-theme`
| WHMCS        | `whmcs-addons`<br>`whmcs-fraud`<br>`whmcs-gateways`<br>`whmcs-notifications`<br>`whmcs-registrars`<br>`whmcs-reports`<br>`whmcs-security`<br>`whmcs-servers`<br>`whmcs-social`<br>`whmcs-support`<br>`whmcs-templates`<br>`whmcs-includes`
| Winter CMS   | **`winter-module`<br>`winter-plugin`<br>`winter-theme`**
| Wolf CMS     | `wolfcms-plugin`
| WordPress    | <b>`wordpress-plugin`<br>`wordpress-theme`</b><br>`wordpress-muplugin`<br>`wordpress-dropin`
| YAWIK        | `yawik-module`
| Zend         | `zend-library`<br>`zend-extra`<br>`zend-module`
| Zikula       | `zikula-module`<br>`zikula-theme`

## Example `composer.json` File

This is an example for a CakePHP plugin. The only important parts to set in your
composer.json file are `"type": "cakephp-plugin"` which describes what your
package is and `"require": { "composer/installers": "~1.0" }` which tells composer
to load the custom installers.

```json
{
    "name": "you/ftp",
    "type": "cakephp-plugin",
    "require": {
        "composer/installers": "~1.0"
    }
}
```

This would install your package to the `Plugin/Ftp/` folder of a CakePHP app
when a user runs `php composer.phar install`.

So submit your packages to [packagist.org](http://packagist.org)!

## Custom Install Paths

If you are requiring a package which has one of the supported types you can
override the install path with the following extra in your `composer.json`:

```json
{
    "extra": {
        "installer-paths": {
            "your/custom/path/{$name}/": ["shama/ftp", "vendor/package"]
        }
    }
}
```

You can determine a non-standard installation path for all packages of a
particular type with the `type:` prefix. The type must be one of types
listed on the supported list above.

``` json
{
    "extra": {
        "installer-paths": {
            "your/custom/path/{$name}/": ["type:wordpress-plugin"]
        }
    }
}
```

You can also install all packages by a particular vendor to a custom
installation path by using the `vendor:` prefix. The path will still
only apply to packages by the vendor with a type in the supported list.

``` json
{
    "extra": {
        "installer-paths": {
            "your/custom/path/{$name}/": ["vendor:my_organization"]
        }
    }
}
```

These would use your custom path for each of the matching packages. The
available variables to use in your paths are: `{$name}`, `{$vendor}`, `{$type}`.

**Note:** If multiple custom installer-paths match for the same package, the first
one which matches will be used.

## Custom Install Names

If you're a package author and need your package to be named differently when
installed consider using the `installer-name` extra.

For example you have a package named `shama/cakephp-ftp` with the type
`cakephp-plugin`. Installing with `composer/installers` would install to the
path `Plugin/CakephpFtp`. Due to the strict naming conventions, you as a
package author actually need the package to be named and installed to
`Plugin/Ftp`. Using the following config within your **package** `composer.json`
will allow this:

```json
{
    "name": "shama/cakephp-ftp",
    "type": "cakephp-plugin",
    "extra": {
        "installer-name": "Ftp"
    }
}
```

Please note the name entered into `installer-name` will be the final and will
not be inflected.

## Disabling installers

There may be time when you want to disable one or more installers from `composer/installers`.
For example, if you are managing a package or project that uses a framework specific installer that
conflicts with `composer/installers` but also have a dependency on a package that depends on `composer/installers`.

Installers can be disabled for your project by specifying the extra
`installer-disable` property. If set to `true`, `"all"`, or `"*"` all installers
will be disabled.

```json
{
    "extra": {
        "installer-disable": true
    }
}
```

Otherwise a single installer or an array of installers may be specified.

```json
{
    "extra": {
        "installer-disable": [
            "cakephp",
            "drupal"
        ]
    }
}
```

**Note:** Using a global disable value (`true`, `"all"`, or `"*"`) will take precedence over individual
installer names if used in an array. The example below will disable all installers.

```json
{
    "extra": {
        "installer-disable": [
          "drupal",
          "all"
        ]
    }
}
```

## Should we allow dynamic package types or paths? No

What are they? The ability for a package author to determine where a package
will be installed either through setting the path directly in their
`composer.json` or through a dynamic package type: `"type":
"framework-install-here"`.

It has been proposed many times. Even implemented once early on and then
removed. Installers won't do this because it would allow a single package
author to wipe out entire folders without the user's consent. That user would
then come here to yell at us.

Anyone still wanting this capability should consider requiring <https://github.com/oomphinc/composer-installers-extender>.
