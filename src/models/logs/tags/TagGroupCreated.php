<?php

namespace Ryssbowh\Activity\models\logs\tags;

use Ryssbowh\Activity\base\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;
use craft\models\TagGroup;

class TagGroupCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created tag group {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/tags/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->tags->getTagGroupById($this->target_id);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return array_merge((new TagGroup)->attributeLabels(), [
        ]);
    }
}