<?php

namespace Ryssbowh\Activity\models\logs\assets;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;

class VolumeCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created volume {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/assets/volumes/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->volumes->getVolumeByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function _getFieldLabels(): array
    {
        return [
            'handle' => \Craft::t('app', 'Handle'),
            'name' => \Craft::t('app', 'Name'),
            'fs' => \Craft::t('app', 'Asset Filesystem'),
            'transformFs' => \Craft::t('app', 'Transform Filesystem'),
            'transformSubpath' => \Craft::t('app', 'Transform Subpath'),
        ];
    }
}