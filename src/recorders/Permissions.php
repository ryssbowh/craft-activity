<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\Recorder;
use craft\base\Model;
use craft\db\Query;
use craft\db\Table;
use craft\models\UserGroup;
use craft\services\UserGroups as CraftUserGroups;
use yii\base\Event;

class Permissions extends Recorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(CraftUserGroups::CONFIG_USERPGROUPS_KEY, function(Event $event) {
            if (!Activity::getRecorder('userGroups')->savingModel) {
                Activity::getRecorder('permissions')->onGroupPermissionsChanged($event->newValue);
            }
        });
    }

    /**
     * When user groups permissions are changed. We have to go through each group as the event doesn't give us the new value
     * if we were to respond on the event CraftUserGroups::CONFIG_USERPGROUPS_KEY.{uid}
     * 
     * @param  array $newValue
     */
    public function onGroupPermissionsChanged(array $newValue)
    {
        if (!$this->shouldSaveLog('userGroupPermissionsChanged')) {
            return;
        }
        foreach ($newValue as $uid => $array) {
            $group = \Craft::$app->userGroups->getGroupByUid($uid);
            // Getting old permissions from database, somehow they aren't included in event $oldValue
            $oldPerms = array_map(function ($array) {
                return $array['name'];
            }, (new Query)
                ->select('name')
                ->from(Table::USERPERMISSIONS_USERGROUPS)
                ->leftJoin(Table::USERPERMISSIONS, Table::USERPERMISSIONS . '.id = permissionId')
                ->where(['groupId' => $group->id])
                ->all()
            );
            $newPerms = $array['permissions'];
            $this->onPermissionsChanged('userGroupPermissionsChanged', $oldPerms, $newPerms, [
                'group' => $group
            ]);
        }
    }

    protected function onPermissionsChanged(string $type, array $oldPerms, array $newPerms, array $params)
    {
        $changed = [];
        $added = array_diff($newPerms, $oldPerms);
        $removed = array_diff($oldPerms, $newPerms);
        if ($added) {
            $changed['added'] = $this->labelledPermissions($added);
        }
        if ($removed) {
            $changed['removed'] = $this->labelledPermissions($removed);
        }
        if ($changed) {
            $params['changedFields']['permissions'] = $changed;
            $this->saveLog($type, $params);
        }
    }

    protected function getActivityHandle(): string
    {
        return 'userGroup';
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        $record = $query = (new Query())
            ->select([
                'id',
                'name',
                'handle',
                'uid',
            ])
            ->from([Table::USERGROUPS])
            ->where(['id' => $id])
            ->one();
        return new UserGroup($record);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name', 'handle'];
    }

    protected function labelledPermissions(array $perms)
    {
        $labelled = [];
        foreach ($perms as $perm) {
            list($section, $label) = $this->findPermission($perm);
            $labelled[$perm] = $section . ': ' . $label;
        }
        return $labelled;
    }

    protected function findPermission(string $perm): array
    {
        $allPerms = \Craft::$app->userPermissions->getAllPermissions();
        foreach ($allPerms as $section => $array) {
            if ($label = $this->findPermissionLabel($perm, $array)) {
                return [$section, $label];
            }
        }
    }

    protected function findPermissionLabel(string $perm, array $perms): ?string
    {
        foreach ($perms as $name => $array) {
            if (strtolower($name) == $perm) {
                return $array['label'];
            }
            if (isset($array['nested']) and $label = $this->findPermissionLabel($perm, $array['nested'])) {
                return $label;
            }
        }
        return null;
    }
}