<?php

namespace Ryssbowh\Activity\base\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\traits\ElementFields;
use craft\base\Element;
use craft\helpers\ElementHelper;

abstract class ElementsRecorder extends Recorder
{
    use ElementFields;

    /**
     * Keep track of old fields values
     * @var array
     */
    protected $oldFields = [];

    /**
     * @var ?string
     * @since 2.4.0
     */
    protected ?string $deleteTypesCategory = null;

    /**
     * @var array
     * @since 2.4.0
     */
    protected array $deleteTypes = [];

    /**
     * Before an element is saved, saves old field values
     *
     * @param Element $element
     */
    public function beforeSaved(Element $element)
    {
        $type = $this->getSavedActivityType($element);
        if (!Activity::$plugin->settings->trackElementFieldsChanges or !$this->shouldSaveElementLog($type, $element)) {
            return;
        }
        if (!$element->firstSave) {
            $oldElement = $this->getElementType()::find()->id($element->id)->anyStatus()->site($element->site)->one();
            if ($oldElement) {
                $this->oldFields[$element->id] = $this->getFieldsValues($oldElement);
            }
        }
    }

    /**
     * Before an element is reverted to a revision, saves old field values
     *
     * @param Element $revision
     * @param Element $element
     */
    public function beforeReverted(Element $revision, Element $element)
    {
        $this->oldFields[$element->id] = $this->getFieldsValues($revision);
    }

    /**
     * Saves a log when an element is saved
     *
     * @param Element $element
     */
    public function onSaved(Element $element)
    {
        $type = $this->getSavedActivityType($element);
        if (!$this->shouldSaveElementLog($type, $element)) {
            return;
        }
        $params = [
            'element' => $element
        ];
        if (Activity::$plugin->settings->trackElementFieldsChanges) {
            $oldFields = $element->firstSave ? [] : $this->oldFields[$element->id] ?? [];
            $changed = $this->getDirtyFields($this->getFieldsValues($element), $oldFields);
            if (Activity::$plugin->settings->ignoreNoElementChanges and !$changed) {
                return;
            }
            $params['changedFields'] = $changed;
        }
        $this->commitLog($type, $params);
    }

    /**
     * Saves a log when an element is deleted
     *
     * @param Element $element
     */
    public function onDeleted(Element $element)
    {
        if ($this->deleteTypesCategory and Activity::$plugin->settings->shouldDeleteActivity($this->deleteTypesCategory)) {
            Activity::$plugin->logs->deleteLogsByType($this->deleteTypes, $element->uid);
            return;
        }
        $type = $this->getActivityHandle() . 'Deleted';
        if (!$this->shouldSaveElementLog($type, $element)) {
            return;
        }
        $this->commitLog($type, [
            'element' => $element
        ]);
    }

    /**
     * Saves a log when an element is restored
     *
     * @param Element $element
     */
    public function onRestored(Element $element)
    {
        $type = $this->getActivityHandle() . 'Restored';
        if (!$this->shouldSaveElementLog($type, $element)) {
            return;
        }
        $this->commitLog($type, [
            'element' => $element
        ]);
    }

    /**
     * Saves a log when an element is moved
     *
     * @param Element $element
     */
    public function onMoved(Element $element)
    {
        $type = $this->getActivityHandle() . 'Moved';
        if (!$this->shouldSaveElementLog($type, $element)) {
            return;
        }
        $this->commitLog($type, [
            'element' => $element
        ]);
    }

    /**
     * Save a log when an element is reverted to a revision
     *
     * @param  Element $element
     * @param  int     $revisionNum
     */
    public function onReverted(Element $element, int $revisionNum)
    {
        $type = $this->getActivityHandle() . 'Reverted';
        if (!$this->shouldSaveElementLog($type, $element)) {
            return;
        }
        $params = [
            'element' => $element,
            'revisionNum' => $revisionNum
        ];
        if (Activity::$plugin->settings->trackElementFieldsChanges) {
            $oldFields = $element->firstSave ? [] : $this->oldFields[$element->id] ?? [];
            $changed = $this->getDirtyFields($this->getFieldsValues($element), $oldFields);
            if (Activity::$plugin->settings->ignoreNoElementChanges and !$changed) {
                return;
            }
            $params['changedFields'] = $changed;
        }
        $this->commitLog($type, $params);
    }

    /**
     * Should an element log be saved
     *
     * @param  string  $type
     * @param  Element $element
     * @return bool
     */
    protected function shouldSaveElementLog(string $type, Element $element): bool
    {
        $settings = Activity::$plugin->settings;
        $root = $this->getRootElement($element);
        if (
            !$this->shouldSaveLog($type) or
            $root->getIsDraft() or
            $root->getIsRevision() or
            ($settings->ignorePropagate and $root->propagating) or
            ($settings->ignoreResave and $root->resaving)
        ) {
            return false;
        }
        return true;
    }

    /**
     * Get the activity log type
     *
     * @param  Element $element
     * @return string
     */
    protected function getSavedActivityType(Element $element): string
    {
        if ($element->firstSave) {
            return $this->getActivityHandle() . 'Created';
        } else {
            return $this->getActivityHandle() . 'Saved';
        }
    }

    /**
     * Get the root element of an element
     * 
     * @param Element $element
     * @return Element
     * @since 3.0.4
     */
    protected function getRootElement(Element $element): Element
    {
        return ElementHelper::rootElement($element);
    }

    /**
     * Get the activity handle, used to build the log type
     *
     * @return string
     */
    abstract protected function getActivityHandle(): string;

    /**
     * Get the element type this recorder handles
     *
     * @return string
     */
    abstract protected function getElementType(): string;

    /**
     * Get an element field values, returns an array of field handlers
     *
     * @param  Element $element
     * @return array
     */
    abstract protected function getFieldsValues(Element $element): array;
}
