<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ElementsRecorder;
use craft\base\Element;
use craft\elements\GlobalSet;
use craft\services\Elements;
use craft\services\Globals as CraftGlobals;
use craft\services\Sites;
use yii\base\Event;

class Globals extends ElementsRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if (Activity::$plugin->settings->ignoreResave) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_RESAVE_ELEMENT, function (Event $event) {
                Activity::getRecorder('globals')->stopRecording();
            });
        }
        if (Activity::$plugin->settings->ignoreUpdateSlugs) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_UPDATE_SLUG_AND_URI, function (Event $event) {
                Activity::getRecorder('globals')->stopRecording();
            });
        }
        if (Activity::$plugin->settings->ignorePropagate) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_PROPAGATE_ELEMENT, function (Event $event) {
                Activity::getRecorder('globals')->stopRecording();
            });
        }
        Event::on(CraftGlobals::class, CraftGlobals::EVENT_BEFORE_SAVE_GLOBAL_SET, function ($event) {
            Activity::getRecorder('globals')->stopRecording();
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
    protected function getFieldsValues(Element $global): array
    {
        return $this->getCustomFieldValues($global);
    }

    /**
     * @inheritDoc
     */
    protected function getSavedActivityType(Element $element): string
    {
        return $this->getActivityHandle() . 'Saved';
    }
}
