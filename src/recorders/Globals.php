<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ElementsRecorder;
use craft\base\Element;
use craft\elements\GlobalSet;
use craft\services\Globals as CraftGlobals;
use yii\base\Event;

class Globals extends ElementsRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftGlobals::class, CraftGlobals::EVENT_BEFORE_SAVE_GLOBAL_SET, function ($event) {
            Activity::getRecorder('globals')->stopRecording = true;
        });
        Event::on(GlobalSet::class, GlobalSet::EVENT_BEFORE_SAVE, function ($event) {
            Activity::getRecorder('globals')->beforeSaved($event->sender);
        });
        Event::on(GlobalSet::class, GlobalSet::EVENT_AFTER_SAVE, function ($event) {
            Activity::getRecorder('globals')->onSaved($event->sender);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return GlobalSet::class;
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'global';
    }

    /**
     * @inheritDoc
     */
    protected function getFields(Element $global): array
    {
        return $this->getFieldValues($global);
    }
}