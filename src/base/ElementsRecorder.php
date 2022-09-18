<?php

namespace Ryssbowh\Activity\base;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\traits\ElementFields;
use craft\base\Element;
use craft\helpers\ElementHelper;

abstract class ElementsRecorder extends Recorder
{
    use ElementFields;

    protected $oldFields = [];

    public function beforeSaved(Element $element)
    {
        $type = $this->getSavedActivityType($element);
        if (!Activity::$plugin->settings->trackElementFieldsChanges or $element->propagating or !$this->shouldSaveLog($type) or ElementHelper::isDraftOrRevision($element)) {
            return;
        }
        if (!$element->firstSave) {
            $oldElement = $this->getElementType()::find()->id($element->id)->anyStatus()->site($element->site)->one();
            $this->oldFields[$element->id] = $this->getFields($oldElement);
        }
    }

    public function onSaved(Element $element)
    {
        $type = $this->getSavedActivityType($element);
        if (ElementHelper::isDraftOrRevision($element) or $element->propagating or !$this->shouldSaveLog($type)) {
            return;
        }
        $params = [
            'element' => $element
        ];
        if (Activity::$plugin->settings->trackElementFieldsChanges) {
            $oldFields = $element->firstSave ? [] : $this->oldFields[$element->id] ?? [];
            $params['changedFields'] = $this->getDirtyFields($this->getFields($element), $oldFields);
        }
        $this->saveLog($type, $params);
    }

    public function onDeleted(Element $element)
    {
        $type = $this->getActivityHandle() . 'Deleted';
        if (ElementHelper::isDraftOrRevision($element) or !$this->shouldSaveLog($type)) {
            return;
        }
        $this->saveLog($type, [
            'element' => $element
        ]);
    }

    public function onRestored(Element $element)
    {
        $type = $this->getActivityHandle() . 'Restored';
        if (!$this->shouldSaveLog($type)) {
            return;
        }
        $this->saveLog($type, [
            'element' => $element
        ]);
    }

    public function onMoved(Element $element)
    {
        $type = $this->getActivityHandle() . 'Moved';
        if (ElementHelper::isDraftOrRevision($element) or !$this->shouldSaveLog($type)) {
            return;
        }
        $this->saveLog($type, [
            'element' => $element,
            'lft' => $element->lft,
            'rgt' => $element->rgt,
            'level' => $element->level
        ]);
    }

    protected function getSavedActivityType(Element $element)
    {
        if ($element->firstSave) {
            return $this->getActivityHandle() . 'Created';
        } else {
            return $this->getActivityHandle() . 'Saved';
        }
    }

    abstract protected function getActivityHandle(): string;
    
    abstract protected function getElementType(): string;

    abstract protected function getFields(Element $element): array;
}