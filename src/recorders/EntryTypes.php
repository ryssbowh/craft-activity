<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\Sections;
use yii\base\Event;

class EntryTypes extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(Sections::CONFIG_ENTRYTYPES_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('entryTypes')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(Sections::CONFIG_ENTRYTYPES_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('entryTypes')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(Sections::CONFIG_ENTRYTYPES_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('entryTypes')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'entryType';
    }

    /**
     * @inheritDoc
     */
    protected function modifyParams(array $params, ConfigEvent $event): array
    {
        $uid = $event->newValue['section'] ?? $event->oldValue['section'];
        $params['section'] = \Craft::$app->sections->getSectionByUid($uid);
        return $params;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'hasTitleField', 'titleTranslationMethod', 'titleFormat', 'fieldLayouts'];
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
    {
        return [
            'hasTitleField' => 'bool'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}