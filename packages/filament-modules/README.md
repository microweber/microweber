# Filament Modules v4.x

[![Latest Version on Packagist](https://img.shields.io/packagist/v/coolsam/modules.svg?style=flat-square)](https://packagist.org/packages/coolsam/modules)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/savannabits/filament-modules/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/savannabits/filament-modules/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/savannabits/filament-modules/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/savannabits/filament-modules/actions?query=workflow%3Afix-php-code-style+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/coolsam/modules.svg?style=flat-square)](https://packagist.org/packages/coolsam/modules)

> **NOTE:** This documentation is for **version 4.x** of the package, which only supports **Laravel 11** and
**nwidart/laravel-modules ^11**. If you are using Laravel 10, please refer to
> the [3.x documentation](https://github.com/savannabits/filament-modules/tree/3.x).

![image](https://github.com/savannabits/filament-modules/assets/5610289/ba191f1d-b5ee-4eb9-9db7-d42a19cc8d38)

This package brings the power of modules to Laravel Filament. It allows you to organize your filament code into fully
autonomous modules that can be easily shared and reused across multiple projects.
With this package, you can turn each of your modules into a fully functional Filament Plugin with its own resources,
pages, widgets, components and more. What's more, you don't even need to register each of these plugins in your main
Filament Panel. All you need to do is register the `ModulesPlugin` in your panel, and it will take care of the rest for
you.

This package is simple a wrapper of [nwidart/laravel-modules](https://docs.laravelmodules.com) package to make it work
with Laravel Filament.

## Features

- A command to prepare your module for Filament
- A command to create a Filament Cluster in your module
- A command to create additional Filament Plugins in your module
- A command to create a new Filament resource in your module
- A command to create a new Filament page in your module
- A command to create a new Filament widget in your module
- Organize your admin panel into Cluster, one for each supported module.

## Requirements

v4.x of this package requires the following dependencies:

- Laravel 11.x or higher
- Filament 3.x or higher
- PHP 8.2 or higher
- nwidart/laravel-modules 11.x

If you are using Laravel 10 please use [version 3.x](https://github.com/savannabits/filament-modules/tree/3.x) instead.

## Installation

You can install the package via composer:

```bash
composer require coolsam/modules:^4
```

This will automatically install `nwidart/laravel-modules: ^11` as well. Make sure you go through
the [documentation](https://laravelmodules.com/docs/v11/introduction) to understand how to use the package and to
configure it properly
before proceeding.

You can publish the config file with:

```bash
php artisan vendor:publish --tag="modules-config"
```

Alternatively, just run the installation command and follow the prompts:

```bash
php artisan module:filament:install
```

### Configuration

After publishing the config file, you can configure the package to your liking. The configuration file is located
at `config/filament-modules.php`.
The following can be adjusted in the configuration file:

- **auto-register-plugins**: If set to true, the package will automatically register all the plugins in your modules.
  Otherwise, you will need to register each plugin manually in your Filament Panel.
- **clusters.enabled**: If set to true, a cluster will be created in each module during the `module:filament:install`
  command and all filament files for that module may reside inside that cluster. Otherwise, filament files will reside
  in Filament/Resources, Filament/Pages, Filament/Widgets, etc.
- **clusters.use-top-navigation**: If set to true, the top navigation will be used to navigate between clusters while
  the actual links will be loaded as a side sub-navigation. In my opinion, this improves UX. Otherwise, the package will
  honor the configuration that you have in your panel.

## Usage

### Register the plugin

The package comes with a `ModulesPlugin` that you can register in your Filament Panel. This plugin will automatically
load all the modules in your application and register them as Filament plugins.
In order to achieve this, you need to register the `ModulesPlugin` in your panel of choice (e.g. Admin Panel) like so:

```php
// e.g. in App\Providers\Filament\AdminPanelProvider.php
 
use Coolsam\Modules\ModulesPlugin;
public function panel(Panel $panel): Panel
{
    return $panel
        ...
        ->plugin(ModulesPlugin::make());
}
```

That's it! now you are ready to start creating some filament code in your module of choice!

### Installing Filament in a module

If you don't have a module already, you can generate one using the `module:make` command like so:

```bash
php artisan module:make MyModule
```

Next, run the `module:filament:install` command to generate the necessary Filament files and directories in your module:

```bash
php artisan module:filament:install MyModule
```

This will guide you interactively on whether you want to organize your code in clusters, and whether you would like to
create a default cluster.
At the end of this installation, you will have the following structure in your module:

- Modules
    - MyModule
        - app
            - Filament
                - Clusters
                    - MyModule
                        - Pages
                        - Resources
                        - Widgets
                    - **MyModule.php**
                - Pages
                - Resources
                - Widgets
                - **MyModulePlugin.php**

As you can see, there are two main files generated: The plugin class and optionally the cluster class. After generation,
you are free to make any modifications to these classes as you may see fit.

The **plugin** will be loaded automatically unless the configuration is set otherwise. As a result, it will also load
all its clusters automatically.

Your module is now ready to be used in your Filament Panel. Use the following commands during development to generate
new resources, pages, widgets and clusters in your module:

### Creating a new resource

```bash
php artisan module:make:filament-resource
```

Follow the interactive prompts to create a new resource in your module.

### Creating a new page

```bash
php artisan module:make:filament-page
```

Follow the interactive prompts to create a new page in your module.

###Creating a new widget

```bash
php artisan module:make:filament-widget
```

Follow the interactive prompts to create a new widget in your module.

### Creating a new cluster

```bash
php artisan module:make:filament-cluster
```

Follow the interactive prompts to create a new cluster in your module.

### Creating a new plugin

```bash
php artisan module:make:filament-plugin
```

Follow the interactive prompts to create a new plugin in your module.

### Create a new Filament Theme

```bash
php artisan module:make:filament-theme
```



### Follow the instructions to protect your resources and pages if your module is not active.

```php
use Coolsam\Modules\Resource;
```
use the above resource class instead of `use Filament/Resources/Resource;` into your resource class file to protect your resources.

```php
use Coolsam\Modules\Page;
```
use the above page class instead of `use Filament/Pages/Page;` into your page class file to protect your pages.

```php
use Coolsam\Modules\TableWidget;
```
use the above page class instead of `use Filament/Pages/TableWidget;` into your widget class file to protect your TableWidget.

```php
use Coolsam\Modules\ChartWidget;
```
use the above page class instead of `use Filament/Pages/ChartWidget;` into your widget class file to protect your ChartWidget.

```php
use Coolsam\Modules\StatsOverviewWidget;
```
use the above page class instead of `use Filament/Pages/StatsOverviewWidget;` into your widget class file to protect your StatsOverviewWidget.

```php
use CanAccessTrait;
```
use the above trait directly into your resource and page class file to protect your resources and pages.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sam Maosa](https://github.com/coolsam726)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
