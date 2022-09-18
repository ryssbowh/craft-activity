<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\models\fieldHandlers\Title;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use craft\base\Element;
use craft\fieldlayoutelements\CustomField;
use craft\fieldlayoutelements\EntryTitleField;
use craft\fieldlayoutelements\TitleField;
use craft\fields\Assets;
use craft\fields\BaseRelationField;
use craft\fields\Categories;
use craft\fields\Checkboxes;
use craft\fields\Color;
use craft\fields\Date;
use craft\fields\Dropdown;
use craft\fields\Email;
use craft\fields\Entries;
use craft\fields\Lightswitch;
use craft\fields\Matrix;
use craft\fields\MultiSelect;
use craft\fields\Number;
use craft\fields\PlainText;
use craft\fields\RadioButtons;
use craft\fields\Tags;
use craft\fields\Time;
use craft\fields\Url;
use craft\fields\Users;
use craft\redactor\Field as Redactor;

trait ElementFields
{
    protected function getDirtyFields(array $newFields, array $oldFields): array
    {
        $dirty = [];
        foreach ($newFields as $name => $handler) {
            if (!array_key_exists($name, $oldFields)) {
                $dirty[$name] = $handler->getDbValue('t');
            } elseif ($handler->isDirty($oldFields[$name])) {
                $dirty[$name] = $handler->getDirty($oldFields[$name]);
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $name => $handler) {
            $dirty[$name] = $handler->getDbValue('f');
        }
        return $dirty;
    }

    protected function getFieldValues(Element $element): array
    {
        $handlers = [];
        foreach ($element->fieldLayout->tabs as $tab) {
            foreach ($tab->elements as $elem) {
                if ($elem instanceof CustomField) {
                    $handle = $elem->field->handle;
                    $class = Activity::$plugin->fieldHandlers->getForElementField(get_class($elem->field));
                    $handlers['field_' . $handle] = new $class([
                        'field' => $elem->field,
                        'element' => $element,
                        'name' => $elem->label ?? $elem->field->name,
                        'value' => $elem->field->serializeValue($element->$handle, $element),
                        'rawValue' => $element->$handle
                    ]);
                } else if ($elem instanceof EntryTitleField or $elem instanceof TitleField) {
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