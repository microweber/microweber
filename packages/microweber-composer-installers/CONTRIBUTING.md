# Contributing

If you would like to help, please take a look at the list of
[issues](https://github.com/composer/installers/issues).

## Pull requests

* [Fork and clone](https://help.github.com/articles/fork-a-repo).
* Run the command `php composer.phar install` to install dependencies.
* Use the command `phpunit` to run the tests.
* Create a branch, commit, push and send us a
  [pull request](https://help.github.com/articles/using-pull-requests).

To ensure a consistent code base, you should make sure the code follows the
coding standards [PSR-1](http://www.php-fig.org/psr/psr-1/) and 
[PSR-2](http://www.php-fig.org/psr/psr-2/).

## Creating a new Installer

* Create a class with your Installer that extends `Composer\Installers\BaseInstaller`.
* Add your Installer to `Composer\Installers\Installer::$supportedTypes`.
* Create unit tests as a separate class or as part of `Composer\Installers\Test\InstallerTest`.
* Add information about your Installer to `README.md` in section "Current Supported Package Types".
* Run the tests.
