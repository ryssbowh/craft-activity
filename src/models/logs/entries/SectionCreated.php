<?php

namespace Ryssbowh\Activity\models\logs\entries;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;

class SectionCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created section {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/sections/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->sections->getSectionByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function _getFieldLabels(): array
    {
        return [
            'name' => \Craft::t('app', 'Name'),
            'handle' => \Craft::t('app', 'Handle'),
            'type' => \Craft::t('app', 'Section Type'),
            'enableVersioning' => \Craft::t('app', 'Enable versionning'),
            'propagationMethod' => \Craft::t('app', 'Propagation Method'),
            'maxLevels' => \Craft::t('app', 'Max Levels'),
            'defaultPlacement' => \Craft::t('app', 'Default Entry Placement'),
        ];
    }
}