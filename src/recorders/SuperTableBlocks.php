<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use yii\base\Event;

/**
 * @since 1.2.0
 */
class SuperTableBlocks extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate('superTableBlockTypes.{uid}', function (Event $event) {
            Activity::getRecorder('superTableBlocks')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd('superTableBlockTypes.{uid}', function (Event $event) {
            Activity::getRecorder('superTableBlocks')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove('superTableBlockTypes.{uid}', function (Event $event) {
            Activity::getRecorder('superTableBlocks')->onRemove($event);
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
                $value[$element['fieldUid']]['width'] = $element['width'];
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
        return 'superTableBlock';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['fields'];
    }
}