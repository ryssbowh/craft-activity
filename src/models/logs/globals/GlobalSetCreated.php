<?php

namespace Ryssbowh\Activity\models\logs\globals;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\elements\GlobalSet;
use craft\helpers\UrlHelper;

class GlobalSetCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created global set {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/globals/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        foreach (\Craft::$app->globals->getAllSets() as $set) {
            if ($set->uid == $this->target_uid) {
                return $set;
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return (new GlobalSet)->attributeLabels();
    }
}