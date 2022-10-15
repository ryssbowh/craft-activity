<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\ProjectConfig;
use yii\base\Event;

class UserGroups extends ConfigModelRecorder
{
    protected $triggered;
    protected $mode;
    protected $added = false;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_USER_GROUPS, function (Event $event) {
            Activity::getRecorder('userGroups')->onGroupsChanged($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_USER_GROUPS, function (Event $event) {
            Activity::getRecorder('userGroups')->onGroupsChanged($event);
        });
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
     * Little fiddling with the data :
     * If we're applying project config need to save the event for the specific events (onAdd, onUpdate) to use it later.
     * If we're not, the specific events have been triggered already and the data is there to use
     * 
     * @param ConfigEvent $event
     */
    public function onGroupsChanged(ConfigEvent $event)
    {
        if (!\Craft::$app->projectConfig->isApplyingExternalChanges) {
            if ($this->triggered and $this->mode) {
                $path = explode('.', $this->triggered->path);
                $this->triggered->tokenMatches = [$path[2]];
                $this->triggered->newValue['permissions'] = $event->newValue[$path[2]]['permissions'];
                $this->emptyQueue();
                if ($this->mode == 'add') {
                    parent::onAdd($this->triggered);
                } else {
                    parent::onUpdate($this->triggered);
                }
                $this->triggered = null;
                $this->mode = null;
            }
        } else {
            $this->triggered = $event;
        }
    }

    public function onUpdate(ConfigEvent $event)
    {
        if (!\Craft::$app->projectConfig->isApplyingExternalChanges) {
            if ($this->triggered === null) {
                //This event is triggered twice, once for the group, once for the permissions
                //the first one has the data we need
                $this->triggered = $event;
                $this->mode = 'update';
            }
        } else {
            $uid = $event->tokenMatches[0];
            $event->newValue['permissions'] = $this->triggered->newValue[$uid]['permissions'];
        }
        if (!$this->queue) {
            parent::onUpdate($event);
        }
    }

    public function onAdd(ConfigEvent $event)
    {
        if (!\Craft::$app->projectConfig->isApplyingExternalChanges) {
            $this->triggered = $event;
            $this->mode = 'add';
        } else if (!$this->added) {
            $uid = $event->tokenMatches[0];
            $event->newValue['permissions'] = $this->triggered->newValue[$uid]['permissions'];
            $this->added = true;
        }
        if (!$this->queue) {
            parent::onAdd($event);
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
    protected function getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'description', 'permissions'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}