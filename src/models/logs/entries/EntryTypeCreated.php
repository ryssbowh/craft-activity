<?php

namespace Ryssbowh\Activity\models\logs\entries;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\helpers\Html;
use craft\helpers\UrlHelper;
use craft\models\Section;

class EntryTypeCreated extends ConfigModelLog
{
    public $sectionData;

    protected $_section;

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created entry type {name} in section {section}', [
            'name' => $this->modelName,
            'section' => $this->sectionName
        ]);
    }

    /**
     * Section setter
     *
     * @param Section $section
     */
    public function setSection(Section $section)
    {
        $this->_section = $section;
        $this->data = [
            'section' => [
                'name' => $section->name,
                'id' => $section->id
            ]
        ];
    }

    /**
     * Section getter
     * 
     * @return ?Model
     */
    public function getSection(): ?Section
    {
        if ($this->_section === null and $this->data['section']['id'] ?? false) {
            $this->_section = \Craft::$app->sections->getSectionById($this->data['section']['id']);
        }
        return $this->_section;
    }

    /**
     * Section name getter
     * 
     * @return string
     */
    public function getSectionName(): string
    {
        $title = $this->data['section']['name'];
        if ($this->section) {
            $title = Html::a($this->section->name, UrlHelper::cpUrl('settings/sections/' . $this->section->id), ['target' => '_blank']);
        }
        return $title;
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/sections/' . $this->section->id . '/entrytypes/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->sections->getEntryTypeByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return [
            'name' => \Craft::t('app', 'Name'),
            'handle' => \Craft::t('app', 'Handle'),
            'hasTitleField' => \Craft::t('app', 'Show the Title field'),
            'titleFormat' => \Craft::t('app', 'Title Format'),
            'titleTranslationMethod' => \Craft::t('app', 'Title Translation Method'),
        ];
    }
}