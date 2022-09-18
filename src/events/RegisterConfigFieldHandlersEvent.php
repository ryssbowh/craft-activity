<?php

namespace Ryssbowh\Activity\events;

use Ryssbowh\Activity\exceptions\FieldHandlerException;
use Ryssbowh\Activity\models\fieldHandlers\config\DefaultCategoryPlacement;
use Ryssbowh\Activity\models\fieldHandlers\config\FieldLayout;
use Ryssbowh\Activity\models\fieldHandlers\config\FieldType;
use Ryssbowh\Activity\models\fieldHandlers\config\PreviewTargets;
use Ryssbowh\Activity\models\fieldHandlers\config\SectionPropagationMethod;
use Ryssbowh\Activity\models\fieldHandlers\config\SectionType;
use Ryssbowh\Activity\models\fieldHandlers\config\SiteSettings;
use Ryssbowh\Activity\models\fieldHandlers\config\TitleTranslationMethod;
use yii\base\Event;

class RegisterConfigFieldHandlersEvent extends Event
{
    protected $_handlers = [];

    public function init()
    {
        parent::init();
        $this->addMany([
            SectionType::class,
            SiteSettings::class,
            FieldLayout::class,
            PreviewTargets::class,
            SectionPropagationMethod::class,
            FieldType::class,
            TitleTranslationMethod::class,
            DefaultCategoryPlacement::class
        ]);
    }

    public function getHandlers(): array
    {
        return $this->_handlers;
    }

    public function add(string $handler, bool $replace = false)
    {
        foreach ($handler::getTargets() as $class => $targets) {
            $targets = is_array($targets) ? $targets : [$targets];
            foreach ($targets as $target) {
                if (isset($this->_handlers[$class][$target]) and !$replace) {
                    throw FieldHandlerException::configRegistered($class, $target, $this->_handlers[$class][$target]);
                }
                $this->_handlers[$class][$target] = $handler;
            }
        }
    }

    public function addMany(array $handlers, bool $replace = false)
    {
        foreach ($handlers as $handler) {
            $this->add($handler, $replace);
        }
    }
}