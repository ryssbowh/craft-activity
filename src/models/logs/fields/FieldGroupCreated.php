<?php

namespace Ryssbowh\Activity\models\logs\fields;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;

class FieldGroupCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created field group {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/fields/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->fields->getGroupByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function _getFieldLabels(): array
    {
        return [
            'name' => \Craft::t('app', 'Name')
        ];
    }
}