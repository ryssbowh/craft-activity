<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\ProjectConfig;
use yii\base\Event;

class UserGroups extends ConfigModelRecorder
{
    protected $inited;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_USER_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('userGroups')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_USER_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('userGroups')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_USER_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('userGroups')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    public function onAdd(ConfigEvent $event)
    {
        if (!$this->queueHasLogForUid($event->tokenMatches[0], 'userGroupCreated')) {
            parent::onAdd($event);
        }
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(ConfigEvent $event)
    {
        if (!$this->queueHasLogForUid($event->tokenMatches[0], 'userGroupSaved')) {
            parent::onUpdate($event);
        }
    }

    /**
     * @inheritDoc
     */
    public function onRemove(ConfigEvent $event)
    {
        if (!$this->queueHasLogForUid($event->tokenMatches[0], 'userGroupDeleted')) {
            parent::onRemove($event);
        }
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'userGroup';
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'description'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}