<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;

class LinkField extends ElementFieldHandler
{
    /**
     * @var array
     */
    protected $_dirty;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $this->value = $this->buildValues();
    }

    /**
     * @inheritDoc
     */
    public function getDirty(FieldHandler $handler): array
    {
        if ($this->_dirty === null) {
            $this->_dirty = $this->buildDirty($this->value, $handler->value);
        }
        return $this->_dirty;
    }

    /**
     * @inheritDoc
     */
    public function isDirty(FieldHandler $handler): bool
    {
        if (get_class($handler) != get_class($this)) {
            return true;
        }
        return !empty($this->getDirty($handler));
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            'typedlinkfield\fields\LinkField'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/link-field';
    }

    /**
     * @inheritDoc
     */
    public function getDbValue(string $valueKey): array
    {
        if ($valueKey == 'f') {
            return $this->buildDirty([], $this->value);
        }
        return $this->buildDirty($this->value, []);
    }

    /**
     * Build dirty values
     *
     * @param  array  $newValue
     * @param  array  $oldFields
     * @return array
     */
    protected function buildDirty(array $newValue, array $oldValue): array
    {
        $dirty = [];
        foreach (['ariaLabel', 'customText', 'newWindow', 'title'] as $key) {
            if (!array_key_exists($key, $oldValue)) {
                $dirty[$key] = [
                    't' => $newValue[$key]
                ];
            } elseif (!array_key_exists($key, $newValue)) {
                $dirty[$key] = [
                    'f' => $oldValue[$key]
                ];
            } elseif ($oldValue[$key] !== $newValue[$key]) {
                $dirty[$key] = [
                    'f' => $oldValue[$key],
                    't' => $newValue[$key]
                ];
            }
        }
        if (!array_key_exists('value', $oldValue)) {
            $dirty['value'] = [
                't' => [
                    'id' => $newValue['value']['id'],
                    'title' => $newValue['value']['title'],
                    'type' => $newValue['value']['type']
                ]
            ];
        } elseif (!array_key_exists('value', $newValue)) {
            $dirty['value'] = [
                'f' => [
                    'id' => $oldValue['value']['id'],
                    'title' => $oldValue['value']['title'],
                    'type' => $oldValue['value']['type']
                ]
            ];
        } elseif ($newValue['value']['id'] != $oldValue['value']['id'] or $newValue['value']['type'] != $oldValue['value']['type']) {
            $dirty['value'] = [
                'f' => [
                    'id' => $oldValue['value']['id'],
                    'title' => $oldValue['value']['title'],
                    'type' => $oldValue['value']['type']
                ],
                't' => [
                    'id' => $newValue['value']['id'],
                    'title' => $newValue['value']['title'],
                    'type' => $newValue['value']['type']
                ]
            ];
        }
        if ($dirty) {
            $dirty['name'] = $this->name;
        }
        return $dirty;
    }

    /**
     * Build the value
     *
     * @return array
     */
    protected function buildValues(): array
    {
        $element = $this->rawValue->getElement(true);
        $type = $this->rawValue->getLinkType();
        return [
            'ariaLabel' => $this->rawValue->ariaLabel,
            'customText' => $this->rawValue->customText,
            'newWindow' => $this->rawValue->target == '_blank',
            'title' => $this->rawValue->title,
            'value' => [
                'id' => $this->rawValue->value,
                'title' => $type->getText($this->rawValue),
                'type' => ($this->rawValue->type == 'craftCommerce-product' ? 'product' : $this->rawValue->type),
            ]
        ];
    }
}
