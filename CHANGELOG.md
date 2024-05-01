# ryssbowh/craft-activity Changelog

## 3.0.0 - unreleased

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