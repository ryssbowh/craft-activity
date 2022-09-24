<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\Recorder;
use craft\base\Widget;
use craft\services\Dashboard as CraftDashboard;
use yii\base\Event;

class Dashboard extends Recorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftDashboard::class, CraftDashboard::EVENT_AFTER_SAVE_WIDGET, function ($event) {
            Activity::getRecorder('dashboard')->onWidgetSaved($event->widget, $event->isNew);
        });
        Event::on(CraftDashboard::class, CraftDashboard::EVENT_AFTER_DELETE_WIDGET, function ($event) {
            Activity::getRecorder('dashboard')->onWidgetDeleted($event->widget);
        });
    }
        
    /**
     * Record log when a widget is saved
     * 
     * @param Widget $widget
     * @param bool   $isNew
     */
    public function onWidgetSaved(Widget $widget, bool $isNew)
    {
        $type = $isNew ? 'widgetCreated' : 'widgetSaved';
        if (!$this->shouldSaveLog($type)) {
            return;
        }
        $this->commitLog($type, [
            'widget' => $widget
        ]);
    }

    /**
     * Record log when a widget is deleted
     * 
     * @param Widget $widget
     */
    public function onWidgetDeleted(Widget $widget)
    {
        if (!$this->shouldSaveLog('widgetDeleted')) {
            return;
        }
        $this->commitLog('widgetDeleted', [
            'widget' => $widget
        ]);
    }
}