# ryssbowh/craft-activity Changelog

## 1.2.4 - 2022-11-23

### Fixed
- Fixed issues with user permission changes not calculated properly [#4](https://github.com/ryssbowh/craft-activity/issues/4)

## 1.2.3 - 2022-11-17

### Fixed
- Fixed issue with TableDefaultValues handler [#1](https://github.com/ryssbowh/craft-activity/issues/1)

## 1.2.2 - 2022-11-16

### Changed
- Updated documentation urls

## 1.2.1 - 2022-11-07

### Fixed
- Fixed issue with Options handler [#1](https://github.com/ryssbowh/craft-activity/issues/1)

## 1.2.0 - 2022-11-04

### Changed
- Signature for `ConfigModelRecorder::getTrackedFieldNames(): array` is now `getTrackedFieldNames(array $config): array` so we can track different fields based on the config being saved
- Signature for `ConfigModelRecorder::getTrackedFieldTypings(): array` is now `getTrackedFieldTypings(array $config): array` so we can have different typings based on the config being saved
- Renamed `ProjectConfigRecorder::onChanged()` to `ProjectConfigRecorder::onConfigChanged()`

### Added
- Individual fields config for all Craft native fields is now tracked
- Individual fields config for [Redactor](https://plugins.craftcms.com/redactor), [Super table](https://plugins.craftcms.com/super-table), [SEO](https://plugins.craftcms.com/seo) and [Typed Link](https://plugins.craftcms.com/typedlinkfield)
- Added event `Ryssbowh\Activity\services\Fields::EVENT_REGISTER_TRACKED_FIELDS`
- Added event `Ryssbowh\Activity\services\Fields::EVENT_REGISTER_FIELD_TYPINGS`
- Added event `Ryssbowh\Activity\services\Fields::EVENT_REGISTER_FIELD_LABELS`
- Added event `Ryssbowh\Activity\base\fieldHandlers\FieldHandler::EVENT_REGISTER_TARGETS`
- Matrix blocks definitions is now tracked through 3 new log types
- Super table blocks definitions is now tracked through 3 new log types

### Fixed
- Dashboard toolbar centering
- Issue with Twig field plugin [#4](https://github.com/ryssbowh/craft-activity/issues/3)

## 1.1.2 - 2022-10-27

### Fixed
- Issue with php 7.4 typing [#2](https://github.com/ryssbowh/craft-activity/issues/2)

## 1.1.1 - 2022-10-27

### Fixed
- Issue with php 7.4 typing [#2](https://github.com/ryssbowh/craft-activity/issues/2)

## 1.1.0 - 2022-10-26

### Changed
- Changed field handler `Redactor` to `LongText`

### Added
- [TinyMCE](https://plugins.craftcms.com/tinymce) field support

### Fixed
- Fixed redactor changed field throwing error when used inside a Matrix field. [#1](https://github.com/ryssbowh/craft-activity/issues/1)

## 1.0.2 - 2022-10-21

### Added
- Logged in and logged out logs

## 1.0.1 - 2022-10-16

### Fixed
- Fixed issue in permissions handler

## 1.0.0 - 2022-10-16

### Changed
- Reinstated field typings

### Fixed
- User group permissions logs

### Added
- User permissions log

## 0.1.2 - 2022-10-12

### Fixed
- Fixed matrix field description

## 0.1.1 - 2022-10-11

### Fixed
- Fixed changelog

## 0.1.0 - 2022-10-11

### Added
- First version