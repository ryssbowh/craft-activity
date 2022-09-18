<?php

namespace Ryssbowh\Activity\base;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\traits\ConfigModelFields;
use craft\base\Model;

abstract class ConfigModelRecorder extends Recorder
{
    use ConfigModelFields;

    public $savingModel;

    protected $oldFields = [];

    public function beforeSaved(Model $model, bool $isNew)
    {
        $type = $this->getSavedActivityType($model, $isNew);
        // $this->savingModel = $model;
        if (!Activity::$plugin->settings->trackConfigFieldsChanges or !$this->shouldSaveLog($type)) {
            return;
        }
        if (!$isNew) {
            $oldModel = $this->loadOldModel($model->id);
            $this->oldFields[get_class($model)][$model->id] = $this->getTrackedFieldValues($oldModel);
        }
    }

    public function onSaved(Model $model, bool $isNew)
    {
        $type = $this->getSavedActivityType($model, $isNew);
        if (!$this->shouldSaveLog($type)) {
            return;
        }
        $params = [
            'model' => $model
        ];
        if (Activity::$plugin->settings->trackConfigFieldsChanges) {
            $oldFields = $isNew ? [] : $this->oldFields[get_class($model)][$model->id] ?? null;
            if ($oldFields !== null) {
                $params['changedFields'] = $this->getDirtyFields($this->getTrackedFieldValues($model), $oldFields);
            }
        }
        $params = $this->modifyParams($params, $type, $model);
        $this->saveLog($type, $params);
        $this->savingModel = null;
    }

    public function onDeleted(Model $model)
    {
        $type = $this->getActivityHandle() . 'Deleted';
        if (!$this->shouldSaveLog($type)) {
            return;
        }
        $params = [
            'model' => $model
        ];
        $params = $this->modifyParams($params, $type, $model);
        $this->saveLog($type, $params);
    }

    protected function getModelNameField(): string
    {
        return 'name';
    }

    protected function getSavedActivityType(Model $model, bool $isNew)
    {
        if ($isNew) {
            return $this->getActivityHandle() . 'Created';
        } else {
            return $this->getActivityHandle() . 'Saved';
        }
    }

    protected function modifyParams(array $params, string $type, Model $model)
    {
        return $params;
    }

    abstract protected function getActivityHandle(): string;

    abstract protected function loadOldModel(int $id): ?Model;
}