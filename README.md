# laminas-development-mode

[![Build Status](https://github.com/laminas/laminas-development-mode/workflows/continuous-integration.yml/badge.svg)](https://github.com/laminas/laminas-development-mode/actions/workflows/continuous-integration.yml")
[![Total Downloads](https://poser.pugx.org/laminas/laminas-development-mode/downloads)](https://packagist.org/packages/laminas/laminas-development-mode)

This package provides a script to allow you to enable and disable development
mode for [laminas-mvc](https://docs.laminas.dev/laminas-mvc) (both versions 2
and 3) and [Mezzio](https://docs.mezzio.dev/mezzio)
applications. The script allows you to specify configuration and modules that
should only be enabled when in development, and not when in production.

## Note to v2 users

If you were using a v2 version of this package previously, invocation has
changed. Previously, you would invoke it via the MVC CLI bootstrap:

```bash
$ php public/index.php development enable  # enable development mode
$ php public/index.php development disable # disable development mode
```

v3 releases now install this as a vendor binary, with no dependencies on other
components:

```bash
$ ./vendor/bin/laminas-development-mode enable  # enable development mode
$ ./vendor/bin/laminas-development-mode disable # disable development mode
```

## Installation

Install this package using Composer:

```bash
$ composer require laminas/laminas-development-mode
```

Once installed, you will need to copy a base development configuration into your
application; this configuration will allow you to override modules and bootstrap
configuration:

```bash
$ cp vendor/laminas/laminas-development-mode/development.config.php.dist config/
```

Optionally, if you want to also have development-specific application
configuration, you can copy another base configuration into your configuration
autoload directory:

```bash
$ cp vendor/laminas/laminas-development-mode/development.local.php.dist config/autoload/
```

In order for the bootstrap development configuration to run, you may need to
update your application bootstrap. Look for the following lines (or similar) in
`public/index.php`:

```php
// Run the application!
Laminas\Mvc\Application::init(require 'config/application.config.php')->run();
```

Replace the above with the following:

```php
// Config
$appConfig = include 'config/application.config.php';
if (file_exists('config/development.config.php')) {
    $appConfig = Laminas\Stdlib\ArrayUtils::merge($appConfig, include 'config/development.config.php');
}

// Run the application!
Laminas\Mvc\Application::init($appConfig)->run();
```

## To enable development mode

```bash
$ cd path/to/project
$ ./vendor/bin/laminas-development-mode enable
```

Note: enabling development mode will also clear your module configuation cache,
to allow safely updating dependencies and ensuring any new configuration is
picked up by your application.

# To disable development mode

```bash
$ cd path/to/project
$ ./vendor/bin/laminas-development-mode disable
```

**Note:** Don't run development mode on your production server!
