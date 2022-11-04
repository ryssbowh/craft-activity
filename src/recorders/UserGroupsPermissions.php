<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\UserGroups;
use yii\base\Event;

class UserGroupsPermissions extends ConfigModelRecorder
{
    protected $triggered = [];
    protected $savedLogs = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        $groups = \Craft::$app->userGroups->getAllGroups();
        foreach ($groups as $group) {
            \Craft::$app->projectConfig->onAdd(UserGroups::CONFIG_USERPGROUPS_KEY . '.' . $group->uid . '.permissions', function (Event $event) {
                Activity::getRecorder('userGroupsPermissions')->onUpdateGroupPerms($event);
            });    
        }
        \Craft::$app->projectConfig->onUpdate(UserGroups::CONFIG_USERPGROUPS_KEY, function (Event $event) {
            Activity::getRecorder('userGroupsPermissions')->onUpdateGroupsPerms($event);
        });
        \Craft::$app->projectConfig->onUpdate(UserGroups::CONFIG_USERPGROUPS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('userGroupsPermissions')->onTriggered($event);
        });
        \Craft::$app->projectConfig->onAdd(UserGroups::CONFIG_USERPGROUPS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('userGroupsPermissions')->onTriggered($event);
        });
    }

    /**
     * Special case for cp requests, the new permissions weren't included in the original event, adding them now
     * 
     * @param ConfigEvent $event
     */
    public function onUpdateGroupPerms(ConfigEvent $event)
    {
        if (!\Craft::$app->projectConfig->isApplyingYamlChanges) {
            $elems = explode('.', $event->path);
            $uid = $elems[2];
            $this->removeQueuedLogsByUid($uid, 'userGroupPermissionsSaved');
            $this->savedLogs[$uid]->newValue['permissions'] = $event->newValue ?? [];
            parent::onUpdate($this->savedLogs[$uid]);
        }
    }
    
    /**
     * Change the event's data, add the old value from the originally triggered event
     * 
     * @param  ConfigEvent $event
     */
    public function onTriggered(ConfigEvent $event)
    {
        if (!$event->oldValue) {
            //New group, need to register an event for the permissions
            \Craft::$app->projectConfig->onAdd(UserGroups::CONFIG_USERPGROUPS_KEY . '.' . $event->tokenMatches[0] . '.permissions', function (Event $event) {
                Activity::getRecorder('userGroupsPermissions')->onUpdateGroupPerms($event);
            });
        }
        $uid = $event->tokenMatches[0];
        if ($this->queueHasLogForUid($uid, 'userGroupPermissionsSaved')) {
            return;
        }
        $newEvent = clone $event;
        $newEvent->newValue = $this->triggered->newValue[$uid] ?? [];
        $newEvent->oldValue = $this->triggered->oldValue[$uid] ?? [];
        $this->savedLogs[$uid] = $newEvent;
        parent::onUpdate($newEvent);
    }

    /**
     * Save the specific group event, to retrieve the old data from it later
     * 
     * @param ConfigEvent $event
     */
    public function onUpdateGroupsPerms(ConfigEvent $event)
    {
        if (!$this->triggered) {
            $this->triggered = $event;
        }
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'userGroupPermissions';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['permissions'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}