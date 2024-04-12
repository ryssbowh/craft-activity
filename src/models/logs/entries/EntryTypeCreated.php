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
        return \Craft::t('activity', 'Created entry type {name}', [
            'name' => $this->modelName
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/entry-types/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->entries->getEntryTypeByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        return [
            'name' => \Craft::t('app', 'Name'),
            'handle' => \Craft::t('app', 'Handle'),
            'entryTypes' => \Craft::t('app', 'Entry Types'),
            'hasTitleField' => \Craft::t('app', 'Show the Title field'),
            'showSlugField' => \Craft::t('app', 'Show the Slug field'),
            'titleFormat' => \Craft::t('app', 'Title Format'),
            'titleTranslationMethod' => \Craft::t('app', 'Title Translation Method'),
            'slugTranslationMethod' => \Craft::t('app', 'Slug Translation Method'),
            'color' => \Craft::t('app', 'Color'),
            'icon' => \Craft::t('app', 'Icon'),
        ];
    }
}
