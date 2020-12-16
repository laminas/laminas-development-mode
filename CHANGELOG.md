# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.3.0 - 2020-12-16

### Added

- [#9](https://github.com/laminas/laminas-development-mode/pull/9) adds support for PHP 8.0.

### Removed

- [#9](https://github.com/laminas/laminas-development-mode/pull/9) removes support for PHP verions prior to 7.3.


-----

### Release Notes for [3.3.0](https://github.com/laminas/laminas-development-mode/milestone/2)



### 3.3.0

- Total issues resolved: **1**
- Total pull requests resolved: **1**
- Total contributors: **2**

#### Enhancement

 - [9: Add PHP 8.0 Support](https://github.com/laminas/laminas-development-mode/pull/9) thanks to @stringerbell and @boesing

## 3.2.1 - 2020-08-03

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#7](https://github.com/laminas/laminas-development-mode/pull/7) fixes autoloader discovery when used on MacOS.

## 3.2.0 - 2018-05-07

### Added

- [zfcampus/zf-development-mode#35](https://github.com/zfcampus/zf-development-mode/pull/35) adds support for PHP 7.2.

- [zfcampus/zf-development-mode#32](https://github.com/zfcampus/zf-development-mode/pull/32) adds a new sub-command, `auto-composer`. When invoked, it uses the value of
  the environment variable COMPOSER_DEV_MODE to determine whether to enable or disable development
  mode locally. If the variable is not present, it does nothing; if `0`, it disables development
  mode, and if `1`, it enables development mode. This can be particularly useful as a composer script:

  ```json
  "scripts": {
    "development-auto": "laminas-development-mode auto-composer",
    "post-install-cmd": ["@development-auto"],
    "post-update-cmd": ["@development-auto"]
  }
  ```

### Changed

- [zfcampus/zf-development-mode#29](https://github.com/zfcampus/zf-development-mode/pull/29) modifies how the `enable` subcommand copies development config files into
  the filesystem. On operating systems that are known to support `symlink()` predictably,
  the command will now create symlinks instead of copies. These include most Linux, BSD,
  and MacOS variants.

### Deprecated

- Nothing.

### Removed

- [zfcampus/zf-development-mode#35](https://github.com/zfcampus/zf-development-mode/pull/35) removes support for HHVM.

### Fixed

- Nothing.

## 3.1.0 - 2017-01-09

### Added

- [zfcampus/zf-development-mode#23](https://github.com/zfcampus/zf-development-mode/pull/23) adds support
  for [Mezzio](https://docs.mezzio.dev/mezzio) applications.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 3.0.0 - 2016-06-22

### Added

- [zfcampus/zf-development-mode#19](https://github.com/zfcampus/zf-development-mode/pull/19) adds a
  standalone vendor binary, which may be invoked as
  `./vendor/bin/laminas-development-mode`.
- [zfcampus/zf-development-mode#19](https://github.com/zfcampus/zf-development-mode/pull/19) adds support
  for PHP 7.

### Deprecated

- Nothing.

### Removed

- [zfcampus/zf-development-mode#19](https://github.com/zfcampus/zf-development-mode/pull/19) removes
  integration with laminas-mvc/laminas-console.
- [zfcampus/zf-development-mode#19](https://github.com/zfcampus/zf-development-mode/pull/19) removes
  the suggestions to install LaminasDeveloperTools and ZFTool, as they are not
  ready for laminas-mvc v3.
- [zfcampus/zf-development-mode#19](https://github.com/zfcampus/zf-development-mode/pull/19) removes
  support for PHP versions less than 5.6.

### Fixed

- Nothing.

## 2.1.2 - 2015-12-21

### Added

- [zfcampus/zf-development-mode#35](https://github.com/zfcampus/zf-development-mode/pull/35) adds support for PHP 7.2.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zfcampus/zf-development-mode#17](https://github.com/zfcampus/zf-development-mode/pull/17) fixes the
  `DevelopmentModeControllerFactory` to check for configuration caching settings
  under the `module_listener_options` top-level key (instead of the settings
  root).

## 2.1.1 - 2015-08-31

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [zfcampus/zf-development-mode#13](https://github.com/zfcampus/zf-development-mode/pull/13) ensures that
  the application configuration cache file is always removed when switching
  to and from development mode.
