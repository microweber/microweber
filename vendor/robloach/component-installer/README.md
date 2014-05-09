Component Installer for Composer [![Build Status](https://secure.travis-ci.org/RobLoach/component-installer.png?branch=master)](http://travis-ci.org/RobLoach/component-installer)
================================

Allows installation of Components via [Composer](http://getcomposer.org).

Usage
-----

To install a Component with Composer, add the Component to your *composer.json*
`require` key. The following will install both [jQuery](http://jquery.com) and
[normalize.css](http://necolas.github.io/normalize.css/):

``` json
{
    "require": {
        "components/jquery": "1.9.*",
        "components/normalize.css": "2.*"
    }
}
```

### Using the Component

The easiest approach is to use the Component statically. Just reference the
Components manually using a `script` or `link` tag:

``` html
<script src="components/jquery/jquery.js"></script>
<link href="components/normalize/normalize.css" rel="stylesheet" type="text/css">
```

For complex projects, a [RequireJS](http://requirejs.org) configuration is
available, which allows autoloading scripts only when needed. A *require.css*
file is also compiled, including all Component stylesheets:

``` html
<!DOCTYPE html>
<html>
    <head>
        <link href="components/require.css" rel="stylesheet" type="text/css">
        <script src="components/require.js"></script>
    </head>
    <body>
        <h1>jQuery+RequireJS Component Installer Sample Page</h1>
        <script>
          require(['jquery'], function($) {
            $('body').css('background-color', 'green');
          });
        </script>
    </body>
</html>
```

Configuration
-------------

There are a number of ways to alter how Components are installed and used.

### Installation Directory

It is possible to switch where Components are installed by changing the
`component-dir` option in your root *composer.json*'s `config`. The following
will install jQuery to *public/jquery* rather than *components/jquery*:

``` json
{
    "require": {
        "components/jquery": "*"
    },
    "config": {
        "component-dir": "public"
    }
}
```

Defaults to `components`.

### Base URL

While `component-dir` depicts where the Components will be installed,
`component-baseurl` tells RequireJS the base path that will use when attempting
to load the scripts in the web browser. It is important to make sure the
`component-baseurl` points to the `component-dir` when loaded externally. See
more about [`baseUrl`](http://requirejs.org/docs/api.html#config-baseUrl) in the
RequireJS documentation.

``` json
{
    "require": {
        "components/jquery": "*"
    },
    "config": {
        "component-dir": "public/assets",
        "component-baseurl": "/assets"
    }
}
```

Defaults to `components`.

Creating a Component
--------------------

To set up a Component to be installed with Component Installer, have it
`require` the package *robloach/component-installer* and set the `type` to
*component*:

``` json
{
    "name": "components/bootstrap",
    "type": "component",
    "require": {
        "robloach/component-installer": "*"
    },
    "extra": {
        "component": {
            "scripts": [
                "js/bootstrap.js"
            ],
            "styles": [
                "css/bootstrap.css"
            ],
            "files": [
                "img/*.png",
                "js/bootstrap.min.js",
                "css/bootstrap.min.css"
            ]
        }
    }
}
```

* `scripts` - List of all the JavaScript files that will be concatenated
together and processed when loading the Component.
* `styles` - List of all the CSS files that should be concatenated together
into the final *require.css* file.
* `files` - Any additional file assets that should be copied into the Component
directory.

### Component Name

Components can provide their own Component name. The following will install
jQuery to *components/myownjquery* rather than *components/jquery*:

``` json
{
    "name": "components/jquery",
    "type": "component",
    "extra": {
        "component": {
            "name": "myownjquery"
        }
    }
}
```

Defaults to the package name, without the vendor.

### RequireJS Configuration

Components can alter how [RequireJS](http://requirejs.org) registers and
interacts with them by changing some of the [configuration
options](http://www.requirejs.org/docs/api.html#config):

``` json
{
    "name": "components/backbone",
    "type": "component",
    "require": {
        "components/underscore": "*"
    },
    "extra": {
        "component": {
            "shim": {
                "deps": ["underscore", "jquery"],
                "exports": "Backbone"
            },
            "config": {
                "color": "blue"
            }
        }
    },
    "config": {
        "component": {
            "waitSeconds": 5
        }
    }
}
```

Current available RequireJS options for individual packages include:
* [`shim`](http://www.requirejs.org/docs/api.html#config-shim)
* [`config`](http://www.requirejs.org/docs/api.html#config-moduleconfig)
* Anything that's passed through `config.component` is sent to Require.js

### Packages Without Composer Support

Using [`repositories`](http://getcomposer.org/doc/05-repositories.md#repositories)
in *composer.json* allows use of Component Installer in packages that don't
explicitly provide their own *composer.json*. In the following example, we
define use of [html5shiv](https://github.com/aFarkas/html5shiv):

``` json
{
    "require": {
        "afarkas/html5shiv": "3.6.*"
    },
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "afarkas/html5shiv",
                "type": "component",
                "version": "3.6.2",
                "dist": {
                    "url": "https://github.com/aFarkas/html5shiv/archive/3.6.2.zip",
                    "type": "zip"
                },
                "source": {
                    "url": "https://github.com/aFarkas/html5shiv.git",
                    "type": "git",
                    "reference": "3.6.2"
                },
                "extra": {
                    "component": {
                        "scripts": [
                            "dist/html5shiv.js"
                        ]
                    },
                },
                "require": {
                    "robloach/component-installer": "*"
                }
            }
        }
    ]
}
```

Not Invented Here
-----------------

There are many other amazing projects from which Component Installer was
inspired. It is encouraged to take a look at some of the [other great package
management systems](http://github.com/wilmoore/frontend-packagers):
* [npm](http://npmjs.org)
* [bower](http://twitter.github.com/bower/)
* [component](http://github.com/component/component)
* [Jam](http://jamjs.org)
* [volo](http://volojs.org)
* [Ender](http://ender.jit.su)
* etc

License
-------

Component Installer is licensed under the MIT License - see LICENSE.md for
details.
