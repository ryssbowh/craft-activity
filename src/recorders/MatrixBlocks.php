<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\Matrix;
use yii\base\Event;

/**
 * @since 1.2.0
 */
class MatrixBlocks extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(Matrix::CONFIG_BLOCKTYPE_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('matrixBlocks')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(Matrix::CONFIG_BLOCKTYPE_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('matrixBlocks')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(Matrix::CONFIG_BLOCKTYPE_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('matrixBlocks')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    public function modifyParams(array $params, ConfigEvent $event): array
    {
        $uid = $event->newValue['field'] ?? $event->oldValue['field'];
        $field = \Craft::$app->fields->getFieldByUid($uid);
        $params['data'] = [
            'field_uid' => $uid,
            'field_name' => $field->name
        ];
        return $params;
    }

    /**
     * @inheritDoc
     */
    protected function getHandler(string $baseName, string $path, array $config, $value): FieldHandler
    {
        if (!$baseName == 'fields') {
            return parent::getHandler($baseName, $path, $config, $value);
        }
        $layout = $config['fieldLayouts'] ?? [];
        $elements = $layout ? reset($layout)['tabs'][0]['elements'] : [];
        foreach ($elements as $element) {
            if (isset($value[$element['fieldUid']])) {
                $value[$element['fieldUid']]['required'] = $element['required'];
            }
        }
        $class = $this->getPathFieldHandler($path, $config);
        return new $class([
            'value' => $this->typeValue($config, $baseName, $value)
        ]);
    }
        
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'matrixBlock';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['fields', 'name', 'handle', 'sortOrder'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}