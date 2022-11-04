# Craft activity (Craft 3)

Record user activity in Craft, this plugin can record and keep track of changed fields for pretty much any event that happens in the frontend/control panel/console whether it's an element or some config that's being changed.

A non exhaustive list of things this plugin can track :
- Elements
  - Entries (created, saved, deleted, restored, moved, reverted to revision)
  - Assets (created, saved, deleted, restored)
  - Users (created, saved, deleted, restored)
  - Globals (saved)
  - Categories (created, saved, deleted, restored, moved)
- Volumes (created, saved, deleted)
- Asset transforms (created, saved, deleted)
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

Install through plugin store or with composer : `composer require rysbowh/craft-activity:^1.0`

## Dashboard

The dashboard is on the main menu, under "User Activity".

You can filter activity by users, type of activity and date range. The hot reload option will reload the activity automatically every 5 seconds.

Description of columns :
- User : The user logged in when the activity was triggered
- Site : The current site when the activity was triggered
- Request : The type of request which can be :
  - Console
  - Site
  - Control Panel
  - Yaml config : This request is for when someone applies yaml config, regardless of whether it's done through the control panel or console
- Activity
- Date

## Users

The latest activity will be displayed on each user edit page in the Control Panel.

## Settings

This plugin has some extensive settings to control the activity you want recorded. A rule system allows you to ignore some activity based on its type and the type of request (cp, console, site, yaml config)

By default the "update slugs and uris", "elements are propagated" and "elements are resaved" logs are ignored which should be what you need in most cases. Turning on those logs can create lots of useless records as they are triggered by the system.

Logs can be deleted when they become too old, and be deleted along with the user that created them.

## Extend

This plugin has been developed with the main focus of being easy to extend, you can define your own recorders, log types, field handlers and record any activity you like.

### Recorders

Define a new recorder :

```
use Ryssbowh\Activity\base\recorders\Recorder;
use yii\base\Event;
use Ryssbowh\Activity\Activity;

class MyRecorder extends Recorder
{
    public function init()
    {
        Event::on(SomeClass::class, SomeClass::EVENT_SOME_EVENT, function (Event $event) {
            Activity::getRecorder(''my-recorder')->somethingChanged($event);
        });
    }

    public function somethingChanged(Event $event)
    {
        $logType = 'somethingCreated';
        $params = [
            'myObject' => $event->object
        ];
        if ($this->shouldSaveLog($logType)) {
            $this->commitLog($logType, $params);
        }
    }
}
```
And register it :
```
use Ryssbowh\Activity\services\Recorders;

Event::on(Recorders::class, Recorders::EVENT_REGISTER, function (Event $event) {
    $event->add('my-recorder', new MyRecorder);
})
```
You'll then be able to get your recorder instance at any point in the application with `Activity::getRecorder('my-recorder')`

In some cases, several events will happen at the same time and you need to control wether your recorder (or others) record or not. 

You can start/stop the recording of any recorder with `Activity::getRecorder('my-recorder')->stopRecording()` at any point.  
You can also empty a recorder log queue (which will only be saved at the end of the request) : `Activity::getRecorder('my-recorder')->emptyQueue()`

You'll find a list of recorders defined by the system in the class `Ryssbowh\Activity\events\RegisterRecordersEvent`.

### Log types

Define a new log type :
```
use Ryssbowh\Activity\base\logs\ActivityLog;

class SomethingCreated extends ActivityLog
{
    /**
     * This is optional, but shows you how to set data on your log
     *
     * @param mixed $object
     */
    public function setMyObject($object): array
    {
        $this->target_uid = $object->uid;
        $this->target_name = $object->name;
        $this->target_class = get_class($object);
        $this->data = [
            'my-field' => $object->field
        ];
    }
}
```
The handle will be automatically determined from the class name, change it by overriding the `getHandle()` method. In this example it will be `somethingCreated`.  
The name will also be automatically determined from the class name, change it by overriding the `getName()` method. In this example it will be `Something created`.  
The title will also be automatically determined from the class name, change it by overriding the `getTitle()` method. In this example it will be `Created something`.  

Register it :

```
use Ryssbowh\Activity\services\Types;

Event::on(Types::class, Types::EVENT_REGISTER, function (Event $event) {
    $event->add(new SomethingCreated);
})
```

### Field handlers

A field handler can change how changes are calculated on a field, and how the value is saved in database, they also can save "fancy" values to be displayed to the user.

The system typically defines handlers for things like arrays, where the label of the value changed also needs to be saved, to display a more user friendly value to the user.

Field handlers can completely override how the changes made to a field are saved, a good example would be entry types field layouts, where calculating changes can be a bit complex.

Each field handler has a target which defines which target(s) it can handle.

#### Project Config fields

For config fields, the target is defined by its Yaml path, example if you wanted to add a handler for the General System Status setting, the path would be `system.live`.

**Define a new handler :**

```
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;

class MyHandler extends FieldHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->fancyValue = $this->value ? 'Live' : 'Offline';
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            'system.live'
        ];
    }
}
```

**Register it:**

```
use Ryssbowh\Activity\services\FieldHandlers;

Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_PROJECTCONFIG_HANDLERS, function (Event $e) {
    $e->add(MyHandler::class);
});
```

That's enough to trigger your field handler when the path `system.live` is changed. This will cause the activity log to save a fancy value ('Live' or 'Offline') in the database alongside the original value (true or false). That fancy value will be displayed to the user instead.

You can also change the description of the field being changed that will be displayed to the user by overriding the `getTemplate(): ?string` method.

### Element fields

Same idea than project config, but here the targets are field classes. Example if you wanted to change how Matrix fields changes are calculated you would do something like this :

**Define a new handler :**

```
use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use craft\fields\Matrix;

class MyHandler extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->fancyValue = $this->calculateFancyValue();
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            Matrix::class
        ];
    }

    protected function calculateFancyValue()
    {

    }
}
```

**Register it:**

```
use Ryssbowh\Activity\services\FieldHandlers;

Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_ELEMENT_HANDLERS, function (Event $e) {
    $e->add(MyHandler::class);
});
```

In this example an exception would be thrown as Matrix fields already have a handler defined by this plugin, but you can replace it by doing `$e->add(MyHandler::class, true);` instead.

## Requirements

This plugin requires Craft 3.7 or above.

## Documentation

See the class reference [here](https://ryssbowh.github.io/docs/craft-activity1/namespaces/ryssbowh-activity.html)

## Known issues

- Drafts aren't managed at the moment as provisional drafts cannot be ignored which creates lots of useless logs. May implement this in the future.
- Hard delete events can't be ignored
- Widgets changes can't be tracked
- Table field default values can't be tracked when the field is removed
- Tags aren't tracked, seems useless since there's no tag management utility in Craft
- Elements moved activity is ignored by default
- The system doesn't track every single config field (such as for fields for example), if you choose to ignore activity when no changes are done, activity won't be recorded even if changes are made to a field that isn't tracked.

## Roadmap

- Track individual field config