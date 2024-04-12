<?php

namespace Ryssbowh\Activity\models\logs\fields;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;

/**
 * @deprecated in 3.0.0
 */
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
        return null;
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return [
            'name' => \Craft::t('app', 'Name')
        ];
    }
}
