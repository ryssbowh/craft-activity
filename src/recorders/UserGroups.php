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
    protected $inited;

    /**
     * @inheritDoc
     */
    public function init()
    {
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
        $uid = $event->tokenMatches[0];
        if (!$this->queueHasLogForUid($uid, 'userGroupSaved') and !$this->queueHasLogForUid($uid, 'userGroupCreated')) {
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
    protected function getTrackedFieldNames(array $config): array
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