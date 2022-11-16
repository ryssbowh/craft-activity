# Craft activity (Craft 4)

See the Craft 3 version [here](https://github.com/ryssbowh/craft-activity/tree/v1)

Record user activity in Craft, this plugin can record and keep track of changed fields for pretty much any event that happens in the frontend/control panel/console whether it's an element or some config that's being changed.

A non exhaustive list of things this plugin can track :
- Elements
  - Entries (created, saved, deleted, restored, moved, reverted to revision)
  - Assets (created, saved, deleted, restored)
  - Users (created, saved, deleted, restored)
  - Globals (saved)
  - Categories (created, saved, deleted, restored, moved)
- Address layout changed
- Volumes (created, saved, deleted)
- Filesystems (created, saved, deleted)
- Image transforms (created, saved, deleted)
- Backups (created, restored)
- Category groups (created, saved, deleted)
- Entry types (created, saved, deleted)
- Sections (created, saved, deleted)
- Fields (created, saved, deleted)
- Matrix blocks (created, saved, deleted)
- Field groups (created, saved, deleted)
- Global sets (created, saved, deleted)
- Plugins (enabled, disabled, installed, uninstalled)
- Routes (saved, deleted)
- Settings changed (assets, emails, users, general)
- Sites (created, saved, deleted)
- Tag groups (created, saved, deleted)
- Users
  - login/logout
  - permissions changed
  - activated, self activated
  - assigned groups
  - used invalid token
  - locked, unlocked
  - registered
  - suspended, unsuspended
  - email verified
  - failed to login
- User groups (created, saved, deleted, permissions changed)
- User layout changed
- Widgets (created, saved, deleted)
- Emails (sent, failed)
- Craft edition changed

All native Craft fields tracking is supported, as well as :
- [Redactor](https://plugins.craftcms.com/redactor)
- [Super table](https://plugins.craftcms.com/super-table)
- [SEO](https://plugins.craftcms.com/seo)
- [Typed Link](https://plugins.craftcms.com/typedlinkfield)
- [TinyMCE](https://plugins.craftcms.com/tinymce)

User activity and the fields changed will remain viewable even when the object recorded for no longer exists.

**This plugin will not work if you've opt out of [Project Config](https://craftcms.com/docs/3.x/project-config.html) on which it's based**

## Installation

Install through plugin store or with composer : `composer require rysbowh/craft-activity:^2.0`

## Requirements

This plugin requires Craft 4.0 or above.

## Documentation

- [Plugin documentation website](https://puzzlers.run/plugins/user-activity/2.x)
- [Class reference](https://ryssbowh.github.io/docs/craft-activity2/namespaces/ryssbowh-activity.html)