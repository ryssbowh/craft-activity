<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use craft\base\Element;
use craft\fieldlayoutelements\CustomField;
use craft\fieldlayoutelements\EntryTitleField;
use craft\fieldlayoutelements\TitleField;

trait ElementFields
{
    /**
     * Calculate dirty fields, input are two arrays of field handlers
     *
     * @param  array $newFields
     * @param  array $oldFields
     * @return array
     */
    protected function getDirtyFields(array $newFields, array $oldFields): array
    {
        $dirty = [];
        foreach ($newFields as $name => $handler) {
            if (!array_key_exists($name, $oldFields)) {
                $dirty[$name] = [
                    'data' => $handler->getDbValue('t'),
                    'handler' => get_class($handler)
                ];
            } elseif ($handler->isDirty($oldFields[$name])) {
                $dirty[$name] = [
                    'data' => $handler->getDirty($oldFields[$name]),
                    'handler' => get_class($handler)
                ];
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $name => $handler) {
            $dirty[$name] = [
                'data' => $handler->getDbValue('f'),
                'handler' => get_class($handler)
            ];
        }
        return $dirty;
    }

    /**
     * Get an element custom field values, returns an array of field handlers
     *
     * @param  Element $element
     * @return array
     */
    protected function getCustomFieldValues(Element $element): array
    {
        $handlers = [];
        foreach ($element->fieldLayout->tabs as $tab) {
            foreach ($tab->elements as $elem) {
                if ($elem instanceof CustomField) {
                    $handle = $elem->field->handle;
                    $handlers['field_' . $handle] = Activity::$plugin->fieldHandlers->getHandlerForField($elem->field, $element, $elem->label);
                } elseif ($elem instanceof EntryTitleField or $elem instanceof TitleField) {
                    $handlers['title'] = new Plain([
                        'element' => $element,
                        'value' => $element->title,
                        'name' => $elem->label ?? \Craft::t('app', 'Title'),
                    ]);
                }
            }
        }
        return $handlers;
    }
}
