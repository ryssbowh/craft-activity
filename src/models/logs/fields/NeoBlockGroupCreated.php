<?php

namespace Ryssbowh\Activity\models\logs\fields;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\Html;
use craft\helpers\UrlHelper;

/**
 * @since 2.3.1
 */
class NeoBlockGroupCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created neo block group {name} in field {field}', [
            'name' => $this->modelName,
            'field' => $this->fieldName
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getModelName(): string
    {
        $title = $this->target_name;
        if ($this->model) {
            $title = $this->model->{$this->nameField};
        }
        return $title;
    }

    /**
     * Get the labels for one field
     *
     * @param  string $name
     * @param  string $type
     * @return ?string
     */
    public function getFieldLabel(string $name): ?string
    {
        $type = func_get_args()[1] ?? false;
        if ($type) {
            $labels = Activity::$plugin->fields->getFieldLabels($type);
        } else {
            $labels = $this->getFieldLabels();
        }
        return $labels[$name] ?? $name;
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        foreach (\Craft::$app->matrix->getAllBlockTypes() as $block) {
            if ($block->uid == $this->target_uid) {
                return $block;
            }
        }
        return null;
    }

    /**
     * Get the associated field
     * 
     * @return ?Model
     */
    protected function getField(): ?Model
    {
        return \Craft::$app->fields->getFieldByUid($this->data['field_uid']);
    }

    /**
     * Get the associated field name
     * 
     * @return string
     */
    protected function getFieldName(): string
    {
        $name = $this->data['field_name'];
        if ($field = $this->field) {
            $name = Html::a($field->name, UrlHelper::cpUrl('settings/fields/edit/' . $field->id), ['target' => '_blank']);
        }
        return $name;
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return [
            'name' => \Craft::t('app', 'Name'),
            'sortOrder' => \Craft::t('app', 'Order'),
            'alwaysShowDropdown' => \Craft::t('activity', 'Always Show Dropdown'),
        ];
    }
}