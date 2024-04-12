<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class Sections extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    protected ?string $deleteTypesCategory = 'sections';

    /**
     * @inheritDoc
     */
    protected array $deleteTypes = ['sectionCreated', 'sectionSaved', 'sectionDeleted'];

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_SECTIONS . '.{uid}', function (Event $event) {
            Activity::getRecorder('sections')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_SECTIONS . '.{uid}', function (Event $event) {
            Activity::getRecorder('sections')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_SECTIONS . '.{uid}', function (Event $event) {
            Activity::getRecorder('sections')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'section';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['name', 'handle', 'type', 'enableVersioning', 'siteSettings', 'previewTargets', 'propagationMethod', 'maxLevels', 'entryTypes', 'maxAuthors'];
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldTypings(): array
    {
        return [
            'enableVersioning' => 'bool'
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
