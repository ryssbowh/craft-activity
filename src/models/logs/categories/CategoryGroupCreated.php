<?php

namespace Ryssbowh\Activity\models\logs\categories;

use Ryssbowh\Activity\base\ConfigModelLog;
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
        return UrlHelper::cpUrl('settings/categories/' . $this->target_id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->categories->getGroupById($this->target_id);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return array_merge((new CategoryGroup)->attributeLabels(), [
            'maxLevels' => \Craft::t('app', 'Max Levels'),
            'defaultPlacement' => \Craft::t('activity', 'Default Placement')
        ]);
    }
}