<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\db\Query;
use craft\db\Table;
use craft\events\ConfigEvent;
use craft\services\UserGroups as CraftUserGroups;
use yii\base\Event;

class UserGroups extends ConfigModelRecorder
{
    protected $permTrigger;
    protected $triggered;

    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(CraftUserGroups::CONFIG_USERPGROUPS_KEY, function (Event $event) {
            Activity::getRecorder('userGroups')->onGroupsUpdate($event);
        });
        \Craft::$app->projectConfig->onUpdate(CraftUserGroups::CONFIG_USERPGROUPS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('userGroups')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(CraftUserGroups::CONFIG_USERPGROUPS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('userGroups')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(CraftUserGroups::CONFIG_USERPGROUPS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('userGroups')->onRemove($event);
        });
    }

    public function onGroupsUpdate(ConfigEvent $event)
    {
        //Need to save the general group trigger to get the value of the new permissions
        //which is not included in each particular group event, as it happens after
        $this->permTrigger = $event;
    }

    public function onUpdate(ConfigEvent $event)
    {
        if (!\Craft::$app->projectConfig->isApplyingYamlChanges) {
            if ($this->triggered === null) {
                //This event is triggered twice, once for the group, once for the permissions
                //the first one has the data we need
                $this->triggered = $event;
                return;
            }
            $this->triggered->newValue = $this->permTrigger->newValue[$event->tokenMatches[0]];
            $this->triggered->tokenMatches = $event->tokenMatches;
            $event = $this->triggered;
        }
        $event->newValue = $this->permTrigger->newValue[$event->tokenMatches[0]];
        parent::onUpdate($event);
        $this->permTrigger = null;
        $this->triggered = null;
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