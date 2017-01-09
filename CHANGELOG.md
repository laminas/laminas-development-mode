# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.1.0 - 2017-01-09

### Added

- [#23](https://github.com/zfcampus/zf-development-mode/pull/23) adds support
  for [Expressive](https://docs.zendframework.com/zend-expressive) applications.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 3.0.0 - 2016-06-22

### Added

- [#19](https://github.com/zfcampus/zf-development-mode/pull/19) adds a
  standalone vendor binary, which may be invoked as
  `./vendor/bin/zf-development-mode`.
- [#19](https://github.com/zfcampus/zf-development-mode/pull/19) adds support
  for PHP 7.

### Deprecated

- Nothing.

### Removed

- [#19](https://github.com/zfcampus/zf-development-mode/pull/19) removes
  integration with zend-mvc/zend-console.
- [#19](https://github.com/zfcampus/zf-development-mode/pull/19) removes
  the suggestions to install ZendDeveloperTools and ZFTool, as they are not
  ready for zend-mvc v3.
- [#19](https://github.com/zfcampus/zf-development-mode/pull/19) removes
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

- [#17](https://github.com/zfcampus/zf-development-mode/pull/17) fixes the
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

- [#13](https://github.com/zfcampus/zf-development-mode/pull/13) ensures that
  the application configuration cache file is always removed when switching
  to and from development mode.
