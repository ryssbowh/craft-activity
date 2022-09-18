<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ElementsRecorder;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use craft\base\Element;
use craft\elements\Asset;
use yii\base\Event;

class Assets extends ElementsRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
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
    protected function getFields(Element $asset): array
    {
        return array_merge(
            [
                'filename' => new Plain([
                    'name' => \Craft::t('app', 'Filename'),
                    'value' => $asset->filename
                ])
            ],
            $this->getFieldValues($asset)
        );
    }
}