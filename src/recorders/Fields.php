<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\Fields as CraftFields;
use yii\base\Event;

class Fields extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(CraftFields::CONFIG_FIELDS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('fields')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(CraftFields::CONFIG_FIELDS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('fields')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(CraftFields::CONFIG_FIELDS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('fields')->onRemove($event);
        });
    }
        
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'field';
    }

    /**
     * @inheritDoc
     */
    protected function getPathFieldHandler(string $path, array $config): string
    {
        $path = explode('.', $path);
        if ($path[2] == 'settings') {
            $path[2] = 'settings[' . $config['type'] .']';
        }
        $path = implode('.', $path);
        return parent::getPathFieldHandler($path, $config);
    }

    /**
     * @inheritDoc
     */
    protected function getHandler(string $baseName, string $path, array $config, $value): FieldHandler
    {
        $class = $this->getPathFieldHandler($path, $config);
        return new $class([
            'value' => $this->typeValue($config, $baseName, $value),
            'data' => [
                'fieldUid' => $this->currentEvent->tokenMatches[0] ?? null
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function modifyParams(array $params, ConfigEvent $event): array
    {
        $params['target_class'] = $event->newValue['type'] ?? $event->oldValue['type'] ?? '';
        return $params;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config)
    {
        if (!isset($config['type'])) {
            return Activity::$plugin->fields->getTrackedFieldNames('_base');
        }
        return Activity::$plugin->fields->getTrackedFieldNames($config['type']);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(array $config): array
    {
        if (!isset($config['type'])) {
            return Activity::$plugin->fields->getTrackedFieldTypings('_base');
        }
        return Activity::$plugin->fields->getTrackedFieldTypings($config['type']);
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}