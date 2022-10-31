<?php

namespace Ryssbowh\Activity\models\logs\assets;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;

class FilesystemCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created file system {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/filesystems/' . $this->model->handle);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->fs->getFilesystemByHandle($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function _getFieldLabels(): array
    {
        return [
            'handle' => \Craft::t('app', 'Handle'),
            'name' => \Craft::t('app', 'Name'),
            'url' => \Craft::t('app', 'Base URL'),
            'hasUrls' => \Craft::t('app', 'Assets in this filesystem have public URLs'),
            'type' => \Craft::t('app', 'Filesystem Type'),
            'settings.path' => \Craft::t('app', 'Base Path'),
        ];
    }
}