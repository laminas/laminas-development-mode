# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

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
