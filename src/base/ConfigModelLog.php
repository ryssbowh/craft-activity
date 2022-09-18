<?php

namespace Ryssbowh\Activity\base;

use craft\base\Model;
use craft\helpers\Html;

abstract class ConfigModelLog extends ActivityLog
{
    protected $_model;

    /**
     * @inheritDoc
     */
    public function getDbData(): array
    {
        return array_merge(parent::getDbData(), [
            'target_id' => $this->model->id,
            'target_class' => get_class($this->model),
            'target_name' => $this->model->{$this->nameField}
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return \Craft::$app->view->renderTemplate('activity/descriptions/config-model', [
            'log' => $this
        ]);
    }

    /**
     * Get the labels for one field
     *
     * @param  string $name
     * @return ?string
     */
    public function getFieldLabel(string $name): ?string
    {
        return $this->getFieldLabels()[$name] ?? null;
    }

    /**
     * Model setter
     * 
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->_model = $model;
    }

    /**
     * Model getter
     * 
     * @return ?Model
     */
    public function getModel(): ?Model
    {
        if ($this->_model === null and $this->target_id ?? false) {
            $this->_model = $this->loadModel();
        }
        return $this->_model;
    }

    /**
     * Get model name
     * 
     * @return string
     */
    public function getModelName(): string
    {
        $title = $this->target_name;
        if ($this->model) {
            $title = Html::a($this->model->{$this->nameField}, $this->getModelLink(), ['target' => '_blank']);
        }
        return $title;
    }

    /**
     * Get model name field name
     * 
     * @return string
     */
    protected function getNameField(): string
    {
        return 'name';
    }

    /**
     * Get the labels for the fields
     * 
     * @return array
     */
    protected function getFieldLabels(): array
    {
        return [];
    }

    /**
     * Get the typings for the fields
     * 
     * @return array
     */
    protected function getFieldTypings(): array
    {
        return [];
    }

    /**
     * Load the model
     * 
     * @return ?Model
     */
    abstract protected function loadModel(): ?Model;

    /**
     * Get a link to the model edit page
     * 
     * @return string
     */
    abstract protected function getModelLink(): string;
}