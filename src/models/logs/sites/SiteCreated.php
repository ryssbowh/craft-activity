<?php

namespace Ryssbowh\Activity\models\logs\sites;

use Ryssbowh\Activity\base\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;
use craft\models\Site;

class SiteCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created site {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/sites/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->sites->getSiteById($this->target_id);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return array_merge((new Site)->attributeLabels(), [
            'hasUrls' => \Craft::t('activity', 'Has its own base URL'),
            'enabled' => \Craft::t('app', 'Enabled'),
            'primary' => \Craft::t('app', 'Primary'),
            'group' => \Craft::t('app', 'Group')
        ]);
    }
}