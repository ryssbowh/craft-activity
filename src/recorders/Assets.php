<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ElementsRecorder;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use craft\base\Element;
use craft\elements\Asset;
use craft\services\Elements;
use yii\base\Event;

class Assets extends ElementsRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if (Activity::$plugin->settings->ignoreResave) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_RESAVE_ELEMENT, function (Event $event) {
                Activity::getRecorder('assets')->stopRecording();
            });
        }
        if (Activity::$plugin->settings->ignoreUpdateSlugs) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_UPDATE_SLUG_AND_URI, function (Event $event) {
                Activity::getRecorder('assets')->stopRecording();
            });
        }
        if (Activity::$plugin->settings->ignorePropagate) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_PROPAGATE_ELEMENT, function (Event $event) {
                Activity::getRecorder('assets')->stopRecording();
            });
        }
        Event::on(Asset::class, Asset::EVENT_BEFORE_SAVE, function ($event) {
            Activity::getRecorder('assets')->beforeSaved($event->sender);
        });
        Event::on(Asset::class, Asset::EVENT_AFTER_SAVE, function ($event) {
            Activity::getRecorder('assets')->onSaved($event->sender);
        });
        Event::on(Asset::class, Asset::EVENT_AFTER_DELETE, function ($event) {
            Activity::getRecorder('assets')->onDeleted($event->sender);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return Asset::class;
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'asset';
    }

    /**
     * @inheritDoc
     */
    protected function getFieldsValues(Element $asset): array
    {
        return array_merge(
            [
                'filename' => new Plain([
                    'name' => \Craft::t('app', 'Filename'),
                    'value' => $asset->filename
                ])
            ],
            $this->getCustomFieldValues($asset)
        );
    }
}