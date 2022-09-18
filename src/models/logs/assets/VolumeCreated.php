<?php

namespace Ryssbowh\Activity\models\logs\assets;

use Ryssbowh\Activity\base\ConfigModelLog;
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
        return \Craft::$app->volumes->getVolumeById($this->target_id);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return [
            'handle' => \Craft::t('app', 'Handle'),
            'name' => \Craft::t('app', 'Name'),
            'hasUrls' => \Craft::t('app', 'Assets in this volume have public URLs'),
            'type' => \Craft::t('app', 'Type'),
            'path' => \Craft::t('app', 'File System Path'),
            'titleTranslationMethod' => \Craft::t('app', 'Title Translation Method'),
            'titleTranslationKeyFormat' => \Craft::t('app', 'Title Translation Key Format'),
        ];
    }
}