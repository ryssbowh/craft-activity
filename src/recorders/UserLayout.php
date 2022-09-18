<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\Recorder;
use Ryssbowh\Activity\models\fieldHandlers\config\FieldLayout as FieldLayoutHandler;
use craft\elements\User;
use craft\models\FieldLayout;
use craft\services\Users;
use yii\base\Event;

class UserLayout extends Recorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(Users::CONFIG_USERLAYOUT_KEY, function(Event $event) {
            Activity::getRecorder('userLayout')->onSaved($event->oldValue, $event->newValue);
        });
        \Craft::$app->projectConfig->onAdd(Users::CONFIG_USERLAYOUT_KEY, function(Event $event) {
            Activity::getRecorder('userLayout')->onSaved($event->oldValue, $event->newValue);
        });
    }
    
    /**
     * @inheritDoc
     */
    public function onSaved(array $oldValue, array $newValue)
    {   
        if (!$this->shouldSaveLog('userLayoutSaved')) {
            return;
        }
        $oldLayout = $this->getUserLayout(reset($oldValue));
        $newLayout = $this->getUserLayout(reset($newValue));
        $newHandler = new FieldLayoutHandler([
            'rawValue' => $newLayout
        ]);
        $oldHandler = new FieldLayoutHandler([
            'rawValue' => $oldLayout
        ]);
        $dirty = ['fieldLayout' => $newHandler->getDirty($oldHandler)];
        $this->saveLog('userLayoutSaved', [
            'changedFields' => $dirty
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getUserLayout(?array $value)
    {
        if ($value === null) {
            $value = [];
        }
        $value['type'] = User::class;
        $value['class'] = FieldLayout::class;
        return \Craft::createObject($value);
    }
}