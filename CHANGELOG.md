# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

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
  the suggestions to install LaminasDeveloperTools and LaminasTool, as they are not
  ready for laminas-mvc v3.
- [zfcampus/zf-development-mode#19](https://github.com/zfcampus/zf-development-mode/pull/19) removes
  support for PHP versions less than 5.6.

### Fixed

- Nothing.

## 2.1.2 - 2015-12-21

### Added

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
