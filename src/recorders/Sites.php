<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\records\Site;
use craft\services\ProjectConfig;
use yii\base\Event;

class Sites extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    protected ?string $deleteTypesCategory = 'sites';

    /**
     * @inheritDoc
     */
    protected array $deleteTypes = ['siteCreated', 'siteSaved', 'siteDeleted'];

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_SITES . '.{uid}', function (Event $event) {
            Activity::getRecorder('sites')->onUpdate($event);
            Activity::getRecorder('categoryGroups')->emptyQueue();
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_SITES . '.{uid}', function (Event $event) {
            Activity::getRecorder('sites')->onAdd($event);
            Activity::getRecorder('categoryGroups')->emptyQueue();
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_SITES . '.{uid}', function (Event $event) {
            Activity::getRecorder('sites')->onRemove($event);
            Activity::getRecorder('categoryGroups')->emptyQueue();
        });
    }

    /**
     * @inheritDoc
     */
    public function onRemove(ConfigEvent $event)
    {
        if (Activity::$plugin->settings->deleteLogsWithSite) {
            $site = Site::find()->where(['uid' => $event->tokenMatches[0]])->one();
            if ($site) {
                Activity::$plugin->logs->deleteSiteLogs($site->id);
            }
        }
        parent::onRemove($event);
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'site';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['name', 'handle', 'language', 'primary', 'hasUrls', 'baseUrl', 'enabled', 'siteGroup'];
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldTypings(): array
    {
        return [
            'hasUrls' => 'bool',
            'enabled' => 'bool',
            'primary' => 'bool'
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
