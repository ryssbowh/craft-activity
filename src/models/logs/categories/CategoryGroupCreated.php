<?php

namespace Ryssbowh\Activity\models\logs\categories;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;
use craft\models\CategoryGroup;

class CategoryGroupCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created category group {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/categories/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->categories->getGroupByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return array_merge((new CategoryGroup)->attributeLabels(), [
            'structure.maxLevels' => \Craft::t('app', 'Max Levels'),
            'defaultPlacement' => \Craft::t('activity', 'Default Placement')
        ]);
    }
}