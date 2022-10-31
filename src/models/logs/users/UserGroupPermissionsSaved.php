<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;
use craft\models\UserGroup;

class UserGroupPermissionsSaved extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved permissions for user group {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/users/groups/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->userGroups->getGroupByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function _getFieldLabels(): array
    {
        return [
            'permissions' => \Craft::t('app', 'Permissions')
        ];
    }
}