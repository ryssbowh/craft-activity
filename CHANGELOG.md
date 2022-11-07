# ryssbowh/craft-activity Changelog

## 2.2.2 - 2022-11-07

### Fixed
- Fixed issue with Options handler [#1](https://github.com/ryssbowh/craft-activity/issues/1)

## 2.2.1 - 2022-11-04

### Fixed
- Fixed user field settings labels
- Fixed untracked matrix field setting `propagationMethod`

## 2.2.0 - 2022-11-04

### Removed
- Removed settings related to no changes as Project Config is already doing this

### Changed
- Signature for `ConfigModelRecorder::getTrackedFieldNames(): array` is now `getTrackedFieldNames(array $config): array` so we can track different fields based on the config being saved
- Signature for `ConfigModelRecorder::getTrackedFieldTypings(): array` is now `getTrackedFieldTypings(array $config): array` so we can have different typings based on the config being saved
- Renamed `ProjectConfigRecorder::onChanged()` to `ProjectConfigRecorder::onConfigChanged()`

### Added
- Individual fields config for all Craft native fields is now tracked
- Individual fields config for [Redactor](https://plugins.craftcms.com/redactor), [Super table](https://plugins.craftcms.com/super-table), [SEO](https://plugins.craftcms.com/seo), [Typed Link](https://plugins.craftcms.com/typedlinkfield) and [TinyMCE](https://plugins.craftcms.com/tinymce) fields is now tracked
- Added event `Ryssbowh\Activity\services\Fields::EVENT_REGISTER_TRACKED_FIELDS`
- Added event `Ryssbowh\Activity\services\Fields::EVENT_REGISTER_FIELD_TYPINGS`
- Added event `Ryssbowh\Activity\services\Fields::EVENT_REGISTER_FIELD_LABELS`
- Added event `Ryssbowh\Activity\base\fieldHandlers\FieldHandler::EVENT_REGISTER_TARGETS`
- Matrix blocks definitions is now tracked through 3 new log types
- Super table blocks definitions is now tracked through 3 new log types

### Fixed
- Dashboard toolbar centering
- Issue with Twig field plugin [#4](https://github.com/ryssbowh/craft-activity/issues/3)

## 2.1.1 - 2022-10-27

### Fixed
- Filters not working [#1](https://github.com/ryssbowh/craft-activity/issues/1)
- Issue with php 7.4 [#2](https://github.com/ryssbowh/craft-activity/issues/2)

## 2.1.0 - 2022-10-26

### Changed
- Changed field handler `Redactor` to `LongText`

### Added
- [TinyMCE](https://plugins.craftcms.com/tinymce) field support

### Fixed
- Fixed redactor changed field throwing error when used inside a Matrix field. [#1](https://github.com/ryssbowh/craft-activity/issues/1)

## 2.0.1 - 2022-10-21

### Added
- Logged in and logged out logs

### Fixed
- Users latest activity style

## 2.0.0 - 2022-10-16

### Added
- User permissions log

## 0.2.0 - 2022-10-16

### Changed
- Craft 4 support
