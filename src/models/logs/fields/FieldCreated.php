<?php

namespace Ryssbowh\Activity\models\logs\fields;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\UrlHelper;

class FieldCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created field {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/fields/edit/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->fields->getFieldByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return [
            'name' => \Craft::t('app', 'Name'),
            'fieldGroup' => \Craft::t('app', 'Group'),
            'handle' => \Craft::t('app', 'Handle'),
            'group' => \Craft::t('app', 'Group'),
            'searchable' => \Craft::t('app', 'Use this field’s values as search keywords'),
            'instructions' => \Craft::t('app', 'Default Instructions'),
            'type' => \Craft::t('app', 'Field Type'),
            'translationMethod' => \Craft::t('app', 'Translation Method'),
            'translationKeyFormat' => \Craft::t('app', 'Translation Key Format')
        ];
    }
}