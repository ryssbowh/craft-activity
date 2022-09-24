<?php

namespace Ryssbowh\Activity\models\logs\sites;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;

class SiteGroupCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created site group {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/sites?groupId=' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->sites->getGroupByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return [
            'name' => \Craft::t('app', 'Name'),
            'handle' => \Craft::t('app', 'Handle')
        ];
    }
}