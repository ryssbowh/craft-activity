# ryssbowh/craft-activity Changelog

## 3.0.2 - 2024-09-14

### Fixed

- Fixed issue with possible null element titles [#26](https://github.com/ryssbowh/craft-activity/issues/26)

## 3.0.1 - 2024-06-30

### Fixed

- Fixed issue with neo blocks all being tracked and causing a SQL error `1406 Data too long` [#23](https://github.com/ryssbowh/craft-activity/issues/23)
- Changed table `activity_changed_fields` data column from `text` to `longtext`

## 3.0.0 - 2024-05-07

### Changed

- Craft 5 support
- Field `craft\fields\Addresses` is now tracked

### Added

- Added field handler `Ryssbowh\Activity\models\fieldHandlers\elements\MatrixNew` which handles matrix blocks. The old matrix handler is still there for legacy events
- Added field handler `Ryssbowh\Activity\models\fieldHandlers\elements\EntryTypes`
- Added field handler `Ryssbowh\Activity\models\fieldHandlers\elements\Authors`
- Added recorder `Ryssbowh\Activity\recorders\UserAddresses`
- Added log `Ryssbowh\Activity\models\logs\addresses\UserAddressCreated`
- Added log `Ryssbowh\Activity\models\logs\addresses\UserAddressDeleted`
- Added log `Ryssbowh\Activity\models\logs\addresses\UserAddressSaved`
- Added log `Ryssbowh\Activity\models\logs\users\UserDeactivated`
- Added method `Ryssbowh\Activity\services\Recorders::emptyQueues`

### Removed

- Removed recorder `Ryssbowh\Activity\recorders\FieldGroups`
- Removed recorder `Ryssbowh\Activity\recorders\MatrixBlocks`
- Removed recorder `Ryssbowh\Activity\recorders\SuperTableBlocks`
