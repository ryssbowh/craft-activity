<?php

namespace Ryssbowh\Activity\events;

use Ryssbowh\Activity\exceptions\FieldHandlerException;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\DefaultCategoryPlacement;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\FieldGroup;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\FieldLayout;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\FieldType;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Permissions;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\PreviewTargets;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\SectionPropagationMethod;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\SectionType;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\SiteGroup;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\SiteSettings;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\TitleTranslationMethod;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\TransportType;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\UserGroup;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Volume;
use yii\base\Event;

class RegisterProjectConfigfieldHandlersEvent extends Event
{
    /**
     * @var array
     */
    protected $_handlers = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->addMany([
            TransportType::class,
            Volume::class,
            UserGroup::class,
            PreviewTargets::class,
            SiteSettings::class,
            SectionType::class,
            FieldLayout::class,
            SectionPropagationMethod::class,
            TitleTranslationMethod::class,
            DefaultCategoryPlacement::class,
            FieldGroup::class,
            FieldType::class,
            SiteGroup::class,
            Permissions::class
        ]);
    }

    /**
     * get registered handlers
     * 
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->_handlers;
    }

    /**
     * Add a field handler to register
     * 
     * @param string  $handler
     * @param boolean $replace
     */
    public function add(string $handler, bool $replace = false)
    {
        foreach ($handler::getTargets() as $path) {
            if (isset($this->_handlers[$path]) and !$replace) {
                throw FieldHandlerException::projectConfigRegistered($path, $this->_handlers[$path]);
            }
            $this->_handlers[$path] = $handler;
        }
    }

    /**
     * Add many field handlers to register
     * 
     * @param array   $handlers
     * @param boolean $replace
     */
    public function addMany(array $handlers, bool $replace = false)
    {
        foreach ($handlers as $handler) {
            $this->add($handler, $replace);
        }
    }
}