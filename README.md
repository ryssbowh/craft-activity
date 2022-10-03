# Craft activity (Craft 3)

Record user activity in Craft, this plugin can record and keep track of changed fields for pretty much any event that happens in the frontend/control panel/console whether it's an element or some config that's being changed.

All Craft fields tracking is supported, as well as [Redactor](https://plugins.craftcms.com/redactor), [Super table](https://plugins.craftcms.com/super-table), [SEO](https://plugins.craftcms.com/seo), [Commerce](https://plugins.craftcms.com/commerce) and [Typed Link](https://plugins.craftcms.com/typedlinkfield) fields.

A non exhaustive list of things this plugin can track :
- Elements
  - Entries (created, saved, deleted, restored, moved)
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
- Field groups (created, saved, deleted)
- Global sets (created, saved, deleted)
- Plugins (enabled, disabled, installed, uninstalled)
- Routes (saved, deleted)
- Settings changed (assets, emails, users, general)
- Sites (created, saved, deleted)
- Tag groups (created, saved, deleted)
- Users
  - activated, self activated
  - assigned groups
  - used invalid token
  - locked, unlocked
  - registered
  - suspended, unsuspended
  - email verified
- User groups (created, saved, deleted, permissions)
- User layout changed
- Widgets (created, saved, deleted)
- Craft edition changed

User activity and the fields changed will remain viewable even when the object recorded for no longer exists.

## Installation

Install through plugin store or with composer : `composer require rysbowh/craft-activity:^0.1`

## Dashboard

The dashboard is on the main menu, under "Activity".

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

## Settings

This plugin has some extensive settings to control the activity you want recorded. Lots of events can be ignored (cp, frontend, console, project config), or you can ignore only some log types.  
Logs can be deleted when they become too old, and be deleted along with the user that created them.

By default the routes logs are ignored (because we can't track their changes), and the "update slugs and uris", "elements are propagated" and "elements are resaved" logs are ignored which should be what you need in most cases. Turning on those logs can create lots of useless records as they are triggered by the system.

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
        $data = [];
        if (!$this->shouldSaveLog($logType)) {
            return;
        }
        $this->commitLog($logType, $data);
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

In some cases, several events will happen at the same time and you need to control wether your recorder (or others) record or not. You can start/stop the recording of any recorder with `Activity::getRecorder('my-recorder')->stopRecording = true` at any point.

### Log types

Define a new log type :
```
use Ryssbowh\Activity\base\logs\ActivityLog;

class SomethingCreated extends ActivityLog
{
    /**
     * @inheritDoc
     */
    public function getDbData(): array
    {
        return array_merge(parent::getDbData(), [
            'my-data' => 'my-value'
        ]);
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

Each field handler has a target which defines when it's triggered.

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
    public static function getTargets(): array
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
    public static function getTargets(): array
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

## Known issues

- Drafts aren't managed at the moment as provisional drafts cannot be ignored which creates lots of useless logs. May implement this in the future.
- Hard delete events can't be ignored
- Routes changes can't be tracked
- Widgets changes can't be tracked
- Each individual fields config isn't tracked

## Roadmap

- Track each individual field config
- Reverting system