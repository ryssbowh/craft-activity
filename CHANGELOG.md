# ryssbowh/craft-activity Changelog

## 3.1.3 - 2025-05-07

### Fixed

- Fixed issue saving matrix fields [#33](https://github.com/ryssbowh/craft-activity/issues/33)

## 3.1.2 - 2025-02-24

### Fixed

- Fixed issue saving sections [#31](https://github.com/ryssbowh/craft-activity/issues/31)

## 3.1.1 - 2025-02-24

### Fixed

- Fixed installation failing on PostgresQL [#32](https://github.com/ryssbowh/craft-activity/issues/32)

## 3.1.0 - 2024-11-07

### Added

- Added setting to ignore activity for admins
- Added setting to ignore activity for some user groups
- Added setting to consider the ignore rules as allow rules

## 3.0.4 - 2024-09-25

### Fixed

- Fixed issue creating users [#28](https://github.com/ryssbowh/craft-activity/issues/28)
- Fixed issues saving user addresses

## 3.0.3 - 2024-09-19

### Fixed

- Fixed issue with possible null plugin edition [#27](https://github.com/ryssbowh/craft-activity/issues/27)

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
