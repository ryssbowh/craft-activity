<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\ProjectConfig;
use yii\base\Event;

class EntryTypes extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_ENTRY_TYPES . '.{uid}', function (Event $event) {
            Activity::getRecorder('entryTypes')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_ENTRY_TYPES . '.{uid}', function (Event $event) {
            Activity::getRecorder('entryTypes')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_ENTRY_TYPES . '.{uid}', function (Event $event) {
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
    protected function getTrackedFieldNames(array $config): array
    {
        return ['name', 'handle', 'hasTitleField', 'titleTranslationMethod', 'titleFormat', 'fieldLayouts'];
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldTypings(): array
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