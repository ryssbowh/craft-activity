<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\ProjectConfig;
use yii\base\Event;

class FileSystems extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_FS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fileSystems')->onFsUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_FS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fileSystems')->onFsAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_FS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fileSystems')->onFsRemove($event);
        });
    }

    public function onFsUpdate(ConfigEvent $event)
    {
        $event->oldValue['handle'] = $event->tokenMatches[0];
        $event->newValue['handle'] = $event->tokenMatches[0];
        $this->onUpdate($event);
    }

    public function onFsAdd(ConfigEvent $event)
    {
        $event->newValue['handle'] = $event->tokenMatches[0];
        $this->onAdd($event);
    }

    public function onFsRemove(ConfigEvent $event)
    {
        $event->oldValue['handle'] = $event->tokenMatches[0];
        $this->onRemove($event);
    }
    
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'filesystem';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'hasUrls', 'settings.path', 'type', 'url'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}