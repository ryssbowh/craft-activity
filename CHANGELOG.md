# ryssbowh/craft-activity Changelog

## 2.3.15 - 2023-10-04

### Fixed
- Fixed issue not being able to add/delete/reorder ignore rules in settings
- Fixed issue saving Neo fields [#20](https://github.com/ryssbowh/craft-activity/issues/20)
- Fixed issue saving Matrix and Super Table fields
- Changed how block rows are calculated

## 2.3.14 - 2023-09-15

### Fixed
- Fixed issue saving eager loaded elements [#18](https://github.com/ryssbowh/craft-activity/issues/18)

## 2.3.13 - 2023-09-01

### Fixed
- Fixed issue saving Neo fields [#17](https://github.com/ryssbowh/craft-activity/issues/17)

## 2.3.12 - 2023-06-16

### Fixed
- Fixed issue saving empty transforms config [#16](https://github.com/ryssbowh/craft-activity/issues/16)

## 2.3.11 - 2023-04-13

### Fixed
- Fixed issue printing logs that aren't strings [#15](https://github.com/ryssbowh/craft-activity/issues/15)

## 2.3.10 - 2023-04-12

### Fixed
- Fixed issue printing logs that aren't strings [#15](https://github.com/ryssbowh/craft-activity/issues/15)

## 2.3.9 - 2023-04-05

### Fixed
- Fixed issue assigning groups to users [#14](https://github.com/ryssbowh/craft-activity/issues/14)

## 2.3.8 - 2023-04-04

### Fixed
- Fixed issue while saving Neo fields [#13](https://github.com/ryssbowh/craft-activity/issues/13)

## 2.3.7 - 2023-02-17

### Fixed
- Fixed wrong url in multi sites for "View all" in user latest activity block

## 2.3.6 - 2023-02-14

### Fixed
- Fixed issue exporting text logs for deleted users [#12](https://github.com/ryssbowh/craft-activity/issues/12)

## 2.3.5 - 2023-02-11

### Fixed
- Fixed issue where url for the user exporting would show `myaccount`
- Fixed issue with long text within nested fields (Matrix, SuperTable, Neo) [#12](https://github.com/ryssbowh/craft-activity/issues/12)
- Fixed issue where long text would be turned into an ajax url in exports
- Activity now sets a context before rendering activity templates

## 2.3.4 - 2023-02-06

### Added
- Logs now saves the IP of the request. Added a setting to display the IP on the dashboard (false by default)

## 2.3.3 - 2023-01-24

### Fixed
- Ignore UI elements in Neo fields [#11](https://github.com/ryssbowh/craft-activity/issues/11)

## 2.3.2 - 2023-01-14

### Added
- Setting to show user full names instead of friendly names

### Fixed
- "System" name not showing in users filter dropdown

## 2.3.1 - 2022-12-16

### Added
- [Neo fields](https://plugins.craftcms.com/neo) support

### Changed
- Field instructions changes are now tracked

### Fixed
- Fixed issue when deleting a field defined by a disabled plugin

## 2.3.0 - 2022-12-15

### Added
- Ability to export logs through the CP and console command [#7](https://github.com/ryssbowh/craft-activity/issues/7)
- Text and Pdf exporters

## 2.2.4 - 2022-11-17

### Fixed
- Fixed issue with TableDefaultValues handler [#1](https://github.com/ryssbowh/craft-activity/issues/1)

## 2.2.3 - 2022-11-16

### Changed
- Updated documentation urls

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
