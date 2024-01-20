<?php

namespace Ryssbowh\Activity\events;

use yii\base\Event;

class RegisterDeleteTypesOptions extends Event
{
    /**
     * @var array
     */
    protected $_options = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->addMany([
            'entries' => \Craft::t('app', 'Entries'),
            'sections' => \Craft::t('app', 'Sections'),
            'entryTypes' => \Craft::t('app', 'Entry Types'),
            'categories' => \Craft::t('app', 'Categories'),
            'categoryGroups' => \Craft::t('app', 'Category Groups'),
            'assets' => \Craft::t('app', 'Assets'),
            'fields' => \Craft::t('app', 'Fields'),
            'fieldGroups' => \Craft::t('activity', 'Field Groups'),
            'filesystems' => \Craft::t('app', 'Filesystems'),
            'globals' => \Craft::t('app', 'Globals'),
            'imageTransforms' => \Craft::t('app', 'Image Transforms'),
            'siteGroups' => \Craft::t('app', 'Site Groups'),
            'sites' => \Craft::t('app', 'Sites'),
            'tagGroups' => \Craft::t('app', 'Tag Group'),
            'userGroups' => \Craft::t('app', 'User Groups'),
            'users' => \Craft::t('app', 'Users'),
            'volumes' => \Craft::t('app', 'Volumes'),
        ]);
    }

    /**
     * Get registered options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->_options;
    }

    /**
     * Add an option
     *
     * @param string $handle
     * @param string $label
     */
    public function add(string $handle, string $label)
    {
        $this->_options[$handle] = $label;
    }

    /**
     * Add many options
     *
     * @param array   $options
     */
    public function addMany(array $options)
    {
        foreach ($options as $handle => $label) {
            $this->add($handle, $label);
        }
    }
}
