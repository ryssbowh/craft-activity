# Craft activity (v3.x)

Record user activity in Craft, this plugin can record and keep track of changed fields for pretty much any event that happens in the frontend/control panel/console whether it's an element or some config that's being changed.

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

## Settings

This plugin has some extensive settings to tweak the activity you want recorded. Lots of events can be ignored (cp, frontend, console, project config), or you can ignore only some log types.  
Logs can be deleted when they become too old, and be deleted along with the user that created them.

By default the routes logs are ignored (because we can't track their changes), and the "update slugs and uris", "elements are propagated" and "elements are resaved" logs are ignored which should be what you need in most cases. Turning on those logs can create lots of useless activities as they are triggered by the system.

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
        $this->saveLog($logType, $data);
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

## Known issues

- provisional drafts cannot be ignored, they are not technically provisional drafts
- hard delete events can't be ignored
- routes changes can't be tracked
- changing edition will create one log for settings as well
- widgets changes can't be tracked